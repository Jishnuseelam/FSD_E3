<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

$result_id = isset($_GET['result_id']) ? $_GET['result_id'] : 0;
$query = "SELECT * FROM quiz_results WHERE id = $result_id AND student_id = {$_SESSION['user_id']}";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    header("Location: dashboard.php");
    exit();
}

$quiz_result = mysqli_fetch_assoc($result);
$passed = $quiz_result['percentage'] >= 60;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Result - Quiz System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="logo">📚 Quiz Engine</div>
        <div class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="take_quiz.php">Take Quiz</a>
            <span>Welcome, <?php echo $_SESSION['user_name']; ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="card" style="text-align: center;">
            <h2>Quiz Results</h2>
            
            <div style="margin: 2rem 0;">
                <div style="font-size: 3rem; color: <?php echo $passed ? '#48bb78' : '#f56565'; ?>">
                    <?php echo $passed ? '✓' : '✗'; ?>
                </div>
                <h3><?php echo $passed ? 'Congratulations! You Passed!' : 'Sorry! You Did Not Pass'; ?></h3>
                
                <div style="margin: 2rem 0; padding: 1rem; background: #f7fafc; border-radius: 10px;">
                    <p><strong>Your Score:</strong> <?php echo $quiz_result['score'] . '/' . $quiz_result['total_questions']; ?></p>
                    <p><strong>Percentage:</strong> <?php echo number_format($quiz_result['percentage'], 2); ?>%</p>
                    <p><strong>Required to Pass:</strong> 60%</p>
                </div>
                
                <?php if($passed): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-certificate"></i> You have earned a certificate!
                        <br>
                        <a href="../generate_certificate.php?result_id=<?php echo $quiz_result['id']; ?>" class="btn btn-success" style="margin-top: 1rem;">
                            <i class="fas fa-download"></i> Download Certificate
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-error">
                        <i class="fas fa-frown"></i> Keep practicing! You can retake the quiz anytime.
                    </div>
                <?php endif; ?>
            </div>
            
            <div>
                <a href="take_quiz.php" class="btn btn-primary">Take Quiz Again</a>
                <a href="dashboard.php" class="btn">Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>