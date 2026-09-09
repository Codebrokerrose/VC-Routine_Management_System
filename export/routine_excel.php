<?php

require_once("../config/session.php");
require_once("../config/functions.php");

/*
====================================================
PRINCIPAL ONLY
====================================================
*/

// if (!isset($_SESSION['user_id'])) {
//     http_response_code(403);
//     exit("Access denied.");
// }


// $currentUser = getUserById((int) $_SESSION['user_id']);

// if (
//     !$currentUser ||
//     strtolower($currentUser['role'] ?? '') !== 'principal'
// ) {
//     http_response_code(403);
//     exit("Access denied. Only the Principal can export routines.");
// }


/*
====================================================
GET DEPARTMENT & SEMESTER
====================================================
*/

$departmentID = isset($_GET['department_id'])
    ? (int) $_GET['department_id']
    : 0;

$semesterID = isset($_GET['semester_id'])
    ? (int) $_GET['semester_id']
    : 0;

if ($departmentID <= 0 || $semesterID <= 0) {
    exit("Invalid Department or Semester.");
}


/*
====================================================
GET DEPARTMENT & SEMESTER INFORMATION
====================================================
*/

$department = getDepartment($departmentID);
$semester = getSemester($semesterID);

if (!$department || !$semester) {
    exit("Department or Semester not found.");
}

$departmentName = $department['department_name'];
$semesterName = $semester['semester_name'];


/*
====================================================
GET ROUTINE
====================================================
*/

$result = getRoutineByDepartmentSemester(
    $departmentID,
    $semesterID
);

if (!$result) {
    exit("Unable to load routine.");
}


/*
====================================================
FILE NAME
====================================================
*/

$safeDepartment = preg_replace(
    '/[^A-Za-z0-9_-]/',
    '_',
    $departmentName
);

$safeSemester = preg_replace(
    '/[^A-Za-z0-9_-]/',
    '_',
    $semesterName
);

$fileName =
    "Routine_" .
    $safeDepartment .
    "_" .
    $safeSemester .
    ".xls";


/*
====================================================
EXCEL HEADERS
====================================================
*/

header("Content-Type: application/vnd.ms-excel");
header(
    'Content-Disposition: attachment; filename="' .
    $fileName .
    '"'
);

header("Pragma: no-cache");
header("Expires: 0");


/*
====================================================
HTML / EXCEL DOCUMENT
====================================================
*/

?>

<html>

<head>

<meta charset="UTF-8">

<style>

body {
    font-family: Arial, sans-serif;
}

.title {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
}

.info {
    font-size: 14px;
    font-weight: bold;
    text-align: center;
}

table {
    border-collapse: collapse;
    width: 100%;
}

th {
    background-color: #6B1322;
    color: white;
    font-weight: bold;
    text-align: center;
    border: 1px solid #000000;
    padding: 8px;
}

td {
    border: 1px solid #000000;
    padding: 7px;
    vertical-align: middle;
}

.center {
    text-align: center;
}

.even {
    background-color: #F7F2E8;
}

</style>

</head>


<body>


<!-- ================================================
     TITLE
================================================ -->

<table>

<tr>
    <td
        colspan="7"
        class="title"
    >
        VC ROUTINE MANAGEMENT SYSTEM
    </td>
</tr>

<tr>
    <td
        colspan="7"
        class="info"
    >
        Department: <?= htmlspecialchars($departmentName) ?>
    </td>
</tr>

<tr>
    <td
        colspan="7"
        class="info"
    >
        Semester: <?= htmlspecialchars($semesterName) ?>
    </td>
</tr>

<tr>
    <td colspan="7"></td>
</tr>

</table>


<!-- ================================================
     ROUTINE TABLE
================================================ -->

<table>

<thead>

<tr>

    <th>Day</th>
    <th>Date</th>
    <th>Start Time</th>
    <th>End Time</th>
    <th>Subject</th>
    <th>Teacher</th>
    <th>Room</th>

</tr>

</thead>

<tbody>

<?php

$rowNumber = 0;

while ($row = mysqli_fetch_assoc($result)):

    $rowNumber++;

    $day = $row['day'] ?? '';
    $classDate = $row['class_date'] ?? '';
    $startTime = $row['start_time'] ?? '';
    $endTime = $row['end_time'] ?? '';
    $subject = $row['subject'] ?? '';
    $teacher = $row['teacher_name'] ?? '';
    $room = $row['room_no'] ?? '';


    /*
    ================================================
    FORMAT DATE
    ================================================
    */

    if (!empty($classDate)) {

        $timestamp = strtotime($classDate);

        if ($timestamp !== false) {

            $classDate = date(
                "d M Y",
                $timestamp
            );
        }
    }


    /*
    ================================================
    FORMAT TIME
    ================================================
    */

    if (!empty($startTime)) {

        $timestamp = strtotime($startTime);

        if ($timestamp !== false) {

            $startTime = date(
                "h:i A",
                $timestamp
            );
        }
    }


    if (!empty($endTime)) {

        $timestamp = strtotime($endTime);

        if ($timestamp !== false) {

            $endTime = date(
                "h:i A",
                $timestamp
            );
        }
    }

    ?>

    <tr
        class="<?= ($rowNumber % 2 === 0) ? 'even' : '' ?>"
    >

        <td class="center">
            <?= htmlspecialchars($day) ?>
        </td>

        <td class="center">
            <?= htmlspecialchars($classDate) ?>
        </td>

        <td class="center">
            <?= htmlspecialchars($startTime) ?>
        </td>

        <td class="center">
            <?= htmlspecialchars($endTime) ?>
        </td>

        <td>
            <?= htmlspecialchars($subject) ?>
        </td>

        <td>
            <?= htmlspecialchars($teacher) ?>
        </td>

        <td class="center">
            <?= htmlspecialchars($room) ?>
        </td>

    </tr>

<?php endwhile; ?>

</tbody>

</table>


</body>

</html>

<?php
exit;
?>