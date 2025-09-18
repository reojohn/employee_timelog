<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';

  // Find user by username
  $result = pg_query_params($conn, "SELECT * FROM users WHERE username = $1", array($username));

  if ($row = pg_fetch_assoc($result)) {
    // Verify password (bcrypt)
    if (password_verify($password, $row['password'])) {

      if ($row['role'] === 'admin') {
        // Admins must pass the pre-set admin key (second factor)
        session_regenerate_id(true);
        $_SESSION['pending_2fa_user_id'] = $row['id'];
        header("Location: verify_admin_key.php");
        exit();
      }

      // Employees: direct login (no second factor)
      session_regenerate_id(true);
      $_SESSION['user_id']   = $row['id'];
      $_SESSION['username']  = $row['username'];
      $_SESSION['role']      = $row['role'];
      header("Location: employee_dashboard.php");
      exit();

    } else {
      // Incorrect password
      header("Location: index.html?error=password");
      exit();
    }
  } else {
    // User not found
    header("Location: index.html?error=user");
    exit();
  }
}
?>
