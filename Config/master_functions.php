<?php

require_once("db.php");

/* ==========================================================
   HTML ESCAPE
========================================================== */

function e($text)
{
    return htmlspecialchars($text ?? "", ENT_QUOTES, "UTF-8");
}

/* ==========================================================
   WORKING DAYS
========================================================== */

function getWorkingDays()
{
    return [

        "Monday",

        "Tuesday",

        "Wednesday",

        "Thursday",

        "Friday",

        "Saturday"

    ];
}

/* ==========================================================
   TIME SLOTS
========================================================== */

function getTimeSlots()
{
    return [

        ["10:45:00","11:45:00"],

        ["11:45:00","12:45:00"],

        ["12:45:00","13:45:00"],

        ["13:45:00","14:45:00"],

        ["15:00:00","16:00:00"],

        ["16:00:00","17:00:00"]

    ];
}

/* ==========================================================
   FORMAT TIME
========================================================== */

function formatTime($time)
{
    if(empty($time)){

        return "";

    }

    return date("h:i A",strtotime($time));
}

/* ==========================================================
   SESSION LIST
========================================================== */

function getMasterSessions()
{
    return [

        "Odd",

        "Even"

    ];
}

/* ==========================================================
   COURSE LIST
========================================================== */

function getMasterCourses()
{
    return [

        "BA",

        "BSc"

    ];
}

/* ==========================================================
   SEMESTERS
========================================================== */

function getMasterSemesters($session)
{

    if($session=="Odd"){

        return [1,3,5];

    }

    return [2,4,6];

}

/* ==========================================================
   LABEL HELPERS
========================================================== */

function getSemesterLabel($semester)
{
    return "Semester ".$semester;
}

function getSessionLabel($session)
{
    return ucfirst(strtolower($session));
}

function getCourseLabel($course)
{
    return strtoupper($course);
}

/* ==========================================================
   GET MASTER ROUTINE
========================================================== */

