<?php
// set_admin_key.php — run once, then delete
require_once 'db.php';

$adminUsername = 'admin1'; // change if needed
$newKey = 'T1m3l0g-Adm1n-9X7Q!'; // your chosen admin key

$hash = password_hash($newKey, PASSWORD_BCRYPT);

$res = pg_query_params(
  $conn,
  "UPDATE users SET second_factor_hash = $1 WHERE username = $2",
  [$hash, $adminUsername]
);

if ($res && pg_affected_rows($res) === 1) {
  echo "Admin key set for user '{$adminUsername}'. You can delete this file now.";
} else {
  echo "Failed to set admin key. Check the username or DB connection.";
}
