<?php
/*
====================================================
VC Routine Management System
Reusable Functions
====================================================
*/

require_once("db.php");

/*==================================================
    TOTAL DEPARTMENTS
==================================================*/

function getDepartmentCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM departments";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

/*==================================================
    TOTAL SEMESTERS
==================================================*/

function getSemesterCount()
{
    global $conn;

    $sql = "SELECT COUNT(*) AS total FROM semesters";
    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

/*==================================================
    TODAY'S CLASSES
==================================================*/

function getTodayClassCount()
{
    global $conn;

    $today = date("l");

    $sql = "SELECT COUNT(*) AS total
            FROM routine
            WHERE day='$today'";

    $result = mysqli_query($conn, $sql);

    $row = mysqli_fetch_assoc($result);

    return $row['total'];
}

/*==================================================
    TODAY'S ROUTINE
==================================================*/

function getTodaySchedule()
{
    global $conn;

    $today = date('Y-m-d');
    $now = date('H:i:s');

    // First try today's remaining classes
    $sql = "
        SELECT
            r.*,
            d.department_name,
            s.semester_name
        FROM routine r
        JOIN departments d
            ON r.department_id = d.department_id
        JOIN semesters s
            ON r.semester_id = s.semester_id
        WHERE
            r.class_date = ?
            AND r.end_time >= ?
        ORDER BY
            r.start_time ASC
        LIMIT 5
    ";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $today, $now);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        return $result;
    }

    // If today's classes are finished,
    // show next working day's classes

    $sql = "
        SELECT
            r.*,
            d.department_name,
            s.semester_name
        FROM routine r
        JOIN departments d
            ON r.department_id = d.department_id
        JOIN semesters s
            ON r.semester_id = s.semester_id
        WHERE
            r.class_date > ?
        ORDER BY
            r.class_date ASC,
            r.start_time ASC
        LIMIT 10
        
    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "s", $today);

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);
}

/*==================================================
    ALL CALENDAR EVENTS
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

function getUpcomingEvents()
{
    global $conn;

    $sql = "
        SELECT *
        FROM events
        WHERE event_date >= CURDATE()
        ORDER BY event_date ASC
        LIMIT 5
    ";

    return mysqli_query($conn, $sql);
}
/*==================================================
    PRINCIPAL INFORMATION
==================================================*/

function getPrincipal()
{
    global $conn;

    $sql = "SELECT *
            FROM users
            WHERE role='principal'
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    return mysqli_fetch_assoc($result);
}

/*==================================================
    GET ALL DEPARTMENTS
==================================================*/

function getDepartments()
{
    global $conn;

    $sql = "SELECT *
            FROM departments
            ORDER BY department_name";

    return mysqli_query($conn, $sql);
}

/*==================================================
    GET ALL SEMESTERS
==================================================*/

// function getSemesters()
// {
//     global $conn;

//     $sql = "SELECT
//                 s.*,
//                 d.department_name
//             FROM semesters s

//             INNER JOIN departments d
//             ON s.department_id=d.department_id

//             ORDER BY d.department_name";

//     return mysqli_query($conn, $sql);
// }


/*==================================================
    DATE FORMAT
==================================================*/

function formatDate($date)
{
    return date("d M Y", strtotime($date));
}

/*==================================================
    TIME FORMAT
==================================================*/

function formatTime($time)
{
    return date("h:i A", strtotime($time));
}

/*==================================================
    SAFE OUTPUT
==================================================*/

function e($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/*==================================================
    GET SINGLE DEPARTMENT
==================================================*/

function getDepartment($departmentID)
{
    global $conn;

    $departmentID = (int) $departmentID;

    $sql = "SELECT *
            FROM departments
            WHERE department_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $departmentID);

    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );
}


/*==================================================
    GET SINGLE SEMESTER
==================================================*/

function getSemester($semesterID)
{
    global $conn;

    $semesterID = (int) $semesterID;

    $sql = "SELECT *
            FROM semesters
            WHERE semester_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $semesterID);

    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );
}


/*==================================================
    GET SEMESTERS OF A DEPARTMENT
==================================================*/

// function getSemestersByDepartment($departmentID)
// {
//     global $conn;

//     $departmentID = (int) $departmentID;

//     $sql = "SELECT *

//             FROM semesters

//             WHERE department_id=?

//             ORDER BY semester_id ASC";

//     $stmt = mysqli_prepare($conn, $sql);

//     mysqli_stmt_bind_param($stmt, "i", $departmentID);

//     mysqli_stmt_execute($stmt);

//     return mysqli_stmt_get_result($stmt);
// }


function getSemesters()
{
    global $conn;

    $sql = "
        SELECT
            semester_id,
            semester_name
        FROM semesters
        ORDER BY semester_id ASC
    ";

    return mysqli_query($conn, $sql);
}

