<?php
session_start();
include('db.php');
if (!isset($_SESSION['user_id'])) {
  header('Location: index.html');
  exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];


exit();
