
<!DOCTYPE html>
<html>

<head>
    <title>Create new Subject - Arellano University Subject Advising System - AUSMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: lightsteelblue;
        }

        .container {
            max-width: 500px;
            margin: 10px auto;
            padding: 30px;
            background-color: lightblue;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
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

        form {
            margin-top: 20px;
        }

        input[type="text"],
        input[type="password"],
        input[type="submit"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: darkcyan;
            color: black;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: darkblue;
        }

        a {
            text-decoration: none;
            color: #1653ec;
            margin-right: 10px;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <header>
        <h1>Arellano University Subject Advising System - AUSMS</h1>
    </header>

    <div class="container">
        <?php
        require_once "config.php";
        include("session-checker.php");
        if (isset($_POST['btnsubmit'])) {
            $sql = "SELECT * FROM tblsubjects WHERE subjectcode = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $_POST['txtsubjectcode']);
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) == 0) {
                        $sql = "INSERT INTO tblsubjects (subjectcode, description, unit, prerequisite1, prerequisite2, prerequisite3, course, createdby, datecreated) VALUES (?,?,?,?,?,?,?,?,?)";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $status = "ACTIVE";
                            $datecreated = date("m/d/Y");
                            $prerequisite1 = ($_POST['cmbprerequisite1'] == "None") ? NULL : $_POST['cmbprerequisite1'];
                            $prerequisite1 = ($_POST['cmbprerequisite1'] == "None") ? NULL : $_POST['cmbprerequisite2'];
                            $prerequisite1 = ($_POST['cmbprerequisite1'] == "None") ? NULL : $_POST['cmbprerequisite3'];
                            $sql_log = "INSERT INTO tblogs (datelogs, timelog, action, ID, module, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                            mysqli_stmt_bind_param($stmt, "sssssssss", $_POST['txtsubjectcode'], $_POST['txtdescription'], $_POST['cmbunit'], $_POST['prerequisite1'], $_POST['prerequisite2'], $_POST['prerequisite3'],  $_POST['cmbcourse'], $_SESSION['username'], $datecreated);
                            if (mysqli_stmt_execute($stmt)) {
                                $datelogs = date('m/d/Y');
                                $timelog = date('h:i:sa');
                                $action = "Create";
                                $module = "Subject Management";
                                $subjectcode = $_POST['txtsubjectcode'];
                                $performedby = $_SESSION['username'];
                                $sql_log = "INSERT INTO tbllogs (datelogs, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                                if ($stmt_log = mysqli_prepare($link, $sql_log)) {
                                    mysqli_stmt_bind_param($stmt_log, "ssssss", $datelogs, $timelog, $action, $module, $subjectcode, $performedby);
                                    if (mysqli_stmt_execute($stmt_log)) {
                                        echo "Subject created successfully";
                                        $_SESSION['success_message'] = "Subject created successfully!";
                                        header("location:subjects-management.php");
                                        exit();
                                    } else {
                                        echo "<font color='red'>Error on adding log</font>";
                                    }
                                } else {
                                    echo "<font color='red'>Error on preparing log statement</font>";
                                }
                            } else {
                                echo "<font color='red'>Error on adding new student</font>";
                            }
                        } else {
                            echo "<font color='red'>Error on preparing student insertion statement</font>";
                        }
                    } else {
                        echo "<font color = 'red'>Subject code already exists</font>";
                    }
                } else {
                    echo "<font color='red'>Error on executing student search statement</font>";
                }
            } else {
                echo "<font color='red'>Error on preparing student search statement</font>";
            }
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <p>Fill up this form and submit to create a new subject</p>
            Subject Code: <input type="text" name="txtsubjectcode" required><br>
            Description: <input type="text" name="txtdescription" required><br>
            Unit: <select name="cmbunit" class="cmbunit" required>
                <option value="">--SELECT UNIT--</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="5">5</option>
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
            <select name="prerequisite1" id="prerequisite1">
                <option value="">--Select Prerequisite 1--</option>
            </select><br>
            Prerequisite 2:
            <select name="prerequisite2" id="prerequisite2">
                <option value="">--Select Prerequisite 2--</option>
            </select><br>
            Prerequisite 3:
            <select name="prerequisite3" id="prerequisite3">
                <option value="">--Select Prerequisite 3--</option>
            </select><br>
            <input type="submit" name="btnsubmit" value="Submit">
            <a href="subjects-management.php">Cancel</a>
        </form>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arellano University</p>
    </footer>

    <script>
        function updatePrerequisites() {
            var courseSelect = document.getElementById('cmbcourse');
            var selectedCourse = courseSelect.value;
            var prerequisite1Select = document.getElementById('prerequisite1');
            var prerequisite2Select = document.getElementById('prerequisite2');
            var prerequisite3Select = document.getElementById('prerequisite3');

            if (selectedCourse) {
                fetchPrerequisites(selectedCourse, prerequisite1Select);
                fetchPrerequisites(selectedCourse, prerequisite2Select);
                fetchPrerequisites(selectedCourse, prerequisite3Select);
            } else {
                clearPrerequisites(prerequisite1Select);
                clearPrerequisites(prerequisite2Select);
                clearPrerequisites(prerequisite3Select);
            }
        }

        function fetchPrerequisites(course, prerequisiteSelect) {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    prerequisiteSelect.innerHTML = xhr.responseText;
                }
            };
            xhr.open("GET", "kahit ano.php?course=" + encodeURIComponent(course), true);
            xhr.send();
        }

        function clearPrerequisites(prerequisiteSelect) {
            prerequisiteSelect.innerHTML = "<option value=''>--Select Prerequisite--</option>";
        }
        document.getElementById('cmbcourse').addEventListener('change', updatePrerequisites);
        updatePrerequisites();
    </script>
</body>

</html>