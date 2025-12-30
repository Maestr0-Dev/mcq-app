<?php
session_start();

      if(isset( $_SESSION['id'])){


include 'classes.php';
//display all existing communities with a button to join
$db = new DB();
$communities = $db->getExistingCommunities($_SESSION['id']);
$db3 = new DB();
$MyCommunities = $db3->MyCommunities($_SESSION['id']);

//display communities where user with id is a member
// $db2 = new DB();
// $CheckCommunities = $db2->CheckCommunities($_SESSION['id']);

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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.6;
            height: 100vh;
            /* overflow: hidden; */
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .header h1 {
            font-size: 28px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
            margin: 0;
        }
        
        .create-btn {
            background: linear-gradient(135deg, #4CAF50, #45a049);
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
        }
        
        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }
        
        .container {
            display: grid;
            grid-template-columns: 380px 1fr;
            height: calc(100vh - 80px);
            gap: 0;
        }
        
        .sidebar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-right: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            flex-direction: column;
        }
        
        .toggle-container {
            padding: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .toggle-buttons {
            display: flex;
            background: #f0f2f5;
            border-radius: 25px;
            padding: 4px;
            position: relative;
        }
        
        .toggle-btn {
            flex: 1;
            padding: 12px 20px;
            text-align: center;
            background: none;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            position: relative;
            z-index: 2;
        }
        
        .toggle-btn.active {
            color: white;
        }
        
        .toggle-slider {
            position: absolute;
            top: 4px;
            left: 4px;
            width: calc(50% - 4px);
            height: calc(100% - 8px);
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            transition: transform 0.3s ease;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        
        .toggle-slider.right {
            transform: translateX(100%);
        }
        
        .communities-container {
            flex: 1;
            overflow-y: auto;
            padding: 0 20px 20px;
        }
        
        .communities-container::-webkit-scrollbar {
            width: 6px;
        }
        
        .communities-container::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .communities-container::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.3);
            border-radius: 3px;
        }
        
        .communities-container::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.5);
        }
        
        .communities-section {
            display: none;
        }
        
        .communities-section.active {
            display: block;
        }
        
        .community {
            display: flex;
            align-items: center;
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .community:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .community img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 3px solid rgba(255, 255, 255, 0.8);
        }
        
        .community-info {
            flex-grow: 1;
        }
        
        .community h2 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #2c3e50;
            font-weight: 600;
        }
        
        .community p {
            font-size: 13px;
            color: #7f8c8d;
            margin-bottom: 0;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        
        .join-btn {
            background: linear-gradient(135deg, #3498db, #2980b9);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 12px;
        }
        
        .join-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .discussion-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .community-header {
            display: flex;
            align-items: center;
            padding: 25px 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.8);
        }
        
        .community-header img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            margin-right: 20px;
            border: 3px solid rgba(102, 126, 234, 0.2);
        }
        
        .community-header h1 {
            font-size: 24px;
            margin: 0 0 5px 0;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .community-header p {
            color: #7f8c8d;
            margin: 0;
        }
        
        .posts-header {
            padding: 20px 30px 0;
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
        }
        
        .posts-container {
            flex: 1;
            overflow-y: auto;
            padding: 20px 30px;
            min-height: 0;
        }
        
        .posts-container::-webkit-scrollbar {
            width: 8px;
        }
        
        .posts-container::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }
        
        .posts-container::-webkit-scrollbar-thumb {
            background: rgba(102, 126, 234, 0.3);
            border-radius: 4px;
        }
        
        .posts-container::-webkit-scrollbar-thumb:hover {
            background: rgba(102, 126, 234, 0.5);
        }
        
        .quiz-item {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            border: 1px solid rgba(102, 126, 234, 0.2);
            border-left: 4px solid #667eea;
            transition: all 0.3s ease;
        }
        
        .quiz-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            border-left-color: #764ba2;
        }
        
        .quiz-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .quiz-author {
            display: flex;
            align-items: center;
        }
        
        .quiz-author img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 12px;
            border: 2px solid rgba(255, 255, 255, 0.8);
        }
        
        .quiz-title {
            font-size: 16px;
            font-weight: 600;
            margin: 10px 0;
            color: #2c3e50;
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
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            font-size: 12px;
        }
        
        .take-quiz-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(231, 76, 60, 0.3);
        }
        
        .active-community {
            background: rgba(102, 126, 234, 0.1) !important;
            border: 2px solid #667eea !important;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
        }
        
        .post-form {
            padding: 20px 30px;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        
        textarea {
            width: 100%;
            padding: 15px;
            border-radius: 12px;
            border: 2px solid rgba(102, 126, 234, 0.2);
            margin-bottom: 15px;
            resize: none;
            font-family: inherit;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
            height: 80px;
        }
        
        textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .post-btn {
            background: linear-gradient(135deg, #2ecc71, #27ae60);
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);
        }
        
        .post-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
        }
        
        .post-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            }
        }
    </style>
