<?php

require_once("../config/session.php");
require_once("../config/functions.php");

/*====================================================
    DEFAULT SELECTION
=====================================================*/

$department_id = isset($_GET['department'])
    ? (int) $_GET['department']
    : 1;

/*
Instead of assuming Semester 1,
load the FIRST semester of the
selected department automatically.
*/

$semester_id = 0;

/*====================================================
    LOAD DEPARTMENTS
=====================================================*/

$departments = getDepartments();

/*====================================================
    LOAD SEMESTERS
=====================================================*/

$semesters = getSemesters();
$tempSemesters = getSemesters();

if (isset($_GET['semester'])) {

    $semester_id = (int) $_GET['semester'];

} else {

    if ($firstSemester = mysqli_fetch_assoc($tempSemesters)) {

        $semester_id = $firstSemester['semester_id'];

    }

}

/*====================================================
    CURRENT DETAILS
=====================================================*/

$currentDepartment = getDepartment($department_id);

$currentSemester = getSemester($semester_id);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>

        Routine Management

    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/header_sidebar.css">

    <link rel="stylesheet" href="../assets/css/routine.css">

</head>

<body>

    <div class="wrapper">

        <?php include("../includes/sidebar.php"); ?>

        <div class="main-content">

            <?php include("../includes/header.php"); ?>

            <div class="routine-container">

                <!-- ==========================================
PAGE HEADER
========================================== -->

                <div class="page-header">

                    <div>

                        <h2>

                            Routine Management

                        </h2>

                        <p>

                            Manage Department Class Routine

                        </p>

                    </div>

                    <div class="today-date">

                        <i class="bi bi-calendar-event"></i>

                        <?= date("l, d F Y"); ?>

                    </div>

                </div>

                <!-- ==========================================
FILTER SECTION
========================================== -->

                <div class="filter-card">

                    <form id="filterForm" class="row g-3 align-items-end" method="GET">

                        <!-- Department -->

                        <div class="col-lg-4">

                            <label for="department" class="form-label">
    Department
</label>

                            <select class="form-select" id="department" name="department">

                                <?php

                                mysqli_data_seek($departments, 0);

                                while ($dept = mysqli_fetch_assoc($departments)) {

                                    ?>

                                    <option value="<?= $dept['department_id']; ?>"
                                        <?= ($department_id == $dept['department_id']) ? 'selected' : ''; ?>

                                        >

                                        <?= e($dept['department_name']); ?>

                                    </option>

                                    <?php

                                }

                                ?>

                            </select>

                        </div>

                        <!-- Semester -->

                        <div class="col-lg-4">

                            <label class="form-label" for="semester">

                                Semester

                            </label>

                            <select class="form-select" id="semester" name="semester">

                                <?php

                                mysqli_data_seek($semesters, 0);

                                while ($sem = mysqli_fetch_assoc($semesters)) {

                                    ?>

                                    <option value="<?= $sem['semester_id']; ?>"
                                        <?= ($semester_id == $sem['semester_id']) ? 'selected' : ''; ?>

                                        >

                                        <?= e($sem['semester_name']); ?>

                                    </option>

                                    <?php

                                }

                                ?>

                            </select>

                        </div>

                        <!-- Button -->

                        <!-- <div class="col-lg-4 text-end">

                            <button type="button" class="btn btn-primary" id="addClassBtn">

                                <i class="bi bi-plus-circle"></i>

                                Add Class

                            </button>

                        </div> -->

                    </form>

                </div>

                <!-- =====================================
        ROUTINE INFORMATION
===================================== -->

