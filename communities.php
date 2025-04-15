<?php
session_start();
include 'classes.php';
//display all existing communities with a button to join
$db = new DB();
$communities = $db->getExistingCommunities();
//display communities where user with id is a member
$db2 = new DB();
$CheckCommunities = $db2->CheckCommunities($_SESSION['id']);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="script.js"></script>
    <title>Community</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid #e1e4e8;
            margin-bottom: 30px;
        }
        
        .create-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: background-color 0.3s;
        }
        
        .create-btn:hover {
            background-color: #45a049;
        }
        
        .container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
        }
        
        .communities-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        
        h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e1e4e8;
        }
        
        .community {
            display: flex;
            align-items: center;
            padding: 15px;
            border-bottom: 1px solid #e1e4e8;
            transition: background-color 0.2s;
        }
        
        .community:hover {
            background-color: #f0f2f5;
        }
        
        .community img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
        }
        
        .community-info {
            flex-grow: 1;
        }
        
        .community h2 {
            font-size: 18px;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .community p {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 10px;
        }
        
        .join-btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .join-btn:hover {
            background-color: #2980b9;
        }
        
        .discussion-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        
        .community-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e1e4e8;
        }
        
        .community-header img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 15px;
        }
        
        .quiz-item {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #3498db;
        }
        
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .quiz-author {
            display: flex;
            align-items: center;
        }
        
        .quiz-author img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .quiz-title {
            font-size: 18px;
            font-weight: 600;
            margin: 10px 0;
        }
        
        .quiz-meta {
            display: flex;
            align-items: center;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .quiz-meta span {
            margin-right: 15px;
        }
        
        .take-quiz-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .take-quiz-btn:hover {
            background-color: #c0392b;
        }
        
        .active-community {
            border-left: 4px solid #3498db;
            background-color: #f0f8ff;
        }
    </style>
</head>
<body>
     
    <div class="header">
        <h1>Community Platform</h1>
        <a href="create_com.php" class="create-btn">Create a community</a>
    </div>

    <div class="container">
        <div class="communities-container">
           

            <div class="communities-section">
                <h1>My Communities</h1>
                <?php
                foreach($CheckCommunities as $Check){
                    $id=$Check['comm_id'];
                    $db3 = new DB();
                    $MyCommunities = $db3->MyCommunities($id);

                    foreach($MyCommunities as $myCom){
                        $path = "comm_profil_imgs/" . $myCom['img'];
                ?>
                <div class="community <?php echo ($myCom['com_id'] == 1) ? 'active-community' : ''; ?>">
                    <img src="<?=$path?>" alt="community profile">
                    <div class="community-info">
                        <h2><?=$myCom['com_name']?></h2>
                        <p><?=$myCom['describtion'] ?></p>
                    </div>
                </div>
                <?php
                    }
                }
                ?>
            </div>
            <div class="communities-section">
                <h1>All Communities</h1>
                <?php
                foreach($CheckCommunities as $Check){
                    if($_SESSION['id'] != $Check['member_id'] ){
                        foreach ($communities as $community) {
                            $path = "comm_profil_imgs/" . $community['img'];
                            $_SESSION['com_id']=$community['com_id'];
                ?>
                <div class="community">
                    <img src="<?=$path?>" alt="community profile">
                    <div class="community-info">
                        <h2><?=$community['com_name']?></h2>
                        <p><?=$community['describtion'] ?></p>
                    </div>
                    <button class="join-btn" onclick="join('<?=$community['com_id']?>','<?=$_SESSION['id']?>')">Join</button>
                </div>
                <?php
                            break;
                        }
                    }
                }
                ?>
            </div>
        </div>

        <div class="discussion-section">
            <div class="community-header">
                <img src="comm_profil_imgs\67d8f68bac4b5.jpg" alt="Community Profile">
                <div>
                    <h1>Quality-Learner's</h1>
                    <p>Only for students of QIS. Success is our portion</p>
                </div>
            </div>

            <h2>Recent Posts</h2>
            
            <!-- Quiz Placeholder 1 -->
            <div class="quiz-item">
                <div class="quiz-header">
                    <div class="quiz-author">
                        <div>
                            <div>Igor Kaze</div>
                            <small>Posted 2 days ago</small>
                        </div>
                    </div>
                </div>
                <div class="quiz-title">JavaScript Fundamentals Quiz</div>
                <div class="quiz-meta">
                    <span>Duration: 15 minutes</span>
                    <span>10 questions</span>
                </div>
                <button class="take-quiz-btn">Take Quiz</button>
            </div>

            <!-- Quiz Placeholder 2 -->
            <div class="quiz-item">
                <div class="quiz-header">
                    <div class="quiz-author">
                        <div>
                            <div>Igor Kaze</div>
                            <small>Posted 5 days ago</small>
                        </div>
                    </div>
                </div>
                <div class="quiz-title">Python Data Structures Challenge</div>
                <div class="quiz-meta">
                    <span>Duration: 20 minutes</span>
                    <span>12 questions</span>
                </div>
                <button class="take-quiz-btn">Take Quiz</button>
            </div>

            <!-- Quiz Placeholder 3 -->
            <div class="quiz-item">
                <div class="quiz-header">
                    <div class="quiz-author">
                        <div>
                            <div>Igor Kaze</div>
                            <small>Posted 1 week ago</small>
                        </div>
                    </div>
                </div>
                <div class="quiz-title">SQL Query Masters Quiz</div>
                <div class="quiz-meta">
                    <span>Duration: 30 minutes</span>
                    <span>15 questions</span>
                </div>
                <button class="take-quiz-btn">Take Quiz</button>
            </div>

            <!-- Quiz Placeholder 4 -->
            <div class="quiz-item">
                <div class="quiz-header">
                    <div class="quiz-author">
                        <div>
                            <div>Igor Kaze</div>
                            <small>Posted 2 weeks ago</small>
                        </div>
                    </div>
                </div>
                <div class="quiz-title">Web Development Basics</div>
                <div class="quiz-meta">
                    <span>Duration: 25 minutes</span>
                    <span>20 questions</span>
                </div>
                <button class="take-quiz-btn">Take Quiz</button>
            </div>
        </div>
    </div>

    <script src="jquery-3.1.0.min.js"></script>
    <script>
        // JavaScript to handle clicking on a community to display its discussions
        document.querySelectorAll('.community').forEach(community => {
            community.addEventListener('click', function() {
                // Remove active class from all communities
                document.querySelectorAll('.community').forEach(c => {
                    c.classList.remove('active-community');
                });
                
                // Add active class to clicked community
                this.classList.add('active-community');
                
                // In a real application, you would load the community's discussions here
                // For now, we're just displaying the placeholder
            });
        });
    </script>
</body>
</html>