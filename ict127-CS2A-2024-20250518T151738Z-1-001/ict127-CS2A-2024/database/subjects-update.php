<?php
require_once "config.php";
include("session-checker.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subjectCode = $_POST['txtsubjectcode'];
    $description = $_POST['txtdescription'];
    $unit = $_POST['cmbunit'];
    $course = $_POST['cmbcourse'];
    $prerequisite1 = ($_POST['cmbprerequisite1'] == "None") ? NULL : $_POST['cmbprerequisite1'];
    $prerequisite2 = ($_POST['cmbprerequisite2'] == "None") ? NULL : $_POST['cmbprerequisite2'];
    $prerequisite3 = ($_POST['cmbprerequisite3'] == "None") ? NULL : $_POST['cmbprerequisite3'];

    $sql_update = "UPDATE tblsubjects SET description = ?, unit = ?, course = ?, prerequisite1 = ?, prerequisite2 = ?, prerequisite3 = ? WHERE subjectcode = ?";
    
    if ($stmt_update = mysqli_prepare($link, $sql_update)) {
        mysqli_stmt_bind_param($stmt_update, "sssssss", $description, $unit, $course, $prerequisite1, $prerequisite2, $prerequisite3, $subjectCode);
        
        if (mysqli_stmt_execute($stmt_update)) {
            // Logging the update action
            $datelogs = date('m/d/Y');
            $timelog = date('h:i:sa');
            $action = "Update";
            $module = "Subjects Management";
            $performedby = $_SESSION['username'];
            
            $sql_log = "INSERT INTO tbllogs (datelogs, timelog, action, ID, module, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt_log = mysqli_prepare($link, $sql_log)) {
                mysqli_stmt_bind_param($stmt_log, "ssssss", $datelogs, $timelog, $action, $subjectCode, $module, $performedby);
                
                if (mysqli_stmt_execute($stmt_log)) {
                    $_SESSION['success_message'] = "Subject updated successfully!";
                    header("Location: subjects-management.php");
                    exit();
                } else {
                    echo "<font color='red'>Error: Could not log the update</font>";
                }
            } else {
                echo "<font color='red'>Error: Log statement preparation failed</font>";
            }
        } else {
            echo "<font color='red'>Error: Update statement execution failed</font>";
        }
    } else {
        echo "<font color='red'>Error: Update statement preparation failed</font>";
    }
}

// Fetch subject details for pre-populating the form
$account = [];
if (isset($_GET['subjectcode']) && !empty(trim($_GET['subjectcode']))) {
    $subjectCode = $_GET['subjectcode'];
    $sql_select = "SELECT * FROM tblsubjects WHERE subjectcode = ?";
    
    if ($stmt_select = mysqli_prepare($link, $sql_select)) {
        mysqli_stmt_bind_param($stmt_select, "s", $subjectCode);
        
        if (mysqli_stmt_execute($stmt_select)) {
            $result = mysqli_stmt_get_result($stmt_select);
            $account = mysqli_fetch_assoc($result);
        } else {
            echo "<font color='red'>Error: Failed to fetch subject details</font>";
        }
    } else {
        echo "<font color='red'>Error: Prepare statement failed</font>";
    }
}

// Available choices for prerequisites
$prerequisiteChoices = ["None", "ITC111", "ITC110", "ITC112"];

// Display the HTML form
?>


<html>
<head>
    <title>Subjects Management - Subject Module</title>
    <style>
   body {
    font-family: Arial, sans-serif;
    background-color: lightblue;
    color: black;
    margin: 0;
    padding: 0;
}

header,
footer {
    background-color: #0145f3;
    color: white;
    padding: 10px;
    text-align: center;
    border-radius: 10px 10px 0 0;
}

footer {
    position: fixed;
    bottom: 0;
    width: 100%;
    border-radius: 0 0 10px 10px;
}

h1 {
    color: #007BFF;
}

form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #90cbf6;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

input[type="text"],
select {
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ddd;
    border-radius: 3px;
    box-sizing: border-box;
}

input[type="submit"],
a {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 10px;
    border: none;
    border-radius: 3px;
    text-decoration: none;
    cursor: pointer;
}

input[type="submit"] {
    color: lightblue;
    background-color: darkblue;
}

input[type="submit"]:hover {
    background-color: #5080fc;
}

a {
    color: black;
    background-color: powderblue;
}

a:hover {
    text-decoration: underline;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    text-align: center;
}

th,
td {
    border: 1px solid #ddd;
    padding: 10px;
}

th {
    background-color: darkblue;
    color: white;
}


a {
    margin-right: 20px;
    color: black;
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 3px;
    border: none;
    color: darkblue;
    background-color: powderblue;
    cursor: pointer;
}

a:hover {
    text-decoration: underline;
}

