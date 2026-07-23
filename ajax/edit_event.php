<?php

require_once("../config/calendar_functions.php");

$event = getEventById($_GET['id']);

?>

<input type="hidden" name="event_id" value="<?= $event['event_id']; ?>">

<div class="row">

    <div class="col-md-6">

        <label class="form-label">

            Title

        </label>

        <input type="text" name="title" class="form-control" value="<?= e($event['title']); ?>" required>

    </div>

   

    <div class="col-md-6 mt-3">

        <label class="form-label">

            Date

        </label>

        <input type="date" name="event_date" class="form-control" value="<?= $event['event_date']; ?>">

    </div>

    <div class="col-md-6 mt-3">

        <label class="form-label">

            Event Type

        </label>

        <select name="event_type" class="form-select">

            <?php

            $types = [
                "Meeting",
                "Holiday",
                "Seminar",
                "Workshop",
                "Examination",
                "Festival",
                "Other"
            ];

            foreach ($types as $type) {

                ?>

                <option <?= ($type == $event['event_type']) ? "selected" : ""; ?>>

                    <?= $type; ?>

                </option>

            <?php } ?>

        </select>

    </div>

    <div class="col-12 mt-3">

        <label class="form-label">

            Description

        </label>

        <textarea name="description" class="form-control" rows="4"><?= e($event['description']); ?></textarea>

    </div>

    <div class="col-12 mt-4 text-end">

        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">

            Cancel

        </button>

        <button class="btn btn-primary" type="submit">

            Update Event

        </button>

    </div>

</div>