/*==================================================
    GET ROUTINE
==================================================*/

function getRoutineByDepartmentSemester($departmentID, $semesterID)
{
    global $conn;

    $sql = "

    SELECT *

    FROM routine

    WHERE department_id=?

    AND semester_id=?

    ORDER BY

    FIELD(day,

    'Monday',

    'Tuesday',

    'Wednesday',

    'Thursday',

    'Friday',

    'Saturday'),

    start_time ASC

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "ii",

        $departmentID,

        $semesterID

    );

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);

}


/*==================================================
    GET ROUTINE USING ID
==================================================*/

function getRoutineById($routineID)
{
    global $conn;

    $routineID = (int) $routineID;

    $sql = "SELECT *

          FROM routine

          WHERE routine_id=?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $routineID

    );

    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(

        mysqli_stmt_get_result($stmt)

    );

}


/*==================================================
    GET ALL TEACHERS
==================================================*/

function getTeachers()
{
    global $conn;

    $sql = "SELECT DISTINCT

          teacher_name

          FROM routine

          ORDER BY teacher_name";

    return mysqli_query($conn, $sql);

}

/*==================================================
    ADD ROUTINE
==================================================*/

function addRoutine($data)
{
    global $conn;

    $sql = "

    INSERT INTO routine

    (

        department_id,
        semester_id,
        day,
        class_date,
        start_time,
        end_time,
        subject,
        teacher_name,
        room_no

    )

    VALUES

    (?,?,?,?,?,?,?,?,?)

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "iisssssss",

        $data['department_id'],
        $data['semester_id'],
        $data['day'],
        $data['class_date'],
        $data['start_time'],
        $data['end_time'],
        $data['subject'],
        $data['teacher_name'],
        $data['room_no']

    );

    return mysqli_stmt_execute($stmt);

}


/*==================================================
    UPDATE ROUTINE
==================================================*/

function updateRoutine($data)
{
    global $conn;

    $sql = "

    UPDATE routine

    SET

    department_id=?,
    semester_id=?,
    day=?,
    class_date=?,
    start_time=?,
    end_time=?,
    subject=?,
    teacher_name=?,
    room_no=?

    WHERE routine_id=?

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "iisssssssi",

        $data['department_id'],
        $data['semester_id'],
        $data['day'],
        $data['class_date'],
        $data['start_time'],
        $data['end_time'],
        $data['subject'],
        $data['teacher_name'],
        $data['room_no'],
        $data['routine_id']

    );

    return mysqli_stmt_execute($stmt);

}


/*==================================================
    DELETE ROUTINE
==================================================*/

function deleteRoutine($routineID)
{
    global $conn;

    $routineID = (int) $routineID;

    $sql = "

    DELETE FROM routine

    WHERE routine_id=?

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $routineID

    );

    return mysqli_stmt_execute($stmt);

}


/*==================================================
    CHECK TIME SLOT CONFLICT
==================================================*/

function isRoutineConflict(
    $departmentID,
    $semesterID,
    $day,
    $start,
    $end,
    $ignoreID = 0
) {
    global $conn;

    $sql = "
        SELECT routine_id
        FROM routine
        WHERE
            department_id = ?
            AND semester_id = ?
            AND day = ?
            AND (? < end_time)
            AND (? > start_time)
    ";

    if ($ignoreID > 0) {
        $sql .= " AND routine_id <> ?";
    }

    $stmt = mysqli_prepare($conn, $sql);

    if ($ignoreID > 0) {

        mysqli_stmt_bind_param(
            $stmt,
            "iisssi",
            $departmentID,
            $semesterID,
            $day,
            $start,
            $end,
            $ignoreID
        );

    } else {

        mysqli_stmt_bind_param(
            $stmt,
            "iisss",
            $departmentID,
            $semesterID,
            $day,
            $start,
            $end
        );
    }

    mysqli_stmt_execute($stmt);

    return mysqli_num_rows(
        mysqli_stmt_get_result($stmt)
    ) > 0;
}


/*==================================================
    CHECK TEACHER AVAILABILITY
==================================================*/

