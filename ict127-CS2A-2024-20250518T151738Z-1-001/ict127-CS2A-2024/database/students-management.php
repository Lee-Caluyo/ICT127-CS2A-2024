
<?php
session_start();
?>

<html>
<head>
    <title>Students Management - Student Module</title>
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
    </script>
</head>
<body>
<header>
    <h1>Arellano University Subject Advising System - AUTMS</h1>
    <div class="account-actions">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
           
            <a href="logout.php">Logout</a>
                 

    </div>
    </form>
</header>

<?php
if (isset($_SESSION['username'])) {
  
    if (isset($_SESSION['success_message'])) {
        echo "<script>showToast('".$_SESSION['success_message']."');</script>";
        unset($_SESSION['success_message']);
    }
        } else {
            header("location:");

        }

?><center>
   <br> <a href="index.php">Home</a>
<div class="search-container">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <center><a href="students-create.php">Create a new Student</a><br></center>   
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

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
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

        function openModal(studentnumber) {
            modal.style.display = "block";
            $.get("students-delete.php?studentid=" + studentnumber, function(data) {
                $("#deleteContent").html(data);
            });
        }

        $(document).on("click", ".deleteLink", function(e) {
            e.preventDefault();
            openModal($(this).data("studentid"));
        });
    });
</script>
</body>

</html>

<?php
function buildTable($result)
{
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Student Number</th><th>LastName</th><th>FirstName</th><th>MiddleName</th><th>Course</th><th>Yearlevel</th><th>Createdby</th><th>Datecreated</th><th>Actions</th>";
        echo "</tr>";
        echo "<br>";


        while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['studentid'] . "</td>";
            echo "<td>" . $row['lastname'] . "</td>";

            echo "<td>" . $row['firstname'] . "</td>";
            echo "<td>" . $row['middlename'] . "</td>";
            echo "<td>" . $row['course'] . "</td>";
            echo "<td>" . $row['yearlevel'] . "</td>";
            echo "<td>" . $row['createdby'] . "</td>";
            echo "<td>" . $row['datecreated'] . "</td>";
            echo "<td>";
            echo "<a href='students-update.php?studentid=" . $row['studentid'] . "' style='color: blue;'>Update</a>";
            echo "<a href='#' class='deleteLink' data-studentid='" . $row['studentid'] . "' style='color: red;'>Delete</a>";
            echo "</td>";
            echo "</tr>";
        }
        
        
        echo "</table>";
    } else {
        echo "No records found.";
    }
}

require_once "config.php";
if (isset($_POST['btnSearch'])) {
    $sql = "SELECT * FROM tblstudents WHERE studentid LIKE ? or lastname LIKE ? ORDER BY studentid";
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
    $sql = "SELECT * FROM tblstudents ORDER BY studentid";
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
