<?php
session_start();
include('db.php');
if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

pg_query_params($conn, "DELETE FROM leave_requests WHERE id = $1 AND user_id = $2 AND status = 'pending'", array($id, $user_id));
header("Location: employee_dashboard.php");
exit();
