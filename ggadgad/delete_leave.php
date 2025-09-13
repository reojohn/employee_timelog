<?php
include('db.php');
$id = $_GET['id'];
$query = "DELETE FROM leave_requests WHERE id = $1";
$result = pg_query_params($conn, $query, array($id));

header("Location: admin_dashboard.php");
exit();
?>
