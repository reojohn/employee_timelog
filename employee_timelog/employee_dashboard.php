<?php
session_start();
include('db.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
  header('Location: index.html');
  exit();
}
$user_id = $_SESSION['user_id'];
$username = explode('@', $_SESSION['username'])[0];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet" />
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e0f7fa, #ffffff);
      color: #333;
    }
    h3, h5 {
      margin-top: 20px;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .hover-btn:hover {
      transform: scale(1.03);
      transition: 0.2s ease;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .dashboard-cards {
      display: flex;
      gap: 20px;
      margin-bottom: 30px;
    }
    .dashboard-cards .card {
      flex: 1;
      padding: 20px;
    }
    .float-end {
      margin-top: -40px;
    }
  </style>
</head>
<body>
  <div class="container mt-4">
  <h3>Welcome, <?php echo htmlspecialchars($username); ?>! <small class="text-muted">User ID: <?php echo $user_id; ?></small></h3>
    <a href="logout.php" class="btn btn-danger btn-sm float-end">Logout</a>
    
    <div class="dashboard-cards">
      <div class="card text-center">
        <h5>Total Requests</h5>
        <p class="text-muted"><i class="bi bi-files"></i> <?php
          $result = pg_query_params($conn, "SELECT COUNT(*) FROM leave_requests WHERE user_id = $1", array($user_id));
          echo pg_fetch_result($result, 0, 0);
        ?></p>
      </div>
      <div class="card text-center">
        <h5>Pending</h5>
        <p class="text-muted"><i class="bi bi-hourglass-split"></i> <?php
          $result = pg_query_params($conn, "SELECT COUNT(*) FROM leave_requests WHERE user_id = $1 AND status = 'pending'", array($user_id));
          echo pg_fetch_result($result, 0, 0);
        ?></p>
      </div>
      <div class="card text-center">
        <h5>Approved</h5>
        <p class="text-muted"><i class="bi bi-check-circle"></i> <?php
          $result = pg_query_params($conn, "SELECT COUNT(*) FROM leave_requests WHERE user_id = $1 AND status = 'approved'", array($user_id));
          echo pg_fetch_result($result, 0, 0);
        ?></p>
      </div>
    </div>

    <h5><i class="bi bi-calendar-check"></i> Request Leave</h5>
    <form action="submit_leave.php" method="POST">
      <div class="row mb-2">
        <div class="col">
          <input type="date" name="start_date" class="form-control" required />
        </div>
        <div class="col">
          <input type="date" name="end_date" class="form-control" required />
        </div>
        <div class="col">
          <button type="button" class="btn btn-primary hover-btn" data-bs-toggle="modal" data-bs-target="#confirmationModal">Submit Leave</button>
        </div>
      </div>
    </form>

    <h5><i class="bi bi-clock"></i> Request Overtime</h5>
    <form action="submit_overtime.php" method="POST">
      <div class="row mb-2">
        <div class="col">
          <input type="date" name="overtime_date" class="form-control" required />
        </div>
        <div class="col">
          <input type="number" step="0.5" name="hours" class="form-control" required />
        </div>
        <div class="col">
          <button type="button" class="btn btn-primary hover-btn" data-bs-toggle="modal" data-bs-target="#confirmationModal">Submit Overtime</button>
        </div>
      </div>
    </form>

    <hr />

    <h5><i class="bi bi-journal-text"></i> My Leave Requests</h5>
    <table class="table table-bordered">
      <thead>
        <tr><th>Start</th><th>End</th><th>Status</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php
        $leave_result = pg_query_params($conn, "SELECT id, start_date, end_date, status FROM leave_requests WHERE user_id = $1 ORDER BY id DESC", array($user_id));
        while ($row = pg_fetch_assoc($leave_result)) {
          echo "<tr><td>{$row['start_date']}</td><td>{$row['end_date']}</td><td>{$row['status']}</td><td>";
          if ($row['status'] === 'pending') {
            echo "<a href='cancel_leave.php?id={$row['id']}' class='btn btn-warning btn-sm hover-btn cancel-btn' data-url='cancel_leave.php?id={$row['id']}'>Cancel</a>";
          } else {
            echo "-";
          }
          echo "</td></tr>";
        }
        ?>
      </tbody>
    </table>

    <h5><i class="bi bi-clock-history"></i> My Overtime Requests</h5>
    <table class="table table-bordered">
      <thead>
        <tr><th>Date</th><th>Hours</th><th>Status</th><th>Action</th></tr>
      </thead>
      <tbody>
        <?php
        $ot_result = pg_query_params($conn, "SELECT id, overtime_date, hours, status FROM overtime_requests WHERE user_id = $1 ORDER BY id DESC", array($user_id));
        while ($row = pg_fetch_assoc($ot_result)) {
          echo "<tr><td>{$row['overtime_date']}</td><td>{$row['hours']}</td><td>{$row['status']}</td><td>";
          if ($row['status'] === 'pending') {
            echo "<a href='cancel_overtime.php?id={$row['id']}' class='btn btn-warning btn-sm hover-btn cancel-btn' data-url='cancel_overtime.php?id={$row['id']}'>Cancel</a>";
          } else {
            echo "-";
          }
          echo "</td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Action</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modalMessage">
          Are you sure you want to submit this request?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirmSubmit">Yes</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let currentForm = null;
    let redirectUrl = null;

    // Handle submit buttons
    document.querySelectorAll('button[data-bs-toggle="modal"]').forEach(button => {
      button.addEventListener('click', () => {
        currentForm = button.closest('form');
        redirectUrl = null;
        document.getElementById('modalMessage').innerText = 'Are you sure you want to submit this request?';
      });
    });

    // Handle cancel buttons
    document.querySelectorAll('.cancel-btn').forEach(button => {
      button.addEventListener('click', function(e) {
        e.preventDefault();
        currentForm = null;
        redirectUrl = button.getAttribute('data-url');
        document.getElementById('modalMessage').innerText = 'Are you sure you want to cancel this request?';
        new bootstrap.Modal(document.getElementById('confirmationModal')).show();
      });
    });

    document.getElementById('confirmSubmit').addEventListener('click', () => {
      if (currentForm) {
        currentForm.submit();
      } else if (redirectUrl) {
        window.location.href = redirectUrl;
      }
    });
  </script>
</body>
</html>
