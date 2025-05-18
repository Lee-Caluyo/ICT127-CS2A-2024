<?php
require_once "config.php";
include("session-checker.php");

$account = [];

if(isset($_POST['btnsubmit'])) { 
    $sql = "UPDATE tblaccounts SET password = ?, usertype = ?, status = ? WHERE username = ?";
    if($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssss", $_POST['txtpassword'], $_POST['cmbtype'], $_POST['rbstatus'], $_GET['username']);
        if(mysqli_stmt_execute($stmt)) {
            $sql = "INSERT INTO tbllogs (datelogs, timelog, action, module, performedby) VALUES (?, ?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)) {
                $date = date("m/d/Y");
                $time = date("h:i:sa");
                $action = "Update";
                $module = "Change Passwords";
                $performedby = $_SESSION['username'];
                mysqli_stmt_bind_param($stmt, "sssss", $date, $time, $action, $module, $performedby);
                if(mysqli_stmt_execute($stmt)) {
                   echo "Password updated successfully";
                   $_SESSION['success_message'] = "Password updated successfully!";
                   header("location:index.php");
                   exit();
                } else {
                    echo "<font color='red'>Error on insert log statement</font>";
                }
            } else {
                echo "<font color='red'>Error on prepare statement</font>";
            }
        } else {
            echo "<font color='red'>Error on update statement</font>";
        }
    } else {
        echo "<font color='red'>Error on prepare statement</font>";
    }
} else { 
    if(isset($_GET['username']) && !empty(trim($_GET['username']))) {
        $sql = "SELECT * FROM tblaccounts WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $_GET['username']);
            if(mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else {
                echo "<font color='red'>Error on loading account data</font>";
            }
        } else {
            echo "<font color='red'>Error on prepare statement</font>";
        }
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
        form input[type="password"] {
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
        <label for="txtusername">Username:</label> <?php echo $_SESSION['username']; ?><br>
                <input type="password" name="txtpassword" id="password" required value="<?php if(isset($account['password'])) echo htmlspecialchars($account['password']); ?>"><br>
                <input type="checkbox" id="showPassword" > Show Password<br><br>
        <input type="submit" name="btnsubmit" value="Update">
        <a href="index.php">Cancel</a>

    </form>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arellano University</p>
    </footer>
    <script>
        document.getElementById("showPassword").addEventListener("change", function() {
    var passwordInput = document.getElementById("password");
    if (this.checked) {
        passwordInput.type = "text"; // Show password
    } else {
        passwordInput.type = "password"; // Hide password
    }
});
    </script>
</body>
</html>