function isTeacherBusy(

    $teacher,

    $day,

    $start,

    $end,

    $ignoreID = 0

) {
    global $conn;

    $sql = "

    SELECT routine_id

    FROM routine

    WHERE

    teacher_name=?

    AND day=?

    AND

    (

        (? < end_time)

        AND

        (? > start_time)

    )

    ";

    if ($ignoreID > 0) {

        $sql .= "

        AND routine_id<>?

        ";

    }

    $stmt = mysqli_prepare($conn, $sql);

    if ($ignoreID > 0) {

        mysqli_stmt_bind_param(

            $stmt,

            "ssssi",

            $teacher,

            $day,

            $start,

            $end,

            $ignoreID

        );

    } else {

        mysqli_stmt_bind_param(

            $stmt,

            "ssss",

            $teacher,

            $day,

            $start,

            $end

        );

    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_num_rows($result) > 0;

}


/*==================================================
    CHECK ROOM AVAILABILITY
==================================================*/

function isRoomBusy(

    $room,

    $day,

    $start,

    $end,

    $ignoreID = 0

) {
    global $conn;

    $sql = "

    SELECT routine_id

    FROM routine

    WHERE

    room_no=?

    AND day=?

    AND

    (

        (? < end_time)

        AND

        (? > start_time)

    )

    ";

    if ($ignoreID > 0) {

        $sql .= "

        AND routine_id<>?

        ";

    }

    $stmt = mysqli_prepare($conn, $sql);

    if ($ignoreID > 0) {

        mysqli_stmt_bind_param(

            $stmt,

            "ssssi",

            $room,

            $day,

            $start,

            $end,

            $ignoreID

        );

    } else {

        mysqli_stmt_bind_param(

            $stmt,

            "ssss",

            $room,

            $day,

            $start,

            $end

        );

    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    return mysqli_num_rows($result) > 0;

}

/*==================================================
    GET ROUTINE BY DAY
==================================================*/

function getRoutineByDay($departmentID, $semesterID, $day)
{
    global $conn;

    $sql = "

    SELECT *

    FROM routine

    WHERE

    department_id=?

    AND semester_id=?

    AND day=?

    ORDER BY start_time ASC

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "iis",

        $departmentID,

        $semesterID,

        $day

    );

    mysqli_stmt_execute($stmt);

    return mysqli_stmt_get_result($stmt);

}


/*==================================================
    GET NEXT AVAILABLE SLOT
==================================================*/

function getNextAvailableSlot($departmentID, $semesterID, $day)
{
    global $conn;

    $sql = "

    SELECT end_time

    FROM routine

    WHERE

    department_id=?

    AND semester_id=?

    AND day=?

    ORDER BY end_time DESC

    LIMIT 1

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "iis",

        $departmentID,

        $semesterID,

        $day

    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {

        return mysqli_fetch_assoc($result);

    }

    return false;

}


/*==================================================
    TOTAL ROUTINE CLASSES
==================================================*/

function getRoutineCount($departmentID, $semesterID)
{
    global $conn;

    $sql = "

    SELECT COUNT(*) total

    FROM routine

    WHERE

    department_id=?

    AND semester_id=?

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "ii",

        $departmentID,

        $semesterID

    );

    mysqli_stmt_execute($stmt);

    $row = mysqli_fetch_assoc(

        mysqli_stmt_get_result($stmt)

    );

    return $row['total'];

}


/*==================================================
    TOTAL TEACHERS
==================================================*/

function getTeacherCount($departmentID)
{
    global $conn;

    $sql = "

    SELECT COUNT(DISTINCT teacher_name) total

    FROM routine

    WHERE department_id=?

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $departmentID

    );

    mysqli_stmt_execute($stmt);

    $row = mysqli_fetch_assoc(

        mysqli_stmt_get_result($stmt)

    );

    return $row['total'];

}


/*==================================================
    TOTAL ROOMS
==================================================*/

function getRoomCount($departmentID)
{
    global $conn;

    $sql = "

    SELECT COUNT(DISTINCT room_no) total

    FROM routine

    WHERE department_id=?

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "i",

        $departmentID

    );

    mysqli_stmt_execute($stmt);

    $row = mysqli_fetch_assoc(

        mysqli_stmt_get_result($stmt)

    );

    return $row['total'];

}


/*==================================================
    GET ALL DAYS
==================================================*/

function getWorkingDays()
{
    return [

        "Monday",

        "Tuesday",

        "Wednesday",

        "Thursday",

        "Friday",

        "Saturday"

    ];

}


/*==================================================
    GET TIME SLOTS
==================================================*/

function getTimeSlots()
{
    return [

        ["09:00:00", "10:00:00"],
        ["10:00:00", "11:00:00"],
        ["11:00:00", "12:00:00"],
        ["12:00:00", "13:00:00"],
        ["13:00:00", "14:00:00"],
        ["14:00:00", "15:00:00"],
        ["15:00:00", "16:00:00"],
        ["16:00:00", "17:00:00"]

    ];
}

/*==================================================
    VALIDATE TIME RANGE
==================================================*/

function isValidTimeRange($startTime, $endTime)
{
    return strtotime($startTime) < strtotime($endTime);
}


/*==================================================
    CHECK IF CLASS DATE IS VALID
==================================================*/

function isValidClassDate($date)
{
    return strtotime($date) !== false;
}


/*==================================================
    GET ROUTINE BY DEPARTMENT & SEMESTER
==================================================*/



/*==================================================
    CHECK DEPARTMENT EXISTS
==================================================*/

