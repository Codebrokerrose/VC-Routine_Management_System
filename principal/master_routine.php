<?php

session_start();


require_once("../config/master_functions.php");

/*=========================================
DEFAULT FILTER
=========================================*/

$sessionType = $_GET['session'] ?? "Odd";

$courseType = $_GET['course'] ?? "BA";

$semester = $_GET['semester'] ?? ($sessionType == "Odd" ? 1 : 2);

/*=========================================
LOAD COMMON DATA
=========================================*/

$workingDays = getWorkingDays();
$timeSlots = getTimeSlots();


/*=========================================
LOAD ROUTINE
=========================================*/

$routine = [];

$data = getMasterRoutine(

    $sessionType,

    $courseType,

    $semester

);

foreach($data as $row){

    $routine
    [$row['day']]
    [$row['start_time']]
    = $row;

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1">

<title>

Master Routine

</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link
href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
rel="stylesheet">

<link
rel="stylesheet"
href="../assets/css/header_sidebar.css">

<link
rel="stylesheet"
href="../assets/css/routine.css">

</head>

<body>

<div class="wrapper">

<?php include("../includes/sidebar.php"); ?>

<div class="main-content">

<?php include("../includes/header.php"); ?>

<div class="routine-container">

<!-- =====================================
PAGE HEADER
===================================== -->

<div class="page-header">

<div>

<h2>

Master Routine

</h2>

<p>

Manage College Master Routine

</p>

</div>

<div class="today-date">

<i class="bi bi-calendar-event"></i>

<?= date("l, d F Y"); ?>

</div>

</div>

<!-- =====================================
FILTER
===================================== -->

<div class="filter-card">

<div class="row g-3">

<div class="col-lg-4">

<label class="form-label">

Session

</label>

<select
id="session"
class="form-select">

<option
value="Odd"

<?= $sessionType=="Odd"?"selected":""; ?>>

Odd Semester

</option>

<option
value="Even"

<?= $sessionType=="Even"?"selected":""; ?>>

Even Semester

</option>

</select>

</div>

<div class="col-lg-4">

<label class="form-label">

Course

</label>

<select
id="course"
class="form-select">

<option
value="BA"

<?= $courseType=="BA"?"selected":""; ?>>

BA

</option>

<option
value="BSc"

<?= $courseType=="BSc"?"selected":""; ?>>

BSc

</option>

</select>

</div>

<div class="col-lg-4">

<label class="form-label">

Semester

</label>

<select
id="semester"
class="form-select">

<?php

foreach(

getMasterSemesters(

$sessionType

)

as $sem){

?>

<option

value="<?= $sem; ?>"

<?= $semester==$sem?"selected":""; ?>>

Semester <?= $sem; ?>

</option>

<?php

}

?>

</select>

</div>

</div>

</div>

<!-- =====================================
ROUTINE INFO
===================================== -->

<div class="routine-info">

    <div class="info-box">

        <i class="bi bi-calendar3"></i>

        <div>

            <span>Session</span>

            <h6 id="infoSession">

                <?= $sessionType; ?>

            </h6>

        </div>

    </div>

    <div class="info-box">

        <i class="bi bi-book"></i>

        <div>

            <span>Course</span>

            <h6 id="infoCourse">

                <?= $courseType; ?>

            </h6>

        </div>

    </div>

    <div class="info-box">

        <i class="bi bi-mortarboard-fill"></i>

        <div>

            <span>Semester</span>

            <h6 id="infoSemester">

                Semester
                <?= $semester; ?>

            </h6>

        </div>

    </div>

    

</div>

<!-- =====================================
MASTER ROUTINE TABLE
===================================== -->

<div class="routine-card">

    <div class="routine-card-header d-flex justify-content-between align-items-center">

        <h5>

            Weekly Master Routine

        </h5>


    </div>

    <div class="routine-body">

        <table class="table table-bordered routine-table">

            <thead>

                <tr>

                    <th class="time-cell">

                        Time

                    </th>

                    <?php foreach ($workingDays as $day) { ?>

                        <th>

                            <?= $day ?>

                        </th>

                    <?php } ?>

                </tr>

            </thead>

            <tbody id="routineBody">

                <?php

                foreach ($timeSlots as $slot) {

                    ?>

                    <tr>

                        <td class="time-cell">

                            <strong>

                                <?= formatTime($slot[0]); ?>

                            </strong>

                            <br>

                            <?= formatTime($slot[1]); ?>

                        </td>

                        <?php

                        foreach ($workingDays as $day) {

                            ?>

                            <td>

                                <?php

                                if (isset($routine[$day][$slot[0]])) {

                                    $class = $routine[$day][$slot[0]];

                                    $color = "";

                                    switch (strtolower(trim($class['course_name']))) {

                                        case "major":

                                            $color = "#18a9e6";

                                            break;

                                        case "minor":

                                        case "minor-1":

                                        case "minor-2":

                                            $color = "#fff200";

                                            break;

                                        case "idc":

                                            $color = "#ffbe33";

                                            break;

                                        case "aec":

                                            $color = "#ff5b57";

                                            break;

                                        case "cvac":

                                            $color = "#ff5b57";

                                            break;

                                        case "add-on":

                                        case "add-on course":

                                        case "add-on courses":

                                            $color = "#f4a261";

                                            break;

                                        default:

                                            $color = "#6B1322";

                                    }

                                    ?>

                                    <div class="routine-box" style="background:<?= $color ?>;color:#000;min-height:110px;">

                                        <div style="font-weight:700;font-size:17px;">

                                            <?= htmlspecialchars($class['course_name']); ?>

                                        </div>

                                        <div style="font-weight:600;">

                                            <?= htmlspecialchars($class['class_type']); ?>

                                        </div>

                                        <?php if (!empty($class['subject'])) { ?>

                                            <div style="font-size:13px;font-weight:600;">

                                                <?= htmlspecialchars($class['subject']); ?>

                                            </div>

                                        <?php } ?>

                                        <?php if (!empty($class['description'])) { ?>

                                            <div style="font-size:12px;">

                                                <?= nl2br(htmlspecialchars($class['description'])); ?>

                                            </div>

                                        <?php } ?>

                                        <div class="routine-actions mt-2">

                                            <button class="btn btn-sm btn-primary editClass"
                                                data-id="<?= $class['master_routine_id']; ?>">

                                                <i class="bi bi-pencil"></i>

                                            </button>

                                            <button class="btn btn-sm btn-danger deleteClass"
                                                data-id="<?= $class['master_routine_id']; ?>">

                                                <i class="bi bi-trash"></i>

                                            </button>

                                        </div>

                                    </div>

                                    <?php

                                } else {

                                    ?>

                                    <div class="addSlot" data-day="<?= $day; ?>" data-start="<?= $slot[0]; ?>"
                                        data-end="<?= $slot[1]; ?>">

                                        <i class="bi bi-plus-lg"></i>

                                    </div>

                                    <?php

                                }

                                ?>

                            </td>

                            <?php

                        }

                        ?>

                    </tr>

                    <?php

                }

                ?>

            </tbody>

        </table>

    </div>

</div>

<!-- =====================================
ROUTINE NOTES
===================================== -->

<!-- =====================================
MASTER ROUTINE NOTES
===================================== -->
<!-- =====================================
MASTER ROUTINE NOTES
===================================== -->

<?php
$note = getMasterRoutineNote();
?>

<div class="card mt-4">

    <div class="card-header d-flex justify-content-between align-items-center">

        <h5 class="mb-0">Master Routine Notes</h5>

        <button type="button" id="updateNoteBtn" class="btn btn-primary">
            <i class="bi bi-save"></i> Update
        </button>

    </div>

    <div class="card-body">

        <div id="masterRoutineNotes">

            <textarea id="masterRoutineNote" class="form-control" rows="5" placeholder="Write routine notes here..."><?=
                $note ? htmlspecialchars($note['note']) : "";
            ?></textarea>

        </div>

    </div>

</div>

<!-- =====================================
HIDDEN VALUES
===================================== -->

<input type="hidden" id="session_type" value="<?= $sessionType; ?>">

<input type="hidden" id="course_type" value="<?= $courseType; ?>">

<input type="hidden" id="semester_id" value="<?= $semester; ?>">

<!-- =====================================
ADD CLASS MODAL
===================================== -->

<div class="modal fade" id="addClassModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-plus-circle-fill"></i>

                    Add Master Routine

                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal">

                </button>

            </div>

            <form id="addClassForm">

                <div class="modal-body">

                    <input type="hidden" name="session_type" id="add_session_type" value="<?= $sessionType; ?>">

                    <input type="hidden" name="course_type" id="add_course_type" value="<?= $courseType; ?>">

                    <input type="hidden" name="semester" id="add_semester" value="<?= $semester; ?>">

                    <div class="row">

                        <!-- DAY -->

                        <div class="col-md-6">

                            <label class="form-label">

                                Day

                            </label>

                            <select name="day" id="day" class="form-select" required>

                                <?php

                                foreach ($workingDays as $day) {

                                    ?>

                                    <option value="<?= $day ?>">

                                        <?= $day ?>

                                    </option>

                                    <?php

                                }

                                ?>

                            </select>

                        </div>

                        <!-- START TIME -->

                        <div class="col-md-3">

                            <label class="form-label">

                                Start Time

                            </label>

                            <input type="time" name="start_time" id="start_time" class="form-control" required>

                        </div>

                        <!-- END TIME -->

                        <div class="col-md-3">

                            <label class="form-label">

                                End Time

                            </label>

                            <input type="time" name="end_time" id="end_time" class="form-control" required>

                        </div>

                        <!-- COURSE -->

                        <div class="col-md-6 mt-3">

                            <label class="form-label">

                                Course Name

                            </label>

                            <select name="course_name" class="form-select" required>

                                <option value="Major">

                                    Major

                                </option>

                                <option value="Minor">

                                    Minor

                                </option>

                                <option value="Minor-1">

                                    Minor-1

                                </option>

                                <option value="Minor-2">

                                    Minor-2

                                </option>

                                <option value="IDC">

                                    IDC

                                </option>

                                <option value="AEC">

                                    AEC

                                </option>

                                <option value="CVAC">

                                    CVAC

                                </option>

                                <option value="Add-On">

                                    Add-On

                                </option>

                            </select>

                        </div>

                        <!-- CLASS TYPE -->

                        <div class="col-md-6 mt-3">

                            <label class="form-label">

                                Class Type

                            </label>

                            <select name="class_type" class="form-select">

                                <option value="Th">

                                    Theory

                                </option>

                                <option value="Th/Tu">

                                    Theory / Tutorial

                                </option>

                                <option value="Practical">

                                    Practical

                                </option>

                                <option value="Workshop">

                                    Workshop

                                </option>

                                <option value="Lab">

                                    Lab

                                </option>

                            </select>

                        </div>

                        <!-- SUBJECT -->

                        <div class="col-md-6 mt-3">

                            <label class="form-label">

                                Subject

                            </label>

                            <input type="text" name="subject" class="form-control"
                                placeholder="CC1 / SEC1 / IDC / CVAC">

                        </div>

                        <!-- DESCRIPTION -->

                        <div class="col-md-6 mt-3">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea name="description" class="form-control" rows="3"
                                placeholder="ENGC / EVS / Add-On / Other details"></textarea>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit" class="btn btn-primary">

                        <i class="bi bi-check-circle"></i>

                        Save Class

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- =====================================
EDIT CLASS MODAL
===================================== -->

<div
class="modal fade"
id="editClassModal"
tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-pencil-square"></i>

                    Edit Master Routine

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">

                </button>

            </div>

            <form id="editClassForm">

                <input
                    type="hidden"
                    id="editRoutineID"
                    name="master_routine_id">

                <div
                    class="modal-body"
                    id="editFormContent">

                    <!-- AJAX LOAD -->

                </div>

                

            </form>

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

                    Delete Class

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
                    style="font-size:65px;color:#dc3545">

                </i>

                <h5 class="mt-3">

                    Delete this class?

                </h5>

                <p class="text-muted">

                    This action cannot be undone.

                </p>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
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

</div>

</div>

</div>

<!-- =====================================
SCRIPTS
===================================== -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="../assets/js/master_routine.js"></script>

</body>

</html>