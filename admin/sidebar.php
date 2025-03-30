<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link type="text/css" rel="stylesheet" href="../myCss/admin_nav.css">
    
</head>
<body>
<?php
/**
 * Sidebar Navigation Menu
 * Include this file in different pages to display a consistent sidebar navigation
 */
?>
<div class="sidebar">
    <div class="sidebar-header">
        <h3>Admin Navigation</h3>
    </div>
    <div class="sidebar-user">
        <!-- <div class="user-image">
            <img src="images/user-avatar.png" alt="User">
        </div> -->
        <div class="user-info">
            <p class="username">Welcome, User</p>
        </div>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">
                <a href="index.php">
                    <i class="icon-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'products.php') ? 'active' : ''; ?>">
                <a href="Verifications.php">
                    <i class="icon-box"></i>
                    <span>Verifications</span>
                </a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>">
                <a href="users.php">
                    <i class="icon-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'analytics.php') ? 'active' : ''; ?>">
                <a href="analytics.php">
                    <i class="icon-chart"></i>
                    <span>Analytics</span>
                </a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>">
                <a href="settings.php">
                    <i class="icon-settings"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </nav>
    <div class="sidebar-footer">
        <a href="logout.php"><i class="icon-logout"></i> Logout</a>
    </div>
</div>
</body>
</html>