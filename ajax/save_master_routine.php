<?php

require_once("../Config/master_functions.php");

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);
    exit;
}

/* -----------------------------
   Collect Data
------------------------------*/

$session_type = trim($_POST['session_type'] ?? '');
$course_type = trim($_POST['course_type'] ?? '');
$semester = intval($_POST['semester'] ?? 0);

$day = trim($_POST['day'] ?? '');
$start_time = trim($_POST['start_time'] ?? '');
$end_time = trim($_POST['end_time'] ?? '');

$course_name = trim($_POST['course_name'] ?? '');
$class_type = trim($_POST['class_type'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$description = trim($_POST['description'] ?? '');

/* -----------------------------
   Validation
------------------------------*/

if (
    empty($session_type) ||
    empty($course_type) ||
    empty($semester) ||
    empty($day) ||
    empty($start_time) ||
    empty($end_time) ||
    empty($course_name)
) {

    echo json_encode([
        "success" => false,
        "message" => "Please fill all required fields."
    ]);

    exit;
}

/* -----------------------------
   Conflict Check
------------------------------*/

if (
    isMasterRoutineConflict(
        $session_type,
        $course_type,
        $semester,
        $day,
        $start_time,
        $end_time
    )
) {

    echo json_encode([
        "success" => false,
        "message" => "A class already exists during this time slot."
    ]);

    exit;
}

/* -----------------------------
   Insert
------------------------------*/

$result = addMasterRoutine(

    $session_type,
    $course_type,
    $semester,

    $day,
    $start_time,
    $end_time,
    $course_name,
    $class_type,
    $subject,
    $description

);

if ($result) {

    echo json_encode([
        "success" => true,
        "message" => "Master routine added successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Unable to save data."
    ]);

}