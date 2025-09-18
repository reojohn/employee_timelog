<?php
session_start();
if (!isset($_SESSION['user_id'])) { header('Location: index.html'); exit(); }
if (($_SESSION['role'] ?? '') !== 'admin') { header('Location: employee_dashboard.php'); exit(); }
include('db.php');
$id = $_GET['id'];
$query = "UPDATE leave_requests SET status = 'Rejected' WHERE id = $1";
$result = pg_query_params($conn, $query, array($id));

header("Location: admin_dashboard.php");
exit();
?>
