<?php

/*
====================================================
EDIT CLASS (AJAX)
====================================================
*/

require_once("../config/functions.php");

/*====================================================
CHECK REQUEST
====================================================*/

if(!isset($_GET['id']))
{
    exit("Invalid Request");
}

$routineID=(int)$_GET['id'];

/*====================================================
LOAD CLASS
====================================================*/

$class=getRoutineById($routineID);

if(!$class)
{
    exit("Class Not Found");
}

?>

<input
type="hidden"
name="routine_id"
value="<?= $class['routine_id']; ?>">

<input
type="hidden"
name="department_id"
value="<?= $class['department_id']; ?>">

<input
type="hidden"
name="semester_id"
value="<?= $class['semester_id']; ?>">

<div class="row">

    <!-- Day -->

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Day

        </label>

        <select
        class="form-select"
        name="day"
        required>

            <?php

            $days=[
                "Monday",
                "Tuesday",
                "Wednesday",
                "Thursday",
                "Friday",
                "Saturday"
            ];

            foreach($days as $day)
            {

            ?>

            <option

            value="<?= $day; ?>"

            <?= ($class['day']==$day)?'selected':''; ?>

            >

            <?= $day; ?>

            </option>

            <?php

            }

            ?>

        </select>

    </div>

    <!-- Class Date -->

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Class Date

        </label>

        <input

        type="date"

        class="form-control"

        name="class_date"

        value="<?= $class['class_date']; ?>"

        required>

    </div>

    <!-- Start Time -->

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Start Time

        </label>

        <input

        type="time"

        class="form-control"

        name="start_time"

        value="<?= substr($class['start_time'],0,5); ?>"

        required>

    </div>

    <!-- End Time -->

    <div class="col-md-6 mb-3">

        <label class="form-label">

            End Time

        </label>

        <input

        type="time"

        class="form-control"

        name="end_time"

        value="<?= substr($class['end_time'],0,5); ?>"

        required>

    </div>

    <!-- Subject -->

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Subject

        </label>

        <input

        type="text"

        class="form-control"

        name="subject"

        value="<?= e($class['subject']); ?>"

        required>

    </div>

    <!-- Teacher -->

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Teacher Name

        </label>

        <input

        type="text"

        class="form-control"

        name="teacher_name"

        value="<?= e($class['teacher_name']); ?>"

        required>

    </div>

    <!-- Room -->

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Room Number

        </label>

        <input

        type="text"

        class="form-control"

        name="room_no"

        value="<?= e($class['room_no']); ?>"

        required>

    </div>

</div>