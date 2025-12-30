<?php
session_start();
include 'classes.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $creator_id = $_SESSION['id'];
    $pass=$_POST['pass'];
    if(!empty($_FILES['profile_picture']['name'])){
   
        $fileName= $_FILES["profile_picture"]["name"];
        $fileSize= $_FILES["profile_picture"]["size"];
        $tmpName= $_FILES["profile_picture"]["tmp_name"];
        $validImageExtension=['jpeg','jpg','png'];
        $imageExtension=explode('.',$fileName);
        $imageExtension=strtolower(end($imageExtension));
        if(!in_array($imageExtension, $validImageExtension)){
            echo"
            <p>Invalid image type</p>
            ";
        }else if($fileSize>500000){
            echo"
            <p>Image too big</p>
            ";
        }else{
            $newImageName=uniqid();
            $newImageName .= '.' . $imageExtension;
   
            move_uploaded_file($tmpName, 'comm_profil_imgs/'. $newImageName);
   
    }
}
if($_SESSION['level']=='A' || $_SESSION['level']=='O'){
    $data = [$creator_id,0,$name, $description,$pass, $newImageName];
    $db = new DB();
    $db->newCommunity($data);
    // header("location:communities.php");
    echo"
    <p style='color:green;'> Study group created </p>
    ";
}   else{
    $data = [0,$creator_id,$name, $description,$pass, $newImageName];
    $db = new DB();
    $db->newCommunity($data);
    header("location:communities.php");
}
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create community</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .community-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.2);
            padding: 40px;
            width: 100%;
            max-width: 550px;
            position: relative;
            overflow: hidden;
        }

        .community-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #4f46e5, #7c3aed, #ec4899);
            background-size: 200% 100%;
            animation: shimmer 3s ease-in-out infinite;
        }

        @keyframes shimmer {
            0%, 100% { background-position: -200% 0; }
            50% { background-position: 200% 0; }
        }

        .header {
            text-align: center;
            margin-bottom: 35px;
        }

        .community-icon {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 20px;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            box-shadow: 0 15px 30px rgba(79, 70, 229, 0.3);
            transform: perspective(1000px) rotateX(15deg);
        }

        .header h1 {
            color: #333;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .header p {
            color: #666;
            font-size: 16px;
            font-weight: 500;
        }

        .discard-link {
            display: inline-flex;
            align-items: center;
            color: #4f46e5;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 25px;
            padding: 12px 18px;
            border-radius: 12px;
            transition: all 0.3s ease;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(124, 58, 237, 0.1));
            border: 2px solid rgba(79, 70, 229, 0.2);
        }

        .discard-link:hover {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.2), rgba(124, 58, 237, 0.2));
            transform: translateX(-8px);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
        }

        .discard-link::before {
            content: '←';
            margin-right: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .success-message, .error-message {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
            animation: slideIn 0.5s ease;
        }

        .success-message {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .error-message {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            box-shadow: 0 8px 25px rgba(239, 68, 68, 0.3);
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-weight: 600;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .form-control {
            width: 100%;
            padding: 18px 22px;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fff;
            font-family: inherit;
            font-weight: 500;
        }

        .form-control::placeholder {
            color: #9ca3af;
            font-weight: 400;
        }

        .form-control:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
            transform: translateY(-3px);
            background: linear-gradient(135deg, #fff, #f8fafc);
        }

        .file-upload {
            position: relative;
            display: block;
            width: 100%;
        }

        .file-upload input[type="file"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .file-upload-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
            border: 3px dashed #4f46e5;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.05), rgba(124, 58, 237, 0.05));
            color: #4f46e5;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s ease;
            min-height: 80px;
            flex-direction: column;
            text-align: center;
        }

        .file-upload-label:hover {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1), rgba(124, 58, 237, 0.1));
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(79, 70, 229, 0.2);
            border-color: #7c3aed;
        }

        .file-upload-label::before {
            content: '📷';
            font-size: 28px;
            margin-bottom: 8px;
        }

        .preview {
            margin-top: 20px;
            text-align: center;
        }

        .preview img {
            max-width: 250px;
            max-height: 250px;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            display: none;
            transition: all 0.4s ease;
            border: 3px solid #fff;
        }

        .preview img:hover {
            transform: scale(1.05) rotate(1deg);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        }

        .create-btn {
            width: 100%;
            padding: 18px 35px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 17px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.4s ease;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            position: relative;
            overflow: hidden;
            margin-top: 15px;
            box-shadow: 0 15px 40px rgba(79, 70, 229, 0.3);
        }

        .create-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s;
        }

        .create-btn:hover::before {
            left: 100%;
        }

        .create-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 25px 50px rgba(79, 70, 229, 0.5);
            background: linear-gradient(135deg, #3730a3 0%, #6d28d9 50%, #db2777 100%);
        }

        .create-btn:active {
            transform: translateY(-2px);
        }

        /* Enhanced animations */
        .form-group {
            animation: fadeInUp 0.7s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }
        .form-group:nth-child(5) { animation-delay: 0.5s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .community-container {
                padding: 30px 25px;
            }
            
            .header h1 {
                font-size: 26px;
            }

            .community-icon {
                width: 70px;
                height: 70px;
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="community-container">
        <div class="header">
            <div class="community-icon">👥</div>
            <h1>Create Community</h1>
            <p>Start your own study group and connect with learners</p>
        </div>
        
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_SESSION['level']=='A' || $_SESSION['level']=='O')): ?>
            <div class="success-message">
                ✅ Study group created successfully!
            </div>
        <?php endif; ?>
        
        <a href="communities.php" class="discard-link">Back to Communities</a>
        
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="name">Community Name</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Enter your community name" required>
            </div>
            
            <div class="form-group">
                <label for="pass">Community Password</label>
                <input type="password" id="pass" name="pass" class="form-control" placeholder="Set a secure password" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" id="description" name="description" class="form-control" placeholder="Describe your community's purpose" required>
            </div>
            
            <div class="form-group">
                <label>Community Picture</label>
                <div class="file-upload">
                    <input type="file" id="input" name="profile_picture" accept="image/jpeg,image/jpg,image/png">
                    <label for="input" class="file-upload-label">
                        <span>Choose a picture for your community</span>
                        <small style="margin-top: 5px; opacity: 0.7;">JPEG, JPG, PNG (max 500KB)</small>
                    </label>
                </div>
                <div class="preview">
                    <img id="preview" src="" alt="Community Preview">
                </div>
            </div>
            
            <button type="submit" class="create-btn">Create Community</button>
        </form>
    </div>

    <script>
        // Enhanced image preview functionality
        const imageInput = document.getElementById('input');
        const imagePreview = document.getElementById('preview');
        
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                // Validate file type
                const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, JPG, PNG)');
                    this.value = '';
                    return;
                }
                
                // Validate file size (500KB)
                if (file.size > 500000) {
                    alert('Image size must be less than 500KB');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                    
                    // Update file upload label
                    const label = document.querySelector('.file-upload-label');
                    label.innerHTML = '<span>✅ Image selected successfully!</span>';
                    label.style.borderColor = '#10b981';
                    label.style.color = '#10b981';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
                // Reset label
                const label = document.querySelector('.file-upload-label');
                label.innerHTML = '<span>Choose a picture for your community</span><small style="margin-top: 5px; opacity: 0.7;">JPEG, JPG, PNG (max 500KB)</small>';
                label.style.borderColor = '#4f46e5';
                label.style.color = '#4f46e5';
            }
        });

        // Enhanced form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const button = document.querySelector('.create-btn');
            
            form.addEventListener('submit', function(e) {
                button.innerHTML = '🚀 Creating Community...';
                button.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                
                // Simulate processing time for better UX
                setTimeout(() => {
                    if (!form.querySelector('.success-message')) {
                        button.innerHTML = 'Create Community';
                        button.style.background = 'linear-gradient(135deg, #4f46e5 0%, #7c3aed 50%, #ec4899 100%)';
                    }
                }, 2000);
            });

            // Enhanced input interactions
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
                
                input.addEventListener('input', function() {
                    if (this.value.length > 0) {
                        this.style.borderColor = '#10b981';
                        this.style.boxShadow = '0 0 0 4px rgba(16, 185, 129, 0.1)';
                    } else {
                        this.style.borderColor = '#e5e7eb';
                        this.style.boxShadow = 'none';
                    }
                });
            });

            // Floating animation for container
            const container = document.querySelector('.community-container');
            let mouseX = 0;
            let mouseY = 0;

            document.addEventListener('mousemove', function(e) {
                mouseX = (e.clientX - window.innerWidth / 2) * 0.01;
                mouseY = (e.clientY - window.innerHeight / 2) * 0.01;
                
                container.style.transform = `perspective(1000px) rotateX(${mouseY}deg) rotateY(${mouseX}deg)`;
            });

            // Reset transform when mouse leaves
            document.addEventListener('mouseleave', function() {
                container.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg)';
            });
        });
    </script>
</body>
</html>