<?php
session_start();

try {
    // Connect to the 'mcqs' database
    $pdo = new PDO("mysql:host=localhost;dbname=interactives_mcqs", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Connection failed: " . $e->getMessage());
}

// Handle Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $subject = $_POST['subject'];

    // File Uploads
    $id_card_path = 'uploads/' . basename($_FILES['id_card']['name']);
    $cert_path = 'uploads/' . basename($_FILES['cert']['name']);
    
    move_uploaded_file($_FILES['id_card']['tmp_name'], $id_card_path);
    move_uploaded_file($_FILES['cert']['tmp_name'], $cert_path);

    // Insert into DB without showing credentials
    $sql = "INSERT INTO teachers (name, phone, email, password, subject, id_card_img, teaaching_cert_img, approved) 
            VALUES (:name, :phone, :email, :password, :subject, :id_card, :cert, 'no')";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':name' => $name,
        ':phone' => $phone,
        ':email' => $email,
        ':password' => $password,
        ':subject' => $subject,
        ':id_card' => $id_card_path,
        ':cert' => $cert_path
    ]);

    // Redirect to prevent resubmission
    header("Location: index.php?success=1");
    exit;
}

// Check if user is admin (for approve/delete)
$is_admin = isset($_SESSION['admin']) && $_SESSION['admin'] == true;

// Approve or Delete a Teacher (Admin Only)
if ($is_admin) {
    if (isset($_GET['approve'])) {
        $id = $_GET['approve'];
        $pdo->prepare("UPDATE teachers SET approved = 'yes' WHERE teacher_id = ?")->execute([$id]);
        header("Location: index.php");
        exit;
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['delete'];
        $pdo->prepare("UPDATE teachers SET deleted = 1 WHERE teacher_id = ?")->execute([$id]);
        header("Location: index.php");
        exit;
    }
}

// Fetch all teachers (Only show name & subject)
$stmt = $pdo->query("SELECT teacher_id, name, subject, approved FROM teachers WHERE deleted = 0");
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <form action="" method="POST" enctype="multipart/form-data">
        <h2>Register as a Teacher</h2>
        <?php if (isset($_GET['success'])) : ?>
            <p class="success">✅ Registration successful! Please wait for approval.</p>
        <?php endif; ?>
        
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="subject" placeholder="Subject" required>
        <label>ID Card:</label><input type="file" name="id_card" required>
        <label>Teaching Certificate:</label><input type="file" name="cert" required>
        <button type="submit">Register</button>
        <p id="log">Already a verified teacher? <a href="login-teach.php">Login!</a></p>
    </form>

  

        
    

</body>
</html>
