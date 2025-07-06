<?php
// Include the DB class
require_once 'classes.php'; // Adjust path as needed

// Initialize database connection
$db = new DB();

// Handle AJAX requests for dynamic content
if (isset($_GET['action']) && $_GET['action'] === 'get_content') {
    $subject = $_GET['subject'] ?? '';
    $category = $_GET['category'] ?? '';
    
    try {
        // Use DB class method to get content
        $results = $db->getStudyContent($subject, $category);
        
        if ($results) {
            // Build content from database results
            $title = ucfirst($subject) . ' - All ' . ucfirst($category);
            $content = '<h3>' . $title . '</h3>';
            
            foreach ($results as $row) {
                $content .= '<div class="content-item mb-4">';
                
                // Display title if available
                if (!empty($row['title'])) {
                    $content .= '<h4>' . htmlspecialchars($row['title']) . '</h4>';
                }
                
                // Process content to handle images
                $processedContent = processContentForImages($row['content']);
                $content .= '<div class="content-body">' . $processedContent . '</div>';
                
                $content .= '</div>';
            }
            
            $response = [
                'title' => $title,
                'content' => $content
            ];
        } else {
            $response = [
                'title' => 'No Content Found',
                'content' => '<div class="alert alert-info">
                    <h4>No Content Available</h4>
                    <p>No content found for this subject and category. Please check back later.</p>
                </div>'
            ];
        }
        
    } catch(Exception $e) {
        error_log("Database query failed: " . $e->getMessage());
        $response = [
            'title' => 'Error',
            'content' => '<div class="alert alert-danger">
                <h4>Error Loading Content</h4>
                <p>Sorry, there was an error loading the content. Please try again later.</p>
            </div>'
        ];
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// Function to process content and convert image references to actual images
function processContentForImages($content) {
    // Define the path where images are stored (adjust this to your actual image directory)
    $imagePath = 'admin/diagrams/'; // Change this to your actual image directory
    
    // Pattern to match image filenames (common extensions)
    $imagePattern = '/\b[\w\-_]+\.(jpg|jpeg|png|gif|bmp|webp|svg)\b/i';
    
    // Replace image filenames with actual img tags
    $processedContent = preg_replace_callback($imagePattern, function($matches) use ($imagePath) {
        $filename = $matches[0];
        $fullPath = $imagePath . $filename;
        
        // Extract title from filename (remove extension and replace underscores/hyphens with spaces)
        $title = pathinfo($filename, PATHINFO_FILENAME);
        $title = str_replace(['_', '-'], ' ', $title);
        $title = ucwords($title);
        
        // Create image HTML with title
        return '<div class="image-container my-3">
                    <img src="' . htmlspecialchars($fullPath) . '" 
                         alt="' . htmlspecialchars($title) . '" 
                         title="' . htmlspecialchars($title) . '"
                         class="img-fluid rounded shadow-sm"
                         onclick="openImageModal(\'' . htmlspecialchars($fullPath) . '\', \'' . htmlspecialchars($title) . '\')"
                         style="cursor: pointer; max-width: 100%; height: auto;">
                    <div class="image-title text-center mt-2">
                        <small class="text-muted">' . htmlspecialchars($title) . '</small>
                    </div>
                </div>';
    }, $content);
    
    return $processedContent;
}

// Get all subjects using DB class
$subjects = $db->getSubjects();

// Define subject icons
$subjectIcons = [
    'biology' => 'fas fa-dna',
    'physics' => 'fas fa-atom',
    'chemistry' => 'fas fa-flask',
    'computer_science' => 'fas fa-laptop-code'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            width: 300px;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 0;
            transition: all 0.3s ease;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar.collapsed {
            width: 60px;
        }
        
        .sidebar-header {
            padding: 20px;
            background: rgba(255,255,255,0.1);
            border-bottom: 1px solid rgba(255,255,255,0.2);
            text-align: center;
        }
        
        .sidebar-header h4 {
            color: white;
            margin: 0;
            font-weight: 600;
            transition: opacity 0.3s ease;
        }
        
        .sidebar.collapsed .sidebar-header h4 {
            opacity: 0;
        }
        
        .toggle-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .toggle-btn:hover {
            background: rgba(255,255,255,0.3);
        }
        
        .nav-item {
            margin: 2px 0;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 0 10px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            cursor: pointer;
            justify-content: space-between;
        }
        
        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white !important;
        }
        
        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white !important;
        }
        
        .nav-link i {
            width: 20px;
            text-align: center;
        }
        
        .nav-link .icon-text {
            display: flex;
            align-items: center;
        }
        
        .nav-link .icon-text i {
            margin-right: 10px;
        }
        
        .nav-link .chevron {
            transition: transform 0.3s ease;
        }
        
        .nav-link.expanded .chevron {
            transform: rotate(90deg);
        }
        
        .sidebar.collapsed .nav-link span {
            opacity: 0;
            width: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed .nav-link {
            justify-content: center;
            margin: 0 5px;
            padding: 12px 10px;
        }
        
        .sidebar.collapsed .nav-link .chevron {
            display: none;
        }
        
        .sub-nav {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            background: rgba(255,255,255,0.05);
            margin: 0 10px;
            border-radius: 8px;
        }
        
        .sub-nav.expanded {
            max-height: 300px;
        }
        
        .sub-nav-link {
            color: rgba(255,255,255,0.7) !important;
            padding: 8px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9em;
        }
        
        .sub-nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white !important;
            padding-left: 25px;
        }
        
        .sub-nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white !important;
        }
        
        .sidebar.collapsed .sub-nav {
            display: none;
        }
        
        .main-content {
            margin-left: 300px;
            padding: 30px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
            background: #f8f9fa;
        }
        
        .sidebar.collapsed + .main-content {
            margin-left: 60px;
        }
        
        .content-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            min-height: 500px;
        }
        
        .welcome-message {
            text-align: center;
            color: #6c757d;
            margin-top: 100px;
        }
        
        .welcome-message img {
            max-width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        
        /* Image styling */
        .image-container {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .image-container:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }
        
        .image-container img:hover {
            transform: scale(1.02);
        }
        
        .image-title {
            font-weight: 500;
            color: #495057;
            margin-top: 10px;
            font-size: 0.9em;
        }
        
        /* Modal styling for image preview */
        .image-modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.8);
            backdrop-filter: blur(5px);
        }
        
        .image-modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
            text-align: center;
        }
        
        .image-modal-content img {
            max-width: 100%;
            max-height: 80vh;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        }
        
        .image-modal-title {
            color: white;
            margin-top: 15px;
            font-size: 1.2em;
            font-weight: 500;
        }
        
        .close-modal {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        
        .close-modal:hover {
            color: #667eea;
        }
        
        pre {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #667eea;
            border-radius: 4px;
            overflow-x: auto;
        }
        
        .loading {
            text-align: center;
            padding: 50px;
        }
        
        .spinner-border {
            color: #667eea;
        }
        
        .content-item {
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .content-item:last-child {
            border-bottom: none;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
            }
            .main-content {
                margin-left: 250px;
            }
            .sidebar.collapsed + .main-content {
                margin-left: 60px;
            }
            
            .image-modal-content {
                max-width: 95%;
                max-height: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <button class="toggle-btn" onclick="toggleSidebar()">
                <i class="fas fa-bars"></i>
            </button>
            <h4>Study</h4>
        </div>
        
        <nav class="nav flex-column mt-3">
            <?php foreach($subjects as $subject): 
                $icon = $subjectIcons[$subject] ?? 'fas fa-book';
                $displayName = ucwords(str_replace('_', ' ', $subject));
                $categories = $db->getCategories($subject);
            ?>
            <div class="nav-item">
                <a class="nav-link" onclick="toggleSubject('<?php echo $subject; ?>')">
                    <div class="icon-text">
                        <i class="<?php echo $icon; ?>"></i>
                        <span><?php echo $displayName; ?></span>
                    </div>
                    <i class="fas fa-chevron-right chevron"></i>
                </a>
                <div class="sub-nav" id="<?php echo $subject; ?>-sub">
                    <?php foreach($categories as $category): 
                        $categoryDisplay = 'All ' . ucfirst($category);
                    ?>
                    <a class="sub-nav-link" onclick="loadContent('<?php echo $subject; ?>', '<?php echo $category; ?>')"><?php echo $categoryDisplay; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </nav>
    </div>
    
    <div class="main-content">
        <div class="content-card">
            <div id="content-area">
                <div class="welcome-message">
                    <img src="Quiz-master-logo.png" alt="Quiz Master Logo" onerror="this.style.display='none';">
                    <h2>What do you want to learn?</h2>
                    <p>Select a subject from the sidebar to start learning!</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Image Modal -->
    <div id="imageModal" class="image-modal" onclick="closeImageModal()">
        <span class="close-modal" onclick="closeImageModal()">&times;</span>
        <div class="image-modal-content">
            <img id="modalImage" src="" alt="">
            <div id="modalTitle" class="image-modal-title"></div>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }
        
        function toggleSubject(subject) {
            const subNav = document.getElementById(subject + '-sub');
            const navLink = event.target.closest('.nav-link');
            
            // Close all other sub-navs
            document.querySelectorAll('.sub-nav').forEach(nav => {
                if (nav !== subNav) {
                    nav.classList.remove('expanded');
                }
            });
            
            // Remove expanded class from all nav-links
            document.querySelectorAll('.nav-link').forEach(link => {
                if (link !== navLink) {
                    link.classList.remove('expanded');
                }
            });
            
            // Toggle current sub-nav
            subNav.classList.toggle('expanded');
            navLink.classList.toggle('expanded');
        }
        
        function loadContent(subject, category) {
            // Remove active class from all sub-nav links
            document.querySelectorAll('.sub-nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Add active class to clicked link
            event.target.classList.add('active');
            
            // Show loading state
            document.getElementById('content-area').innerHTML = `
                <div class="loading">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Loading content...</p>
                </div>
            `;
            
            // Fetch content via AJAX
            fetch(`?action=get_content&subject=${subject}&category=${category}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('content-area').innerHTML = `
                        <h1 class="mb-4">${data.title}</h1>
                        ${data.content}
                    `;
                })
                .catch(error => {
                    document.getElementById('content-area').innerHTML = `
                        <div class="alert alert-danger">
                            <h4>Error Loading Content</h4>
                            <p>Sorry, there was an error loading the content. Please try again.</p>
                        </div>
                    `;
                });
        }
        
        // Image modal functions
        function openImageModal(imageSrc, title) {
            const modal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');
            const modalTitle = document.getElementById('modalTitle');
            
            modalImage.src = imageSrc;
            modalImage.alt = title;
            modalTitle.textContent = title;
            modal.style.display = 'block';
            
            // Prevent body scrolling when modal is open
            document.body.style.overflow = 'hidden';
        }
        
        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.style.display = 'none';
            
            // Re-enable body scrolling
            document.body.style.overflow = 'auto';
        }
        
        // Close modal when clicking outside the image
        document.getElementById('imageModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeImageModal();
            }
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
        
        // Auto-collapse sidebar on mobile
        function checkScreenSize() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
            }
        }
        
        window.addEventListener('resize', checkScreenSize);
        window.addEventListener('load', checkScreenSize);
    </script>
</body>
</html>