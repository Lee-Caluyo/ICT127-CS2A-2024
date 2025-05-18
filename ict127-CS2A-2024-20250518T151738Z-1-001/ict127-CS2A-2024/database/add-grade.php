
<html>
<head>
    <title>Add Grade  - Student Module</title>
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

th {
    background-color: darkblue;
    color: grey;
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
   
  
</head>

<?php
session_start();
require_once "config.php";

$current_user = $_SESSION['username'] ?? '';

if(isset($_POST['studentnumber'])) {
    $student_number = mysqli_real_escape_string($link, $_POST['studentnumber']);
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
    'yearlevel' => $student_info['yearlevel'],
    'subject_code' => $subject_code
);

            
                echo "<h2>Student Information</h2>";
                echo "<p>Student ID: " . htmlspecialchars($student_info['studentid']) . "</p>";
                echo "<p>Last Name: " . htmlspecialchars($student_info['lastname']) . "</p>";
                echo "<p>First Name: " . htmlspecialchars($student_info['firstname']) . "</p>";
                echo "<p>Middle Name: " . htmlspecialchars($student_info['middlename']) . "</p>";
                echo "<p>Course: " . htmlspecialchars($student_info['course']) . "</p>";
                echo "<p>Year Level: " . htmlspecialchars($student_info['yearlevel']) . "</p>";
                echo "<p>Subject Code: " . htmlspecialchars($student_info['subject_code']) . "</p>"; // Display subject_code

                // Rest of your code...


                $course = $student_info['course'];
                $sql_subjects = "SELECT * FROM tblsubjects WHERE course = ?";
                if ($stmt_subjects = mysqli_prepare($link, $sql_subjects)) {
                    mysqli_stmt_bind_param($stmt_subjects, "s", $course);
                    if(mysqli_stmt_execute($stmt_subjects)) {
                        $result_subjects = mysqli_stmt_get_result($stmt_subjects);

                        echo "<table>";
                        echo "<tr><th>Subject Code</th><th>Description</th><th>Unit</th><th>Grade</th><th>Encoded By</th><th>Date Encoded</th></tr>";
                        while ($row_subjects = mysqli_fetch_assoc($result_subjects)) {
                            $subject_code = $row_subjects['subjectcode'];

                            $sql_grades = "SELECT grade FROM tblgrades WHERE studentid = ? AND subjectcode = ?";
                            if ($stmt_grades = mysqli_prepare($link, $sql_grades)) {
                                mysqli_stmt_bind_param($stmt_grades, "ss", $student_number, $subject_code);
                                if(mysqli_stmt_execute($stmt_grades)) {
                                    $result_grades = mysqli_stmt_get_result($stmt_grades);

                                    if(mysqli_num_rows($result_grades) > 0) {
                                        $grade_row = mysqli_fetch_assoc($result_grades);
                                        $grade = $grade_row['grade'];
                                    } else {
                                        $grade = "";
                                    }
                                } else {
                                    echo "Error retrieving grades: " . mysqli_error($link);
                                }
                            } else {
                                echo "Error preparing SQL statement for grades: " . mysqli_error($link);
                            }
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($subject_code) . "</td>";
                            echo "<td>" . htmlspecialchars($row_subjects['description']) . "</td>";
                            echo "<td>" . htmlspecialchars($row_subjects['unit']) . "</td>";
                            echo "<td>" . htmlspecialchars($grade) . "</td>";
                            echo "<td>" . htmlspecialchars($row_subjects['encoded by']) . "</td>";
                            echo "<td>" . htmlspecialchars($row_subjects['date encoded']) . "</td>";
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
<header>
            <h1> Add Grade - Arellano University Subject Advising System - AUSMS</h1>

        </header>
<?php

$student_info = $_SESSION['student_info'] ?? null;

$student_subjects = array();
if ($student_info) {
    $course = $student_info['course'];
    $sql_subjects = "SELECT * FROM tblsubjects WHERE course = ?";
    if ($stmt_subjects = mysqli_prepare($link, $sql_subjects)) {
        mysqli_stmt_bind_param($stmt_subjects, "s", $course);
        if (mysqli_stmt_execute($stmt_subjects)) {
            $result_subjects = mysqli_stmt_get_result($stmt_subjects);
            while ($row_subject = mysqli_fetch_assoc($result_subjects)) {
                $student_subjects[] = $row_subject;
            }
        } else {
            echo "Error retrieving subjects: " . mysqli_error($link);
        }
    } else {
        echo "Error preparing SQL statement for subjects: " . mysqli_error($link);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $studentid = $student_info['studentid'];
    $subject_code = $_POST['subject_code'];
    $grade = $_POST['grade'];

  
    $encodedby = $current_user; 
    $dateencoded = date("m/d/Y "); 

    $sql_insert_grades = "INSERT INTO tblgrades (studentid, subject_code, grade, encodedby, dateencoded) VALUES (?, ?, ?, ?, ?)";
    if ($stmt_insert_grades = mysqli_prepare($link, $sql_insert_grades)) {
        mysqli_stmt_bind_param($stmt_insert_grades, "sssss", $studentid, $subject_code, $grade, $encodedby, $dateencoded);
        if (mysqli_stmt_execute($stmt_insert_grades)) {
            echo "<h2>Grade successfully added</h2>";
        } else {
            echo "Error adding grade: " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt_insert_grades);
    } else {
        echo "Error preparing SQL statement: " . mysqli_error($link);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Grade</title>
</head>
<body>


  

    <?php 
    if ($student_info && $student_subjects):
    ?>
        <div style="display: flex; flex-direction: column; justify-content: center; align-items: center;">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" style="width: 300px;">
        <label for="student_id" style="background-color: lightskyblue;">Student ID: <br><br></label>
        <input type="text" name="student_id" id="student_id" value="<?php echo htmlspecialchars($student_info['studentid']); ?>" readonly style="width: 100%;"><br><br>

        <label for="student_name" style="background-color: lightskyblue;">Student Name: <br><br></label>
        <input type="text" name="student_name" id="student_name" value="<?php echo htmlspecialchars($student_info['fullname']); ?>" readonly style="width: 100%;"><br><br>

        <label for="course" style="background-color: lightskyblue;">Course: <br><br></label>
        <input type="text" name="course" id="course" value="<?php echo htmlspecialchars($student_info['course']); ?>" readonly style="width: 100%;"><br><br>

        <label for="year_level" style="background-color: lightskyblue;">Year Level: <br><br></label>
        <input type="text" name="year_level" id="year_level" value="<?php echo htmlspecialchars($student_info['yearlevel']); ?>" readonly style="width: 100%;"><br><br>

        <label for="subject_code" style="background-color: lightskyblue;">Subject Code: <br><br></label>
        <select name="subject_code" id="subject_code" style="width: 100%;">
            <?php foreach ($student_subjects as $subject): ?>
                <option value="<?php echo htmlspecialchars($subject['subjectcode']); ?>"><?php echo htmlspecialchars($subject['subjectcode']); ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="grade" style="background-color: lightskyblue;">Grade: <br><br></label>
        <select name="grade" id="grade" required style="width: 100%;">
            <option value="">--Select Grade--</option>
            <option value="1.00">1.00</option>
            <option value="1.25">1.25</option>
            <option value="1.50">1.50</option>
            <option value="1.75">1.75</option>
            <option value="2.00">2.00</option>
            <option value="2.25">2.25</option>
            <option value="2.50">2.50</option>
            <option value="2.75">2.75</option>
            <option value="3.00">3.00</option>
        </select><br><br>

        <button type="submit" style="background-color: lightskyblue; color: black; width: 100%;">Save</button><br>
    </form>

</div>

        </form>
    <?php else: ?>
        <p>No student information available. Please go back and try again.</p>
    <?php endif; ?>

    <br>
     <footer>
       
             <br> <br> <a href="grades-management.php">Back</a>
        </footer> 
</body>
</html>