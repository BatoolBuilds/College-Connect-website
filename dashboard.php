<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: home.html");
    exit();
}

$role = $_SESSION['role'];
$email = $_SESSION['user'];

// Database connection
$conn = new mysqli("localhost", "root", "", "collegeconnect");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user info
switch ($role) {
    case "student":
        $result = $conn->query("SELECT * FROM students WHERE Email='$email'");
        break;
    case "teacher":
        $result = $conn->query("SELECT * FROM teachers WHERE Email='$email'");
        break;
    case "admin":
        $result = $conn->query("SELECT * FROM admin WHERE Email='$email'");
        break;
}

$userData = $result->fetch_assoc();

// Admin fetches
$allStudents = ($role == "admin") ? $conn->query("SELECT * FROM students") : null;
$allTeachers = ($role == "admin") ? $conn->query("SELECT * FROM teachers") : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <link rel="stylesheet" href="style.css">

    <script>
        // Show respective section
        function showSection() {
            const value = document.getElementById("infoSelect").value;

            document.querySelectorAll(".info-box").forEach(div => {
                div.style.display = "none";
            });

            if (value) {
                document.getElementById(value).style.display = "block";
            }
        }

        // Sidebar toggle
        function toggleMenu() {
            document.getElementById("dashMenu").classList.toggle("open");
        }
    </script>
</head>

<body class="dashboard-body">

<!-- ========== DASHBOARD HEADER ========== -->
<header class="dash-header">
    <div class="dash-logo">Dashboard</div>

    <!-- Menu Button -->
    <button class="dash-menu-icon" onclick="toggleMenu()">☰</button>
</header>

<!-- ========== SLIDING SIDEBAR ========== -->
<nav id="dashMenu" class="dash-nav">
    <ul>
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="logout.php">Log Out</a></li>
    </ul>
</nav>

<!-- ========== MAIN CONTENT ========== -->
<div class="container">

    <h2>Welcome, <?= $userData['Name']; ?></h2>

    <select id="infoSelect" onchange="showSection()">
        <option value="">Select Information</option>

        <?php if ($role == "student"): ?>
            <option value="studentInfo">Student Information</option>
        <?php endif; ?>

        <?php if ($role == "teacher"): ?>
            <option value="teacherInfo">Teacher Information</option>
        <?php endif; ?>

        <?php if ($role == "admin"): ?>
            <option value="allStudents">All Students</option>
            <option value="allTeachers">All Teachers</option>
        <?php endif; ?>
    </select>

    <!-- STUDENT INFO -->
    <div id="studentInfo" class="info-box" style="display:none;">
        <h3>Student Information</h3>
        <table>
            <tr><th>ID</th><td><?= $userData['Student_ID']; ?></td></tr>
            <tr><th>Name</th><td><?= $userData['Name']; ?></td></tr>
            <tr><th>Email</th><td><?= $userData['Email']; ?></td></tr>
            <tr><th>Contact</th><td><?= $userData['Contact_Number']; ?></td></tr>
            <tr><th>Class</th><td><?= $userData['Class_ID']; ?></td></tr>
        </table>
    </div>

    <!-- TEACHER INFO -->
    <div id="teacherInfo" class="info-box" style="display:none;">
        <h3>Teacher Information</h3>
        <table>
            <tr><th>ID</th><td><?= $userData['Teacher_ID']; ?></td></tr>
            <tr><th>Name</th><td><?= $userData['Name']; ?></td></tr>
            <tr><th>Email</th><td><?= $userData['Email']; ?></td></tr>
            <tr><th>Subject</th><td><?= $userData['Subject']; ?></td></tr>
        </table>
    </div>

    <!-- ALL STUDENTS -->
    <div id="allStudents" class="info-box" style="display:none;">
        <h3>All Students</h3>
        <div class="table-wrapper">
            <table>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Contact</th><th>Class</th></tr>
                <?php if ($role == "admin"):
                    while ($row = $allStudents->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['Student_ID']; ?></td>
                            <td><?= $row['Name']; ?></td>
                            <td><?= $row['Email']; ?></td>
                            <td><?= $row['Contact_Number']; ?></td>
                            <td><?= $row['Class_ID']; ?></td>
                        </tr>
                <?php endwhile; endif; ?>
            </table>
        </div>
    </div>

    <!-- ALL TEACHERS -->
    <div id="allTeachers" class="info-box" style="display:none;">
        <h3>All Teachers</h3>
        <div class="table-wrapper">
            <table>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th></tr>
                <?php if ($role == "admin"):
                    while ($row = $allTeachers->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['Teacher_ID']; ?></td>
                            <td><?= $row['Name']; ?></td>
                            <td><?= $row['Email']; ?></td>
                            <td><?= $row['Subject']; ?></td>
                        </tr>
                <?php endwhile; endif; ?>
            </table>
        </div>
    </div>

</div>

</body>
</html>
