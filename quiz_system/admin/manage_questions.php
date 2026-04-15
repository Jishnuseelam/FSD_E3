<?php
session_start();
if(!isset($_SESSION['admin_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

$questions_query = "SELECT * FROM questions ORDER BY id DESC";
$questions_result = mysqli_query($conn, $questions_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Questions - Admin</title>
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
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                <h2>Manage Questions</h2>
                <a href="add_question.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Question
                </a>
            </div>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th>Options</th>
                            <th>Correct</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($question = mysqli_fetch_assoc($questions_result)): ?>
                            <tr>
                                <td><?php echo $question['id']; ?></td>
                                <td><?php echo substr(htmlspecialchars($question['question_text']), 0, 50) . '...'; ?></td>
                                <td>
                                    A: <?php echo htmlspecialchars($question['option_a']); ?><br>
                                    B: <?php echo htmlspecialchars($question['option_b']); ?><br>
                                    C: <?php echo htmlspecialchars($question['option_c']); ?><br>
                                    D: <?php echo htmlspecialchars($question['option_d']); ?>
                                </td>
                                <td><?php echo $question['correct_answer']; ?></td>
                                <td>
                                    <a href="edit_question.php?id=<?php echo $question['id']; ?>" class="btn btn-warning" style="padding: 0.3rem 0.8rem;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="delete_question.php?id=<?php echo $question['id']; ?>" class="btn btn-danger" style="padding: 0.3rem 0.8rem;" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>