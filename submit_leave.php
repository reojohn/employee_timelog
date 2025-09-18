<?php
session_start();
include('db.php');

$user_id = $_SESSION['user_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$query = "INSERT INTO leave_requests (user_id, start_date, end_date, status) VALUES ($1, $2, $3, 'pending')";
pg_query_params($conn, $query, array($user_id, $start_date, $end_date));

header('Location: employee_dashboard.php');
?>