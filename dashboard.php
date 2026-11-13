
<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'user') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">

    <h2>User Dashboard</h2>

    <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h3>

    <p>You are logged in as a regular user.</p>

    <a href="../logout.php">Logout</a>

</div>

</body>
</html>
