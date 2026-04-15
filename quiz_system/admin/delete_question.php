<?php
session_start();
if(!isset($_SESSION['admin_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

include '../config/database.php';

$id = isset($_GET['id']) ? $_GET['id'] : 0;

$query = "DELETE FROM questions WHERE id = $id";
mysqli_query($conn, $query);

header("Location: manage_questions.php");
exit();
?>