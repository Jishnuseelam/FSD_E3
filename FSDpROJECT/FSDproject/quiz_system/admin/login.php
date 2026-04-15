<?php
session_start();
if(isset($_SESSION['admin_id']) && $_SESSION['user_type'] == 'admin') {
    header("Location: dashboard.php");
    exit();
}

include '../config/database.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = md5($_POST['password']);
    
    $query = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if(mysqli_num_rows($result) == 1) {
        $admin = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['full_name'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['user_type'] = 'admin';
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Quiz System</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="logo">📚 Quiz Engine - Admin</div>
        <div class="nav-links">
            <a href="../index.php">Home</a>
            <a href="login.php">Admin Login</a>
        </div>
    </div>

    <div class="login-container">
        <div class="card">
            <h2 style="text-align: center; margin-bottom: 1.5rem;">
                <i class="fas fa-user-cog"></i> Admin Login
            </h2>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-success" style="width: 100%;">
                    <i class="fas fa-sign-in-alt"></i> Admin Login
                </button>
            </form>
            
            <p style="text-align: center; margin-top: 1rem; color: #666;">
                Default: username: admin, password: admin123
            </p>
        </div>
    </div>
</body>
</html>