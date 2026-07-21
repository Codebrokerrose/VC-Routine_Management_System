<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once("../config/master_functions.php");

$note = trim($_POST['note'] ?? "");

if (saveMasterRoutineNote($note)) {
    echo "success";
} else {
    global $conn;
    die("MySQL Error : " . mysqli_error($conn));
}