
<?php
session_start();
include "../db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Kunin ang User ID
if (!isset($_GET['id'])) {
    die("User ID not found!");
}

$id = (int) $_GET['id'];

// Kunin ang existing user information
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found!");
}

// I-save ang updated information
if (isset($_POST['update'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];

    if (!in_array($role, ['admin', 'user'], true)) {
        die("Invalid role!");
    }

    $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $role, $id);

    if (mysqli_stmt_execute($stmt)) {
        header("Location: abc.php");
        exit();
    } else {
        echo "Update failed: " . htmlspecialchars(mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">

    <h2>Edit User</h2>

    <form method="POST">

        <label>Username:</label>
        <input type="text" name="username"
        value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <br><br>

        <label>Email:</label>
        <input type="email" name="email"
        value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <br><br>

        <label>Role:</label>
        <select name="role">
            <option value="user" <?php if ($user['role'] == 'user') echo 'selected'; ?>>
                User
            </option>

            <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>
                Admin
            </option>
        </select>

        <br><br>

        <button type="submit" name="update">Update User</button>

    </form>

    <br>
    <a href="abc.php">Back to Users</a>

</div>

</body>
</html>
