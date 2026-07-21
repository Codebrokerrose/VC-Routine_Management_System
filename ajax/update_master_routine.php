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

/* ---------------------------------------
   Collect Form Data
----------------------------------------*/

$id = intval($_POST['master_routine_id'] ?? 0);

$day = trim($_POST['day'] ?? '');

$start_time = trim($_POST['start_time'] ?? '');

$end_time = trim($_POST['end_time'] ?? '');

$course_name = trim($_POST['course_name'] ?? '');

$class_type = trim($_POST['class_type'] ?? '');

$subject = trim($_POST['subject'] ?? '');

$description = trim($_POST['description'] ?? '');

/* ---------------------------------------
   Validation
----------------------------------------*/

if (
    $id <= 0 ||
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

/* ---------------------------------------
   Get Existing Record
----------------------------------------*/

$routine = getMasterRoutineById($id);

if (!$routine) {

    echo json_encode([
        "success" => false,
        "message" => "Routine not found."
    ]);

    exit;
}

/* ---------------------------------------
   Conflict Check
----------------------------------------*/

if (

    isMasterRoutineConflictForUpdate(

        $id,

        $routine['session_type'],

        $routine['course_type'],

        $routine['semester'],

        $day,

        $start_time,

        $end_time

    )

) {

    echo json_encode([
        "success" => false,
        "message" => "Another class already exists during this time slot."
    ]);

    exit;
}

/* ---------------------------------------
   Update
----------------------------------------*/

$result = updateMasterRoutine(

    $id,

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
        "message" => "Master routine updated successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Unable to update record."
    ]);

}