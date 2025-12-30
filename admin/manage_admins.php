<?php
session_start();
include '../classes.php';

// Check if the user is a Level 3 admin
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_level'] != 3) {
    header("location:" . BASE_URL . "admin/login.php");
    exit();
}

$db = new DB();
$message = "";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_admin'])) {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $level = $_POST['level'];
        $phone = $_POST['phone'];
        
        if ($db->addAdmin($username, $email, $password, $level, $phone)) {
            $message = "Admin added successfully.";
        } else {
            $message = "Error adding admin. Username or email may already exist.";
        }
    } elseif (isset($_POST['update_level'])) {
        $admin_id = $_POST['admin_id'];
        $level = $_POST['level'];
        
        if ($db->updateAdminLevel($admin_id, $level)) {
            $message = "Admin level updated successfully.";
        } else {
            $message = "Error updating admin level.";
        }
    }
}

$admins = $db->getAllAdmins();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Admins</title>
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
</head>
<body>
    <?php include 'admin_nav.php'; ?>
    <div class="container">
        <h1>Manage Admins</h1>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <!-- Add Admin Form -->
        <h2>Add New Admin</h2>
        <form action="" method="post">
            <input type="text" name="username" required placeholder="Username">
            <input type="email" name="email" required placeholder="Email">
            <input type="password" name="password" required placeholder="Password">
            <select name="level" required>
                <option value="1">Level 1 (Content Editor)</option>
                <option value="2">Level 2 (Full Admin)</option>
                <option value="3">Level 3 (Super Admin)</option>
            </select>
            <input type="number" name="phone" required placeholder="Phone number">

            <button type="submit" name="add_admin">Add Admin</button>
        </form>

        <!-- Admins List -->
        <h2>Existing Admins</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Level</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): 
                    if($admin['admin_id'] != '1'):?>
                    <tr>
                        <td><?php echo $admin['admin_id']; ?></td>
                        <td><?php echo $admin['adm_name']; ?></td>
                        <td><?php echo $admin['emails']; ?></td>
                        <td><?php echo $admin['phone']; ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="admin_id" value="<?php echo $admin['admin_id']; ?>">
                                <select name="level">
                                    <option value="1" <?php if ($admin['level'] == 1) echo 'selected'; ?>>Level 1</option>
                                    <option value="2" <?php if ($admin['level'] == 2) echo 'selected'; ?>>Level 2</option>
                                    <option value="3" <?php if ($admin['level'] == 3) echo 'selected'; ?>>Level 3</option>
                                </select>
                                <button type="submit" name="update_level">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php
            endif;
            endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
