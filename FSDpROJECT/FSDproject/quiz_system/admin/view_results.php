<?php
session_start();
if(!isset($_SESSION['admin_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

$query = "SELECT * FROM quiz_results ORDER BY quiz_date DESC";
$results = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Results - Admin</title>
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
            <h2>Student Quiz Results</h2>
            
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Certificate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($result = mysqli_fetch_assoc($results)): ?>
                            <tr>
                                <td><?php echo $result['id']; ?></td>
                                <td><?php echo htmlspecialchars($result['student_name']); ?></td>
                                <td><?php echo $result['score'] . '/' . $result['total_questions']; ?></td>
                                <td><?php echo number_format($result['percentage'], 2); ?>%</td>
                                <td>
                                    <?php if($result['percentage'] >= 60): ?>
                                        <span style="color: #48bb78;">Passed</span>
                                    <?php else: ?>
                                        <span style="color: #f56565;">Failed</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('Y-m-d H:i', strtotime($result['quiz_date'])); ?></td>
                                <td>
                                    <?php if($result['certificate_issued']): ?>
                                        <span style="color: #48bb78;">Issued</span>
                                    <?php else: ?>
                                        <span style="color: #f56565;">Not Issued</span>
                                    <?php endif; ?>
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