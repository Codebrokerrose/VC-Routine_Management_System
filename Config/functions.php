<?php
/*
====================================================
VC Routine Management System
Reusable Functions
====================================================
*/

require_once("db.php");

/*==================================================
    TOTAL DEPARTMENTS
==================================================*/

function getDepartmentCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM departments";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

/*==================================================
    TOTAL SEMESTERS
==================================================*/

function getSemesterCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM semesters";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

/*==================================================
    TODAY'S CLASSES
==================================================*/

function getTodayClassCount()
{
    global $conn;

    $today = date("l");

    $sql = "SELECT COUNT(*) AS total
            FROM routine
            WHERE day='$today'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

/*==================================================
    TODAY'S ROUTINE
==================================================*/

function getTodaySchedule()
{
    global $conn;

    $today = date('Y-m-d');
    $now = date('H:i:s');

    // First try today's remaining classes
    $sql = "
        SELECT
            r.*,
            d.department_name,
            s.semester_name
        FROM routine r
        JOIN departments d
            ON r.department_id = d.department_id
        JOIN semesters s
            ON r.semester_id = s.semester_id
        WHERE
            r.class_date = ?
            AND r.end_time >= ?
        ORDER BY
            r.start_time ASC
        LIMIT 5
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $today, $now);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        return $result;
    }

    // If today's classes are finished,
    // show next working day's classes

    $sql = "
        SELECT
            r.*,
            d.department_name,
            s.semester_name
        FROM routine r
        JOIN departments d
            ON r.department_id = d.department_id
        JOIN semesters s
            ON r.semester_id = s.semester_id
        WHERE
            r.class_date > ?
        ORDER BY
            r.class_date ASC,
            r.start_time ASC
        LIMIT 10
        
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $today);

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
}

/*==================================================
    ALL CALENDAR EVENTS
==================================================*/

function getCalendarEvents()
{
    global $conn;

    $sql = "
        SELECT *
        FROM events
        ORDER BY event_date ASC
    ";

    return mysqli_query($conn, $sql);
}

function getUpcomingEvents()
{
    global $conn;

    $sql = "
        SELECT *
        FROM events
        WHERE event_date >= CURDATE()
        ORDER BY event_date ASC
        LIMIT 5
    ";

    return mysqli_query($conn, $sql);
}
/*==================================================
    PRINCIPAL INFORMATION
==================================================*/

function getPrincipal()
{
    global $conn;

    $sql = "SELECT *
            FROM users
            WHERE role='principal'
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_assoc($result);
}

/*==================================================
    GET ALL DEPARTMENTS
==================================================*/

function getDepartments()
{
    global $conn;

    $sql = "SELECT *
            FROM departments
            ORDER BY department_name";

    return mysqli_query($conn, $sql);
}

/*==================================================
    GET ALL SEMESTERS
==================================================*/

function getSemesters()
{
    global $conn;

    $sql = "SELECT
                s.*,
                d.department_name
            FROM semesters s

            INNER JOIN departments d
            ON s.department_id=d.department_id

            ORDER BY d.department_name";

    return mysqli_query($conn, $sql);
}

/*==================================================
    GET ROUTINE BY SEMESTER
==================================================*/

function getRoutine($semesterID)
{
    global $conn;

    $semesterID = (int) $semesterID;

    $sql = "SELECT
                r.*,
                d.department_name,
                s.semester_name

            FROM routine r

            INNER JOIN departments d
            ON r.department_id=d.department_id

            INNER JOIN semesters s
            ON r.semester_id=s.semester_id

            WHERE r.semester_id=$semesterID

            ORDER BY
                FIELD(day,
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday'),
                start_time";

    return mysqli_query($conn, $sql);
}

/*==================================================
    DATE FORMAT
==================================================*/

function formatDate($date)
{
    return date("d M Y", strtotime($date));
}

/*==================================================
    TIME FORMAT
==================================================*/

function formatTime($time)
{
    return date("h:i A", strtotime($time));
}

/*==================================================
    SAFE OUTPUT
==================================================*/

function e($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

?>