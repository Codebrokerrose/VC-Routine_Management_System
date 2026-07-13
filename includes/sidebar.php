<?php

$currentPage = basename($_SERVER['PHP_SELF']);

?>

<!-- ======================================
        SIDEBAR START
======================================= -->

<div class="sidebar">

    <!-- Logo -->

    <div class="sidebar-logo">

        <img
            src="../assets/images/logo.jfif"
            alt="College Logo">

        <h4>VC RMS</h4>

        <small>Routine Management</small>

    </div>

    <!-- Navigation -->

    <ul class="sidebar-menu">

        <!-- Dashboard -->

        <li>

            <a
                href="dashboard.php"

                class="<?= ($currentPage == "dashboard.php") ? 'active' : ''; ?>">

                <i class="bi bi-speedometer2"></i>

                <span>Dashboard </span>

            </a>

        </li>

        <!-- Routine -->

        <li>

            <a
                href="routine.php"

                class="<?= ($currentPage == "routine.php") ? 'active' : ''; ?>">

                <i class="bi bi-calendar-week"></i>

               <span> Routine</span>

            </a>

        </li>

        <!-- Calendar -->

        <li>

            <a
                href="calendar.php"

                class="<?= ($currentPage == "calendar.php") ? 'active' : ''; ?>">

                <i class="bi bi-calendar-event"></i>

               <span> Calendar </span>

            </a>

        </li>

        <!-- Logout -->

        <li class="logout-item">

            <a
                href="../auth/logout.php">

                <i class="bi bi-box-arrow-right"></i>

               <span> Logout</span>

            </a>

        </li>

    </ul>

</div>

<!-- ======================================
        SIDEBAR END
======================================= -->