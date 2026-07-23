<?php

require_once("../config/calendar_functions.php");

$result = getCalendarEvents();

$events = [];

while ($row = mysqli_fetch_assoc($result)) {

    $events[] = [

        "id" => $row['event_id'],

        "title" => $row['title'],

        "start" => $row['event_date'],

        "description" => $row['description'],

        "event_type" => $row['event_type']

    ];

}

header("Content-Type: application/json");

echo json_encode($events);