<?php

require_once("../config/calendar_functions.php");

$data = [

    "event_id" => $_POST['event_id'],

    "title" => trim($_POST['title']),

    "description" => trim($_POST['description']),

    "event_date" => $_POST['event_date'],

    "event_type" => $_POST['event_type']

];

if (updateEvent($data)) {

    jsonResponse(true, "Event Updated.");

}

jsonResponse(false, "Update Failed.");