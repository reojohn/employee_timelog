<?php
session_start();
include('db.php');

$user_id = $_SESSION['user_id'];
$overtime_date = $_POST['overtime_date'];
$hours = $_POST['hours'];

$query = "INSERT INTO overtime_requests (user_id, overtime_date, hours, status) VALUES ($1, $2, $3, 'pending')";
pg_query_params($conn, $query, array($user_id, $overtime_date, $hours));

header('Location: employee_dashboard.php');
?>