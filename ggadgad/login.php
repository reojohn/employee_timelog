<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];

  // Query the user from the database
  $result = pg_query_params($conn, "SELECT * FROM users WHERE username = $1", array($username));
  
  if ($row = pg_fetch_assoc($result)) {
    // Check the password
    if (password_verify($password, $row['password'])) {
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['role'] = $row['role'];

      // Redirect to respective dashboards based on role
      if ($row['role'] === 'admin') {
        header("Location: admin_dashboard.php");
      } else {
        header("Location: employee_dashboard.php");
      }
      exit();
    } else {
      // Redirect with password error
      header("Location: index.html?error=password");
      exit();
    }
  } else {
    // Redirect with user not found error
    header("Location: index.html?error=user");
    exit();
  }
}
?>
