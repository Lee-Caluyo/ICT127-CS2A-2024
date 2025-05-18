<!DOCTYPE html>
<html>

<head>
    <title>Login Page - Arellano University Subject Advising System - AUTMS</title>
    <style>
        body {
            background-color: #98d6f5;
            font-family: Arial, sans-serif; 
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            background-color: lightskyblue; 
            padding: 20px;
            border-radius: 10px;
            width: 500px;
            text-align: center;
            color: darkblue;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: calc(100% - 12px); 
            padding: 6px;
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            color: #1653ec;
        }
        input[type="submit"] {
            background-color: #085ec2;
            color: black;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: lightcyan;
        }
        p.error {
            color: red;
            margin-top: 10px;
        }
        
        header {
            background-color: #1653ec;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            top: 0;
            width: 100%;
        }
       
        footer {
            background-color: #1653ec;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

    </style>
</head>

<body>
    <header>
        <h1>Arellano University Subject Advising System - AUTMS</h3>
    </header>

    <?php
    $errorMessage = "";
    
    if(isset($_POST['btnlogin']))
    {
        
        require_once "config.php";
        
        $sql = "SELECT * FROM tblaccounts WHERE username = ? AND password = ? AND status = 'ACTIVE'";
        
        if($stmt = mysqli_prepare($link, $sql))
        {
            
            mysqli_stmt_bind_param($stmt, "ss", $_POST['txtusername'], $_POST['txtpassword']);
            
            if(mysqli_stmt_execute($stmt))
            {
               
                $result = mysqli_stmt_get_result($stmt);
               
                if(mysqli_num_rows($result) > 0)
                {
                    $account = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    session_start();
                    $_SESSION['username'] = $_POST['txtusername'];
                    $_SESSION['usertype'] = $account['usertype'];
                    header("Location: index.php");
                }
                else
                {
                    $errorMessage = "Incorrect login details or account is inactive"; 
                }
            }
        }
        else
        {
            $errorMessage = "Error on the select statement"; 
        }
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
        <h2>Login</h2>
        Username: <input type="text" name="txtusername" required> <br>
        Password: <input type="password" name="txtpassword" required> <br>
        <input type="submit" name="btnlogin" value="Login">
        <?php
        if(!empty($errorMessage)) {
            echo "<p class='error'>$errorMessage</p>";
        }
        ?>
    </form>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Arellano University</p>
    </footer>
</body>
</html>
