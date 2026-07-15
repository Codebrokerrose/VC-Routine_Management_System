<?php

require_once("../config/session.php");
require_once("../config/functions.php");

/*=========================================
    SELECTED FILTERS
=========================================*/

$department_id = isset($_GET['department'])
    ? (int) $_GET['department']
    : 1;

$semester_id = isset($_GET['semester'])
    ? (int) $_GET['semester']
    : 1;

/*=========================================
    LOAD DATA
=========================================*/

$departments = getDepartments();

$semesters = getSemestersByDepartment($department_id);

$currentDepartment = getDepartment($department_id);

$currentSemester = getSemester($semester_id);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>

        Routine Management

    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="../assets/css/header_sidebar.css">

    <link rel="stylesheet" href="../assets/css/routine.css">

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

            <!-- Header -->

            <?php include("../includes/header.php"); ?>

            <!-- =====================================
        PAGE CONTENT
===================================== -->

            <div class="routine-container">

                <!-- =====================================
        PAGE TITLE
===================================== -->

                <div class="page-header">

                    <div>

                        <h2>

                            Routine / Time Table

                        </h2>

                        <p>

                            Manage weekly department routine

                        </p>

                    </div>

                    <div class="today-date">

                        <i class="bi bi-calendar-event"></i>

                        <?= date("l, d F Y"); ?>

                    </div>

                </div>

                <!-- =====================================
        FILTER BAR