function departmentExists($departmentID)
{
    global $conn;

    $sql = "

        SELECT department_id

        FROM departments

        WHERE department_id = ?

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $departmentID
    );

    mysqli_stmt_execute($stmt);

    return mysqli_num_rows(
        mysqli_stmt_get_result($stmt)
    ) > 0;

}


/*==================================================
    CHECK SEMESTER EXISTS
==================================================*/

function semesterExists($semesterID)
{
    global $conn;

    $sql = "

        SELECT semester_id

        FROM semesters

        WHERE semester_id = ?

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $semesterID
    );

    mysqli_stmt_execute($stmt);

    return mysqli_num_rows(
        mysqli_stmt_get_result($stmt)
    ) > 0;

}


/*==================================================
    GET DEPARTMENT NAME
==================================================*/

function getDepartmentName($departmentID)
{
    $department = getDepartment($departmentID);

    return $department
        ? $department['department_name']
        : "";

}


/*==================================================
    GET SEMESTER NAME
==================================================*/

function getSemesterName($semesterID)
{
    $semester = getSemester($semesterID);

    return $semester
        ? $semester['semester_name']
        : "";

}


/*==================================================
    RESPONSE (AJAX)
==================================================*/

function jsonResponse($status, $message, $data = [])
{
    header("Content-Type: application/json");

    echo json_encode([

        "status" => $status,

        "message" => $message,

        "data" => $data

    ]);

    exit;

}


/*==================================================
    REDIRECT
==================================================*/

function redirect($url)
{
    header("Location: " . $url);
    exit;
}


/*==================================================
    SANITIZE INPUT
==================================================*/

function clean($value)
{
    return trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
}

/*==================================================
    CHECK USER LOGIN
==================================================*/

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}


/*==================================================
    GET USER BY ID
==================================================*/

function getUserById($userID)
{
    global $conn;

    $sql = "SELECT *
            FROM users
            WHERE user_id = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $userID
    );

    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );
}


/*==================================================
    GET USER BY EMAIL
==================================================*/

function getUserByEmail($email)
{
    global $conn;

    $sql = "SELECT *
            FROM users
            WHERE email = ?";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $email
    );

    mysqli_stmt_execute($stmt);

    return mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmt)
    );
}


/*==================================================
    CHECK DUPLICATE SUBJECT
==================================================*/

function subjectExists($subject, $departmentID, $semesterID)
{
    global $conn;

    $sql = "

        SELECT routine_id

        FROM routine

        WHERE

        subject=?

        AND department_id=?
        AND semester_id=?

        LIMIT 1

    ";

    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param(

        $stmt,

        "sii",

        $subject,

        $departmentID,

        $semesterID

    );

    mysqli_stmt_execute($stmt);

    return mysqli_num_rows(

        mysqli_stmt_get_result($stmt)

    ) > 0;

}


/*==================================================
    GET CURRENT DAY
==================================================*/

function getCurrentDay()
{
    return date("l");
}


/*==================================================
    GET CURRENT DATE
==================================================*/

function getCurrentDate()
{
    return date("Y-m-d");
}


/*==================================================
    GET CURRENT TIME
==================================================*/

function getCurrentTime()
{
    return date("H:i:s");
}


/*==================================================
    FORMAT DATETIME
==================================================*/

function formatDateTime($datetime)
{
    return date(
        "d M Y h:i A",
        strtotime($datetime)
    );
}


/*==================================================
    SUCCESS MESSAGE
==================================================*/

function successMessage($message)
{
    $_SESSION['success'] = $message;
}


/*==================================================
    ERROR MESSAGE
==================================================*/

function errorMessage($message)
{
    $_SESSION['error'] = $message;
}


/*==================================================
    DISPLAY SUCCESS
==================================================*/

function showSuccess()
{
    if (isset($_SESSION['success'])) {

        echo '

        <div class="alert alert-success alert-dismissible fade show">

        ' . $_SESSION['success'] . '

        <button

        class="btn-close"

        data-bs-dismiss="alert">

        </button>

        </div>';

        unset($_SESSION['success']);

    }
}


/*==================================================
    DISPLAY ERROR
==================================================*/

function showError()
{
    if (isset($_SESSION['error'])) {

        echo '

        <div class="alert alert-danger alert-dismissible fade show">

        ' . $_SESSION['error'] . '

        <button

        class="btn-close"

        data-bs-dismiss="alert">

        </button>

        </div>';

        unset($_SESSION['error']);

    }
}


/*==================================================
    GENERATE RANDOM STRING
==================================================*/

function randomString($length = 10)
{
    return substr(

        str_shuffle(

            "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789"

        ),

        0,

        $length

    );
}


/*==================================================
    DEBUG
==================================================*/

function dd($data)
{
    echo "<pre>";

    print_r($data);

    echo "</pre>";

    die();
}

?>