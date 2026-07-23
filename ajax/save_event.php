<?php

require_once("../config/calendar_functions.php");

$title = trim($_POST['title'] ?? "");
$description = trim($_POST['description'] ?? "");
$event_date = $_POST['event_date'] ?? "";
$event_type = $_POST['event_type'] ?? "Other";

if ($title == "" || $event_date == "") {
    jsonResponse(false, "Please fill all required fields.");
}

if (eventExists($title, $event_date)) {
    jsonResponse(false, "Event already exists.");
}

$data = [

    "title" => $title,

    "description" => $description,

    "event_date" => $event_date,

    "event_type" => $event_type

];

if (addEvent($data)) {
    jsonResponse(true, "Event Added.");
}

jsonResponse(false, "Unable to save event.");