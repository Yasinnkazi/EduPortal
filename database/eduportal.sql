-- ============================================================
-- EduPortal - Educational Student Management Portal
-- Database script for SQL Injection demonstration lab (classroom use only)
--
-- WARNING: This database powers an intentionally vulnerable application
-- designed ONLY for educational demonstrations of SQL Injection in a
-- controlled laboratory environment. Never deploy on a public server.
-- ============================================================

CREATE DATABASE IF NOT EXISTS eduportal CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE eduportal;

-- ------------------------------------------------------------
-- Table: students
-- ------------------------------------------------------------
DROP TABLE IF EXISTS students;
CREATE TABLE students (
  id INT AUTO_INCREMENT PRIMARY KEY,
  roll_no VARCHAR(20) UNIQUE NOT NULL,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  course_id INT,
  semester INT DEFAULT 1,
  email VARCHAR(100),
  phone VARCHAR(20),
  address VARCHAR(255),
  city VARCHAR(50),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Table: admins
-- ------------------------------------------------------------
DROP TABLE IF EXISTS admins;
CREATE TABLE admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  role VARCHAR(50)
);

-- ------------------------------------------------------------
-- Table: courses
-- ------------------------------------------------------------
DROP TABLE IF EXISTS courses;
CREATE TABLE courses (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(20) UNIQUE NOT NULL,
  name VARCHAR(100) NOT NULL,
  department VARCHAR(80),
  duration VARCHAR(20),
  seats INT,
  fee DECIMAL(10,2),
  description TEXT
);

-- ------------------------------------------------------------
-- Table: faculty
-- ------------------------------------------------------------
DROP TABLE IF EXISTS faculty;
CREATE TABLE faculty (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  designation VARCHAR(80),
  department VARCHAR(80),
  qualification VARCHAR(100),
  email VARCHAR(100),
  phone VARCHAR(20)
);

-- ------------------------------------------------------------
-- Table: results
-- ------------------------------------------------------------
DROP TABLE IF EXISTS results;
CREATE TABLE results (
  id INT AUTO_INCREMENT PRIMARY KEY,
  roll_no VARCHAR(20) NOT NULL,
  subject VARCHAR(100) NOT NULL,
  marks INT,
  total INT DEFAULT 100,
  percentage DECIMAL(5,2),
  grade VARCHAR(5),
  exam_type VARCHAR(30),
  semester INT
);

-- ============================================================
-- SEED DATA
-- ============================================================

-- Courses -----------------------------------------------------
INSERT INTO courses (code, name, department, duration, seats, fee, description) VALUES
('BCS',  'B.Sc. Computer Science', 'Computer Science', '3 Years', 120, 45000.00, 'Programming, data structures, web & database technologies with AI electives.'),
('BIT',  'B.Sc. Information Technology', 'Information Technology', '3 Years', 100, 42000.00, 'Networks, software engineering, cloud computing and cyber security.'),
('BCA',  'BCA (Bachelor of Computer Applications)', 'Computer Applications', '3 Years', 100, 38000.00, 'Application development, database management and system analysis.'),
('BEC',  'B.E. Computer Engineering', 'Engineering', '4 Years', 60, 85000.00, 'Core engineering with computer systems, OS and embedded computing.'),
('BCOM', 'B.Com (Commerce)', 'Commerce', '3 Years', 150, 30000.00, 'Accounting, economics, taxation and business management.'),
('BSCB', 'B.Sc. Biotechnology', 'Life Sciences', '3 Years', 60, 55000.00, 'Molecular biology, genetics, bioprocess and bioinformatics.');

-- Faculty -----------------------------------------------------
INSERT INTO faculty (name, designation, department, qualification, email, phone) VALUES
('Dr. Rajesh Kulkarni',  'Professor',      'Computer Science',   'Ph.D. (Computer Science)', 'rajesh.kulkarni@eduportal.ac.in', '9820012345'),
('Prof. Meera Nair',     'Associate Professor', 'Information Technology', 'M.Tech (IT)', 'meera.nair@eduportal.ac.in', '9820012346'),
('Dr. Sanjay Deshpande', 'Professor',      'Computer Applications', 'Ph.D. (IT)', 'sanjay.deshpande@eduportal.ac.in', '9820012347'),
('Prof. Anita Sharma',   'Assistant Professor', 'Computer Science', 'M.Sc. (CS), M.Phil', 'anita.sharma@eduportal.ac.in', '9820012348'),
('Dr. Vikram Patil',     'Associate Professor', 'Engineering',    'Ph.D. (Electronics)', 'vikram.patil@eduportal.ac.in', '9820012349');

