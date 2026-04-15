<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

// Fetch all questions
$questions_query = "SELECT * FROM questions ORDER BY id";
$questions_result = mysqli_query($conn, $questions_query);
$total_questions = mysqli_num_rows($questions_result);

if($total_questions == 0) {
    $error = "No questions available. Please contact administrator.";
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_quiz'])) {
    $score = 0;
    $answers = $_POST['answer'];
    
    // Calculate score
    foreach($answers as $q_id => $answer) {
        $query = "SELECT correct_answer FROM questions WHERE id = $q_id";
        $result = mysqli_query($conn, $query);
        $question = mysqli_fetch_assoc($result);
        
        if($question['correct_answer'] == $answer) {
            $score++;
        }
    }
    
    $percentage = ($score / $total_questions) * 100;
    
    // Save result
    $student_id = $_SESSION['user_id'];
    $student_name = $_SESSION['user_name'];
    $certificate_issued = ($percentage >= 60) ? 1 : 0;
    
    $insert_query = "INSERT INTO quiz_results (student_id, student_name, score, total_questions, percentage, certificate_issued) 
                     VALUES ($student_id, '$student_name', $score, $total_questions, $percentage, $certificate_issued)";
    
    if(mysqli_query($conn, $insert_query)) {
        $result_id = mysqli_insert_id($conn);
        header("Location: result.php?result_id=$result_id");
        exit();
    } else {
        $error = "Failed to save result!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz - Quiz System</title>
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
        <div class="card">
            <h2><i class="fas fa-question-circle"></i> Take Quiz</h2>
            <p style="color: #666; margin: 0.5rem 0;">Answer all questions. Passing score: 60%</p>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php else: ?>
                <form method="POST" action="" id="quizForm">
                    <?php 
                    $counter = 1;
                    while($question = mysqli_fetch_assoc($questions_result)): 
                    ?>
                        <div class="question-card">
                            <h4>Question <?php echo $counter; ?>: <?php echo htmlspecialchars($question['question_text']); ?></h4>
                            <div class="options">
                                <div class="option">
                                    <input type="radio" name="answer[<?php echo $question['id']; ?>]" value="A" required>
                                    <label><?php echo htmlspecialchars($question['option_a']); ?></label>
                                </div>
                                <div class="option">
                                    <input type="radio" name="answer[<?php echo $question['id']; ?>]" value="B">
                                    <label><?php echo htmlspecialchars($question['option_b']); ?></label>
                                </div>
                                <div class="option">
                                    <input type="radio" name="answer[<?php echo $question['id']; ?>]" value="C">
                                    <label><?php echo htmlspecialchars($question['option_c']); ?></label>
                                </div>
                                <div class="option">
                                    <input type="radio" name="answer[<?php echo $question['id']; ?>]" value="D">
                                    <label><?php echo htmlspecialchars($question['option_d']); ?></label>
                                </div>
                            </div>
                        </div>
                    <?php 
                        $counter++;
                    endwhile; 
                    ?>
                    
                    <button type="submit" name="submit_quiz" class="btn btn-primary">
                        <i class="fas fa-check-circle"></i> Submit Quiz
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        // Add confirmation before submission
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            if(!confirm('Are you sure you want to submit your answers?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>