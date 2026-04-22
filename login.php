<?php
session_start();

// Database connection
$host = "localhost";
$user = "root";
$pass = "";
$db = "collegeconnect";

$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form input
$email = $_POST['email'];
$password = $_POST['password'];  // For students & teachers: this is their ID


/* ============================
   1. ADMIN LOGIN CHECK
   ============================ */
$admin_sql = "SELECT * FROM admin WHERE Email='$email' AND Password='$password'";
$admin_result = $conn->query($admin_sql);

if ($admin_result->num_rows > 0) {

    $_SESSION['user'] = $email;
    $_SESSION['role'] = "admin";

    echo "
    <html>
    <head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: 'Welcome back, Admin!',
        confirmButtonColor: '#4CAF50'
    }).then(function() {
        window.location.href = 'dashboard.php';
    });
    </script>
    </body>
    </html>
    ";
    exit();
}


/* ============================
   2. STUDENT LOGIN CHECK
   ============================ */
$student_sql = "SELECT * FROM students WHERE Email='$email' AND Student_ID='$password'";
$student_result = $conn->query($student_sql);

if ($student_result->num_rows > 0) {

    $_SESSION['user'] = $email;
    $_SESSION['role'] = "student";

    echo "
    <html>
    <head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: 'Welcome back, Student!',
        confirmButtonColor: '#4CAF50'
    }).then(function() {
        window.location.href = 'dashboard.php';
    });
    </script>
    </body>
    </html>
    ";
    exit();
}


/* ============================
   3. TEACHER LOGIN CHECK
   ============================ */
$teacher_sql = "SELECT * FROM teachers WHERE Email='$email' AND Teacher_ID='$password'";
$teacher_result = $conn->query($teacher_sql);

if ($teacher_result->num_rows > 0) {

    $_SESSION['user'] = $email;
    $_SESSION['role'] = "teacher";

    echo "
    <html>
    <head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
    <script>
    Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: 'Welcome back, Teacher!',
        confirmButtonColor: '#4CAF50'
    }).then(function() {
        window.location.href = 'dashboard.php';
    });
    </script>
    </body>
    </html>
    ";
    exit();
}


/* ============================
   4. LOGIN FAILED
   ============================ */
echo "
<html>
<head>
<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'error',
    title: 'Oops...',
    text: 'Incorrect email or password',
    confirmButtonColor: '#4CAF50'
}).then(function() {
    window.location.href = 'home.html';
});
</script>
</body>
</html>
";
exit();

?>
