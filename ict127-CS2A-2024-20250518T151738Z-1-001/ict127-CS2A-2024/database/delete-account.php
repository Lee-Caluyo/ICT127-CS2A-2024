<?php
require_once "config.php";
include('session-checker.php');

// Check if the form is submitted
if(isset($_POST['btnDelete'])) {
    // Check if username is provided
    if(isset($_POST['txtusername'])) {
        // Prepare and execute the deletion query
        $sql = "DELETE FROM tblaccounts WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
            if(mysqli_stmt_execute($stmt)) {
                // Deletion successful, log the action
                $datelogs = date('m/d/Y');
                $timelog = date('h:i:sa');
                $action = "Delete";
                $module = "Accounts Management";
                $ID = $_POST['txtusername'];
                $performedby = $_SESSION['username'];
                
                $sql_log = "INSERT INTO tbllogs (datelogs, timelog, action, module, ID, performedby) VALUES (?, ?, ?, ?, ?, ?)";
                if($stmt_log = mysqli_prepare($link, $sql_log)) {
                    mysqli_stmt_bind_param($stmt_log, "ssssss", $datelogs, $timelog, $action, $module, $ID, $performedby);
                    if(mysqli_stmt_execute($stmt_log)) {
                        // Log successful, redirect with success message
                        $_SESSION['success_message'] = "Account deleted successfully!";
                        header("location:accounts-management.php");
                        exit();
                    } else {
                        $error_message = "Error logging the action";
                    }
                } else {
                    $error_message = "Error preparing logging statement";
                }
            } else {
                $error_message = "Error deleting the account";
            }
        } else {
            $error_message = "Error preparing deletion statement";
        }
    } else {
        $error_message = "Username not provided";
    }
} else {
    $error_message = "Invalid request";
}
?>

<html>
<head>
    <title>Delete Account - Arellano University Subject Advising System - AUSAS</title>
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
            <input type="hidden" name="txtusername" value="<?php /*echo isset($_POST['txtusername']) ? htmlspecialchars($_POST['txtusername']) : '';*/echo trim($_GET['username']); ?>">
            <h1>Are you sure you want to delete?</h1>
            <div class="button-container">
                <input type="submit" name="btnDelete" class="deleteButtons" value="Yes">
                <a href="accounts-management.php" class="deleteButtons">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
