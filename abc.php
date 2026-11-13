
<?php
session_start();
include "../db.php";

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['delete_user']) && isset($_POST['delete_id'])) {

    $id = (int) $_POST['delete_id'];

    if ($id <= 0) {
        die("Invalid User ID!");
    }

    if ($id === (int) $_SESSION['user_id']) {
        die("You cannot delete your own account!");
    }

    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            header("Location: abc.php");
            exit();
        } else {
            echo "No user was deleted. Please check the User ID.";
        }

    } else {
        echo "Delete failed: " . htmlspecialchars(mysqli_stmt_error($stmt));
    }
}

$sql = "SELECT id, username, email, role FROM users";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="../style.css?v=2">
</head>
<body>

<div class="container">

    <h2>User Management</h2>

    <a href="add.php">+ Add New User</a>
<br><br>

    <table border="1" cellpadding="10">

        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>

            <td><?php echo htmlspecialchars($row['username']); ?></td>

            <td><?php echo htmlspecialchars($row['email']); ?></td>

            <td><?php echo htmlspecialchars($row['role']); ?></td>

            <td>
                <a href="edit.php?id=<?php echo (int)$row['id']; ?>">
                    Edit
                </a>

                <form method="POST" style="display:inline;"
                    onsubmit="return confirm('Are you sure you want to delete this user?');">

                    <input type="hidden" name="delete_id"
                        value="<?php echo (int)$row['id']; ?>">

                    <button type="submit" name="delete_user">
                        Delete
                    </button>

                </form>
            </td>
        </tr>

        <?php } ?>

    </table>

    <br>

    <a href="dashboard.php">Back to Dashboard</a>
    |
    <a href="../logout.php">Logout</a>

</div>

</body>
</html>
