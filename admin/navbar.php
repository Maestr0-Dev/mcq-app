<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        .navbar {
            width: 100%;
            background-color: #333;
            overflow: hidden;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .navbar li a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }
        .navbar li a:hover {
            background-color: #111;
        }
        .navbar li.active a {
            background-color: #4CAF50;
        }
    </style>
</head>
<body>
<div class="navbar">
    <ul>
        <li class="<?php echo (!isset($_GET['page']) || $_GET['page'] == 'home') ? 'active' : ''; ?>">
            <a href="dashboard.php?page=home">Dashboard</a>
        </li>
        <li class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'verifications') ? 'active' : ''; ?>">
            <a href="dashboard.php?page=verifications">Verifications</a>
        </li>
        <li class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'trusted_teachers') ? 'active' : ''; ?>">
            <a href="dashboard.php?page=trusted_teachers">Trusted Teachers</a>
        </li>
        <li class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'study_page_management') ? 'active' : ''; ?>">
            <a href="dashboard.php?page=study_page_management">Study Page</a>
        </li>
        <li class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'ai_agent') ? 'active' : ''; ?>">
            <a href="dashboard.php?page=ai_agent">AI Agent</a>
        </li>
         <li class="<?php echo (isset($_GET['page']) && $_GET['page'] == 'cms') ? 'active' : ''; ?>">
            <a href="dashboard.php?page=cms">CMS</a>
        </li>
    </ul>
</div>
</body>
</html>
