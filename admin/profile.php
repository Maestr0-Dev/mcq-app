<?php
session_start();
include '../classes.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("location:" . BASE_URL . "admin/login.php");
    exit();
}

$db = new DB();
$admin_id = $_SESSION['admin_id'];
$message = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($db->updateAdminProfile($admin_id, $username, $email, $password)) {
        $message = "Profile updated successfully.";
        $_SESSION['admin_name'] = $username;
    } else {
        $message = "Error updating profile.";
    }
}

$admin = $db->getAdminById($admin_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Profile</title>
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
</head>
<body>
    <?php include 'admin_nav.php'; ?>
    <div class="container">
        <h1>Admin Profile</h1>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <input type="text" name="username" required placeholder="Username" value="<?php echo $admin['adm_name']; ?>">
            <input type="email" name="email" required placeholder="Email" value="<?php echo $admin['emails']; ?>">
            <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
            <button type="submit">Update Profile</button>
            <a href="logout.php">Logout</a>
        </form>
    </div>
</body>
</html>
