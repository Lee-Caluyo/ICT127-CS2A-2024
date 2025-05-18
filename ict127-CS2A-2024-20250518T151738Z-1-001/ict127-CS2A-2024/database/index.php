<html>
<head>
    <title>Home Page - Arellano University Subject Advising System - AUTMS</title>
    <style>
        body {
            background-color: #98d6f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            background-color: rgba(22, 83, 236, 0.8);
            color: white;
            padding: 20px;
            text-align: center;
            width: 100%;
        }

        nav {
            background-color: rgba(7, 94, 194, 0.8);
            color: white;
            padding: 10px;
            text-align: center;
            width: 100%;
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        section {
            padding: 20px;
            text-align: center;
            color: white;
        }

        footer {
            background-color: rgba(22, 83, 236, 0.8);
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .welcome-user {
            font-size: 70px;
        }

        .welcome-type {
            text-align: center;
            font-size: 25px;
        }
    </style>
</head>

<body>
   
    <header>
        <h1>Welcome to Arellano University Subject Advising System - AUTMS</h1>
    </header>
    <nav>
        <ul>
            <?php
            session_start();
            $usertype = $_SESSION['usertype'];
            switch ($_SESSION['usertype']) {
                case 'ADMINISTRATOR':
                    echo "<li><a href='accounts-management.php'>Accounts</a></li>";
                    echo "<li><a href='students-management.php'>Students</a></li>";
                    echo "<li><a href='subjects-management.php'>Subjects</a></li>";
                    echo "<li><a href='subjects-advisingsystem.php'>Subject-Advising</a></li>";
                    echo "<li><a href='grades-management.php'>Grades</a></li>";
                    echo "<li><a href='change-pass.php'>Change password</a></li>";


                    break;  
                case 'REGISTRAR':
                    echo "<li><a href='students-management.php'>Students</a></li>";
                    echo "<li><a href='subjects-management.php'>Subjects</a></li>";
                    echo "<li><a href='grades-management.php'>Grades</a></li>";
                    echo "<li><a href='change-pass.php'>Change password</a></li>";
                    break;
                case 'STUDENT':
                if ($usertype === 'STUDENT')
                {
                    echo "<li><a href='grades-view.php'>View Grades</a></li>";
                    echo "<li><a href='view-advising.php'>Subject-Advising</a></li>";
                    echo "<li><a href='change-pass.php'>Change password</a></li>";
                }
                 break;
                case 'STAFF':
                   
                    echo "<li>No visible </li>";
                    break;
                default:
                    // Handle unknown usertypess
                    echo "<li>Unknown usertype.</li>";
                    break;
            }
            ?>
            <li><a href='logout.php'>Logout</a></li>
        </ul>
    </nav>
    <div class="whole-wrapper">
        <div class="separation-left">
            <div class="welcome-wrap">
                <div class="account-profile">
                  
                      
                        
                </div>
                <?php
                //check if there is a session recorded
                if (isset($_SESSION['username'])) {
                    echo "<p class='welcome-user'>Welcome, " . $_SESSION['username'] . "!</p>";
                    echo "<p class='welcome-type'>Account type: " . $_SESSION['usertype'] . "</p>";
                } else {
                    //redirect the user to the login page
                    header("location: login.php");
                }
                ?>
            </div>
        </div>
    </div>
     <footer>
        <p>&copy; <?php echo date("Y"); ?> Arellano University</p>
    </footer>
</body>
</html>
    