===================================== -->

                <div class="filter-card">

                    <form method="GET" id="filterForm" class="row g-3 align-items-end">

                        <!-- Department -->

                        <div class="col-lg-4">

                            <label class="form-label">

                                Department

                            </label>

                            <select class="form-select" name="department" id="department">

                                <?php

                                while ($dept = mysqli_fetch_assoc($departments)) {

                                    ?>

                                    <option value="<?= $dept['department_id']; ?>"
                                        <?= ($department_id == $dept['department_id']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($dept['department_name']); ?>

                                    </option>

                                    <?php

                                }

                                ?>

                            </select>

                        </div>

                        <!-- Semester -->

                        <div class="col-lg-4">

                            <label class="form-label">

                                Semester

                            </label>

                            <select class="form-select" name="semester" id="semester">

                                <?php

                                while ($sem = mysqli_fetch_assoc($semesters)) {

                                    ?>

                                    <option value="<?= $sem['semester_id']; ?>" <?= ($semester_id == $sem['semester_id']) ? 'selected' : ''; ?>>

                                        <?= htmlspecialchars($sem['semester_name']); ?>

                                    </option>

                                    <?php

                                }

                                ?>

                            </select>

                        </div>

                        <!-- Button -->

                        <div class="col-lg-4 text-end">

                            <button type="button" class="btn btn-primary" id="addClassBtn">

                                <i class="bi bi-plus-circle"></i>

                                Add Class

                            </button>

                        </div>

                    </form>

                </div>

                <!-- =====================================
        ROUTINE INFORMATION
===================================== -->

                <div class="routine-info">

                    <div class="info-box">

                        <i class="bi bi-building"></i>

                        <div>

                            <span>

                                Department

                            </span>

                            <h6>

                                <?= htmlspecialchars($currentDepartment['department_name']); ?>

                            </h6>

                        </div>

                    </div>

                    <div class="info-box">

                        <i class="bi bi-mortarboard-fill"></i>

                        <div>

                            <span>

                                Semester

                            </span>

                            <h6>

                                <?= htmlspecialchars($currentSemester['semester_name']); ?>

                            </h6>

                        </div>

                    </div>

                    <div class="info-box">

                        <i class="bi bi-calendar-week"></i>

                        <div>

                            <span>

                                Working Days

                            </span>

                            <h6>

                                Monday - Saturday

                            </h6>

                        </div>

                    </div>

                    <div class="info-box">

                        <i class="bi bi-clock-history"></i>

                        <div>

                            <span>

                                Routine Status

                            </span>

                            <h6>

                                Active

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

                    <div class="table-responsive">

                        <table class="table routine-table align-middle">

                            <thead>

                                <tr>

                                    <th style="width:150px;">

                                        Time

                                    </th>

                                    <th>

                                        Monday

                                    </th>

                                    <th>

                                        Tuesday

                                    </th>

                                    <th>

                                        Wednesday

                                    </th>

                                    <th>

                                        Thursday

                                    </th>

                                    <th>

                                        Friday

                                    </th>

                                    <th>

                                        Saturday

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                /*=========================================
                                    TIME SLOTS
                                =========================================*/

                                $timeSlots = [

                                    ["09:00:00", "10:00:00"],
                                    ["10:00:00", "11:00:00"],
                                    ["11:00:00", "12:00:00"],
                                    ["12:00:00", "13:00:00"],
                                    ["13:00:00", "14:00:00"],
                                    ["14:00:00", "15:00:00"],
                                    ["15:00:00", "16:00:00"],
                                    ["16:00:00", "17:00:00"]

                                ];

                                $days = [

                                    "Monday",
                                    "Tuesday",
                                    "Wednesday",
                                    "Thursday",
                                    "Friday",
                                    "Saturday"

                                ];

                                /*=========================================
                                    LOAD ROUTINE
                                =========================================*/

                                $routineData = [];

                                $sql = "

SELECT *

FROM routine

WHERE department_id = ?

AND semester_id = ?

ORDER BY start_time ASC

";

                                $stmt = mysqli_prepare($conn, $sql);

                                mysqli_stmt_bind_param(

                                    $stmt,

                                    "ii",

                                    $department_id,

                                    $semester_id

                                );

                                mysqli_stmt_execute($stmt);

                                $result = mysqli_stmt_get_result($stmt);

                                while ($row = mysqli_fetch_assoc($result)) {

                                    $routineData
                                    [$row['day']]
                                    [$row['start_time']]
                                        = $row;

                                }

                                /*=========================================
                                    DRAW TABLE
                                =========================================*/

                                foreach ($timeSlots as $slot) {

                                    echo "<tr>";

                                    echo "<td class='time-column'>";

                                    echo date("h:i A", strtotime($slot[0]));

                                    echo "<br>";

                                    echo "-";

                                    echo "<br>";

                                    echo date("h:i A", strtotime($slot[1]));

                                    echo "</td>";

                                    foreach ($days as $day) {

                                        echo "<td>";

                                        if (isset($routineData[$day][$slot[0]])) {

                                            $class = $routineData[$day][$slot[0]];

                                            ?>

                                            <div class="routine-box" data-id="<?= $class['routine_id']; ?>">

                                                <div class="routine-subject">

                                                    <?= htmlspecialchars($class['subject']); ?>

                                                </div>

                                                <div class="routine-teacher">

                                                    <?= htmlspecialchars($class['teacher_name']); ?>

                                                </div>


                                                <div class="routine-actions">

                                                    <button class="btn btn-sm btn-outline-primary editClass"
                                                        data-id="<?= $class['routine_id']; ?>">

                                                        <i class="bi bi-pencil-square"></i>

                                                    </button>

                                                    <button class="btn btn-sm btn-outline-danger deleteClass"
                                                        data-id="<?= $class['routine_id']; ?>">

                                                        <i class="bi bi-trash"></i>

                                                    </button>

                                                </div>

                                            </div>

                                            <?php

                                        } else {

                                            ?>

                                            <div class="empty-slot">

                                                --

                                            </div>

                                            <?php

                                        }

                                        echo "</td>";

                                    }

                                    echo "</tr>";

                                }

                                ?>

                            </tbody>

                        </table>

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

                                    <i class="bi bi-plus-circle"></i>

                                    Add New Class

                                </h5>

                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>

                            </div>

                            <form id="addClassForm">

                                <div class="modal-body">

                                    <input type="hidden" name="department_id" value="<?= $department_id; ?>">

                                    <input type="hidden" name="semester_id" value="<?= $semester_id; ?>">

                                    <div class="row">

                                        <!-- Day -->

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">

                                                Day

                                            </label>

                                            <select class="form-select" name="day" required>

                                                <option value="Monday">Monday</option>
                                                <option value="Tuesday">Tuesday</option>
                                                <option value="Wednesday">Wednesday</option>
                                                <option value="Thursday">Thursday</option>
                                                <option value="Friday">Friday</option>
                                                <option value="Saturday">Saturday</option>

                                            </select>

                                        </div>

                                        <!-- Date -->

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">

                                                Class Date

                                            </label>

                                            <input type="date" class="form-control" name="class_date" required>

                                        </div>

                                        <!-- Start -->

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">

                                                Start Time

                                            </label>

                                            <input type="time" class="form-control" name="start_time" required>

                                        </div>

                                        <!-- End -->

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">

                                                End Time

                                            </label>

                                            <input type="time" class="form-control" name="end_time" required>

                                        </div>

                                        <!-- Subject -->

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">

                                                Subject

                                            </label>

                                            <input type="text" class="form-control" name="subject" required>

                                        </div>

                                        <!-- Teacher -->

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">

                                                Teacher Name

                                            </label>

                                            <input type="text" class="form-control" name="teacher_name" required>

                                        </div>

                                        <!-- Room -->

                                        <div class="col-md-6 mb-3">

                                            <label class="form-label">

                                                Room Number

                                            </label>

                                            <input type="text" class="form-control" name="room_no">

                                        </div>

                                    </div>

                                </div>

                                <div class="modal-footer">

                                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">

                                        Cancel

                                    </button>

                                    <button class="btn btn-primary" type="submit">

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

                                <button type="button" class="btn-close" data-bs-dismiss="modal">
                                </button>

                            </div>

                            <form id="editClassForm">

                                <input type="hidden" name="routine_id" id="editRoutineId">

                                <div class="modal-body" id="editFormContent">

                                    <!-- Loaded by AJAX -->

                                </div>

                                <div class="modal-footer">

                                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">

                                        Cancel

                                    </button>

                                    <button class="btn btn-primary" type="submit">

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

                <div class="modal fade" id="deleteModal" tabindex="-1">

                    <div class="modal-dialog modal-dialog-centered">

                        <div class="modal-content">

                            <div class="modal-header">

                                <h5 class="modal-title text-danger">

                                    Delete Class

                                </h5>

                                <button class="btn-close" data-bs-dismiss="modal">
                                </button>

                            </div>

                            <div class="modal-body">

                                <p>

                                    Are you sure you want to delete this class?

                                </p>

                            </div>

                            <div class="modal-footer">

                                <button class="btn btn-secondary" data-bs-dismiss="modal">

                                    Cancel

                                </button>

                                <button id="confirmDelete" class="btn btn-danger">

                                    Delete

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ==========================================================
        JAVASCRIPT LIBRARIES
========================================================== -->

                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

                <script src="../assets/js/routine.js"></script>

                <script>

                    const departmentSelect = document.getElementById("department");

                    const semesterSelect = document.getElementById("semester");

                    /*=========================================
                    Department Change
                    =========================================*/

                    departmentSelect.addEventListener("change", function () {

                        document.getElementById("filterForm").submit();

                    });

                    /*=========================================
                    Semester Change
                    =========================================*/

                    semesterSelect.addEventListener("change", function () {

                        document.getElementById("filterForm").submit();

                    });

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

                            window.location =

                                "delete_routine.php?id=" + deleteRoutineId;

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

                    /*=========================================
                    Submit Add Form
                    =========================================*/

                    document.getElementById("addClassForm")

                        .addEventListener("submit", function () {

                        });

                    /*=========================================
                    Submit Edit Form
                    =========================================*/

                    document.getElementById("editClassForm")

                        .addEventListener("submit", function () {

                        });

                </script>

</body>

</html>