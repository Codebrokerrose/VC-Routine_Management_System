<?php

require_once("../config/session.php");
require_once("../config/calendar_functions.php");

/*=========================================
LOAD DATA
=========================================*/


?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>

        Academic Calendar

    </title>

    <!-- Bootstrap -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Theme CSS -->

    <link rel="stylesheet" href="../assets/css/header_sidebar.css">

    <link rel="stylesheet" href="../assets/css/calendar.css">

    <!-- FullCalendar -->

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

</head>

<body>

    <div class="wrapper">

        <!-- =====================================
SIDEBAR
===================================== -->

        <?php include("../includes/sidebar.php"); ?>

        <!-- =====================================
MAIN CONTENT
===================================== -->

        <div class="main-content">

            <!-- =====================================
HEADER
===================================== -->

            <?php include("../includes/header.php"); ?>

            <div class="routine-container">


                <!-- =====================================
FILTER CARD
===================================== -->

                <div class="filter-card">

                    <div class="row g-3 align-items-end">

                         <div>

                        <h2>

                            Academic Calendar

                        </h2>

                        <p>

                            Manage College Events, Holidays & Activities

                        </p>

                    </div>

                        <div class="col-lg-12 text-end">

                            <button class="btn btn-primary" id="addEventBtn">

                                <i class="bi bi-plus-circle-fill"></i>

                                Add Event

                            </button>

                        </div>

                    </div>

                </div>

                <!-- =====================================
CALENDAR CARD
===================================== -->

                <div class="routine-card">

                    <div class="routine-card-header d-flex justify-content-between align-items-center">

                        <h5>

                            College Academic Calendar

                        </h5>

                    </div>

                    <div class="routine-body">

                        <div id="calendar"></div>

                    </div>

                </div>

                <!-- =====================================
ADD EVENT MODAL
===================================== -->

<div
class="modal fade"
id="addEventModal"
tabindex="-1">

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5 class="modal-title">

<i class="bi bi-plus-circle-fill"></i>

Add Academic Event

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>

</div>

<form id="addEventForm">

<div class="modal-body">

<div class="row">

<div class="col-12">

<label class="form-label">

Event Title

</label>

<input
type="text"
name="title"
class="form-control"
required>

</div>


<div class="col-md-6 mt-3">

<label class="form-label">

Event Date

</label>

<input
type="date"
name="event_date"
class="form-control"
required>

</div>

<div class="col-md-6 mt-3">

<label class="form-label">

Event Type

</label>

<select
name="event_type"
class="form-select">

<option value="Meeting">Meeting</option>

<option value="Holiday">Holiday</option>

<option value="Seminar">Seminar</option>

<option value="Workshop">Workshop</option>

<option value="Examination">Examination</option>

<option value="Festival">Festival</option>

<option value="Other">Other</option>

</select>

</div>

<div class="col-12 mt-3">

<label class="form-label">

Description

</label>

<textarea
name="description"
class="form-control"
rows="4"></textarea>

</div>

</div>

</div>

<div class="modal-footer">

<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal">

Cancel

</button>

<button
type="submit"
class="btn btn-primary">

<i class="bi bi-check-circle-fill"></i>

Save Event

</button>

</div>

</form>

</div>

</div>

</div>

<!-- =====================================
EDIT EVENT MODAL
===================================== -->

<div
class="modal fade"
id="editEventModal"
tabindex="-1">

<div class="modal-dialog modal-lg modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5>

Edit Event

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>

</div>

<form id="editEventForm">

<div
class="modal-body"
id="editEventContent">

<!-- AJAX -->

</div>

</form>

</div>

</div>

</div>

<!-- =====================================
EVENT DETAILS
===================================== -->

<div
class="modal fade"
id="eventModal"
tabindex="-1">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-header">

<h5>

Event Details

</h5>

<button
type="button"
class="btn-close"
data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body">

<h4 id="eventTitle"></h4>

<hr>

<p>

<strong>Date :</strong>

<span id="eventDate"></span>

</p>



<p>

<strong>Description</strong>

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

<!-- =====================================
DELETE MODAL
===================================== -->

<div
class="modal fade"
id="deleteModal"
tabindex="-1">

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">

<div class="modal-header bg-danger text-white">

<h5>

Delete Event

</h5>

<button
type="button"
class="btn-close btn-close-white"
data-bs-dismiss="modal">

</button>

</div>

<div class="modal-body text-center">

<i
class="bi bi-trash-fill"
style="font-size:70px;color:#dc3545;">

</i>

<h5 class="mt-3">

Delete this Event?

</h5>

<p>

This action cannot be undone.

</p>

</div>

<div class="modal-footer">

<button
class="btn btn-secondary"
data-bs-dismiss="modal">

Cancel

</button>

<button
id="confirmDelete"
class="btn btn-danger">

Delete

</button>

</div>

</div>

</div>

</div>

<input
type="hidden"
id="deleteEventID">

</div>

</div>

</div>

<?php

$calendarEvents = getCalendarEvents();

?>

<script>

const calendarEvents=[

<?php

$first = true;

while ($event = mysqli_fetch_assoc($calendarEvents)) {

    if (!$first)
        echo ",";

    ?>

    {

    id:"<?= $event['event_id']; ?>",

    title:"<?= addslashes($event['title']); ?>",

    start:"<?= $event['event_date']; ?>",

    event_type:"<?= $event['event_type']; ?>",

    description:"<?= addslashes($event['description']); ?>"

    }

    <?php

    $first = false;

}

?>

];

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

<script src="../assets/js/calendar.js"></script>

</body>

</html>