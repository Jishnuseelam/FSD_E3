<?php
session_start();
if(isset($_SESSION['user_id']) && isset($_SESSION['user_type'])) {
    if($_SESSION['user_type'] == 'student') {
        header("Location: student/dashboard.php");
        exit();
    } elseif($_SESSION['user_type'] == 'admin') {
        header("Location: admin/dashboard.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz System - Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="logo">📚 Quiz Engine</div>
        <div class="nav-links">
            <a href="index.php">Home</a>
        </div>
    </div>

    <div class="container">
        <div class="card" style="text-align: center;">
            <h1>Welcome to Automated Quiz Engine</h1>
            <p style="margin: 1rem 0; font-size: 1.2rem;">Test your knowledge and earn certificates!</p>
            
            <div class="login-buttons">
                <a href="student/login.php" class="btn btn-primary">
                    <i class="fas fa-user-graduate"></i> Student Login
                </a>
                <a href="admin/login.php" class="btn btn-success">
                    <i class="fas fa-user-cog"></i> Admin Login
                </a>
            </div>
            
            <div style="margin-top: 2rem;">
                <p>New Student? <a href="student/register.php">Register here</a></p>
            </div>
        </div>
        
        <div class="card">
            <h2>Features</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-top: 1.5rem;">
                <div>
                    <i class="fas fa-question-circle" style="font-size: 2rem; color: #667eea;"></i>
                    <h3>Automated Quiz</h3>
                    <p>Take timed quizzes with multiple choice questions</p>
                </div>
                <div>
                    <i class="fas fa-chart-line" style="font-size: 2rem; color: #667eea;"></i>
                    <h3>Instant Results</h3>
                    <p>Get immediate feedback and scores</p>
                </div>
                <div>
                    <i class="fas fa-certificate" style="font-size: 2rem; color: #667eea;"></i>
                    <h3>PDF Certificate</h3>
                    <p>Download certificate on passing the quiz</p>
                </div>
                <div>
                    <i class="fas fa-chalkboard-teacher" style="font-size: 2rem; color: #667eea;"></i>
                    <h3>Admin Panel</h3>
                    <p>Manage questions and view student results</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>