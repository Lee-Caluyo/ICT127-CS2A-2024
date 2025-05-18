<!DOCTYPE html>
<html>

<head>
    <title>Create new Student - Arellano University Subject Advising System - AUSMS</title>
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

            $sql = "SELECT * FROM tblstudents WHERE studentid = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $_POST['txtstudentid']);
                if (mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
                    if (mysqli_num_rows($result) == 0) {
                        
                        $sql = "INSERT INTO tblstudents (studentid, lastname, firstname, middlename, course, yearlevel, createdby, datecreated) VALUES (?,?,?,?,?,?,?,?)";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $status = "ACTIVE";
                            $datecreated = date("m/d/Y");
                            mysqli_stmt_bind_param($stmt, "ssssssss", $_POST['txtstudentid'], $_POST['txtlastname'], $_POST['txtfirstname'], $_POST['txtmiddlename'], $_POST['cmbcourse'], $_POST['cmbyearlevel'], $_SESSION['username'], $datecreated);
                            if (mysqli_stmt_execute($stmt)) {
                                
                               $datelogs = date('m/d/Y');
                            $timelog = date('h:i:sa');
                            $action = "Create";
                            $module = "Students Management";
                            $studentid = $_POST['txtstudentid'];
                            $performedby = $_SESSION['username'];

                            $sql_log = "INSERT INTO tbllogs (datelogs, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";

                            if ($stmt_log = mysqli_prepare($link, $sql_log)) {
                                mysqli_stmt_bind_param($stmt_log, "ssssss", $datelogs, $timelog, $action, $module, $studentid, $performedby);
                                if (mysqli_stmt_execute($stmt_log)) {
                                    echo "Student created successfully";
                                    $_SESSION['success_message'] = "Student created successfully!";
                                    header("location:students-management.php");
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
                        echo "<font color = 'red'>Student ID already exists</font>";
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
            <p>Fill up this form and submit to create a new student</p>
                Student ID: <input type="text" name="txtstudentid" required><br>
                Last Name: <input type="text" name="txtlastname" required><br>
                First Name: <input type="text" name="txtfirstname" required><br>
                Middle Name: <input type="text" name="txtmiddlename" required><br>
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
            Year Level:
            <select name="cmbyearlevel" id="cmbyearlevel" required>
                <option value="">--Select Year Level--</option>
                <option value="1ST">1ST</option>
                <option value="2ND">2ND</option>
                <option value="3RD">3RD</option>
                <option value="4TH">4TH</option>
                <option value="5TH">5TH</option>
            </select><br>
            <input type="submit" name="btnsubmit" value="Submit">
            <a href="students-management.php">Cancel</a>
        </form>
    </div>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arellano University</p>
    </footer>
</body>

</html>
