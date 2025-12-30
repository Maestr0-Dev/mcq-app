<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Content Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .content-wrapper {
            margin-top: 20px;
        }
        .form-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
        }
        .btn-gradient:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            color: white;
        }
        .alert-custom {
            border-radius: 8px;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .table {
            border-radius: 8px;
            overflow: hidden;
        }
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .content-preview {
            max-width: 200px;
            word-wrap: break-word;
        }
        .sql-code {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            border-radius: 4px;
            padding: 15px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        .diagram-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        .diagram-upload-area:hover {
            border-color: #667eea;
            background: #f0f4ff;
        }
        .diagram-upload-area.dragover {
            border-color: #667eea;
            background: #e3f2fd;
        }
        .diagram-preview {
            max-width: 150px;
            max-height: 150px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .content-type-selector {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .content-type-radio {
            margin-right: 20px;
        }
        #text-content-section, #diagram-content-section {
            transition: all 0.3s ease;
        }
        .file-info {
            background: #e3f2fd;
            border-radius: 6px;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <?php   include "../classes.php"; ?>
    <?php include 'admin_nav.php'; ?>

    <?php
   

    require_once 'admin_class.php'; 
    $db = new admindb();
    
    $uploadDir = 'diagrams/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $message = '';
    if ($_POST) {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $content = $_POST['content'];
                    $contentType = $_POST['content_type'];
                    
                    if ($contentType === 'diagram' && isset($_FILES['diagram']) && $_FILES['diagram']['error'] == 0) {
                        $file = $_FILES['diagram'];
                        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml'];
                        
                        if (in_array($file['type'], $allowedTypes)) {
                            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                            $filePath = $uploadDir . $fileName;
                            
                            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                                $content = $filePath; // Store the file path as content
                            } else {
                                $message = 'Error uploading diagram file.';
                                break;
                            }
                        } else {
                            $message = 'Invalid file type. Please upload an image file (JPEG, PNG, GIF, SVG).';
                            break;
                        }
                    }
                    
                    $message = $db->addStudyContent($_POST['subject'], $_POST['category'], $_POST['title'], $content);
                    break;
                    
                case 'update':
                    $content = $_POST['content'];
                    $contentType = $_POST['content_type'];
                    $existingContent = $db->getStudyContentById($_POST['id']);
                    
                    // Handle diagram upload for update
                    if ($contentType === 'diagram' && isset($_FILES['diagram']) && $_FILES['diagram']['error'] == 0) {
                        $file = $_FILES['diagram'];
                        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml'];
                        
                        if (in_array($file['type'], $allowedTypes)) {
                            // Delete old diagram if it exists
                            if ($existingContent && file_exists($existingContent['content']) && strpos($existingContent['content'], 'diagrams/') === 0) {
                                unlink($existingContent['content']);
                            }
                            
                            $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                            $fileName = uniqid() . '_' . time() . '.' . $fileExtension;
                            $filePath = $uploadDir . $fileName;
                            
                            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                                $content = $filePath; // Store the file path as content
                            } else {
                                $message = 'Error uploading diagram file.';
                                break;
                            }
                        } else {
                            $message = 'Invalid file type. Please upload an image file (JPEG, PNG, GIF, SVG).';
                            break;
                        }
                    }
                    
                    $message = $db->updateStudyContent($_POST['id'], $_POST['subject'], $_POST['category'], $_POST['title'], $content);
                    break;
                    
                case 'delete':
                    // Get content to delete diagram file if exists
                    $contentToDelete = $db->getStudyContentById($_POST['id']);
                    if ($contentToDelete && file_exists($contentToDelete['content']) && strpos($contentToDelete['content'], 'diagrams/') === 0) {
                        unlink($contentToDelete['content']);
                    }
                    $message = $db->deleteStudyContent($_POST['id']);
                    break;
            }
        }
    }
    
    // Get content for editing if ID is provided
    $editContent = null;
    if (isset($_GET['edit'])) {
        $editContent = $db->getStudyContentById($_GET['edit']);
    }
    
    // Get all content for display
    $allContent = $db->getAllStudyContent();
    ?>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white fw-bold" href="#">
                <i class="fas fa-cogs me-2"></i>Study Content Admin
            </a>
        </div>
    </nav>
    
    <div class="container content-wrapper">
        <?php if ($message): ?>
            <div class="alert alert-info alert-custom">
                <i class="fas fa-info-circle me-2"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        
        <!-- Add/Edit Form -->
        <div class="form-card">
            <h2 class="mb-4">
                <i class="fas fa-<?php echo $editContent ? 'edit' : 'plus'; ?> me-2"></i>
                <?php echo $editContent ? 'Edit Content' : 'Add New Content'; ?>
            </h2>
            
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $editContent ? 'update' : 'add'; ?>">
                <?php if ($editContent): ?>
                    <input type="hidden" name="id" value="<?php echo $editContent['id']; ?>">
                <?php endif; ?>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Subject</label>
                        <select name="subject" class="form-select" required>
                            <option value="">Select Subject</option>
                            <option value="biology" <?php echo ($editContent && $editContent['subject'] == 'biology') ? 'selected' : ''; ?>>Biology</option>
                            <option value="physics" <?php echo ($editContent && $editContent['subject'] == 'physics') ? 'selected' : ''; ?>>Physics</option>
                            <option value="chemistry" <?php echo ($editContent && $editContent['subject'] == 'chemistry') ? 'selected' : ''; ?>>Chemistry</option>
                            <option value="computer_science" <?php echo ($editContent && $editContent['subject'] == 'computer_science') ? 'selected' : ''; ?>>Computer Science</option>
                            <option value="mathematics" <?php echo ($editContent && $editContent['subject'] == 'mathematics') ? 'selected' : ''; ?>>Mathematics</option>
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="">Select Category</option>
                            <option value="definitions" <?php echo ($editContent && $editContent['category'] == 'definitions') ? 'selected' : ''; ?>>Definitions</option>
                            <option value="experiments" <?php echo ($editContent && $editContent['category'] == 'experiments') ? 'selected' : ''; ?>>Experiments</option>
                            <option value="laws" <?php echo ($editContent && $editContent['category'] == 'laws') ? 'selected' : ''; ?>>Laws</option>
                            <option value="diagrams" <?php echo ($editContent && $editContent['category'] == 'diagrams') ? 'selected' : ''; ?>>Diagrams</option>
                            <option value="formulas" <?php echo ($editContent && $editContent['category'] == 'formulas') ? 'selected' : ''; ?>>Formulas</option>
                            <option value="concepts" <?php echo ($editContent && $editContent['category'] == 'concepts') ? 'selected' : ''; ?>>Concepts</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo $editContent ? htmlspecialchars($editContent['title']) : ''; ?>" placeholder="Enter content title">
                </div>
                
                <!-- Content Type Selector -->
                <div class="content-type-selector">
                    <label class="form-label fw-bold mb-3">Content Type</label>
                    <div class="d-flex">
                        <div class="content-type-radio">
                            <input type="radio" id="text-content" name="content_type" value="text" 
                                   <?php echo (!$editContent || (strpos($editContent['content'], 'diagrams/') !== 0)) ? 'checked' : ''; ?>>
                            <label for="text-content" class="form-label ms-2">
                                <i class="fas fa-font me-1"></i>Text Content
                            </label>
                        </div>
                        <div class="content-type-radio">
                            <input type="radio" id="diagram-content" name="content_type" value="diagram"
                                   <?php echo ($editContent && strpos($editContent['content'], 'diagrams/') === 0) ? 'checked' : ''; ?>>
                            <label for="diagram-content" class="form-label ms-2">
                                <i class="fas fa-image me-1"></i>Diagram/Image
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Text Content Section -->
                <div id="text-content-section" class="mb-3">
                    <label class="form-label fw-bold">Text Content</label>
                    <textarea name="content" class="form-control" rows="8" placeholder="Enter the study content here..."><?php echo ($editContent && strpos($editContent['content'], 'diagrams/') !== 0) ? htmlspecialchars($editContent['content']) : ''; ?></textarea>
                    <div class="form-text">You can use HTML tags for formatting (e.g., &lt;br&gt;, &lt;strong&gt;, &lt;em&gt;)</div>
                </div>
                
                <!-- Diagram Content Section -->
                <div id="diagram-content-section" class="mb-3" style="display: none;">
                    <label class="form-label fw-bold">Diagram/Image Upload</label>
                    <div class="diagram-upload-area" id="diagram-upload-area">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h5>Upload Diagram</h5>
                        <p class="text-muted mb-3">Drag and drop your image here or click to browse</p>
                        <input type="file" name="diagram" id="diagram-input" accept="image/*" style="display: none;">
                        <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('diagram-input').click();">
                            <i class="fas fa-folder-open me-2"></i>Choose File
                        </button>
                        <div class="form-text mt-2">Supported formats: JPEG, PNG, GIF, SVG (Max size: 10MB)</div>
                    </div>
                    
                    <?php if ($editContent && strpos($editContent['content'], 'diagrams/') === 0): ?>
                        <div class="file-info">
                            <strong>Current Diagram:</strong>
                            <div class="mt-2">
                                <img src="<?php echo $editContent['content']; ?>" alt="Current diagram" class="diagram-preview">
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div id="file-info" class="file-info" style="display: none;">
                        <strong>Selected File:</strong>
                        <div id="file-details"></div>
                        <div id="file-preview" class="mt-2"></div>
                    </div>
                </div>
                
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-gradient">
                        <i class="fas fa-save me-2"></i>
                        <?php echo $editContent ? 'Update Content' : 'Add Content'; ?>
                    </button>
                    <?php if ($editContent): ?>
                        <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel Edit
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- Display All Content -->
        <div class="table-card">
            <h2 class="mb-4">
                <i class="fas fa-list me-2"></i>All Study Content
            </h2>
            
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Content Preview</th>
                            <th>Type</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($allContent)): ?>
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                    No content available. Add some content to get started!
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($allContent as $content): ?>
                            <tr>
                                <td><span class="badge bg-primary"><?php echo $content['id']; ?></span></td>
                                <td>
                                    <span class="badge bg-info">
                                        <?php echo ucwords(str_replace('_', ' ', $content['subject'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        <?php echo ucfirst($content['category']); ?>
                                    </span>
                                </td>
                                <td class="fw-bold"><?php echo htmlspecialchars($content['title']); ?></td>
                                <td class="content-preview">
                                    <?php if (strpos($content['content'], 'diagrams/') === 0): ?>
                                        <img src="<?php echo $content['content']; ?>" alt="Diagram" class="diagram-preview" style="max-width: 80px; max-height: 80px;">
                                    <?php else: ?>
                                        <?php echo htmlspecialchars(substr(strip_tags($content['content']), 0, 100)) . '...'; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (strpos($content['content'], 'diagrams/') === 0): ?>
                                        <span class="badge bg-success"><i class="fas fa-image me-1"></i>Diagram</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning"><i class="fas fa-font me-1"></i>Text</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('M j, Y', strtotime($content['created_at'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="?edit=<?php echo $content['id']; ?>" class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this content?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $content['id']; ?>">
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Database Table Creation SQL -->
       
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Content type switcher
        document.querySelectorAll('input[name="content_type"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const textSection = document.getElementById('text-content-section');
                const diagramSection = document.getElementById('diagram-content-section');
                const textArea = document.querySelector('textarea[name="content"]');
                
                if (this.value === 'text') {
                    textSection.style.display = 'block';
                    diagramSection.style.display = 'none';
                    textArea.required = true;
                } else {
                    textSection.style.display = 'none';
                    diagramSection.style.display = 'block';
                    textArea.required = false;
                }
            });
        });
        
        // Initialize content type display
        document.addEventListener('DOMContentLoaded', function() {
            const checkedRadio = document.querySelector('input[name="content_type"]:checked');
            if (checkedRadio) {
                checkedRadio.dispatchEvent(new Event('change'));
            }
        });
        
        // File upload handling
        const diagramInput = document.getElementById('diagram-input');
        const uploadArea = document.getElementById('diagram-upload-area');
        const fileInfo = document.getElementById('file-info');
        const fileDetails = document.getElementById('file-details');
        const filePreview = document.getElementById('file-preview');
        
        // Click to upload
        uploadArea.addEventListener('click', function(e) {
            if (e.target.tagName !== 'BUTTON') {
                diagramInput.click();
            }
        });
        
        // Drag and drop
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('dragover');
        });
        
        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
        });
        
        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                diagramInput.files = files;
                handleFileSelect(files[0]);
            }
        });
        
        // File input change
        diagramInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                handleFileSelect(e.target.files[0]);
            }
        });
        
        function handleFileSelect(file) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/svg+xml'];
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid image file (JPEG, PNG, GIF, SVG)');
                return;
            }
            
            // Validate file size (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('File size must be less than 10MB');
                return;
            }
            
            // Display file info
            fileDetails.innerHTML = `
                <div><strong>Name:</strong> ${file.name}</div>
                <div><strong>Size:</strong> ${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                <div><strong>Type:</strong> ${file.type}</div>
            `;
            
            // Display preview
            const reader = new FileReader();
            reader.onload = function(e) {
                filePreview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="diagram-preview">`;
            };
            reader.readAsDataURL(file);
            
            fileInfo.style.display = 'block';
        }
    </script>
</body>
</html>
