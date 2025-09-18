<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['pending_2fa_user_id'])) {
  header('Location: index.html');
  exit;
}

$userId = $_SESSION['pending_2fa_user_id'];

$sql  = "SELECT id, username, role, second_factor_hash FROM users WHERE id = $1";
$res  = pg_query_params($conn, $sql, [$userId]);
if (!$res) {
  die('Database error while loading admin key.');
}

$user = pg_fetch_assoc($res);
if (!$user || $user['role'] !== 'admin') {
  header('Location: index.html');
  exit;
}

if (empty($user['second_factor_hash'])) {
  die('Admin key is not configured. Please contact the system owner to set it.');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['key'])) {
  $entered = trim($_POST['key']);
  if (password_verify($entered, $user['second_factor_hash'])) {
    session_regenerate_id(true);
    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['role']      = $user['role'];
    unset($_SESSION['pending_2fa_user_id']);
    header('Location: admin_dashboard.php');
    exit;
  } else {
    $error = 'Invalid admin key.';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Second Factor</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-4">
          <div class="card-body p-4">
            <h3 class="card-title text-center mb-3">Admin Second Factor</h3>

            <?php if (!empty($error)): ?>
              <div class="alert alert-danger" role="alert">
                <?=htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?>
              </div>
            <?php endif; ?>

            <form method="post">
              <div class="mb-3">
                <label for="key" class="form-label">Enter Admin Key</label>
                <input type="password" class="form-control" id="key" name="key" required>
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Verify</button>
              </div>
            </form>

            <div class="text-center mt-3">
              <a href="index.html" class="text-decoration-none">Back to Login</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS (optional, for interactivity) -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
