<?php
session_start();
include 'admin_class.php';
include '../classes.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("location: login.php");
    exit();
}

$db = new admindb();
$message = "";

// Handling form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_news'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $expiry_date = $_POST['expiry_date'];
        if ($db->addNews($title, $content, $expiry_date)) {
            $message = "News added successfully.";
        } else {
            $message = "Error adding news.";
        }
    } elseif (isset($_POST['update_news'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $expiry_date = $_POST['expiry_date'];
        if ($db->updateNews($id, $title, $content, $expiry_date)) {
            $message = "News updated successfully.";
        } else {
            $message = "Error updating news.";
        }
    } elseif (isset($_POST['delete_news'])) {
        $id = $_POST['id'];
        if ($db->deleteNews($id)) {
            $message = "News deleted successfully.";
        } else {
            $message = "Error deleting news.";
        }
    }
}

$news_items = $db->getAllNews();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage News</title>
    <link type="text/css" rel="stylesheet" href="CMS-style.css">
</head>
<body>
    <?php include 'admin_nav.php'; ?>
    <div class="container">
        <h1>Manage News</h1>
        <?php if ($message): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>

        <h2>Add News</h2>
        <form action="" method="post">
            <input type="text" name="title" required placeholder="Title">
            <textarea name="content" required placeholder="Content"></textarea>
            <input type="date" name="expiry_date" placeholder="Expiry Date">
            <button type="submit" name="add_news">Add News</button>
        </form>

        <h2>Existing News</h2>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Expiry Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($news_items as $item): ?>
                    <tr>
                        <form action="" method="post">
                            <td><input type="text" name="title" value="<?php echo $item['title']; ?>"></td>
                            <td><textarea name="content"><?php echo $item['content']; ?></textarea></td>
                            <td><input type="date" name="expiry_date" value="<?php echo $item['expiry_date']; ?>"></td>
                            <td>
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <button type="submit" name="update_news">Update</button>
                                <button type="submit" name="delete_news" onclick="return confirm('Are you sure?')">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
