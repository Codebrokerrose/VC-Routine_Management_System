<?php

require_once("../config/calendar_functions.php");

$eventID = intval($_POST['event_id'] ?? 0);

if ($eventID <= 0) {

    jsonResponse(false, "Invalid Event.");

}

if (deleteEvent($eventID)) {

    jsonResponse(true, "Deleted Successfully.");

}

jsonResponse(false, "Delete Failed.");