<?php

require_once("../Config/master_functions.php");

$session = $_GET['session'] ?? "Odd";
$course = $_GET['course'] ?? "BA";
$semester = $_GET['semester'] ?? 1;

$workingDays = getWorkingDays();
$timeSlots = getTimeSlots();

$routines = getMasterRoutine($session, $course, $semester);

$routineMap = [];

foreach ($routines as $row) {

    $routineMap[$row['day']][$row['start_time']] = $row;

}

foreach ($timeSlots as $slot) {

    echo "<tr>";

    echo "<td class='time-cell'>";

    echo "<strong>" . formatTime($slot[0]) . "</strong><br>";

    echo formatTime($slot[1]);

    echo "</td>";

    foreach ($workingDays as $day) {

        echo "<td>";

        if (isset($routineMap[$day][$slot[0]])) {

            $routine = $routineMap[$day][$slot[0]];

            $color = "#6B1322";

            switch (strtolower(trim($routine['course_name']))) {

                case "major":
                    $color = "#18A9E6";
                    break;

                case "minor":
                case "minor-1":
                case "minor-2":
                    $color = "#FFF200";
                    break;

                case "idc":
                    $color = "#FFBE33";
                    break;

                case "aec":
                    $color = "#FF5B57";
                    break;

                case "cvac":
                    $color = "#FF5B57";
                    break;

                case "add-on":
                case "add-on course":
                case "add-on courses":
                    $color = "#F4A261";
                    break;
            }

            ?>

            <div class="routine-box" style="background:<?= $color ?>;color:#000;min-height:110px;">

                <div style="font-size:17px;font-weight:700;">

                    <?= htmlspecialchars($routine['course_name']); ?>

                </div>

                <div style="font-weight:600;">

                    <?= htmlspecialchars($routine['class_type']); ?>

                </div>

                <?php if (!empty($routine['subject'])) { ?>

                    <div style="font-size:13px;font-weight:600;">

                        <?= htmlspecialchars($routine['subject']); ?>

                    </div>

                <?php } ?>

                <?php if (!empty($routine['description'])) { ?>

                    <div style="font-size:12px;">

                        <?= nl2br(htmlspecialchars($routine['description'])); ?>

                    </div>

                <?php } ?>

                <div class="routine-actions mt-2">

                    <button class="btn btn-sm btn-primary editClass" data-id="<?= $routine['master_routine_id']; ?>">

                        <i class="bi bi-pencil"></i>

                    </button>

                    <button class="btn btn-sm btn-danger deleteClass" data-id="<?= $routine['master_routine_id']; ?>">

                        <i class="bi bi-trash"></i>

                    </button>

                </div>

            </div>

            <?php

        } else {

            ?>

            <div class="addSlot" data-day="<?= $day; ?>" data-start="<?= $slot[0]; ?>" data-end="<?= $slot[1]; ?>">

                <i class="bi bi-plus-lg"></i>

            </div>

            <?php
        }

        echo "</td>";
    }

    echo "</tr>";
}