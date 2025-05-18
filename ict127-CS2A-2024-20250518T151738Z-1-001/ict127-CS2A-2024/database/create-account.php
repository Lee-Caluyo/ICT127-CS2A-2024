
<!DOCTYPE html>
<html>

<head>
    <title>Create new Account - Arellano University Subject Advising System - AUSMS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: lightsteelblue; 
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
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
            background-color: darkblue ; 
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
   
    $sql = "SELECT * FROM tblaccounts WHERE username = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $_POST['txtusername']);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) ==  0) {
                //insert account
                $sql = "INSERT INTO tblaccounts (username, password, usertype, status, createdby, datecreated) VALUES (?,?,?,?,?,?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    $status = "ACTIVE"; 
                    $datecreated = date("m/d/Y");
                    mysqli_stmt_bind_param($stmt, "ssssss", $_POST['txtusername'], $_POST['txtpassword'], $_POST['cmbtype'], $status, $_SESSION['username'], $datecreated);
                    if (mysqli_stmt_execute($stmt)) {
                        $sql = "INSERT INTO tbllogs (datelogs,timelog,action,module,ID, performedby) VALUES (?,?,?,?,?,?)";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            $date = date("m/d/Y");
                            $time = date("h:i:sa");
                            $action = "Create";
                            $module = "Accounts Management";
                            mysqli_stmt_bind_param($stmt, "ssssss", $date, $time, $action, $module, trim($_POST['txtusername']), $_SESSION['username']);
                            if (mysqli_stmt_execute($stmt)) {
                               echo "Accounts created successfully";
                                $_SESSION['success_message'] = "Accounts Create successfully!";
                                header("location:accounts-management.php");
                                exit();
                            } else {
                                echo "<font color='red'>Error on insert Log</font>";
                            }
                        }
                    }
                } else {
                    echo "Error on adding new account";
                }
            } else {
                echo "<font color = 'red'>Username already in use</font>";
            }
        }
    } else {
        echo "Error on finding if user exist";
    }
}

?>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
            <p>Fill up this form and submit to create a new account</p>
            Username: <input type="text" name="txtusername" required><br>
            Password: <input type="password" name="txtpassword" required><br>
            User type: <select name="cmbtype" id="cmbtype" required>
                <option value="">--Select Usertype--</option> 
                <option value="ADMINISTRATOR">Administrator</option> 
                <option value="REGISTRAR">Registrar</option> 
                <option value="STUDENT">Student</option> 
            </select><br>
            <input type="submit" name="btnsubmit" value="Submit">
            <a href="accounts-management.php">Cancel</a>
        </form>


    </div>


    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arellano University</p>
    </footer>
</body>

</html>

