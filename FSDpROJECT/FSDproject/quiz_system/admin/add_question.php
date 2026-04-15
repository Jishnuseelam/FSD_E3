<?php
session_start();
if(!isset($_SESSION['admin_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_text = mysqli_real_escape_string($conn, $_POST['question_text']);
    $option_a = mysqli_real_escape_string($conn, $_POST['option_a']);
    $option_b = mysqli_real_escape_string($conn, $_POST['option_b']);
    $option_c = mysqli_real_escape_string($conn, $_POST['option_c']);
    $option_d = mysqli_real_escape_string($conn, $_POST['option_d']);
    $correct_answer = mysqli_real_escape_string($conn, $_POST['correct_answer']);
    
    $query = "INSERT INTO questions (question_text, option_a, option_b, option_c, option_d, correct_answer) 
              VALUES ('$question_text', '$option_a', '$option_b', '$option_c', '$option_d', '$correct_answer')";
    
    if(mysqli_query($conn, $query)) {
        $success = "Question added successfully!";
    } else {
        $error = "Failed to add question!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Question - Admin</title>
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
            <h2>Add New Question</h2>
            
            <?php if($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Question Text</label>
                    <textarea name="question_text" rows="3" required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Option A</label>
                    <input type="text" name="option_a" required>
                </div>
                
                <div class="form-group">
                    <label>Option B</label>
                    <input type="text" name="option_b" required>
                </div>
                
                <div class="form-group">
                    <label>Option C</label>
                    <input type="text" name="option_c" required>
                </div>
                
                <div class="form-group">
                    <label>Option D</label>
                    <input type="text" name="option_d" required>
                </div>
                
                <div class="form-group">
                    <label>Correct Answer</label>
                    <select name="correct_answer" required>
                        <option value="">Select Correct Answer</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add Question
                </button>
                <a href="manage_questions.php" class="btn">Cancel</a>
            </form>
        </div>
    </div>
</body>
</html>