<?php

/*
====================================================
GET ROUTINE (AJAX)
====================================================
*/

require_once("../config/functions.php");

/*====================================================
CHECK PARAMETERS
====================================================*/

if (
    !isset($_GET['department_id']) ||
    !isset($_GET['semester_id'])
) {
    exit("<tr><td colspan='7'>Invalid Request</td></tr>");
}

$departmentID = (int) $_GET['department_id'];
$semesterID = (int) $_GET['semester_id'];

/*====================================================
LOAD ROUTINE
====================================================*/

$result = getRoutineByDepartmentSemester(
    $departmentID,
    $semesterID
);

$routine = [];

while ($row = mysqli_fetch_assoc($result)) {

    $routine[$row['day']][$row['start_time']] = $row;
}

/*====================================================
TIME SLOTS
====================================================*/

$days = [

    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday"

];

$timeSlots = getTimeSlots();

/*====================================================
GENERATE TABLE
====================================================*/

foreach ($timeSlots as $slot) {

    echo "<tr>";

    ?>

    <td class="time-cell">

        <strong>

            <?= formatTime($slot[0]); ?>

        </strong>

        <br>

        <?= formatTime($slot[1]); ?>

    </td>

    <?php

    foreach ($days as $day) {

        echo "<td>";

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

                    <button class="btn btn-sm btn-danger deleteClass" data-id="<?= $class['routine_id']; ?>">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>

            </div>

            <?php

        } else {

            ?>

            <button class="btn btn-light addSlot" data-day="<?= $day; ?>" data-start="<?= $slot[0]; ?>" data-end="<?= $slot[1]; ?>">

                <i class="bi bi-plus-lg"></i>

            </button>

            <?php
        }

        echo "</td>";
    }

    echo "</tr>";
}
?>