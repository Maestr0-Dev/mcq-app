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
        
        .my-books-btn {
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
        
        .my-books-btn:hover {
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
            <button class="my-books-btn" onclick="openMyBooks()">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                </svg>
                My Books
            </button>
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
    
    <script>
        // Simple JavaScript for modal functionality
        function openMyBooks() {
            document.getElementById("myBooksModal").style.display = "block";
        }
        
        function closeMyBooks() {
            document.getElementById("myBooksModal").style.display = "none";
        }
        
        // Close modal if clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById("myBooksModal");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>