<?php

/*
====================================================
DELETE ROUTINE (AJAX)
====================================================
*/

require_once("../config/functions.php");

header("Content-Type: application/json");

/*====================================================
CHECK REQUEST METHOD
====================================================*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid request."
    ]);

    exit;
}

/*====================================================
CHECK ROUTINE ID
====================================================*/

if (!isset($_POST['routine_id'])) {

    echo json_encode([
        "status" => "error",
        "message" => "Routine ID is missing."
    ]);

    exit;
}

$routineID = (int) $_POST['routine_id'];

/*====================================================
VALIDATE ROUTINE ID
====================================================*/

if ($routineID <= 0) {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid Routine ID."
    ]);

    exit;
}

/*====================================================
CHECK ROUTINE EXISTS
====================================================*/

$routine = getRoutineById($routineID);

if (!$routine) {

    echo json_encode([
        "status" => "error",
        "message" => "Routine not found."
    ]);

    exit;
}

/*====================================================
DELETE ROUTINE
====================================================*/

if (deleteRoutine($routineID)) {

    echo json_encode([
        "status" => "success",
        "message" => "Class deleted successfully."
    ]);

} else {

    echo json_encode([
        "status" => "error",
        "message" => "Unable to delete class."
    ]);

}

exit;

?>