-- Admins ------------------------------------------------------
INSERT INTO admins (username, password, full_name, email, role) VALUES
('admin',    'admin123',    'Abhishek Yadav',       'admin@eduportal.ac.in',  'Administrator'),
('hod_cs',   'hod123',      'Dr. Rajesh Kulkarni',  'hod.cs@eduportal.ac.in', 'HOD - CS'),
('coord',    'coord123',    'Prof. Meera Nair',     'coord@eduportal.ac.in',  'Coordinator'),
('admin2',   'admin@2026',  'Anita Sharma',         'admin2@eduportal.ac.in', 'Assistant Admin'),
('superuser','root@edu',    'Super Admin',          'super@eduportal.ac.in',  'Super Administrator');

-- Students (20) ----------------------------------------------
INSERT INTO students (roll_no, name, username, password, course_id, semester, email, phone, address, city) VALUES
('BCS2026-001', 'Aarav Mehta',      'aarav01',   'student123', 1, 6, 'aarav.mehta@student.edu',  '9890010001', '12 Linking Road', 'Mumbai'),
('BCS2026-002', 'Ishita Rao',       'ishita02',  'student123', 1, 6, 'ishita.rao@student.edu',   '9890010002', '45 Carter Road', 'Mumbai'),
('BCS2026-003', 'Rohan Gupta',      'rohan03',   'student123', 1, 4, 'rohan.gupta@student.edu',  '9890010003', '7 MG Road', 'Pune'),
('BCS2026-004', 'Sneha Kulkarni',   'sneha04',   'student123', 1, 4, 'sneha.kulk@student.edu',   '9890010004', '21 FC Road', 'Pune'),
('BCS2026-005', 'Vivaan Joshi',     'vivaan05',  'student123', 1, 2, 'vivaan.joshi@student.edu', '9890010005', '3 Station Road', 'Nashik'),
('BIT2026-006', 'Ananya Iyer',      'ananya06',  'student123', 2, 6, 'ananya.iyer@student.edu',   '9890010006', '8 Marine Drive', 'Mumbai'),
('BIT2026-007', 'Kabir Shah',       'kabir07',   'student123', 2, 4, 'kabir.shah@student.edu',    '9890010007', '33 SG Highway', 'Ahmedabad'),
('BIT2026-008', 'Diya Patel',       'diya08',    'student123', 2, 2, 'diya.patel@student.edu',    '9890010008', '56 Ambawadi', 'Ahmedabad'),
('BIT2026-009', 'Arjun Singh',      'arjun09',   'student123', 2, 6, 'arjun.singh@student.edu',   '9890010009', '9 Juhu Lane', 'Mumbai'),
('BCA2026-010', 'Myra Fernandes',   'myra10',    'student123', 3, 6, 'myra.fern@student.edu',     '9890010010', '17 Colaba Causeway', 'Mumbai'),
('BCA2026-011', 'Advait Desai',     'advait11',  'student123', 3, 4, 'advait.desai@student.edu',  '9890010011', '29 Law Garden', 'Ahmedabad'),
('BCA2026-012', 'Kavya Reddy',      'kavya12',   'student123', 3, 2, 'kavya.reddy@student.edu',   '9890010012', '5 Banjara Hills', 'Hyderabad'),
('BCA2026-013', 'Nikhil Verma',     'nikhil13',  'student123', 3, 6, 'nikhil.verma@student.edu',  '9890010013', '22 Hazratganj', 'Lucknow'),
('BEC2026-014', 'Riya Chatterjee',  'riya14',    'student123', 4, 8, 'riya.chat@student.edu',     '9890010014', '14 Salt Lake', 'Kolkata'),
('BEC2026-015', 'Yash Nair',        'yash15',    'student123', 4, 6, 'yash.nair@student.edu',     '9890010015', '41 Panjim Market', 'Goa'),
('BEC2026-016', 'Tanvi Bansal',     'tanvi16',   'student123', 4, 4, 'tanvi.bansal@student.edu',  '9890010016', '18 Connaught Place', 'New Delhi'),
('BCOM2026-017','Aditya More',      'aditya17',  'student123', 5, 6, 'aditya.more@student.edu',   '9890010017', '30 Shivaji Nagar', 'Mumbai'),
('BCOM2026-018','Sara Sheikh',      'sara18',    'student123', 5, 4, 'sara.sheikh@student.edu',   '9890010018', '25 Linking Road', 'Mumbai'),
('BSCB2026-019','Dev Sharma',       'dev19',     'student123', 6, 6, 'dev.sharma@student.edu',    '9890010019', '48 Garia Main Road', 'Kolkata'),
('BSCB2026-020','Anjali Patil',     'anjali20',  'student123', 6, 4, 'anjali.patil@student.edu',  '9890010020', '13 Tilak Road', 'Pune');

