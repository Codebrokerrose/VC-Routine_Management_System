<?php

/*
====================================================
GET SEMESTERS (AJAX)
====================================================
*/

require_once("../config/functions.php");

/*====================================================
FETCH ALL SEMESTERS
====================================================*/

$result = getSemesters();

$semesters = [];

while ($row = mysqli_fetch_assoc($result)) {

    $semesters[] = [

        "semester_id" => $row['semester_id'],

        "semester_name" => $row['semester_name']

    ];

}

/*====================================================
RETURN JSON
====================================================
*/

header("Content-Type: application/json");

echo json_encode($semesters);

exit;

?>