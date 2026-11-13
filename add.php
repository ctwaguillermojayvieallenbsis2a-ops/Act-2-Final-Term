
<?php
session_start();
include "../db.php";

// Admin access only
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = "";

if (isset($_POST['add_user'])) {

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($username == "" || $email == "" || $password == "") {
        $message = "Please fill in all fields.";
    } elseif (!in_array($role, ['admin', 'user'])) {
        $message = "Invalid role.";
    } else {

        // Check if email already exists
        $check = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {
            $message = "Email already exists!";
        } else {

            // Securely hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, email, password, role)
                    VALUES (?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $username,
                $email,
                $hashed_password,
                $role
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: abc.php");
                exit();
            } else {
                $message = "Error adding user.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User</title>
    <link rel="stylesheet" href="../style.css?v=2">
</head>
<body>

<div class="container">

    <h2>Add New User</h2>

    <p><?php echo htmlspecialchars($message); ?></p>

    <form method="POST">

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Role:</label>
        <select name="role">
            <option value="user">User</option>
            <option value="admin">Admin</option>
        </select>

        <button type="submit" name="add_user">Add User</button>

    </form>

    <br>
    <a href="abc.php">Back to User Management</a>

</div>

</body>
</html>
