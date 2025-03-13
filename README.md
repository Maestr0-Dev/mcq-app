# MCQ App

This is a Multiple Choice Questions (MCQ) application built with PHP and MySQL. The application allows users to take quizzes, view their results, and track their performance over time.

## Table of Contents

- [Installation](#installation)
- [Usage](#usage)
- [File Structure](#file-structure)
- [Contributing](#contributing)
- [License](#license)

## Installation

1. Clone the repository to your local machine:
    ```bash
    git clone https://github.com/yourusername/mcq-app.git
    ```

2. Move into the project directory:
    ```bash
    cd mcq-app
    ```

3. Set up your database and import the provided SQL file to create the necessary tables.

4. Update the database connection details in [classes.php](http://_vscodecontentref_/1):
    ```php
    // filepath: /c:/xampp/htdocs/mcq-app/classes.php
    private $servername = "localhost";
    private $username = "your_db_username";
    private $password = "your_db_password";
    private $dbname = "your_db_name";
    ```

5. Start your local server (e.g., using XAMPP) and navigate to the project directory in your browser.

## Usage

1. Open the application in your browser.
2. Select the desired quiz criteria (year, subject, etc.).
3. Take the quiz by answering the questions.
4. Submit the quiz to view your results.
5. View your performance history.

## File Structure

- [index.php](http://_vscodecontentref_/2): The main entry point of the application.
- [classes.php](http://_vscodecontentref_/3): Contains the `DB` class for database interactions.
- [quest_selection.php](http://_vscodecontentref_/4): Page for selecting quiz criteria.
- [quiz.php](http://_vscodecontentref_/5): Page for displaying and taking the quiz.
- [result.php](http://_vscodecontentref_/6): Page for displaying quiz results.
- [perf.php](http://_vscodecontentref_/7): Page for viewing performance history.
- `get_data.php`: Script for retrieving data from the database (used for generating charts).
- `chart.html`: Example page for displaying data in chart form using Chart.js.
- [script.js](http://_vscodecontentref_/8): JavaScript file for additional client-side functionality.
- [jquery-3.1.0.min.js](http://_vscodecontentref_/9): jQuery library.

## Contributing

Contributions are welcome! Please fork the repository and submit a pull request with your changes.

## License

This project is licensed under the MIT License.