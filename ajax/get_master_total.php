<?php

require_once("../Config/master_functions.php");

$session = $_GET['session'] ?? 'Odd';
$course = $_GET['course'] ?? 'BA';
$semester = intval($_GET['semester'] ?? 1);

echo getMasterRoutineCount(
    $session,
    $course,
    $semester
);