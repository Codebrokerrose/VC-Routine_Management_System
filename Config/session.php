<?php
/*
==================================================
VC Routine Management System
Session Management
==================================================
*/

session_start();

/*
--------------------------------------------------
Prevent Browser Cache
--------------------------------------------------
*/

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

/*
--------------------------------------------------
Check Login
--------------------------------------------------
*/

if (!isset($_SESSION['id'])) {

    header("Location: ../index.php");
    exit();

}

/*
--------------------------------------------------
Store Session Variables
--------------------------------------------------
*/

$userID = $_SESSION['id'];
$userName = $_SESSION['name'];
$userEmail = $_SESSION['email'];
$userRole = $_SESSION['role'];
$userDepartment = $_SESSION['department'];

/*
--------------------------------------------------
Check User Status
--------------------------------------------------
*/

if ($userRole != "principal" && $userRole != "teacher") {

    session_destroy();

    header("Location: ../index.php");
    exit();

}
?>