<div class="routine-info">

    <div class="info-box">

        <i class="bi bi-building"></i>

        <div>

            <span>Department</span>

            <h6 id="infoDepartment">
                    <?= e($currentDepartment['department_name']); ?>
            </h6>
                
                        </div>
                
                    </div>
                
                    <div class="info-box">
                
                        <i class="bi bi-mortarboard-fill"></i>
                
                        <div>
                
                            <span>Semester</span>
                
                            <h6 id="infoSemester">
                                 <?= e($currentSemester['semester_name']); ?>
                            </h6>
                
                        </div>
                
                    </div>
                
                    <div class="info-box">
                
                        <i class="bi bi-calendar-week"></i>
                
                        <div>
                
                            <span>Working Days</span>
                
                            <h6>Monday - Saturday</h6>
                
                        </div>
                
                    </div>
                
                    <div class="info-box">
                
                        <i class="bi bi-clock"></i>
                
                        <div>
                
                            <span>Total Classes</span>
                
                            <h6 id="infoTotalClasses">
                                <?= getRoutineCount($department_id, $semester_id); ?>
                            </h6>
                
                        </div>
                
                    </div>
                
                </div>
                
                <!-- =====================================
                        ROUTINE TABLE
                ===================================== -->
                
                <div class="routine-card">
                
                    <div class="routine-card-header">
                
                        <h5>
                
                            Weekly Routine
                
                        </h5>
                
                    </div>
                <div class="routine-table-wrapper">
                    <div class="table-responsive">
                
                        <table class="table table-bordered routine-table">
                
                            <thead>
                
                                <tr>
                
                                    <th width="140">
                
                                        Time
                
                                    </th>
                
                                    <th>Monday</th>
                
                                    <th>Tuesday</th>
                
                                    <th>Wednesday</th>
                
                                    <th>Thursday</th>
                
                                    <th>Friday</th>
                
                                    <th>Saturday</th>
                
                                </tr>
                
                            </thead>
                
                            <tbody id="routineBody">
                
                                <?php

                                $days = [

                                    "Monday",
                                    "Tuesday",
                                    "Wednesday",
                                    "Thursday",
                                    "Friday",
                                    "Saturday"

                                ];

                                $timeSlots = getTimeSlots();

                                $routine = [];

                                $result = getRoutineByDepartmentSemester(

                                    $department_id,

                                    $semester_id

                                );

                                while ($row = mysqli_fetch_assoc($result)) {

                                    $routine
                                    [$row['day']]
                                    [$row['start_time']]
                                        = $row;

                                }

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

                                        foreach ($days as $day) {

                                            ?>
                
                                            <td>
                
                                                <?php

                                                if (isset($routine[$day][$slot[0]])) {

                                                    $class = $routine[$day][$slot[0]];

                                                    ?>
                
                                                    <div class="routine-box" data-id="<?= $class['routine_id']; ?>">
                
                                                        <div class="subject">
                
                                                            <?= e($class['subject']); ?>
                
                                                        </div>
                
                                                        <div class="teacher">
                
                                                            <?= e($class['teacher_name']); ?>
                
                                                        </div>
                
                                                        <div class="room">
                
                                                            Room :
                
                                                            <?= e($class['room_no']); ?>
                
                                                        </div>
                
                                                        <div class="routine-buttons">
                
                                                            <button class="btn btn-sm btn-primary editClass" data-id="<?= $class['routine_id']; ?>">
                
                                                                <i class="bi bi-pencil"></i>
                
                                                            </button>
                
                                                            <button class="btn btn-sm btn-danger deleteClass"
                                                                data-id="<?= $class['routine_id']; ?>">
                
                                                                <i class="bi bi-trash"></i>
                
                                                            </button>
                
                                                        </div>
                
                                                    </div>
                
                                                    <?php

                                                } else {

                                                    ?>
                
                                                    <button class="btn btn-light addSlot" data-day="<?= $day; ?>" data-start="<?= $slot[0]; ?>"
                                                        data-end="<?= $slot[1]; ?>">
                
                                                        <i class="bi bi-plus-lg"></i>
                
                                                    </button>
                
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
                
                </div>
                <!-- ==========================================================
                    ADD CLASS MODAL
========================================================== -->

<div class="modal fade" id="addClassModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-plus-circle-fill"></i>

                    Add New Class

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form id="addClassForm">

                <div class="modal-body">

                    <!-- Hidden Values -->

                    <input
                        type="hidden"
                        id="department_id"
                        name="department_id"
                        value="<?= $department_id; ?>">

                    <input
                        type="hidden"
                        id="semester_id"
                        name="semester_id"
                        value="<?= $semester_id; ?>">

                    <div class="row">

                        <!-- Day -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label" for="day">

                                Day

                            </label>

                            <select
                                class="form-select"
                                id="day"
                                name="day"
                                required>

                                <option value="">Select Day</option>

                                <option>Monday</option>

                                <option>Tuesday</option>

                                <option>Wednesday</option>

                                <option>Thursday</option>

                                <option>Friday</option>

                                <option>Saturday</option>

                            </select>

                        </div>

                        <!-- Class Date -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label" for="class_date">

                                Class Date

                            </label>

                            <input
                                type="date"
                                class="form-control"
                                name="class_date"
                                id="class_date"
                                required>

                        </div>

                        <!-- Start -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label" for="start_time">

                                Start Time

                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="start_time"
                                name="start_time"
                                required>

                        </div>

                        <!-- End -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                End Time

                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="end_time"
                                name="end_time"
                                required>

                        </div>

                        <!-- Subject -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label" for="subject">

                                Subject

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="subject"
                                id="subject"
                                required>

                        </div>

                        <!-- Teacher -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label" for="teacher_name">

                                Teacher Name

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="teacher_name"
                                id="teacher_name"
                                required>

                        </div>

                        <!-- Room -->

                        <div class="col-md-6 mb-3">

                            <label class="form-label" for="room_no">

                                Room Number

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="room_no"
                                id="room_no"
                                required>

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                        type="button">

                        Cancel

                    </button>

                    <button
                        class="btn btn-primary"
                        type="submit">

                        <i class="bi bi-check-circle"></i>

                        Save Class

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- ==========================================================
                    EDIT CLASS MODAL
========================================================== -->

<div class="modal fade" id="editClassModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    <i class="bi bi-pencil-square"></i>

                    Edit Class

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
                    name="routine_id"
                    id="editRoutineID">

                <div
                    class="modal-body"

                    id="editFormContent">

                </div>

                <div class="modal-footer">

                    <button
                        class="btn btn-secondary"
                        type="button"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        class="btn btn-primary"
                        type="submit">

                        Update Class

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- ==========================================================
                    DELETE MODAL
========================================================== -->

<div class="modal fade"

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

                    style="font-size:60px;color:#dc3545">

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
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    class="btn btn-danger"

                    id="confirmDelete">

                    Delete

                </button>

            </div>

        </div>

    </div>

</div>

            </div>

        </div>

    </div>

 
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

                <script src="../assets/js/routine.js"></script>

                <!-- <script>

                   

                    /*=========================================
                    Add Class
                    =========================================*/

                    document.getElementById("addClassBtn")

                        .addEventListener("click", function () {

                            const modal = new bootstrap.Modal(

                                document.getElementById("addClassModal")

                            );

                            modal.show();

                        });

                    /*=========================================
                    Delete Class
                    =========================================*/

                    let deleteRoutineId = 0;

                    document.querySelectorAll(".deleteClass")

                        .forEach(function (btn) {

                            btn.addEventListener("click", function () {

                                deleteRoutineId = this.dataset.id;

                                new bootstrap.Modal(

                                    document.getElementById("deleteModal")

                                ).show();

                            });

                        });

                  document.getElementById("confirmDelete")
.addEventListener("click", function () {

    fetch("../ajax/delete_routine.php", {

        method: "POST",

        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },

        body: "routine_id=" + deleteRoutineId

    })

    .then(response => response.json())

    .then(function(result){

        if(result.status === "success"){

            deleteModal.hide();

            loadRoutine();

        }else{

            alert(result.message);

        }

    })

    .catch(function(){

        alert("Unable to delete class.");

    });

});

                    /*=========================================
                    Edit Class
                    =========================================*/

                    document.querySelectorAll(".editClass")

                        .forEach(function (btn) {

                            btn.addEventListener("click", function () {

                                let id = this.dataset.id;

                                fetch("edit_class.php?id=" + id)

                                    .then(response => response.text())

                                    .then(function (html) {

                                        document.getElementById(

                                            "editFormContent"

                                        ).innerHTML = html;

                                        new bootstrap.Modal(

                                            document.getElementById(

                                                "editClassModal"

                                            )

                                        ).show();

                                    });

                            });

                        });

                  

                </script> -->

</body>

</html>

