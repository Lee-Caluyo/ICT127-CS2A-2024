<?php
require_once "config.php";
include("session-checker.php");

if(isset($_POST['btnDelete'])) { 
    $student_id = $_POST['txtStudentid'];
    
    // Delete student record
    $sql_delete = "DELETE FROM tblstudents WHERE studentid = ?";
    if($stmt_delete = mysqli_prepare($link, $sql_delete)) {
        mysqli_stmt_bind_param($stmt_delete, "s", $student_id);
        if(mysqli_stmt_execute($stmt_delete)) {
            // Log deletion action
            $sql_log = "INSERT INTO tbllogs (datelogs, timelog, action, ID, module, performedby) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt_log = mysqli_prepare($link, $sql_log)) {
                $datelogs = date('m/d/Y');
                $timelog = date('h:i:sa');
                $action = "Delete";
                $module = "Students Management";
                $ID = $student_id;
                $performedby = $_SESSION['username'];
                mysqli_stmt_bind_param($stmt_log, "ssssss", $datelogs, $timelog, $action, $ID, $module, $performedby);
                if (mysqli_stmt_execute($stmt_log)) {
                    $_SESSION['success_message'] = "Student deleted successfully!";
                    header("location: students-management.php");
                    exit();
                } else {
                    $error_message = "Error logging the action";
                }
            } else {
                $error_message = "Error preparing logging statement";
            }
        } else {
            $error_message = "Error deleting student record";
        }
    } else {
        $error_message = "Error preparing deletion statement";
    }
}

// Redirect if no student ID is provided
if(!isset($_GET['studentid']) || empty($_GET['studentid'])) {
    header("location: students-management.php");
    exit();
}

$student_id = $_GET['studentid'];

?>

<html>
<head>
    <title>Delete Student - Arellano University Subject Advising System - AUSAS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .container2 {
            display: flex;
            justify-content: center;
            width: 60%;
            margin: 10 auto;
            padding: 10px;
        }
        .deleteform{
            background-color: lightblue;
            padding: 10px;            
        }
        input[type="submit"], a {
            padding: 10px 20px;
            border: none;
            color: #fff;
            background-color: blue;
            cursor: pointer;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 10px;
        }
        .deleteButton {
            background-color: #d9534f;
        }
        
        input[type="submit"]:hover, a:hover {
            opacity: 0.8;
        }
        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container2">
        <form class="deleteform" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <input type="hidden" name="txtStudentid" value="<?php echo htmlspecialchars($student_id); ?>">
            <h1>Are you sure you want to delete?</h1>
            <div class="button-container">
                <input type="submit" name="btnDelete" class="deleteButtons" value="Yes">
                <a href="students-management.php" class="deleteButtons">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>