<html>
<head>
    <title>Subjects Management - Subject Module</title>
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
        <h1>Arellano University Subject Advising System - AUSMS</h1>
    </header>

    <div style="text-align: center;"><br>
        <a href="index.php">Home</a>
    </div>

    <?php
session_start();
require_once "config.php";

// Check if the user is logged in
if(isset($_SESSION['username'])) {
    $studentid = $_SESSION['username'];

    // Fetch student information for the logged-in user
    $sql_student = "SELECT * FROM tblstudents WHERE studentid = ?";
    if ($stmt_student = mysqli_prepare($link, $sql_student)) {  
        mysqli_stmt_bind_param($stmt_student, "s", $studentid);
        if(mysqli_stmt_execute($stmt_student)) {
            $result_student = mysqli_stmt_get_result($stmt_student);
            $student_info = mysqli_fetch_assoc($result_student);

            if($student_info) {
                echo "<div style='text-align: center;' id='student-info'>";
                echo "<h2 style=\"color:red;\">Student Information</h2>";
                echo "<p style=\"color:darkblue;\">Student Number: " . htmlspecialchars($student_info['studentid']) . "</p>";
                echo "<p style=\"color:darkblue;\">Last Name: " . htmlspecialchars($student_info['lastname']) . "</p>";
                echo "<p style=\"color:darkblue;\">First Name: " . htmlspecialchars($student_info['firstname']) . "</p>";
                echo "<p style=\"color:darkblue;\">Middle Name: " . htmlspecialchars($student_info['middlename']) . "</p>";
                echo "<p style=\"color:darkblue;\">Course: " . htmlspecialchars($student_info['course']) . "</p>";
                echo "<p style=\"color:darkblue;\">Year Level: " . htmlspecialchars($student_info['yearlevel']) . "</p>";
                echo "</div>";

                // Fetch subjects for the logged-in user's course
                $course = $student_info['course'];
                $sql_subjects = "SELECT * FROM tblsubjects WHERE course = ?";
                if ($stmt_subjects = mysqli_prepare($link, $sql_subjects)) {
                    mysqli_stmt_bind_param($stmt_subjects, "s", $course);
                    if(mysqli_stmt_execute($stmt_subjects)) {
                        $result_subjects = mysqli_stmt_get_result($stmt_subjects);
                        echo "<table>";
                        echo "<tr><th>Subject Code</th><th>Description</th><th>Unit</th><th>Pre Requisite 1</th><th>Pre Requisite 2</th><th>Pre Requisite 3</th></tr>";
                        while ($row_subjects = mysqli_fetch_assoc($result_subjects)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row_subjects['subjectcode']) . "</td>";
                            echo "<td>" . htmlspecialchars($row_subjects['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row_subjects['unit']) . "</td>";
                            echo "<td>" . htmlspecialchars($row_subjects['prerequisite1']) . "</td>";
                            echo "<td>" . htmlspecialchars($row_subjects['prerequisite2']) . "</td>";
                            echo "<td>" . htmlspecialchars($row_subjects['prerequisite3']) . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";
                    } else {
                        echo "Error retrieving subjects: " . mysqli_error($link);
                    }
                } else {
                    echo "Error preparing SQL statement for subjects: " . mysqli_error($link);
                }
            } else {
                echo "No student found for the logged-in user.";
            }
        } else {
            echo "Error retrieving student information: " . mysqli_error($link);
        }
    } else {
        echo "Error preparing SQL statement for student information: " . mysqli_error($link);
    }
} else {
    echo "Please log in to view this page.";
}
?>
