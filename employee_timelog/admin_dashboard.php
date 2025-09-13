<?php
session_start();
include('db.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
  header('Location: index.html');
  exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #e0f7fa, #ffffff);
      color: #333;
    }
    .btn:hover {
      transform: scale(1.03);
      transition: 0.2s ease;
    }
    tr:hover {
      background-color: #f1f1f1;
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .flash-card {
      transition: box-shadow 0.3s, transform 0.3s;
    }
    .flash-card:hover {
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
      transform: translateY(-5px);
    }
  </style>
</head>
<body>
  <div class="container mt-4">
    <h3>Welcome, Admin!</h3>
    <a href="logout.php" class="btn btn-danger btn-sm float-end">Logout</a>
    <div class="row justify-content-center mb-4">
      <div class="col-md-3">
        <div class="card flash-card p-3 text-center">
          <h6>Total Leave Requests</h6>
          <h4>
            <?php
              $count = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM leave_requests"), 0, 0);
              echo $count;
            ?>
          </h4>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card flash-card p-3 text-center">
          <h6>Total Overtime Requests</h6>
          <h4>
            <?php
              $count = pg_fetch_result(pg_query($conn, "SELECT COUNT(*) FROM overtime_requests"), 0, 0);
              echo $count;
            ?>
          </h4>
        </div>
      </div>
    </div>
    <hr>
    <h5><i class="bi bi-calendar-check"></i> Leave Requests</h5>
    <table class="table table-bordered">
      <thead>
        <tr><th>User ID</th><th>Start</th><th>End</th><th>Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php
        $leave_result = pg_query($conn, "SELECT * FROM leave_requests ORDER BY id DESC");
        while ($row = pg_fetch_assoc($leave_result)) {
          echo "<tr><td>{$row['user_id']}</td><td>{$row['start_date']}</td><td>{$row['end_date']}</td><td>{$row['status']}</td>
          <td>
            <button class='btn btn-success btn-sm confirm-btn' data-url='approve_leave.php?id={$row['id']}'>Approve</button>
            <button class='btn btn-warning btn-sm confirm-btn' data-url='reject_leave.php?id={$row['id']}'>Reject</button>
            <button class='btn btn-danger btn-sm confirm-btn' data-url='delete_leave.php?id={$row['id']}'>Delete</button>
          </td></tr>";
        }
        ?>
      </tbody>
    </table>

    <h5><i class="bi bi-clock"></i> Overtime Requests</h5>
    <table class="table table-bordered">
      <thead>
        <tr><th>User ID</th><th>Date</th><th>Hours</th><th>Status</th><th>Actions</th></tr>
      </thead>
      <tbody>
        <?php
        $overtime_result = pg_query($conn, "SELECT * FROM overtime_requests ORDER BY id DESC");
        while ($row = pg_fetch_assoc($overtime_result)) {
          echo "<tr><td>{$row['user_id']}</td><td>{$row['overtime_date']}</td><td>{$row['hours']}</td><td>{$row['status']}</td>
          <td>
            <button class='btn btn-success btn-sm confirm-btn' data-url='approve_overtime.php?id={$row['id']}'>Approve</button>
            <button class='btn btn-warning btn-sm confirm-btn' data-url='reject_overtime.php?id={$row['id']}'>Reject</button>
            <button class='btn btn-danger btn-sm confirm-btn' data-url='delete_overtime.php?id={$row['id']}'>Delete</button>
          </td></tr>";
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmModalLabel">Confirm Action</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to proceed with this action?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirmAction">Yes</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let actionUrl = '';
    document.querySelectorAll('.confirm-btn').forEach(button => {
      button.addEventListener('click', () => {
        actionUrl = button.getAttribute('data-url');
        new bootstrap.Modal(document.getElementById('confirmModal')).show();
      });
    });

    document.getElementById('confirmAction').addEventListener('click', () => {
      window.location.href = actionUrl;
    });
  </script>
</body>
</html>
