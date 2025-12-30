<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        .admin-navbar {
            width: 100%;
            background-color: #2c3e50;
            overflow: hidden;
            border-bottom: 2px solid #3498db;
        }
        .admin-navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: flex-start;
        }
        .admin-navbar li a {
            display: block;
            color: white;
            text-align: center;
            padding: 10px 16px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background-color 0.3s, color 0.3s;
        }
        .admin-navbar li a:hover {
            background-color: #3498db;
            color: #fff;
        }
        .admin-navbar li.active a {
            background-color: #3498db;
            color: white;
        }
    </style>
</head>
<body>
<div class="admin-navbar">
    <ul>
        <?php if (isset($_SESSION['admin_level']) && $_SESSION['admin_level'] >= 2): ?>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>admin/dashboard.php">Dashboard</a>
            </li>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'Verifications.php') ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>admin/Verifications.php">Verifications</a>
            </li>
        <?php endif; ?>

        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'CMS.php') ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>admin/CMS.php">CMS</a>
        </li>
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'study_page_management.php') ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>admin/study_page_management.php">Study Page</a>
        </li>
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'ai_agent.php') ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>admin/ai_agent.php">AI Agent</a>
        </li>
        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage_news.php') ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>admin/manage_news.php">Manage News</a>
        </li>

        <?php if (isset($_SESSION['admin_level']) && $_SESSION['admin_level'] == 3): ?>
            <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'manage_admins.php') ? 'active' : ''; ?>">
                <a href="<?php echo BASE_URL; ?>admin/manage_admins.php">Manage Admins</a>
            </li>
        <?php endif; ?>

        <li class="<?php echo (basename($_SERVER['PHP_SELF']) == 'profile.php') ? 'active' : ''; ?>">
            <a href="<?php echo BASE_URL; ?>admin/profile.php">Profile</a>
        </li>
    </ul>
</div>
</body>
</html>
