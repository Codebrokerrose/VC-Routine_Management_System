<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | VC Routine Management System</title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body {

            background: #F7F2E8;

            font-family: Poppins, sans-serif;

        }

        .forgot-card {

            max-width: 500px;

            margin: 80px auto;

            background: #FFFDFC;

            border-radius: 20px;

            padding: 40px;

            box-shadow: 0 15px 35px rgba(0, 0, 0, .08);

            border: 1px solid #D9C8A8;

        }

        .icon {

            font-size: 70px;

            color: #6B1322;

        }

        h2 {

            color: #6B1322;

            font-weight: 700;

        }

        .btn-theme {

            background: #6B1322;

            color: white;

            border: none;

        }

        .btn-theme:hover {

            background: #8D2135;

            color: white;

        }
    </style>

</head>

<body>

    <div class="container">

        <div class="forgot-card text-center">

            <i class="bi bi-shield-lock-fill icon"></i>

            <h2 class="mt-3">
                Forgot Password
            </h2>

            <p class="text-muted">

                This Routine Management System is an internal
                application of Vivekananda College.

            </p>

            <div class="alert alert-warning mt-4">

                <strong>Password Reset is disabled.</strong>

                <br><br>

                Please contact the
                <strong>Principal</strong>
                or
                <strong>System Administrator</strong>

                to reset your password.

            </div>

            <div class="mt-4">

                <a href="../index.php" class="btn btn-theme">

                    <i class="bi bi-arrow-left-circle"></i>

                    Back to Login

                </a>

            </div>

        </div>

    </div>

</body>

</html>