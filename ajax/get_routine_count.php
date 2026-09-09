<?php

require_once("../config/functions.php");

if (
    !isset($_GET['department_id']) ||
    !isset($_GET['semester_id'])
) {
    echo 0;
    exit;
}

$departmentID = (int) $_GET['department_id'];
$semesterID = (int) $_GET['semester_id'];

echo getRoutineCount($departmentID, $semesterID);