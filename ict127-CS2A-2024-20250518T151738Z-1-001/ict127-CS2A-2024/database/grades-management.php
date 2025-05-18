
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

        var buttonContainer = document.createElement('div');
        buttonContainer.style.textAlign = 'center';
        buttonContainer.appendChild(addButton);

        // Find the existing student information container
        var studentInfoDiv = document.querySelector('#student-info');
        if (studentInfoDiv) {
            // Insert the button container after the student information div
            studentInfoDiv.insertAdjacentElement('afterend', buttonContainer);
        } else {
            // Fallback: Append the button container to the end of the body
            document.body.appendChild(buttonContainer);
        }
    }
</script>
</head>
<body>
 <header>
            <h1>Arellano University Subject Advising System - AUSMS</h1>
        </header><br>

<div style="text-align: center;">
    <a href="index.php">Home</a>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">

     <label for="studentnumber" style="background-color: lightskyblue;">Enter Student Number:</label>

        <input type="text" name="studentnumber" id="studentnumber">
      <button type="submit" style="background-color: darkblue; color: white;">Submit</button>

    </form>


    <br>
   
</div>


    <?php
    session_start();
    require_once "config.php";

    if(isset($_POST['studentnumber'])) {
        $student_number = mysqli_real_escape_string($link, $_POST['studentnumber']);
        $encoded_by = $_SESSION['username'];
        $sql_student = "SELECT * FROM tblstudents WHERE studentid = ?";
        if ($stmt_student = mysqli_prepare($link, $sql_student)) {  
            mysqli_stmt_bind_param($stmt_student, "s", $student_number);
            if(mysqli_stmt_execute($stmt_student)) {
                $result_student = mysqli_stmt_get_result($stmt_student);
                $student_info = mysqli_fetch_assoc($result_student);

                if($student_info) {
                    $_SESSION['student_info'] = array(
                        'studentid' => $student_info['studentid'],
                        'fullname' => $student_info['firstname'] . ' ' . $student_info['middlename'] . ' ' . $student_info['lastname'],
                        'course' => $student_info['course'],
                        'yearlevel' => $student_info['yearlevel']
                    );

                echo "<div style='text-align: center;'>";
               echo "<h2 style=\"color:red\">Student Information</h2>";

              echo "<p style=\"color:darkblue\">Student Number: " . htmlspecialchars($student_info['studentid']) . "</p>";
echo "<p style=\"color:darkblue\">Last Name: " . htmlspecialchars($student_info['lastname']) . "</p>";
echo "<p style=\"color:darkblue\">First Name: " . htmlspecialchars($student_info['firstname']) . "</p>";
echo "<p style=\"color:darkblue\">Middle Name: " . htmlspecialchars($student_info['middlename']) . "</p>";
echo "<p style=\"color:darkblue\">Course: " . htmlspecialchars($student_info['course']) . "</p>";
echo "<p style=\"color:darkblue\">Year Level: " . htmlspecialchars($student_info['yearlevel']) . "</p>";
echo "<script>displayAddGradeButton();</script>";
echo "</div>";





                    $course = $student_info['course'];
                    $sql_subjects = "SELECT * FROM tblsubjects WHERE course = ?";
                    if ($stmt_subjects = mysqli_prepare($link, $sql_subjects)) {
                        mysqli_stmt_bind_param($stmt_subjects, "s", $course);
                        if(mysqli_stmt_execute($stmt_subjects)) {
                            $result_subjects = mysqli_stmt_get_result($stmt_subjects);
                            echo "<table>";
                            echo "<tr><th>Subject Code</th><th>Description</th><th>Unit</th><th>Grade</th><th>Encoded By</th><th>Date Encoded</th><th>Action</th></tr>";
                            while ($row_subjects = mysqli_fetch_assoc($result_subjects)) {
                                $subject_code = $row_subjects['subjectcode'];

                                $sql_grades = "SELECT grade FROM tblgrades WHERE studentid = ? AND subject_code = ?";
                                if ($stmt_grades = mysqli_prepare($link, $sql_grades)) {
                                    mysqli_stmt_bind_param($stmt_grades, "ss", $student_number, $subject_code);
                                    if(mysqli_stmt_execute($stmt_grades)) {
                                        $result_grades = mysqli_stmt_get_result($stmt_grades);

                                        $grade = "";

                                        if(mysqli_num_rows($result_grades) > 0) {
                                            $grade_row = mysqli_fetch_assoc($result_grades);
                                            $grade = $grade_row['grade'];
                                        }
                                    } else {
                                        echo "Error retrieving grades: " . mysqli_error($link);
                                    }
                                } else {
                                    echo "Error preparing SQL statement for grades: " . mysqli_error($link);
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
                                echo "<a href='grades-update.php?studentid=" . htmlspecialchars($student_info['studentid']) . "' style='color: blue;'>Update</a>";
                                echo "<a href='grades-delete.php?studentid=" . htmlspecialchars($student_info['studentid']) . "' style='color: red;'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            echo "</table>";
                            echo "<br>";
                            
                        } else {
                            echo "Error retrieving subjects: " . mysqli_error($link);
                        }
                    } else {
                        echo "Error preparing SQL statement for subjects: " . mysqli_error($link);
                    }
                } else {
                    echo "No student found with the provided student number.";
                }
            } else {
                echo "Error retrieving student information: " . mysqli_error($link);
            }
        } else {
            echo "Error preparing SQL statement for student information: " . mysqli_error($link);
        }
    }
    ?>
     <footer>
            <p>&copy; <?php echo date("Y"); ?> Arellano University</p>
        </footer>

</body>
</html>