<?php
require_once('vendor/autoload.php'); // Include Composer autoload

use TCPDF;

// Get the student ID from the query string
$student_id = isset($_GET['id']) ? intval($_GET['id']) : '';

// Connect to the database
include("../LoginRegisterAuthentication/connection.php");

// SQL query to fetch student details and grades
$query = "SELECT s.*, sg.*
          FROM students s 
          JOIN student_grades sg ON s.id = sg.student_id
          WHERE s.id = '$student_id'";

$result = mysqli_query($connection, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($connection));
}

// Fetch the student details
$student = mysqli_fetch_assoc($result);

// Create a new PDF instance
$pdf = new TCPDF();
$pdf->AddPage();

// Set PDF title and font
$pdf->SetTitle('School Form (SF2) Daily Attendance Report');
$pdf->SetFont('helvetica', '', 12);

// Add content
$html = '
<h2>School Form (SF2) Daily Attendance Report of Learners</h2>
<p><strong>REGION VII SCHOOL NAME: LANAO NATIONAL HIGHSCHOOL DIVISION CEBU PROVINCE SCHOOL ID: ' . htmlspecialchars($student['school_id']) . ' SCHOOL YEAR: ' . htmlspecialchars($student['school_year']) . '</strong></p>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>Learners Full Name</th>
            <th>Grade & Section</th>
            <th>Teacher</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>' . htmlspecialchars($student['id']) . '</td>
            <td>' . htmlspecialchars($student['learners_name']) . '</td>
            <td>' . htmlspecialchars($student['grade'] . ' - ' . $student['section']) . '</td>
            <td></td>
        </tr>
    </tbody>
</table>

<h4>Subject Grades:</h4>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>Subject</th>
            <th>Written Exam</th>
            <th>Performance Task</th>
            <th>Quarterly Exam</th>
            <th>Final Grade</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>' . htmlspecialchars($student['subject']) . '</td>
            <td>' . htmlspecialchars($student['written_exam']) . '</td>
            <td>' . htmlspecialchars($student['performance_task']) . '</td>
            <td>' . htmlspecialchars($student['quarterly_exam']) . '</td>
            <td>' . htmlspecialchars($student['final_grade']) . '</td>
        </tr>
    </tbody>
</table>
';

$pdf->writeHTML($html);

// Output PDF
$pdf->Output('form2_report.pdf', 'D');
?>