header {
    display: flex;
    text-align: center;
    background-color: #0145f3;
    padding: 10px;
    text-align: center;
    height: 70px;
}

header h1 {
    margin: 0;
    color: white;
    padding: 10px;
    text-align: left;
}

.search-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.search-container input[type="text"],
.search-container input[type="submit"] {
    margin: 0 10px;
}

.account-actions {
    display: flex;
    gap: 10px;
    margin-left: auto;
}

.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.4);
    padding-top: 60px;
}

.modal-content {
    background-color: #90cbf6;
    margin: 2% auto;
    padding: 10px;
    border: 0px solid #888;
    width: 100%;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: darkblue;
    text-decoration: none;
    cursor: pointer;
}

.toast {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: black;
    color: white;
    padding: 15px 30px;
    border-radius: 5px;
    z-index: 9999;
}
    </style>
    <script>
        function showToast(message) {
            var toast = document.createElement('div');
            toast.classList.add('toast');
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(function() {
                toast.remove();
            }, 3000);
        }
    </script>
</head>
<body>
    <header>
        <h1>Arellano University Subject Advising System - AUSMS</h1>
    </header>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <p>Fill up this form and submit to update the subject</p>
        Subject Code: <input type="text" name="txtsubjectcode" value="<?php echo htmlspecialchars($account['subjectcode'] ?? ''); ?>" readonly><br>
        Description: <input type="text" name="txtdescription" value="<?php echo htmlspecialchars($account['description'] ?? ''); ?>" required><br>
        Unit:
        <select name="cmbunit" class="cmbunit" required>
            <option value="">--SELECT UNIT--</option>
            <option value="1" <?php echo ($account['unit'] ?? '') == '1' ? 'selected' : ''; ?>>1</option>
            <option value="2" <?php echo ($account['unit'] ?? '') == '2' ? 'selected' : ''; ?>>2</option>
            <option value="3" <?php echo ($account['unit'] ?? '') == '3' ? 'selected' : ''; ?>>3</option>
            <option value="5" <?php echo ($account['unit'] ?? '') == '5' ? 'selected' : ''; ?>>5</option>
        </select><br>
        Course:
             <select name="cmbcourse" id="cmbcourse" required>
            <option value="">--Select Course--</option>
                <option value = "BS in Accountancy">BS in Accountancy</option>
                <option value = "BS in Busienss Administration">BS in Busienss Administration</option>
                <option value = "BS in Hotel and Restaurant Management">BS in Hotel and Restaurant Management</option>
                <option value = "BS in Tourism Managemen">BS in Tourism Management</option>
                <option value = "BS in Computer Science">BS in Computer Science</option>
                <option value = "BS in Information Technology">BS in Information Technology</option>
                <option value = "BS in Civil Engineering">BS in Civil Engineering</option>
                <option value = "BS in Electronics Engineering">BS in Electronics Engineering</option>
                <option value = "BS in Industrial Engineering">BS in Industrial Engineering</option>
                <option value = "BS in Psychology">BS in Psychology</option>
                <option value = "BS in Education">BS in Education</option>
                <option value = "Bachelor of Elementary Education">Bachelor of Elementary Education</option>
                <option value = "Bachelor of Secondary Education">Bachelor of Secondary Education</option>
                <option value = "BA in Communication">BA in Communication</option>
                <option value = "BA in Political Science">BA in Political Science</option>
                <option value = "BA in Psychology">BA in Psychology</option>
                <option value = "BA in English">BA in English</option>
                <option value = "BS in Criminology">BS in Criminology</option>
                <option value = "BS in Nursing">BS in Nursing</option>
                <option value = "BS in Radiologic Technology">BS in Radiologic Technology</option>
                <option value = "BS in Medical Technology">BS in Medical Technology</option>
            </select><br>
        Prerequisite 1:
        <select name="cmbprerequisite1">
            <?php foreach ($prerequisiteChoices as $option) {
                $selected = ($account['prerequisite1'] ?? '') == $option ? 'selected' : '';
                echo "<option value='$option' $selected>$option</option>";
            } ?>
        </select><br>
        Prerequisite 2:
        <select name="cmbprerequisite2">
            <?php foreach ($prerequisiteChoices as $option) {
                $selected = ($account['prerequisite2'] ?? '') == $option ? 'selected' : '';
                echo "<option value='$option' $selected>$option</option>";
            } ?>
        </select><br>
        Prerequisite 3:
        <select name="cmbprerequisite3">
            <?php foreach ($prerequisiteChoices as $option) {
                $selected = ($account['prerequisite3'] ?? '') == $option ? 'selected' : '';
                echo "<option value='$option' $selected>$option</option>";
            } ?>
        </select><br>
        <input type="submit" name="btnsubmit" value="Update">
        <a href="subjects-management.php">Cancel</a>
    </form>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arellano University</p>
    </footer>
</body>
</html>