function getMasterRoutine(
    $session,
    $course,
    $semester
){

    global $conn;

    $stmt=$conn->prepare("
        SELECT *
        FROM master_routine
        WHERE session_type=?
        AND course_type=?
        AND semester=?
        ORDER BY
        FIELD(day,
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ),
        start_time ASC
    ");

    $stmt->bind_param(
        "ssi",
        $session,
        $course,
        $semester
    );

    $stmt->execute();

    return $stmt
        ->get_result()
        ->fetch_all(MYSQLI_ASSOC);

}

/* ==========================================================
   GET ROUTINE BY ID
========================================================== */

function getMasterRoutineById($id)
{

    global $conn;

    $stmt=$conn->prepare("
        SELECT *
        FROM master_routine
        WHERE master_routine_id=?
        LIMIT 1
    ");

    $stmt->bind_param("i",$id);

    $stmt->execute();

    $result=$stmt->get_result();

    if($result->num_rows>0){

        return $result->fetch_assoc();

    }

    return false;

}

/* ==========================================================
   GET ROUTINE BY DAY & TIME
========================================================== */

function getMasterRoutineByDayTime(
    $session,
    $course,
    $semester,
    $day,
    $startTime
){

    global $conn;

    $stmt=$conn->prepare("
        SELECT *
        FROM master_routine
        WHERE session_type=?
        AND course_type=?
        AND semester=?
        AND day=?
        AND start_time=?
        LIMIT 1
    ");

    $stmt->bind_param(
        "ssiss",
        $session,
        $course,
        $semester,
        $day,
        $startTime
    );

    $stmt->execute();

    $result=$stmt->get_result();

    if($result->num_rows){

        return $result->fetch_assoc();

    }

    return false;

}

/* ==========================================================
   TOTAL CLASSES OF SEMESTER
========================================================== */

function getMasterRoutineCount(
    $session,
    $course,
    $semester
){

    global $conn;

    $stmt=$conn->prepare("
        SELECT COUNT(*) total
        FROM master_routine
        WHERE session_type=?
        AND course_type=?
        AND semester=?
    ");

    $stmt->bind_param(
        "ssi",
        $session,
        $course,
        $semester
    );

    $stmt->execute();

    $row=$stmt
        ->get_result()
        ->fetch_assoc();

    return (int)$row['total'];

}

/* ==========================================================
   GET COMPLETE ROUTINE
========================================================== */

function getAllMasterRoutine()
{

    global $conn;

    $result=$conn->query("
        SELECT *
        FROM master_routine
        ORDER BY
        session_type,
        course_type,
        semester,
        FIELD(day,
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ),
        start_time
    ");

    return $result->fetch_all(MYSQLI_ASSOC);

}

/* ==========================================================
   ADD MASTER ROUTINE
========================================================== */

function addMasterRoutine(
    $session,
    $course,
    $semester,
    $day,
    $startTime,
    $endTime,
    $courseName,
    $classType,
    $subject,
    $description
) {

    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO master_routine
        (
            session_type,
            course_type,
            semester,
            day,
            start_time,
            end_time,
            course_name,
            class_type,
            subject,
            description
        )
        VALUES
        (
            ?,?,?,?,?,?,?,?,?,?
        )
    ");

    $stmt->bind_param(
        "ssisssssss",
        $session,
        $course,
        $semester,
        $day,
        $startTime,
        $endTime,
        $courseName,
        $classType,
        $subject,
        $description
    );

    return $stmt->execute();

}

/* ==========================================================
   UPDATE MASTER ROUTINE
========================================================== */

function updateMasterRoutine(
    $id,
    $day,
    $startTime,
    $endTime,
    $courseName,
    $classType,
    $subject,
    $description
) {

    global $conn;

    $stmt = $conn->prepare("
        UPDATE master_routine
        SET
            day=?,
            start_time=?,
            end_time=?,
            course_name=?,
            class_type=?,
            subject=?,
            description=?
        WHERE master_routine_id=?
    ");

    $stmt->bind_param(
        "sssssssi",
        $day,
        $startTime,
        $endTime,
        $courseName,
        $classType,
        $subject,
        $description,
        $id
    );

    return $stmt->execute();

}

/* ==========================================================
   DELETE MASTER ROUTINE
========================================================== */

function deleteMasterRoutine($id)
{

    global $conn;

    $stmt = $conn->prepare("
        DELETE
        FROM master_routine
        WHERE master_routine_id=?
    ");

    $stmt->bind_param(
        "i",
        $id
    );

    return $stmt->execute();

}

/* ==========================================================
   DELETE SEMESTER ROUTINE
========================================================== */

function deleteSemesterRoutine(
    $session,
    $course,
    $semester
) {

    global $conn;

    $stmt = $conn->prepare("
        DELETE
        FROM master_routine
        WHERE session_type=?
        AND course_type=?
        AND semester=?
    ");

    $stmt->bind_param(
        "ssi",
        $session,
        $course,
        $semester
    );

    return $stmt->execute();

}

/* ==========================================================
   DUPLICATE SEMESTER
========================================================== */

function duplicateSemesterRoutine(
    $fromSession,
    $fromCourse,
    $fromSemester,
    $toSession,
    $toCourse,
    $toSemester
) {

    global $conn;

    $stmt = $conn->prepare("
        INSERT INTO master_routine
        (
            session_type,
            course_type,
            semester,
            day,
            start_time,
            end_time,
            course_name,
            class_type,
            subject,
            description
        )

        SELECT
            ?,
            ?,
            ?,
            day,
            start_time,
            end_time,
            course_name,
            class_type,
            subject,
            description

        FROM master_routine

        WHERE session_type=?
        AND course_type=?
        AND semester=?
    ");

    $stmt->bind_param(
        "ssissi",
        $toSession,
        $toCourse,
        $toSemester,
        $fromSession,
        $fromCourse,
        $fromSemester
    );

    return $stmt->execute();

}

/* ==========================================================
   CHECK ROUTINE CONFLICT
========================================================== */

function isMasterRoutineConflict(
    $session,
    $course,
    $semester,
    $day,
    $startTime,
    $endTime
) {

    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) total
        FROM master_routine
        WHERE session_type=?
        AND course_type=?
        AND semester=?
        AND day=?
        AND
        (
            (? < end_time)
            AND
            (? > start_time)
        )
    ");

    $stmt->bind_param(
        "ssisss",
        $session,
        $course,
        $semester,
        $day,
        $startTime,
        $endTime
    );

    $stmt->execute();

    $row = $stmt
        ->get_result()
        ->fetch_assoc();

    return ($row['total'] > 0);

}

/* ==========================================================
   CHECK ROUTINE CONFLICT FOR UPDATE
========================================================== */

function isMasterRoutineConflictForUpdate(
    $id,
    $session,
    $course,
    $semester,
    $day,
    $startTime,
    $endTime
) {

    global $conn;

    $stmt = $conn->prepare("
        SELECT COUNT(*) total
        FROM master_routine
        WHERE session_type=?
        AND course_type=?
        AND semester=?
        AND day=?
        AND master_routine_id<>?
        AND
        (
            (? < end_time)
            AND
            (? > start_time)
        )
    ");

    $stmt->bind_param(
        "ssisiss",
        $session,
        $course,
        $semester,
        $day,
        $id,
        $startTime,
        $endTime
    );

    $stmt->execute();

    $row = $stmt
        ->get_result()
        ->fetch_assoc();

    return ($row['total'] > 0);

}

/* ==========================================================
   CHECK SLOT EXISTS
========================================================== */

function masterRoutineSlotExists(
    $session,
    $course,
    $semester,
    $day,
    $startTime,
    $endTime
) {

    global $conn;

    $stmt = $conn->prepare("
        SELECT master_routine_id
        FROM master_routine
        WHERE session_type=?
        AND course_type=?
        AND semester=?
        AND day=?
        AND start_time=?
        AND end_time=?
        LIMIT 1
    ");

    $stmt->bind_param(
        "ssisss",
        $session,
        $course,
        $semester,
        $day,
        $startTime,
        $endTime
    );

    $stmt->execute();

    return ($stmt->get_result()->num_rows > 0);

}

/* ==========================================================
   GET MASTER ROUTINE NOTE
========================================================== */

/* ==========================================================
   GET MASTER ROUTINE NOTE
========================================================== */

function getMasterRoutineNote()
{
    global $conn;

    $result = $conn->query("
        SELECT note
        FROM master_routine_notes
        ORDER BY note_id DESC
        LIMIT 1
    ");

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }

    return false;
}

/* ==========================================================
   SAVE MASTER ROUTINE NOTE
========================================================== */

function saveMasterRoutineNote($note)
{
    global $conn;

    $result = $conn->query("SELECT note_id FROM master_routine_notes LIMIT 1");

    if (!$result) {
        die($conn->error);
    }

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();

        $stmt = $conn->prepare("
            UPDATE master_routine_notes
            SET note=?
            WHERE note_id=?
        ");

        if (!$stmt) {
            die($conn->error);
        }

        $stmt->bind_param("si", $note, $row['note_id']);

    } else {

        $stmt = $conn->prepare("
            INSERT INTO master_routine_notes(note)
            VALUES(?)
        ");

        if (!$stmt) {
            die($conn->error);
        }

        $stmt->bind_param("s", $note);
    }

    if (!$stmt->execute()) {
        die($stmt->error);
    }

    return true;
}

/* ==========================================================
   DELETE NOTE
========================================================== */

function deleteMasterRoutineNote($masterRoutineId)
{
    global $conn;

    $stmt = $conn->prepare("
        DELETE
        FROM master_routine_notes
        WHERE master_routine_id=?
    ");

    $stmt->bind_param(
        "i",
        $masterRoutineId
    );

    return $stmt->execute();
}

/* ==========================================================
   NOTE EXISTS
========================================================== */

function masterRoutineNoteExists($masterRoutineId)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT note_id
        FROM master_routine_notes
        WHERE master_routine_id=?
        LIMIT 1
    ");

    $stmt->bind_param(
        "i",
        $masterRoutineId
    );

    $stmt->execute();

    return ($stmt->get_result()->num_rows > 0);
}

/* ==========================================================
   GET NOTE TEXT
========================================================== */

function getMasterRoutineNoteText($masterRoutineId)
{
    $note = getMasterRoutineNote($masterRoutineId);

    if (!$note) {

        return "";

    }

    return $note['note'];
}