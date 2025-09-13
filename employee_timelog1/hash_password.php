<?php
$password = "adminsample123";
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed password for 'admin1' is: <br><strong>$hashed</strong>";
?>
