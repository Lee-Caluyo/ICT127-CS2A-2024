<?php
session_start();
?>

<html>
<head>
    <title>Accounts Management - Student Module</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    background-color: lightblue;
    color: black;
    margin: 0;
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
    color: white;
}

h1 {
    color: #007BFF;
}

h4 {
    color: #555;
}

form {
    margin-top: 20px;
}

input[type="text"] {
    width: 600px;
    padding: 5px;
    border-radius: 3px;
    border: 1px solid #ddd;
    margin-right: 5px;
}

input[type="submit"] {
    padding: 5px 10px;
    border-radius: 3px;
    border: none;
    color: lightblue;
    background-color: darkblue;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #5080fc;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
    align: center;
}

th,
td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

        $(document).ready(function() {
            var modal = document.getElementById("myModal");
            var span = document.getElementsByClassName("close")[0];

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            function openModal(username) {
                modal.style.display = "block";
                $.get("delete-account.php?username=" + username, function(data) {
                    $("#deleteContent").html(data);
                });
            }

            $(document).on("click", ".deleteLink", function(e) {
                e.preventDefault();
                openModal($(this).data("username"));
            });
        });
    </script>
</head>
<body>
<header>
    <h1>Arellano University Subject Advising System - AUTMS</h1>
    <div class="account-actions">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <a href="logout.php">Logout</a>
             
        </form>
    </div>
</header>

<?php
if (isset($_SESSION['username'])) {
    
    if (isset($_SESSION['success_message'])) {
        echo "<script>showToast('".$_SESSION['success_message']."');</script>";
        unset($_SESSION['success_message']); 
    }
} else {
    header("location:login.php");
}

?>
  <br> <a href="index.php">Home</a>
<div class="search-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <center><a href="create-account.php">Accounts new account</a><br></center>   
        <br>
        Search: <input type="text" name="txtSearch">
        <input type="submit" name="btnSearch" value="Search">
    </form>
</div>

<div id="myModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="deleteContent"></div>
    </div>
</div>

<?php
function buildTable($result){
    if(mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Username</th> <th>Usertype</th> <th>Status</th> <th>Created by</th> <th>Date created</th> <th>Action</th>";
        echo "</tr>";
        echo "<br>";
        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>". $row['username'] . "</td>";
            echo "<td>". $row['usertype'] . "</td>";
            echo "<td>". $row['status'] . "</td>";
            echo "<td>". $row['createdby'] . "</td>";
            echo "<td>". $row['datecreated'] . "</td>";

            echo "<td>";
            echo "<a href='update-account.php?username=" . $row['username'] . "'>Update</a>";
            echo "<a href='#' class='deleteLink' data-username='" . $row['username'] . "'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No record/s found.";
    }
}

require_once "config.php";
if (isset($_POST['btnSearch'])) {
    $sql = "SELECT * FROM tblaccounts WHERE username LIKE ? or usertype LIKE ? ORDER BY username";
    if($stmt = mysqli_prepare($link, $sql) ) {
        $searchvalue =  '%' . $_POST['txtSearch'] . '%';
        mysqli_stmt_bind_param($stmt, "ss",$searchvalue, $searchvalue);
        if(mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            buildTable($result);
        }
    } else {
        echo "Error on search";
    }
} else {
    $sql = "SELECT * FROM tblaccounts ORDER BY username";
    if ($stmt = mysqli_prepare($link, $sql)) {
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            buildTable($result);
        }
    } else {
        echo "Error on accounts load";
    }
}
?>

</body>
</html>
