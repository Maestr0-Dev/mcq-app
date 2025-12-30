<?php
session_start();
include 'classes.php';
$name="";
$num="";
$email="";
$level="";
if($_SESSION['logged_in']==true){
    $name=$_SESSION['uname'];
    $num=$_SESSION['number'];
    $email=$_SESSION['email'];
    $level=$_SESSION['level'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name=$_POST['name'];
    $num=$_POST['num'];
    $email=$_POST['email'];
    $level=$_POST['level'];
    $data=[$name,$num,$email,$level,$_SESSION['id']];
    $db=new DB();
    $db->update_learner($data);
    $_SESSION['uname']=$name;
    $_SESSION['number']=$num;
    $_SESSION['email']=$email;
    $_SESSION['level']=$level;
    header("location:user_profile.php");
   
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modify your informations</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: white;
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
            padding: 40px;
            width: 100%;
            max-width: 500px;
            position: relative;
            overflow: hidden;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #333;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
            font-size: 16px;
        }

        .discard-link {
            display: inline-flex;
            align-items: center;
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 25px;
            padding: 10px 16px;
            border-radius: 10px;
            transition: all 0.3s ease;
            background: rgba(102, 126, 234, 0.1);
            border: 1px solid rgba(102, 126, 234, 0.2);
        }

        .discard-link:hover {
            background: rgba(102, 126, 234, 0.2);
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }

        .discard-link::before {
            content: '←';
            margin-right: 8px;
            font-size: 18px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fff;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
            transform: translateY(-2px);
        }

        select.form-control {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 16px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 45px;
            appearance: none;
        }

        select.form-control:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        }

        .save-btn {
            width: 100%;
            padding: 16px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            margin-top: 10px;
        }

        .save-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .save-btn:hover::before {
            left: 100%;
        }

        .save-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
        }

        .save-btn:active {
            transform: translateY(-1px);
        }

        /* Floating animation for form */
        .form-group {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(20px);
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .form-group:nth-child(3) { animation-delay: 0.3s; }
        .form-group:nth-child(4) { animation-delay: 0.4s; }

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
            
            .profile-container {
                padding: 30px 25px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }

        /* User icon styling */
        .user-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            font-weight: 300;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <div class="header">
            <!-- <div class="user-icon">👤</div> -->
            <h1>Update Profile</h1>
            <p>Modify your personal information</p>
        </div>
        
        <a href="user_profile.php" class="discard-link">Back to Profile</a>
        
        <form action="" method="post">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?=$name?>" required>
            </div>
            
            <div class="form-group">
                <label for="num">Phone Number</label>
                <input type="text" id="num" name="num" class="form-control" value="<?=$num?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="<?=$email?>" required>
            </div>
            
            <div class="form-group">
                <label for="level">Education Level</label>
                <select name="level" id="level" class="form-control" required>
                    <option value="O" <?=$level=='O' ? 'selected' : ''?>>O Level</option>
                    <option value="A" <?=$level=='A' ? 'selected' : ''?>>A Level</option>
                </select>
            </div>
            
            <button type="submit" class="save-btn">Save Changes</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const button = document.querySelector('.save-btn');
            
            form.addEventListener('submit', function(e) {
                button.style.background = 'linear-gradient(135deg, #22c55e, #16a34a)';
                button.innerHTML = '✓ Saving...';
            });

            // Add input focus effects
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.style.transform = 'scale(1.02)';
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>
<?php
}else{
    header("location:login.php");
}