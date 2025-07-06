<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Textbook Store</title>
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
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
            background: linear-gradient(135deg, #6366F1, #A855F7);
            padding: 20px;
            border-radius: 10px;
            color: white;
        }
        
        .back-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .back-btn:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        
        .header-left {
            display: flex;
            align-items: center;
        }
        
        .header-title {
            margin-left: 20px;
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        .nav-buttons {
            display: flex;
            gap: 10px;
        }
        
        .header-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .header-btn:hover {
            background: rgba(255, 255, 255, 0.4);
        }
        
        .search-container {
            margin-bottom: 30px;
            width: 100%;
            position: relative;
        }
        
        .search-input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            font-size: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            padding-left: 45px;
        }
        
        .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }
        
        .subject-section {
            margin-bottom: 40px;
        }
        
        .subject-header {
            font-size: 1.5rem;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #d1d5db;
            color: #4338ca;
        }
        
        .books-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .book-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .book-image {
            height: 200px;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .book-image img {
            max-height: 100%;
            max-width: 80%;
            object-fit: contain;
        }
        
        .book-details {
            padding: 15px;
        }
        
        .book-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .book-author {
            font-size: 0.9rem;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .book-price {
            color: #6366F1;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }
        
        .buy-btn {
            background: linear-gradient(135deg, #6366F1, #A855F7);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .buy-btn:hover {
            opacity: 0.9;
        }
        
        /* My Books Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }
        
        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 80%;
            max-width: 800px;
            animation: modalFadeIn 0.3s;
        }
        
        @keyframes modalFadeIn {
            from {opacity: 0; transform: translateY(-20px);}
            to {opacity: 1; transform: translateY(0);}
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #4338ca;
        }
        
        .close-modal {
            font-size: 1.5rem;
            cursor: pointer;
            background: none;
            border: none;
            color: #6b7280;
        }
        
        .my-books-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 15px;
        }
        
        .my-book-item {
            display: flex;
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 10px;
            align-items: center;
            gap: 10px;
        }
        
        .my-book-image {
            width: 50px;
            height: 70px;
            background-color: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
        }
        
        .my-book-info {
            flex: 1;
        }
        
        .my-book-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 3px;
        }
        
        .my-book-author {
            font-size: 0.8rem;
            color: #6b7280;
        }
        
        .no-books-message {
            text-align: center;
            padding: 30px;
            color: #6b7280;
            font-size: 1.1rem;
        }
        
        /* Notes Section Styles */
        .notes-section {
            margin-bottom: 40px;
        }
        
        .notes-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .note-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .note-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .note-header {
            background: linear-gradient(135deg, #6366F1, #A855F7);
            color: white;
            padding: 15px;
        }
        
        .note-title {
            font-size: 1.1rem;
            font-weight: 600;
        }
        
        .note-content {
            padding: 15px;
            height: 150px;
            overflow: hidden;
            position: relative;
        }
        
        .note-content::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50px;
            background: linear-gradient(transparent, white);
        }
        
        .note-actions {
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #e5e7eb;
        }
        
        .note-btn {
            background: none;
            border: none;
            color: #6366F1;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .my-notes-card {
            border: 2px dashed #d1d5db;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 30px;
            cursor: pointer;
            height: 100%;
            transition: all 0.3s ease;
        }
        
        .my-notes-card:hover {
            border-color: #6366F1;
            background-color: rgba(99, 102, 241, 0.05);
        }
        
        .add-note-icon {
            width: 50px;
            height: 50px;
            background-color: #6366F1;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: white;
        }
        
        .add-note-text {
            font-weight: 600;
            color: #6366F1;
        }
        
        /* New Note Modal */
        .note-modal-content {
            max-width: 600px;
        }
        
        .note-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        
        .form-label {
            font-weight: 500;
            color: #4b5563;
        }
        
        .form-input {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .form-textarea {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 5px;
            font-size: 1rem;
            min-height: 200px;
            resize: vertical;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .cancel-btn {
            padding: 10px 15px;
            background-color: #e5e7eb;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }
        
        .save-btn {
            padding: 10px 20px;
            background: linear-gradient(135deg, #6366F1, #A855F7);
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-left">
                <a href="home.php" class="back-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="header-title">Official Textbooks</h1>
            </div>
            <div class="nav-buttons">
                <button class="header-btn" onclick="openMyBooks()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                    </svg>
                    My Books
                </button>
                <button class="header-btn" onclick="scrollToNotes()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                    </svg>
                    Notes
                </button>
            </div>
        </header>
        
        <div class="search-container">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" class="search-input" placeholder="Search for textbooks...">
        </div>
        
        <main>
            <section class="subject-section">
                <h2 class="subject-header">Mathematics</h2>
                <div class="books-container">
                    <div class="book-card">
                        <div class="book-image">
                        <i class="fa fa-book"></i>
                        </div>
                        <div class="book-details">
                            <h3 class="book-title">PSYCHOMOTOR: Mechanics</h3>
                            <p class="book-author">Chu Ferdinand</p>
                            <p class="book-price">4,500 CFA</p>
                            <button class="buy-btn">Buy</button>
                        </div>
                    </div>
                    
                    <div class="book-card">
                        <div class="book-image">
                        <i class="fa fa-book"></i>
                        </div>
                        <div class="book-details">
                            <h3 class="book-title">PSYCHOMOTOR: Statistics</h3>
                            <p class="book-author">Chu Ferdinand</p>
                            <p class="book-price">4,500 CFA</p>
                            <button class="buy-btn">Buy</button>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="subject-section">
                <h2 class="subject-header">Physics</h2>
                <div class="books-container">
                    <div class="book-card">
                        <div class="book-image">
                           <i class="fa fa-book"></i>
                        </div>
                        <div class="book-details">
                            <h3 class="book-title">Applied A-level Physics</h3>
                            <p class="book-author"></p>
                            <p class="book-price">5,000 CFA</p>
                            <button class="buy-btn">Buy</button>
                        </div>
                    </div>
                    
                    <div class="book-card">
                        <div class="book-image">
                        <i class="fa fa-book"></i>
                        </div>
                        <div class="book-details">
                            <h3 class="book-title">Breakthrough Physics</h3>
                            <p class="book-author"></p>
                            <p class="book-price">5,200 CFA</p>
                            <button class="buy-btn">Buy</button>
                        </div>
                    </div>
                </div>
            </section>
            
            <section class="subject-section">
                <h2 class="subject-header">Chemistry</h2>
                <div class="books-container">
                    <div class="book-card">
                        <div class="book-image">
                        <i class="fa fa-book"></i>
                        </div>
                        <div class="book-details">
                            <h3 class="book-title">A-level Chemistry</h3>
                            <p class="book-author">Ngulle Emmanuel</p>
                            <p class="book-price">8,000 CFA</p>
                            <button class="buy-btn">Buy</button>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Notes Section -->
            <section id="notesSection" class="notes-section">
                <h2 class="subject-header">Notes</h2>
                <div class="notes-container">
                    <!-- Mathematics Notes -->
                    <div class="note-card">
                        <div class="note-header">
                            <h3 class="note-title">Mathematics Notes</h3>
                        </div>
                        <div class="note-content">
                            <p>Sample notes on Calculus, Algebra, and Statistics. Click to view comprehensive study materials, formulas, and example problems in various mathematics topics.</p>
                            <p>Includes key concepts for differentiation, integration, linear algebra, and probability theory.</p>
                        </div>
                        <div class="note-actions">
                            <button class="note-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                View
                            </button>
                            <button class="note-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Download PDF
                            </button>
                        </div>
                    </div>
                    
                    <!-- Physics Notes -->
                    <div class="note-card">
                        <div class="note-header">
                            <h3 class="note-title">Physics Notes</h3>
                        </div>
                        <div class="note-content">
                            <p>Comprehensive notes on Mechanics, Electricity, Magnetism, and Modern Physics. Includes formulas, diagrams, and practical examples.</p>
                            <p>Topics cover Newton's laws, circuits, electromagnetic induction, and quantum physics basics.</p>
                        </div>
                        <div class="note-actions">
                            <button class="note-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                View
                            </button>
                            <button class="note-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Download PDF
                            </button>
                        </div>
                    </div>
                    
                    <!-- Chemistry Notes -->
                    <div class="note-card">
                        <div class="note-header">
                            <h3 class="note-title">Chemistry Notes</h3>
                        </div>
                        <div class="note-content">
                            <p>Study materials for Organic Chemistry, Inorganic Chemistry, and Physical Chemistry. Contains reaction mechanisms, periodic table trends, and thermodynamics concepts.</p>
                            <p>Includes lab safety protocols and experiment guidelines for practical sessions.</p>
                        </div>
                        <div class="note-actions">
                            <button class="note-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                                View
                            </button>
                            <button class="note-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="7 10 12 15 17 10"></polyline>
                                    <line x1="12" y1="15" x2="12" y2="3"></line>
                                </svg>
                                Download PDF
                            </button>
                        </div>
                    </div>
                    
                    <!-- My Notes (Add New Notes) -->
                    <div class="my-notes-card" onclick="openNewNoteModal()">
                        <div class="add-note-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </div>
                        <p class="add-note-text">Add New Notes</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <!-- My Books Modal -->
    <div id="myBooksModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">My Purchased Books</h2>
                <button class="close-modal" onclick="closeMyBooks()">&times;</button>
            </div>
            <div id="myBooksList" class="my-books-list">
                <!-- Example of a purchased book (this would typically be populated by JavaScript) -->
                <div class="my-book-item">
                    <div class="my-book-image">
                        <img src="/api/placeholder/50/70" alt="Book thumbnail">
                    </div>
                    <div class="my-book-info">
                        <h4 class="my-book-title">PSYCHOMOTOR: Mechanics</h4>
                        <p class="my-book-author">Chu Ferdinand</p>
                    </div>
                </div>
                <!-- For demonstration - would be dynamic in real implementation -->
                <div class="no-books-message" style="display:none;">
                    You haven't purchased any books yet.
                </div>
            </div>
        </div>
    </div>
    
    <!-- New Note Modal -->
    <div id="newNoteModal" class="modal">
        <div class="modal-content note-modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Create New Note</h2>
                <button class="close-modal" onclick="closeNewNoteModal()">&times;</button>
            </div>
            <form class="note-form" id="newNoteForm">
                <div class="form-group">
                    <label class="form-label" for="noteTitle">Title</label>
                    <input type="text" id="noteTitle" class="form-input" placeholder="Enter note title">
                </div>
                <div class="form-group">
                    <label class="form-label" for="noteSubject">Subject</label>
                    <select id="noteSubject" class="form-input">
                        <option value="">Select a subject</option>
                        <option value="mathematics">Mathematics</option>
                        <option value="physics">Physics</option>
                        <option value="chemistry">Chemistry</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="noteContent">Content</label>
                    <textarea id="noteContent" class="form-textarea" placeholder="Write your notes here..."></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="cancel-btn" onclick="closeNewNoteModal()">Cancel</button>
                    <button type="button" class="save-btn" onclick="saveNote()">Save as PDF</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Modal functionality
        function openMyBooks() {
            document.getElementById("myBooksModal").style.display = "block";
        }
        
        function closeMyBooks() {
            document.getElementById("myBooksModal").style.display = "none";
        }
        
        function openNewNoteModal() {
            document.getElementById("newNoteModal").style.display = "block";
        }
        
        function closeNewNoteModal() {
            document.getElementById("newNoteModal").style.display = "none";
        }
        
        // Function to scroll to Notes section
        function scrollToNotes() {
            const notesSection = document.getElementById("notesSection");
            if (notesSection) {
                notesSection.scrollIntoView({ behavior: "smooth" });
            }
        }
        
        // Function to save note as PDF
        function saveNote() {
            // Get form values
            const title = document.getElementById("noteTitle").value;
            const subject = document.getElementById("noteSubject").value;
            const content = document.getElementById("noteContent").value;
            
            // Basic validation
            if (!title || !subject || !content) {
                alert("Please fill in all fields");
                return;
            }
            
            // Here in a real implementation, we would use a library like jsPDF
            // to generate the PDF file and trigger download
            
            alert("Note saved as PDF! (Simulation)");
            closeNewNoteModal();
            
            // In a real implementation, you'd add the note to the user's notes collection
            // and display it in the interface
        }
        
        // Close modals if clicking outside of them
        window.onclick = function(event) {
            const myBooksModal = document.getElementById("myBooksModal");
            const newNoteModal = document.getElementById("newNoteModal");
            
            if (event.target === myBooksModal) {
                myBooksModal.style.display = "none";
            }
            
            if (event.target === newNoteModal) {
                newNoteModal.style.display = "none";
            }
        }
        </script>
</body>
</html>