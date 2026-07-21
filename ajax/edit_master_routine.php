<?php

require_once("../Config/master_functions.php");

$id = $_GET['id'] ?? 0;

$routine = getMasterRoutineById($id);

if (!$routine) {

    echo "<div class='alert alert-danger'>Routine not found.</div>";

    exit;

}

?>

<input type="hidden" name="master_routine_id" value="<?php echo $routine['master_routine_id']; ?>">

<div class="row">

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Day

        </label>

        <select name="day" class="form-select" required>

            <?php

            $days = getWorkingDays();

            foreach ($days as $day) {

                ?>

                <option value="<?= $day ?>" <?= ($routine['day'] == $day) ? 'selected' : ''; ?>>

                    <?= $day ?>

                </option>

                <?php

            }

            ?>

        </select>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">

            Start Time

        </label>

        <input type="time" name="start_time" class="form-control" value="<?= $routine['start_time']; ?>" required>

    </div>

    <div class="col-md-3 mb-3">

        <label class="form-label">

            End Time

        </label>

        <input type="time" name="end_time" class="form-control" value="<?= $routine['end_time']; ?>" required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Course Name

        </label>

        <input type="text" name="course_name" class="form-control"
            value="<?= htmlspecialchars($routine['course_name']); ?>" required>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Class Type

        </label>

        <select name="class_type" class="form-select">

            <?php

            $types = [
                "Th",
                "Th/Tu",
                "Practical",
                "Lab",
                "Workshop"
            ];

            foreach ($types as $type) {

                ?>

                <option value="<?= $type ?>" <?= ($routine['class_type'] == $type) ? 'selected' : ''; ?>>

                    <?= $type ?>

                </option>

                <?php

            }

            ?>

        </select>

    </div>

    <div class="col-md-6 mb-3">

        <label class="form-label">

            Subject

        </label>

        <input type="text" name="subject" class="form-control" value="<?= htmlspecialchars($routine['subject']); ?>">

    </div>

    

</div>

<div class="modal-footer">

    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

        Cancel

    </button>

    <button type="submit" class="btn btn-primary">

        Update Class

    </button>

</div>