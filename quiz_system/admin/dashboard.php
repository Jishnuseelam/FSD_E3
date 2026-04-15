<?php
session_start();
if(!isset($_SESSION['admin_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

// Get statistics
$students_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM students"))['total'];
$questions_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM questions"))['total'];
$results_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM quiz_results"))['total'];
$passed_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM quiz_results WHERE percentage >= 60"))['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Quiz System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="logo">📚 Quiz Engine - Admin Panel</div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_questions.php">Manage Questions</a>
            <a href="view_results.php">View Results</a>
            <span>Welcome, <?php echo $_SESSION['admin_name']; ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Admin Dashboard</h2>
            <p>Welcome back, <?php echo $_SESSION['admin_name']; ?>!</p>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
                <div style="background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 1.5rem; border-radius: 10px; text-align: center;">
                    <i class="fas fa-users" style="font-size: 2rem;"></i>
                    <h3 style="margin: 0.5rem 0;"><?php echo $students_count; ?></h3>
                    <p>Total Students</p>
                </div>
                
                <div style="background: linear-gradient(135deg, #48bb78, #38a169); color: white; padding: 1.5rem; border-radius: 10px; text-align: center;">
                    <i class="fas fa-question-circle" style="font-size: 2rem;"></i>
                    <h3 style="margin: 0.5rem 0;"><?php echo $questions_count; ?></h3>
                    <p>Total Questions</p>
                </div>
                
                <div style="background: linear-gradient(135deg, #ed8936, #dd6b20); color: white; padding: 1.5rem; border-radius: 10px; text-align: center;">
                    <i class="fas fa-chart-line" style="font-size: 2rem;"></i>
                    <h3 style="margin: 0.5rem 0;"><?php echo $results_count; ?></h3>
                    <p>Quiz Attempts</p>
                </div>
                
                <div style="background: linear-gradient(135deg, #f56565, #c53030); color: white; padding: 1.5rem; border-radius: 10px; text-align: center;">
                    <i class="fas fa-certificate" style="font-size: 2rem;"></i>
                    <h3 style="margin: 0.5rem 0;"><?php echo $passed_count; ?></h3>
                    <p>Certificates Issued</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>