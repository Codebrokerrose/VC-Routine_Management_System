<?php

session_start();

require_once("../config/db.php");

// Check if form submitted

if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: ../index.php");
    exit();

}

// Get form data

$email = trim($_POST['email']);
$password = trim($_POST['password']);

// Empty validation

if (empty($email) || empty($password)) {

    header("Location: ../index.php?error=Please fill all fields");
    exit();

}

// Prepared Statement

$sql = "SELECT * FROM users WHERE email=? LIMIT 1";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "s", $email);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// User exists?

if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);

    // User Active?

    if ($user['status'] != "Active") {

        header("Location: ../index.php?error=Account Disabled");
        exit();

    }

    /*
    -----------------------------------------------------
    DEVELOPMENT VERSION
    -----------------------------------------------------
    Currently password is plain text.

    Later replace with

    password_verify()

    */

    if ($password == $user['password']) {

        // Store Session

        $_SESSION['id'] = $user['id'];

        $_SESSION['name'] = $user['name'];

        $_SESSION['email'] = $user['email'];

        $_SESSION['role'] = $user['role'];

        $_SESSION['department'] = $user['department'];

        // Redirect based on Role

        if ($user['role'] == "principal") {

            header("Location: ../principal/dashboard.php");

        } elseif ($user['role'] == "teacher") {

            header("Location: ../teacher/dashboard.php");

        } else {

            session_destroy();

            header("Location: ../index.php?error=Invalid Role");

        }

        exit();

    } else {

        header("Location: ../index.php?error=Incorrect Password");
        exit();

    }

} else {

    header("Location: ../index.php?error=Email Not Found");
    exit();

}

?>