<?php

require_once("../config/session.php");
require_once("../config/functions.php");

/*
====================================================
Dashboard Statistics
====================================================
*/

$totalDepartments = getDepartmentCount();
// $totalSemesters = getSemesterCount();
$totalClasses = getTodayClassCount();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>

        Principal Dashboard

    </title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/header_sidebar.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>

<!-- Event Details Modal -->

<div class="modal fade" id="eventModal" tabindex="-1">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

Event Details

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<h5 id="eventTitle"></h5>

<hr>

<p>

<strong>Date :</strong>

<span id="eventDate"></span>

</p>

<p>

<strong>Description :</strong>

</p>

<p id="eventDescription"></p>

</div>

<div class="modal-footer">

<button
class="btn btn-secondary"
data-bs-dismiss="modal">

Close

</button>

</div>

</div>

</div>

</div>

<body>

    <div class="wrapper">

        <!-- ===========================================
SIDEBAR
=========================================== -->

        <?php include("../includes/sidebar.php"); ?>

        <!-- ===========================================
MAIN CONTENT
=========================================== -->

        <div class="main-content">

            <!-- ===========================================
HEADER
=========================================== -->

            <?php include("../includes/header.php"); ?>

            <div class="container-fluid dashboard-container">

                <!-- ===========================================
PAGE HEADER
=========================================== -->

                <div class="dashboard-header">

                    <div>

                        <h1>

                            Dashboard

                        </h1>

                        <p>

                            Welcome back,

                            <strong>

                                <?= htmlspecialchars($userName); ?>

                            </strong>

                        </p>

                    </div>

                    <!-- <div class="current-date">

                        <i class="bi bi-calendar-event"></i>

                        <?= date("l, d F Y"); ?>

                    </div> -->

                </div>

                <!-- ===========================================
DASHBOARD CARDS
=========================================== -->

                <div class="row g-4">

                    <!-- Department Card -->

                    <div class="col-lg-4 col-md-6">

                        <div class="dashboard-card">

                            <div class="card-top">

                                <div class="icon-circle department">

                                    <i class="bi bi-building-fill"></i>

                                </div>

                                <div class="card-content">

                                    <h2>

                                        <?= $totalDepartments; ?>

                                    </h2>

                                    <p>

                                        Departments

                                    </p>

                                </div>

                            </div>

                           

                        </div>

                    </div>

                    <!-- Semester Card -->

                    <!-- <div class="col-lg-4 col-md-6">

                        <div class="dashboard-card">

                            <div class="card-top">

                                <div class="icon-circle semester">

                                    <i class="bi bi-mortarboard-fill"></i>

                                </div>

                                <div class="card-content">

                                    <h2>

                                      
                                    </h2>

                                    <p>

                                        Semesters

                                    </p>

                                </div>

                            </div>

                            

                        </div>

                    </div> -->

                    <!-- Today's Classes -->

                    <div class="col-lg-4 col-md-12">

                        <div class="dashboard-card">

                            <div class="card-top">

                                <div class="icon-circle class">

                                    <i class="bi bi-calendar2-week-fill"></i>

                                </div>

                                <div class="card-content">

                                    <h2>

                                        <?= $totalClasses; ?>

                                    </h2>

                                    <p>

                                        Today's Classes

                                    </p>

                                </div>

                            </div>

                           

                        </div>

                    </div>

                </div>

                <!-- ===========================================
SCHEDULE + CALENDAR ROW
=========================================== -->

                <div class="row mt-4">

                    <!-- ===========================
TODAY'S SCHEDULE
=========================== -->

                    <div class="col-lg-8">

                        <div class="dashboard-box">

                            <div class="box-header">

                                <div>

                                    <i class="bi bi-calendar-week-fill"></i>

                                    Today's Schedule Overview

                                </div>

                                <a href="routine.php">

                                    View Full Schedule

                                </a>

                            </div>

                            <div class="box-body">

                                <table class="table schedule-table align-middle">

                                    <thead>

                                        <tr>

                                            <th>Time</th>

                                            <th>Department</th>

                                            <th>Semester</th>

                                            <th>Subject</th>

                                            <th>Teacher</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <!--
PART 1B STARTS HERE

Next response will generate

Dynamic Schedule

Calendar

Footer

Javascript

Closing Tags

-->

<?php

$schedule = getTodaySchedule();

if (mysqli_num_rows($schedule) > 0) {

    while ($row = mysqli_fetch_assoc($schedule)) {

        ?>

        <tr>


            <td>

                <?= formatTime($row['start_time']); ?>

                -

                <?= formatTime($row['end_time']); ?>

            </td>

            <td>

                <span class="department-badge">

                    <?= e($row['department_name']); ?>

                </span>

            </td>

            <td>

                <?= e($row['semester_name']); ?>

            </td>

            <td>

                <?= e($row['subject']); ?>

            </td>

            <td>

                <?= e($row['teacher_name']); ?>

            </td>

        </tr>

        <?php

    }

} else {

    ?>

    <tr>

        <td colspan="5" class="text-center text-muted">
    No Upcoming Classes
</td>

    </tr>

    <?php

}

?>

</tbody>

</table>

</div>

</div>

</div>

<!-- ===========================================
ACADEMIC CALENDAR
=========================================== -->

<div class="col-lg-4">

    <div class="dashboard-box">

        <div class="box-header">

            <div>

                <i class="bi bi-calendar3"></i>

                Calendar

            </div>

        </div>

        <div class="box-body">

            <div id="calendar"></div>

            <hr class="my-4">

            <h6 class="event-heading">

                Upcoming Events

            </h6>

            <?php

            $upcomingEvents = getUpcomingEvents();

            if (mysqli_num_rows($upcomingEvents) > 0) {

                while ($event = mysqli_fetch_assoc($upcomingEvents)) {

                    ?>

                    <div class="event-card">

                        <div class="event-date">

                            <?= formatDate($event['event_date']); ?>

                        </div>

                        <div class="event-title">

                            <?= e($event['title']); ?>

                        </div>

                        <div class="event-description">

                            <?= e($event['description']); ?>

                        </div>

                    </div>

                    <?php

                }

            } else {

                ?>

                <p class="text-muted">

                    No Upcoming Events

                </p>

                <?php

            }

            ?>

        </div>

    </div>

</div>

</div>

</div>

<!-- ===============================
END MAIN CONTENT
=============================== -->

</div>

<?php

$calendarEvents = getCalendarEvents();

?>

<script>

    const calendarEvents = [

<?php

$first = true;

while ($event = mysqli_fetch_assoc($calendarEvents)) {

    if (!$first) {
        echo ",";
    }

    ?>

            {
                title: "<?= addslashes($event['title']); ?>",
                start: "<?= $event['event_date']; ?>",
                description: "<?= addslashes($event['description']); ?>"
            }

                <?php

                $first = false;
}

?>

];

</script>


<!-- ===============================
Bootstrap
=============================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Calendar Library -->

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<!-- Dashboard JS -->

<script src="../assets/js/dashboard.js"></script>



</body>

</html>

