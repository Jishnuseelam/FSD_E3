<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

// Get student's previous quiz results
$query = "SELECT * FROM quiz_results WHERE student_id = {$_SESSION['user_id']} ORDER BY quiz_date DESC";
$results = mysqli_query($conn, $query);
$has_taken_quiz = mysqli_num_rows($results) > 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Quiz System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="logo">📚 Quiz Engine</div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="take_quiz.php">Take Quiz</a>
            <span style="color: #667eea;">Welcome, <?php echo $_SESSION['user_name']; ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="card">
            <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
            <p style="margin-top: 0.5rem;">Ready to test your knowledge?</p>
            
            <div style="margin-top: 2rem;">
                <a href="take_quiz.php" class="btn btn-primary">
                    <i class="fas fa-play"></i> Start New Quiz
                </a>
            </div>
        </div>
        
        <div class="card">
            <h3><i class="fas fa-history"></i> Your Quiz History</h3>
            
            <?php if($has_taken_quiz): ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Score</th>
                                <th>Percentage</th>
                                <th>Certificate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($result = mysqli_fetch_assoc($results)): ?>
                                <tr>
                                    <td><?php echo date('F d, Y', strtotime($result['quiz_date'])); ?></td>
                                    <td><?php echo $result['score'] . '/' . $result['total_questions']; ?></td>
                                    <td>
                                        <?php echo $result['percentage']; ?>%
                                        <?php if($result['percentage'] >= 60): ?>
                                            <span style="color: #48bb78;">(Passed)</span>
                                        <?php else: ?>
                                            <span style="color: #f56565;">(Failed)</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($result['percentage'] >= 60 && !$result['certificate_issued']): ?>
                                            <a href="../generate_certificate.php?result_id=<?php echo $result['id']; ?>" class="btn btn-success" style="padding: 0.3rem 0.8rem; font-size: 0.9rem;">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        <?php elseif($result['percentage'] >= 60 && $result['certificate_issued']): ?>
                                            <span style="color: #48bb78;">Certificate Issued</span>
                                        <?php else: ?>
                                            <span style="color: #f56565;">Not Eligible</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p style="color: #666;">You haven't taken any quiz yet. Start now!</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>