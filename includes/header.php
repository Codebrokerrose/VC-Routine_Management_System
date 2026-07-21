<?php

/*
==========================================
Header
==========================================
*/


if (!isset($userName) || empty($userName)) {
    $userName = "Principal";
}

date_default_timezone_set("Asia/Kolkata");

$currentDate = date("l, d F Y");
$currentTime = date("h:i A");

?>

<header class="top-navbar">

    <div class="navbar-left">

        <button class="menu-toggle d-lg-none">

            <i class="bi bi-list"></i>

        </button>

        <div class="page-title">

            <h3>

                Routine Management System

            </h3>

            <span>

                Vivekananda College

            </span>

        </div>

    </div>

    <div class="navbar-right">

        <!-- Date -->

        <div class="date-box">

            <i class="bi bi-calendar-event"></i>

            <?= $currentDate; ?>

        </div>

        <!-- Time -->

        <div class="time-box">

            <i class="bi bi-clock"></i>

            <?= $currentTime; ?>

        </div>

        <!-- Notification -->

        <!-- <div class="notification">

            <button class="btn notification-btn">

                <i class="bi bi-bell-fill"></i>

                <span class="notification-badge">

                    3

                </span>

            </button>

        </div> -->

        <!-- Profile -->

        <div class="dropdown">

            <button class="btn profile-btn dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle" class="profile-img" ></i>

               

                <span>

                    <?= e($userName); ?>

                </span>

            </button>

            <ul class="dropdown-menu dropdown-menu-end shadow">

                <li>

                    <h6 class="dropdown-header">

                        <?= e($userName); ?>

                    </h6>

                </li>

                <li>

                    <span class="dropdown-item-text">

                        Principal

                    </span>

                </li>

                <li>

                    <hr class="dropdown-divider">

                </li>

                <!-- <li>

                    <a href="profile.php" class="dropdown-item">

                        <i class="bi bi-person-circle"></i>

                        My Profile

                    </a>

                </li> -->

                <li>

                    <a href="../auth/logout.php" class="dropdown-item text-danger">

                        <i class="bi bi-box-arrow-right"></i>

                        Logout

                    </a>

                </li>

            </ul>

        </div>

    </div>

</header>