<?php

/* =====================================================
   SESSION
===================================================== */

// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }


/* =====================================================
   REQUIRED FILES
===================================================== */

// require_once("../config/functions.php");
require_once("../config/session.php");
require_once("../config/master_functions.php");


/* =====================================================
   PRINCIPAL ONLY
===================================================== */

// Uncomment this section when Principal-only
// restriction is required.

/*
if (!isset($_SESSION['user_id'])) {

    http_response_code(403);

    exit("Access denied.");

}

$currentUser = getUserById(
    (int) $_SESSION['user_id']
);

if (
    !$currentUser ||
    strtolower($currentUser['role'] ?? '') !== 'principal'
) {

    http_response_code(403);

    exit(
        "Access denied. Only the Principal can export routines."
    );

}
*/


/* =====================================================
   GET SELECTED MASTER ROUTINE FILTERS
===================================================== */

$sessionType = $_GET['session'] ?? '';

$courseType = $_GET['course'] ?? '';

$semester = isset($_GET['semester'])
    ? (int) $_GET['semester']
    : 0;


/* =====================================================
   VALIDATE SESSION
===================================================== */

if (
    $sessionType !== 'Odd' &&
    $sessionType !== 'Even'
) {

    exit("Invalid session selected.");

}


/* =====================================================
   VALIDATE COURSE
===================================================== */

if (
    $courseType !== 'BA' &&
    $courseType !== 'BSc'
) {

    exit("Invalid course selected.");

}


/* =====================================================
   VALIDATE SEMESTER
===================================================== */

$validSemesters = getMasterSemesters(
    $sessionType
);

if (
    !in_array(
        $semester,
        $validSemesters,
        true
    )
) {

    exit("Invalid semester selected.");

}


/* =====================================================
   GET SELECTED MASTER ROUTINE
===================================================== */

$result = getMasterRoutine(
    $sessionType,
    $courseType,
    $semester
);


/* =====================================================
   FILE NAME
===================================================== */

$safeSession = preg_replace(
    '/[^A-Za-z0-9_-]/',
    '_',
    $sessionType
);

$safeCourse = preg_replace(
    '/[^A-Za-z0-9_-]/',
    '_',
    $courseType
);

$fileName =
    "Master_Routine_" .
    $safeSession .
    "_" .
    $safeCourse .
    "_Semester_" .
    $semester .
    ".xls";


/* =====================================================
   EXCEL HEADERS
===================================================== */

header(
    "Content-Type: application/vnd.ms-excel; charset=UTF-8"
);

header(
    'Content-Disposition: attachment; filename="' .
    $fileName .
    '"'
);

header("Pragma: no-cache");

header("Expires: 0");


/* =====================================================
   SAFE OUTPUT
===================================================== */

function excelSafe($value)
{
    return htmlspecialchars(
        (string)($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>

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

        .subtitle {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
        }

        .info {
            font-size: 13px;
            font-weight: bold;
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


<!-- =====================================================
     TITLE
===================================================== -->

<table>

    <tr>

        <td colspan="10" class="title">

            VC ROUTINE MANAGEMENT SYSTEM

        </td>

    </tr>


    <tr>

        <td colspan="10" class="subtitle">

            MASTER ROUTINE

        </td>

    </tr>


    <tr>

        <td colspan="10" class="info">

            Session:
            <?= excelSafe($sessionType); ?>

            &nbsp;&nbsp;&nbsp;

            Course:
            <?= excelSafe($courseType); ?>

            &nbsp;&nbsp;&nbsp;

            Semester:
            <?= excelSafe($semester); ?>

        </td>

    </tr>


    <tr>

        <td colspan="10"></td>

    </tr>

</table>


<!-- =====================================================
     MASTER ROUTINE TABLE
===================================================== -->

<table>

    <thead>

        <tr>

            <th>Session Type</th>

            <th>Course Type</th>

            <th>Semester</th>

            <th>Day</th>

            <th>Start Time</th>

            <th>End Time</th>

            <th>Course Name</th>

            <th>Class Type</th>

            <th>Subject</th>

            <th>Description</th>

        </tr>

    </thead>


    <tbody>


<?php

$rowNumber = 0;


/* =====================================================
   IMPORTANT:

   getMasterRoutine() returns an ARRAY.

   Therefore use foreach(),
   NOT mysqli_fetch_assoc().
===================================================== */

foreach ($result as $row):

    $rowNumber++;


    $rowSessionType =
        $row['session_type'] ?? '';


    $rowCourseType =
        $row['course_type'] ?? '';


    $rowSemester =
        $row['semester'] ?? '';


    $day =
        $row['day'] ?? '';


    $startTime =
        $row['start_time'] ?? '';


    $endTime =
        $row['end_time'] ?? '';


    $courseName =
        $row['course_name'] ?? '';


    $classType =
        $row['class_type'] ?? '';


    $subject =
        $row['subject'] ?? '';


    $description =
        $row['description'] ?? '';


    /* =================================================
       FORMAT START TIME
    ================================================= */

    if (!empty($startTime)) {

        $timestamp = strtotime($startTime);

        if ($timestamp !== false) {

            $startTime =
                date(
                    "h:i A",
                    $timestamp
                );

        }

    }


    /* =================================================
       FORMAT END TIME
    ================================================= */

    if (!empty($endTime)) {

        $timestamp = strtotime($endTime);

        if ($timestamp !== false) {

            $endTime =
                date(
                    "h:i A",
                    $timestamp
                );

        }

    }

?>


        <tr
            class="<?= ($rowNumber % 2 === 0)
                ? 'even'
                : '' ?>"
        >

            <td>

                <?= excelSafe($rowSessionType); ?>

            </td>


            <td>

                <?= excelSafe($rowCourseType); ?>

            </td>


            <td class="center">

                <?= excelSafe($rowSemester); ?>

            </td>


            <td class="center">

                <?= excelSafe($day); ?>

            </td>


            <td class="center">

                <?= excelSafe($startTime); ?>

            </td>


            <td class="center">

                <?= excelSafe($endTime); ?>

            </td>


            <td>

                <?= excelSafe($courseName); ?>

            </td>


            <td>

                <?= excelSafe($classType); ?>

            </td>


            <td>

                <?= excelSafe($subject); ?>

            </td>


            <td>

                <?= nl2br(
                    excelSafe($description)
                ); ?>

            </td>

        </tr>


<?php

endforeach;

?>


    </tbody>

</table>


</body>

</html>


<?php

exit;

?>