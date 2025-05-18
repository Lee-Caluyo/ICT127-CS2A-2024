  <?php
require_once "config.php";
include("session-checker.php");

$error_message = "";

if(isset($_POST['btnsubmit'])) { 
    $sql_update = "UPDATE tblstudents SET lastname = ?, firstname = ?, middlename = ?, course = ?, yearlevel = ? WHERE studentid = ?";
    
    if($stmt_update = mysqli_prepare($link, $sql_update)) {
        mysqli_stmt_bind_param($stmt_update, "ssssss", $_POST['txtlastname'], $_POST['txtfirstname'], $_POST['txtmiddlename'], $_POST['cmbcourse'], $_POST['cmbyearlevel'], $_POST['txtStudentid']);
        
        if(mysqli_stmt_execute($stmt_update)) {
            $sql_log = "INSERT INTO tbllogs (datelogs, timelog, action, ID, module, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            
            if ($stmt_log = mysqli_prepare($link, $sql_log)) {
                $datelogs = date('m/d/Y');
                $timelog = date('h:i:sa');
                $action = "Update";
                $module = "Students Management";
                $ID = $_POST['txtStudentid'];
                $performedby = $_SESSION['username'];
                
                mysqli_stmt_bind_param($stmt_log, "ssssss", $datelogs, $timelog, $action, $ID, $module, $performedby);
                
                if (mysqli_stmt_execute($stmt_log)) {
                    $_SESSION['success_message'] = "Student updated successfully!";
                    header("location: students-management.php");
                    exit();
                } else {
                    $error_message = "Error inserting log statement: " . mysqli_error($link);
                }
            } else {
                $error_message = "Error preparing log statement: " . mysqli_error($link);
            }
        } else {
            $error_message = "Error updating student: " . mysqli_error($link); 
        }
    } else {
        $error_message = "Error preparing update statement: " . mysqli_error($link);
    }
}

if(!empty($error_message)) {
    echo "<font color='red'>$error_message</font>";
}

$account = array();

if(isset($_GET['studentid']) && !empty(trim($_GET['studentid']))) {
    $student_id = $_GET['studentid'];

    $sql_select = "SELECT * FROM tblstudents WHERE studentid = ?";
    
    if($stmt_select = mysqli_prepare($link, $sql_select)) {
        mysqli_stmt_bind_param($stmt_select, "s", $student_id);
        
        if(mysqli_stmt_execute($stmt_select)) {
            $result = mysqli_stmt_get_result($stmt_select);
            $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
        } else {
            echo "<font color='red'>Error loading account data</font>";
        }
    } else {
        echo "<font color='red'>Error preparing statement</font>";
    }
}
?>


    <!DOCTYPE html>
    <html>
    <head>
        <title>Update Account - Arellano University Subject Advising System - AUSMS</title>
        <style>
            body {
                background-color: lightsteelblue; 
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            form {
                max-width: 600px;
                margin: 20px auto;
                padding: 20px;
                background-color: lightblue;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            form p {
                font-size: 18px;
                font-weight: bold;
            }
            form input[type="text"] {
                width: 100%;
                padding: 10px;
                margin: 5px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }
            form select {
                width: 100%;
                padding: 10px;
                margin: 5px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }
            form input[type="radio"] {
                margin-right: 10px;
            }
            header,
            footer {
                background-color: #1653ec; 
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
            form input[type="submit"],
            form a {
                padding: 10px 20px;
                margin-top: 10px;
                display: inline-block;
                text-decoration: none;
                color: black;
                background-color: #52a3fa;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            form a {
                background-color: #52a3fa;
                margin-left: 10px;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Arellano University Subject Advising System - AUSMS</h1>
        </header>

         <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="POST">

            <p>Change the value on this form and submit to update the account</p>
            StudentID: <?php echo isset($account['studentid']) ? $account['studentid'] : ''; ?><br>
            Last Name: <input type="text" name="txtlastname" value="<?php echo isset($account['lastname']) ? $account['lastname'] : ''; ?>" required><br>
            First Name: <input type="text" name="txtfirstname" value="<?php echo isset($account['firstname']) ? $account['firstname'] : ''; ?>" required><br>
            Middle Name: <input type="text" name="txtmiddlename" value="<?php echo isset($account['middlename']) ? $account['middlename'] : ''; ?>" required><br>
     Course:
            <select name="cmbcourse" id="cmbcourse" required>
                <option value="">--Select Course--</option>
                <!-- List of courses here -->
                <?php
                $courses = array(
                    "BS in Accountancy",
                    "BS in Business Administration",
                    "BS in Hotel and Restaurant Management",
                    "BS in Tourism Management",
                    "BS in Computer Science",
                    "BS in Information Technology",
                    "BS in Civil Engineering",
                    "BS in Electronics Engineering",
                    "BS in Industrial Engineering",
                    "BS in Psychology",
                    "BS in Education",
                    "Bachelor of Elementary Education",
                    "Bachelor of Secondary Education",
                    "BA in Communication",
                    "BA in Political Science",
                    "BA in Psychology",
                    "BA in English",
                    "BS in Criminology",
                    "BS in Nursing",
                    "BS in Radiologic Technology",
                    "BS in Medical Technology"
                );

                foreach ($courses as $course) {
                    $selected = isset($account['course']) && $account['course'] == $course ? 'selected' : '';
                    echo "<option value='$course' $selected>$course</option>";
                }
                ?>
            </select><br>

            
            <label for="cmbtype">Change Year Level to:</label>
            
            <select name="cmbyearlevel" id="cmbyearlevel" required>
                <option value="">--Select Year Level--</option>
                <option value="1ST" <?php echo isset($account['yearlevel']) && $account['yearlevel'] == '1ST' ? 'selected' : ''; ?>>1ST</option>
                <option value="2ND" <?php echo isset($account['yearlevel']) && $account['yearlevel'] == '2ND' ? 'selected' : ''; ?>>2ND</option>
                <option value="3RD" <?php echo isset($account['yearlevel']) && $account['yearlevel'] == '3RD' ? 'selected' : ''; ?>>3RD</option>
                <option value="4TH" <?php echo isset($account['yearlevel']) && $account['yearlevel'] == '4TH' ? 'selected' : ''; ?>>4TH</option>
            </select><br>
            
            <input type="hidden" name="txtStudentid" value="<?php echo isset($account['studentid']) ? $account['studentid'] : ''; ?>">
            <input type="submit" name="btnsubmit" value="Update">
            <a href="students-management.php">Cancel</a>
        </form>


        <footer>
            <p>&copy; <?php echo date("Y"); ?> Arellano University</p>
        </footer>
    </body>
    </html>
