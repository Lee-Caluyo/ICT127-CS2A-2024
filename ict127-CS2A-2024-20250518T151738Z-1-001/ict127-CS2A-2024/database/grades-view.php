
<html>
<head>
    <title>Grades Management - Student Module</title>
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

        function displayAddGradeButton() {
            var addButton = document.createElement('a');
            addButton.href = 'add-grade.php';
            addButton.textContent = 'Add Grade';
            document.body.appendChild(addButton);
        }
    </script>
</head>
<body>
 <header>
            <h1>Arellano University Subject Advising System - AUSMS</h1>

        </header><br>
         <a href='index.php'>Home</a>

<?php
session_start();
require_once "config.php";

// Check if user is logged in
if(!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Fetch the username from the session
$encoded_by = $_SESSION['username'];




// Fetch student information based on logged-in username
$sql_student_info = "SELECT * FROM tblstudents WHERE studentid = '$encoded_by'";
$result_student_info = mysqli_query($link, $sql_student_info);

// Check if student exists
if(mysqli_num_rows($result_student_info) == 1) {
    $student_info = mysqli_fetch_assoc($result_student_info);

    // Display the header
    echo "
   
    <div style='text-align: center;'>
        <br>
       
    </div>
    <div style='text-align: center;'>
        <h2>Student Information</h2>
        <p>Student Number: " . htmlspecialchars($student_info['studentid']) . "</p>
        <p>Last Name: " . htmlspecialchars($student_info['lastname']) . "</p>
        <p>First Name: " . htmlspecialchars($student_info['firstname']) . "</p>
        <p>Middle Name: " . htmlspecialchars($student_info['middlename']) . "</p>
        <p>Course: " . htmlspecialchars($student_info['course']) . "</p>
        <p>Year Level: " . htmlspecialchars($student_info['yearlevel']) . "</p>
    </div>";

    // Fetch and display subject information for the logged-in student
    $course = $student_info['course'];
    $sql_subjects = "SELECT * FROM tblsubjects WHERE course = '$course'";
    $result_subjects = mysqli_query($link, $sql_subjects);

    if (mysqli_num_rows($result_subjects) > 0) {
        echo "<table>";
        echo "<tr><th>Subject Code</th><th>Description</th><th>Unit</th><th>Grade</th><th>Encoded By</th><th>Date Encoded</th>";
        while ($row_subjects = mysqli_fetch_assoc($result_subjects)) {
            $subject_code = $row_subjects['subjectcode'];

            $sql_grades = "SELECT grade FROM tblgrades WHERE studentid = '" . $student_info['studentid'] . "' AND subject_code = '$subject_code'";
            $result_grades = mysqli_query($link, $sql_grades);

            $grade = "";
            if (mysqli_num_rows($result_grades) > 0) {
                $grade_row = mysqli_fetch_assoc($result_grades);
                $grade = $grade_row['grade'];
            }

            // Get current date with day included
            $dateencoded = date('l, F j, Y');

            echo "<tr>";
            echo "<td>" . htmlspecialchars($subject_code) . "</td>";
            echo "<td>" . htmlspecialchars($row_subjects['description']) . "</td>";
            echo "<td>" . htmlspecialchars($row_subjects['unit']) . "</td>";
            echo "<td>" . htmlspecialchars($grade) . "</td>";
            echo "<td>" . htmlspecialchars($encoded_by) . "</td>";
            echo "<td>" . htmlspecialchars($dateencoded) . "</td>";
            echo "<td>";
        
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<br>";
    } else {
        echo "No subjects found for this course.";
    }

    // Display the footer
    echo "
    <footer>
        <p>&copy; " . date("Y") . " Arellano University</p>
    </footer>
    </body>
    </html>
    ";
} else {
    // Display error if student not found
    echo "Error: Student not found.";
}

?>