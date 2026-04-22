<?php
session_start();
session_destroy();
?>

<html>
<head>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<script>
Swal.fire({
    icon: 'success',
    title: 'Logged Out',
    text: 'You have logged out successfully.',
    confirmButtonColor: '#4CAF50'
}).then(function() {
    window.location.href = 'home.html';
});
</script>

</body>
</html>