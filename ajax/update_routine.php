<?php

/*
====================================================
UPDATE ROUTINE (AJAX)
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
GET FORM DATA
====================================================*/

$data = [

    "routine_id" => (int) ($_POST['routine_id'] ?? 0),

    "department_id" => (int) ($_POST['department_id'] ?? 0),

    "semester_id" => (int) ($_POST['semester_id'] ?? 0),

    "day" => clean($_POST['day'] ?? ""),

    "class_date" => clean($_POST['class_date'] ?? ""),

    "start_time" => clean($_POST['start_time'] ?? ""),

    "end_time" => clean($_POST['end_time'] ?? ""),

    "subject" => clean($_POST['subject'] ?? ""),

    "teacher_name" => clean($_POST['teacher_name'] ?? ""),

    "room_no" => clean($_POST['room_no'] ?? "")

];

/*====================================================
VALIDATION
====================================================*/

if ($data['routine_id'] <= 0) {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid Routine ID."
    ]);

    exit;
}

foreach ($data as $key => $value) {

    if ($key == "routine_id") {
        continue;
    }

    if ($value === "") {

        echo json_encode([
            "status" => "error",
            "message" => "All fields are required."
        ]);

        exit;
    }
}

/*====================================================
CHECK ROUTINE EXISTS
====================================================*/

if (!getRoutineById($data['routine_id'])) {

    echo json_encode([
        "status" => "error",
        "message" => "Routine not found."
    ]);

    exit;
}

/*====================================================
CHECK DEPARTMENT
====================================================*/

if (!departmentExists($data['department_id'])) {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid Department."
    ]);

    exit;
}

/*====================================================
CHECK SEMESTER
====================================================*/

if (!semesterExists($data['semester_id'])) {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid Semester."
    ]);

    exit;
}

/*====================================================
CHECK DATE
====================================================*/

if (!isValidClassDate($data['class_date'])) {

    echo json_encode([
        "status" => "error",
        "message" => "Invalid Class Date."
    ]);

    exit;
}

/*====================================================
CHECK TIME
====================================================*/

if (!isValidTimeRange($data['start_time'], $data['end_time'])) {

    echo json_encode([
        "status" => "error",
        "message" => "End Time must be greater than Start Time."
    ]);

    exit;
}

/*====================================================
CHECK ROUTINE CONFLICT
====================================================*/

if (

    isRoutineConflict(

        $data['department_id'],

        $data['semester_id'],

        $data['day'],

        $data['start_time'],

        $data['end_time'],

        $data['routine_id']

    )

) {

    echo json_encode([
        "status" => "error",
        "message" => "Another class already exists in this time slot."
    ]);

    exit;
}

/*====================================================
CHECK TEACHER
====================================================*/

if (

    isTeacherBusy(

        $data['teacher_name'],

        $data['day'],

        $data['start_time'],

        $data['end_time'],

        $data['routine_id']

    )

) {

    echo json_encode([
        "status" => "error",
        "message" => "Teacher is already busy during this time."
    ]);

    exit;
}

if (

    isRoutineConflict(

        $data['department_id'],

        $data['semester_id'],

        $data['day'],

        $data['start_time'],

        $data['end_time'],

        $data['routine_id'] ?? 0

    )

) {

    echo json_encode([
        "status" => "error",
        "message" => "This semester already has a class in this time slot."
    ]);

    exit;
}

/*====================================================
UPDATE ROUTINE
====================================================*/

if (updateRoutine($data)) {

    echo json_encode([

        "status" => "success",

        "message" => "Routine updated successfully."

    ]);

} else {

    echo json_encode([

        "status" => "error",

        "message" => "Unable to update routine."

    ]);

}
?>