-- Results (Semester 6 sample: BCS / BIT / BCA / BEC / BCOM / BSCB) -----
INSERT INTO results (roll_no, subject, marks, total, percentage, grade, exam_type, semester) VALUES
('BCS2026-001','Machine Learning',      88, 100, 88.00, 'A+', 'University Theory', 6),
('BCS2026-001','Web Technologies',      82, 100, 82.00, 'A',  'University Theory', 6),
('BCS2026-001','Database Systems',      91, 100, 91.00, 'A+', 'University Theory', 6),
('BCS2026-001','Software Engineering',  76, 100, 76.00, 'B+', 'University Theory', 6),
('BCS2026-001','Cyber Security',        85, 100, 85.00, 'A',  'University Theory', 6),
('BCS2026-002','Machine Learning',      78, 100, 78.00, 'B+', 'University Theory', 6),
('BCS2026-002','Web Technologies',      90, 100, 90.00, 'A+', 'University Theory', 6),
('BCS2026-002','Database Systems',      72, 100, 72.00, 'B',  'University Theory', 6),
('BCS2026-002','Software Engineering',  84, 100, 84.00, 'A',  'University Theory', 6),
('BCS2026-002','Cyber Security',        79, 100, 79.00, 'B+', 'University Theory', 6),
('BCS2026-003','Machine Learning',      64, 100, 64.00, 'B',  'University Theory', 4),
('BCS2026-003','Operating Systems',     71, 100, 71.00, 'B',  'University Theory', 4),
('BCS2026-003','Data Structures',       83, 100, 83.00, 'A',  'University Theory', 4),
('BCS2026-003','Computer Networks',     69, 100, 69.00, 'B',  'University Theory', 4),
('BCS2026-003','DBMS',                  88, 100, 88.00, 'A+', 'University Theory', 4),
('BCS2026-004','Machine Learning',      92, 100, 92.00, 'A+', 'University Theory', 4),
('BCS2026-004','Operating Systems',     86, 100, 86.00, 'A',  'University Theory', 4),
('BCS2026-004','Data Structures',       95, 100, 95.00, 'A+', 'University Theory', 4),
('BCS2026-004','Computer Networks',     90, 100, 90.00, 'A+', 'University Theory', 4),
('BCS2026-004','DBMS',                  93, 100, 93.00, 'A+', 'University Theory', 4),
('BCS2026-005','Python Programming',    74, 100, 74.00, 'B+', 'University Theory', 2),
('BCS2026-005','Digital Electronics',   68, 100, 68.00, 'B',  'University Theory', 2),
('BCS2026-005','Discrete Mathematics',  77, 100, 77.00, 'B+', 'University Theory', 2),
('BCS2026-005','English',               81, 100, 81.00, 'A',  'University Theory', 2),
('BIT2026-006','Network Security',      87, 100, 87.00, 'A+', 'University Theory', 6),
('BIT2026-006','Cloud Computing',       79, 100, 79.00, 'B+', 'University Theory', 6),
('BIT2026-006','Data Analytics',        84, 100, 84.00, 'A',  'University Theory', 6),
('BIT2026-006','Web Security',          73, 100, 73.00, 'B',  'University Theory', 6),
('BIT2026-007','Network Security',      66, 100, 66.00, 'B',  'University Theory', 4),
('BIT2026-007','Operating Systems',     82, 100, 82.00, 'A',  'University Theory', 4),
('BIT2026-007','Computer Networks',     71, 100, 71.00, 'B',  'University Theory', 4),
('BIT2026-007','DBMS',                  78, 100, 78.00, 'B+', 'University Theory', 4),
('BIT2026-008','C Programming',         69, 100, 69.00, 'B',  'University Theory', 2),
('BIT2026-008','Mathematics',           76, 100, 76.00, 'B+', 'University Theory', 2),
('BIT2026-008','English',               80, 100, 80.00, 'A',  'University Theory', 2),
('BIT2026-009','Network Security',      91, 100, 91.00, 'A+', 'University Theory', 6),
('BIT2026-009','Cloud Computing',       85, 100, 85.00, 'A',  'University Theory', 6),
('BIT2026-009','Data Analytics',        77, 100, 77.00, 'B+', 'University Theory', 6),
('BIT2026-009','Web Security',          82, 100, 82.00, 'A',  'University Theory', 6),
('BCA2026-010','Java Programming',      83, 100, 83.00, 'A',  'University Theory', 6),
('BCA2026-010','Web Development',       89, 100, 89.00, 'A+', 'University Theory', 6),
('BCA2026-010','RDBMS',                 75, 100, 75.00, 'B+', 'University Theory', 6),
('BCA2026-010','Software Testing',      70, 100, 70.00, 'B',  'University Theory', 6),
('BCA2026-010','E-Commerce',            78, 100, 78.00, 'B+', 'University Theory', 6),
('BCA2026-011','Java Programming',      72, 100, 72.00, 'B',  'University Theory', 4),
('BCA2026-011','Data Structures',       84, 100, 84.00, 'A',  'University Theory', 4),
('BCA2026-011','Operating Systems',     68, 100, 68.00, 'B',  'University Theory', 4),
('BCA2026-011','DBMS',                  81, 100, 81.00, 'A',  'University Theory', 4),
('BCA2026-012','C Programming',         65, 100, 65.00, 'B',  'University Theory', 2),
('BCA2026-012','Mathematics',           72, 100, 72.00, 'B',  'University Theory', 2),
('BCA2026-012','Communication Skills',  78, 100, 78.00, 'B+', 'University Theory', 2),
('BCA2026-013','Java Programming',      86, 100, 86.00, 'A',  'University Theory', 6),
('BCA2026-013','Web Development',       91, 100, 91.00, 'A+', 'University Theory', 6),
('BCA2026-013','RDBMS',                 79, 100, 79.00, 'B+', 'University Theory', 6),
('BCA2026-013','Software Testing',      74, 100, 74.00, 'B+', 'University Theory', 6),
('BCA2026-013','E-Commerce',            80, 100, 80.00, 'A',  'University Theory', 6),
('BEC2026-014','Data Structures',       89, 100, 89.00, 'A+', 'University Theory', 8),
('BEC2026-014','Compiler Design',       76, 100, 76.00, 'B+', 'University Theory', 8),
('BEC2026-014','Machine Learning',      83, 100, 83.00, 'A',  'University Theory', 8),
('BEC2026-014','Computer Networks',     71, 100, 71.00, 'B',  'University Theory', 8),
('BEC2026-015','Operating Systems',     84, 100, 84.00, 'A',  'University Theory', 6),
('BEC2026-015','DBMS',                  77, 100, 77.00, 'B+', 'University Theory', 6),
('BEC2026-015','Microprocessors',       69, 100, 69.00, 'B',  'University Theory', 6),
('BEC2026-015','Object Oriented Prog.', 82, 100, 82.00, 'A',  'University Theory', 6),
('BEC2026-016','Digital Logic Design',  73, 100, 73.00, 'B',  'University Theory', 4),
('BEC2026-016','Data Structures',       86, 100, 86.00, 'A',  'University Theory', 4),
('BEC2026-016','Discrete Maths',        79, 100, 79.00, 'B+', 'University Theory', 4),
('BCOM2026-017','Financial Accounting', 85, 100, 85.00, 'A',  'University Theory', 6),
('BCOM2026-017','Business Economics',   78, 100, 78.00, 'B+', 'University Theory', 6),
('BCOM2026-017','Taxation',             72, 100, 72.00, 'B',  'University Theory', 6),
('BCOM2026-017','Management Studies',   80, 100, 80.00, 'A',  'University Theory', 6),
('BCOM2026-018','Financial Accounting', 70, 100, 70.00, 'B',  'University Theory', 4),
('BCOM2026-018','Business Maths',       83, 100, 83.00, 'A',  'University Theory', 4),
('BCOM2026-018','Microeconomics',       77, 100, 77.00, 'B+', 'University Theory', 4),
('BSCB2026-019','Molecular Biology',    84, 100, 84.00, 'A',  'University Theory', 6),
('BSCB2026-019','Genetics',             79, 100, 79.00, 'B+', 'University Theory', 6),
('BSCB2026-019','Biochemistry',         82, 100, 82.00, 'A',  'University Theory', 6),
('BSCB2026-019','Bioinformatics',       74, 100, 74.00, 'B+', 'University Theory', 6),
('BSCB2026-020','Cell Biology',         71, 100, 71.00, 'B',  'University Theory', 4),
('BSCB2026-020','Microbiology',         85, 100, 85.00, 'A',  'University Theory', 4),
('BSCB2026-020','Chemistry',            78, 100, 78.00, 'B+', 'University Theory', 4);

-- Extra sample students WITHOUT results (to make the search lab richer)
INSERT INTO students (roll_no, name, username, password, course_id, semester, email, phone, address, city) VALUES
('BCS2026-021', 'Riddhi Gokhale', 'riddhi21', 'student123', 1, 6, 'riddhi.gokhale@student.edu', '9890010021', '55 Worli Sea Face', 'Mumbai'),
('BCS2026-022', 'Kunal Shetty',  'kunal22',  'student123', 1, 4, 'kunal.shetty@student.edu',  '9890010022', '19 Andheri West', 'Mumbai');
