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
   Get ID
----------------------------------------*/

$id = intval($_POST['id'] ?? 0);

if ($id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid routine ID."
    ]);

    exit;
}

/* ---------------------------------------
   Check Record Exists
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
   Delete Record
----------------------------------------*/

$result = deleteMasterRoutine($id);

if ($result) {

    echo json_encode([
        "success" => true,
        "message" => "Routine deleted successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Unable to delete routine."
    ]);

}