</head>
<body>
     
    <div class="header">
        <h1>Community Platform</h1>
        <a href="create_com.php" class="create-btn">Create a community</a>
    </div>

    <div class="container">
        <div class="sidebar">
            <div class="toggle-container">
                <div class="toggle-buttons">
                    <div class="toggle-slider" id="toggleSlider"></div>
                    <button class="toggle-btn active" id="myCommunitiesBtn">My Communities</button>
                    <button class="toggle-btn" id="allCommunitiesBtn">All Communities</button>
                </div>
            </div>
            
            <div class="communities-container">
                <div class="communities-section active" id="myCommunitiesSection">
                    <?php
                        foreach($MyCommunities as $myCom){
                            $path = "comm_profil_imgs/" . $myCom['img'];
                    ?>
                    <div class="community" data-com-id="<?=$myCom['com_id']?>" data-com-name="<?=$myCom['com_name']?>" data-com-desc="<?=$myCom['describtion']?>" data-com-img="<?=$path?>">
                        <img src="<?=$path?>" alt="community profile">
                        <div class="community-info">
                            <h2><?=$myCom['com_name']?></h2>
                            <p><?=$myCom['describtion'] ?></p>
                        </div>
                    </div>
                    <?php
                        }
                    ?>
                </div>
                
                <div class="communities-section" id="allCommunitiesSection">
                    <?php
                            foreach ($communities as $community) {
                                $path = "comm_profil_imgs/" . $community['img'];
                    ?>
                    <div class="community" data-com-id="<?=$community['com_id']?>">
                        <img src="<?=$path?>" alt="community profile">
                        <div class="community-info">
                            <h2><?=$community['com_name']?></h2>
                            <p><?=$community['describtion'] ?></p>
                        </div>
                        <button class="join-btn" onclick="join('<?=$community['com_id']?>','<?=$_SESSION['id']?>')">Join</button>
                    </div>
                    <?php
                            }
                    ?>
                </div>
            </div>
        </div>

        <div class="discussion-section">
            <div class="community-header">
                <img src="comm_profil_imgs/default.jpg" alt="Community Profile" id="discussion-com-img">
                <div>
                    <h1 id="discussion-com-name">Select a Community</h1>
                    <p id="discussion-com-desc">Join or select a community to see the discussion.</p>
                </div>
            </div>

            <h2 class="posts-header">Recent Posts</h2>
            <div class="posts-container" id="posts-container">
                <!-- Posts will be loaded here -->
            </div>

            <div class="post-form">
                <textarea id="post-content" placeholder="What's on your mind? Share with the community..."></textarea>
                <button id="post-btn" class="post-btn">Share Post</button>
            </div>
        </div>
    </div>

    <script src="jquery-3.1.0.min.js"></script>
    <script>
        let activeCommunityId = null;

        // Toggle functionality
        const myCommunitiesBtn = document.getElementById('myCommunitiesBtn');
        const allCommunitiesBtn = document.getElementById('allCommunitiesBtn');
        const myCommunitiesSection = document.getElementById('myCommunitiesSection');
        const allCommunitiesSection = document.getElementById('allCommunitiesSection');
        const toggleSlider = document.getElementById('toggleSlider');

        myCommunitiesBtn.addEventListener('click', function() {
            switchToMyCommunitiesView();
        });

        allCommunitiesBtn.addEventListener('click', function() {
            switchToAllCommunitiesView();
        });

        function switchToMyCommunitiesView() {
            myCommunitiesBtn.classList.add('active');
            allCommunitiesBtn.classList.remove('active');
            myCommunitiesSection.classList.add('active');
            allCommunitiesSection.classList.remove('active');
            toggleSlider.classList.remove('right');
        }

        function switchToAllCommunitiesView() {
            allCommunitiesBtn.classList.add('active');
            myCommunitiesBtn.classList.remove('active');
            allCommunitiesSection.classList.add('active');
            myCommunitiesSection.classList.remove('active');
            toggleSlider.classList.add('right');
        }

        // Community selection functionality
        document.addEventListener('click', function(e) {
            const community = e.target.closest('.community');
            if (community && !e.target.classList.contains('join-btn')) {
                document.querySelectorAll('.community').forEach(c => c.classList.remove('active-community'));
                community.classList.add('active-community');
                
                activeCommunityId = community.dataset.comId;
                const communityName = community.dataset.comName;
                const communityDesc = community.dataset.comDesc;
                const communityImg = community.dataset.comImg;

                if (communityName && communityDesc && communityImg) {
                    document.getElementById('discussion-com-name').textContent = communityName;
                    document.getElementById('discussion-com-desc').textContent = communityDesc;
                    document.getElementById('discussion-com-img').src = communityImg;
                    
                    fetchPosts(activeCommunityId);
                }
            }
        });

        function fetchPosts(comId) {
            fetch(`get_community_posts.php?com_id=${comId}`)
                .then(response => response.json())
                .then(posts => {
                    const postsContainer = document.getElementById('posts-container');
                    postsContainer.innerHTML = '';
                    if (posts.error) {
                        postsContainer.innerHTML = `<div class="quiz-item"><p>${posts.error}</p></div>`;
                        return;
                    }
                    posts.forEach(post => {
                        const postElement = document.createElement('div');
                        postElement.classList.add('quiz-item');
                        
                        let content = '';
                        if (post.post_type === 'quiz') {
                            content = `
                                <div class="quiz-title">Quiz: ${post.content}</div>
                                <button class="take-quiz-btn" onclick="takeQuiz(${post.quiz_id})">Take Quiz</button>
                            `;
                        } else {
                            content = `<p>${post.content}</p>`;
                        }

                        postElement.innerHTML = `
                            <div class="quiz-header">
                                <div class="quiz-author">
                                    <div>
                                        <div style="font-weight: 600;">${post.stud_name}</div>
                                        <small style="color: #7f8c8d;">Posted on ${new Date(post.created_at).toLocaleString()}</small>
                                    </div>
                                </div>
                            </div>
                            ${content}
                        `;
                        postsContainer.appendChild(postElement);
                    });
                })
                .catch(error => {
                    console.error('Error fetching posts:', error);
                    document.getElementById('posts-container').innerHTML = '<div class="quiz-item"><p>Error loading posts. Please try again.</p></div>';
                });
        }

        document.getElementById('post-btn').addEventListener('click', function() {
            const content = document.getElementById('post-content').value;
            const postBtn = this;
            
            if (!content.trim() || !activeCommunityId) {
                alert('Please select a community and write something.');
                return;
            }

            // Disable button during submission
            postBtn.disabled = true;
            postBtn.textContent = 'Posting...';

            fetch('create_post.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    com_id: activeCommunityId,
                    content: content
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    document.getElementById('post-content').value = '';
                    fetchPosts(activeCommunityId);
                } else {
                    alert('Error: ' + result.error);
                }
            })
            .catch(error => {
                console.error('Error creating post:', error);
                alert('Error creating post. Please try again.');
            })
            .finally(() => {
                // Re-enable button
                postBtn.disabled = false;
                postBtn.textContent = 'Share Post';
            });
        });

        // Auto-resize textarea
        const textarea = document.getElementById('post-content');
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
    </script>
</body>
</html>
<?php
}
else{
    header("location:login.php");
}
?>