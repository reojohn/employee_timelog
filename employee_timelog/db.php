<?php
$conn = pg_connect("host=localhost dbname=timelog user=postgres password=admin1234");
if (!$conn) {
  die("Connection failed: " . pg_last_error());
}
?>





