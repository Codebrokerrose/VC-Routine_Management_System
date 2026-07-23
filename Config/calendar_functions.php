<?php

require_once("db.php");

/*==================================================
GET ALL EVENTS
==================================================*/

function getCalendarEvents()
{
    global $conn;

    $sql = "
        SELECT *
        FROM events
        ORDER BY event_date ASC
    ";

    return mysqli_query($conn, $sql);
}

/*==================================================
GET EVENT BY ID
==================================================*/

function getEventById($eventID)
{
    global $conn;

    $stmt = mysqli_prepare($conn, "
        SELECT *
        FROM events
        WHERE event_id=?
        LIMIT 1
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $eventID
    );

    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );
}

/*==================================================
ADD EVENT
==================================================*/

function addEvent($data)
{
    global $conn;

    $stmt = mysqli_prepare($conn, "
        INSERT INTO events
        (
            title,
            description,
            event_date,
            event_type
        )
        VALUES
        (
            ?,?,?,?
        )
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $data['title'],
        $data['description'],
        $data['event_date'],
        $data['event_type']
    );

    return mysqli_stmt_execute($stmt);
}

/*==================================================
UPDATE EVENT
==================================================*/

function updateEvent($data)
{
    global $conn;

    $stmt = mysqli_prepare($conn, "
        UPDATE events
        SET
            title=?,
            description=?,
            event_date=?,
            event_type=?
        WHERE event_id=?
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $data['title'],
        $data['description'],
        $data['event_date'],
        $data['event_type'],
        $data['event_id']
    );

    return mysqli_stmt_execute($stmt);
}

/*==================================================
DELETE EVENT
==================================================*/

function deleteEvent($eventID)
{
    global $conn;

    $stmt = mysqli_prepare($conn, "
        DELETE
        FROM events
        WHERE event_id=?
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $eventID
    );

    return mysqli_stmt_execute($stmt);
}

/*==================================================
CHECK DUPLICATE EVENT
==================================================*/

function eventExists($title, $eventDate)
{
    global $conn;

    $stmt = mysqli_prepare($conn, "
        SELECT event_id
        FROM events
        WHERE title=?
        AND event_date=?
        LIMIT 1
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "ss",
        $title,
        $eventDate
    );

    mysqli_stmt_execute($stmt);

    return mysqli_num_rows(
        mysqli_stmt_get_result($stmt)
    ) > 0;
}

/*==================================================
GET UPCOMING EVENTS
==================================================*/

function getUpcomingEvents($limit = 5)
{
    global $conn;

    $stmt = mysqli_prepare($conn, "
        SELECT *
        FROM events
        WHERE event_date >= CURDATE()
        ORDER BY event_date ASC
        LIMIT ?
    ");

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $limit
    );

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
}

/*==================================================
JSON RESPONSE
==================================================*/

function jsonResponse($success, $message)
{
    header("Content-Type: application/json");

    echo json_encode([
        "success" => $success,
        "message" => $message
    ]);

    exit;
}

/*==================================================
SAFE OUTPUT
==================================================*/

function e($text)
{
    return htmlspecialchars(
        $text ?? "",
        ENT_QUOTES,
        "UTF-8"
    );
}

?>