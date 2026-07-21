-- ==========================================
-- VC Routine Management System Database
-- Database: VC
-- ==========================================
USE VC;

-- --------------------------
-- Users Table
-- --------------------------

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('principal','teacher') NOT NULL,
    department VARCHAR(100) NOT NULL,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users(name,email,password,role,department,status) VALUES
('Principal','principal@vc.edu','123456','principal','Administration','Active'),
('Prof. S Roy','sroy@vc.edu','123456','teacher','Computer Science','Active'),
('Prof. M Dutta','mdutta@vc.edu','123456','teacher','Physics','Active'),
('Prof. A Sen','asen@vc.edu','123456','teacher','Mathematics','Active');

-- --------------------------
-- Departments
-- --------------------------

CREATE TABLE departments (
    department_id INT AUTO_INCREMENT PRIMARY KEY,
    department_name VARCHAR(100) NOT NULL UNIQUE
);

INSERT INTO departments(department_name) VALUES
('Computer Science'),
('Physics'),
('Chemistry'),
('Mathematics'),
('Commerce'),
('English');

-- --------------------------
-- Semesters
-- --------------------------

CREATE TABLE semesters (
    semester_id INT AUTO_INCREMENT PRIMARY KEY,
    department_id INT NOT NULL,
    semester_name VARCHAR(30) NOT NULL,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
        ON DELETE CASCADE
);

INSERT INTO semesters(department_id,semester_name) VALUES
(1,'Semester I'),
(1,'Semester II'),
(1,'Semester III'),
(1,'Semester IV'),
(1,'Semester V'),
(1,'Semester VI');

-- --------------------------
-- Routine
-- --------------------------

CREATE TABLE routine (
    routine_id INT AUTO_INCREMENT PRIMARY KEY,
    department_id INT NOT NULL,
    semester_id INT NOT NULL,
    day ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    subject VARCHAR(100) NOT NULL,
    teacher_name VARCHAR(100) NOT NULL,
    room_no VARCHAR(30),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
        ON DELETE CASCADE,
    FOREIGN KEY (semester_id) REFERENCES semesters(semester_id)
        ON DELETE CASCADE
);

INSERT INTO routine
(department_id,semester_id,day,start_time,end_time,subject,teacher_name,room_no)
VALUES
(1,1,'Monday','10:00:00','11:00:00','Programming Fundamentals','Prof. S Roy','CS-101'),
(1,1,'Monday','11:00:00','12:00:00','Mathematics I','Prof. A Sen','CS-102'),
(1,1,'Tuesday','10:00:00','11:00:00','Digital Logic','Prof. S Roy','CS-101');

CREATE TABLE master_routine (

    master_routine_id INT AUTO_INCREMENT PRIMARY KEY,

    session_type ENUM('Odd','Even') NOT NULL,

    course_type ENUM('BA','BSc') NOT NULL,

    semester TINYINT NOT NULL,

    day ENUM(
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday'
    ) NOT NULL,

    start_time TIME NOT NULL,

    end_time TIME NOT NULL,

    course_name VARCHAR(120) NOT NULL,

    class_type VARCHAR(30) DEFAULT NULL,

    subject VARCHAR(255) DEFAULT NULL,

    description TEXT DEFAULT NULL

);

INSERT INTO master_routine
(session_type, course_type, semester, day, start_time, end_time, course_name, class_type, subject, teacher_name)
VALUES

('Odd','BA',1,'Monday','10:45:00','11:45:00',
'Major','Th/Tu','CC1 / SEC1','Dr. A Sen'),

('Odd','BA',1,'Monday','11:45:00','12:45:00',
'Minor-1','Th/Tu','Minor-1','Dr. P Roy'),

('Odd','BA',1,'Monday','12:45:00','13:45:00',
'Minor-1','Th/Tu','Minor-1','Dr. P Roy'),

('Odd','BA',1,'Monday','15:00:00','16:00:00',
'IDC','Th/Tu','Interdisciplinary Course','Dr. S Das'),

('Odd','BSc',3,'Tuesday','10:45:00','11:45:00',
'Major','Practical','Organic Chemistry Lab','Dr. M Ghosh'),

('Odd','BSc',3,'Tuesday','11:45:00','12:45:00',
'IDC','Th/Tu','Data Analysis','Dr. R Paul'),

('Even','BA',2,'Wednesday','10:45:00','11:45:00',
'AEC','Th','Communicative English','Dr. S Roy'),

('Even','BA',4,'Thursday','12:45:00','13:45:00',
'CVAC','Th','Environmental Studies','Dr. T Mitra'),

('Even','BSc',6,'Friday','15:00:00','16:00:00',
'Major','Th','Machine Learning','Dr. A Chatterjee'),

('Even','BSc',6,'Saturday','16:00:00','17:00:00',
'Add-On Course','Workshop','Python for Data Science','Industry Expert');

-- --------------------------
-- Calendar Events
-- --------------------------

CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    department_id INT NULL,
    created_by INT,
    FOREIGN KEY (department_id) REFERENCES departments(department_id)
        ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id)
        ON DELETE SET NULL
);

INSERT INTO events(title,description,event_date,department_id,created_by)
VALUES
('Faculty Meeting','Monthly faculty meeting','2026-08-01',NULL,1),
('Internal Exam','Semester I Internal Exam','2026-08-05',1,2);


CREATE TABLE master_routine_notes (
    note_id INT AUTO_INCREMENT PRIMARY KEY,
    note TEXT NOT NULL
);