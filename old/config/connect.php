<?php
$con = new mysqli("localhost","base_admin","bagja123","base_to");

// Check connection
if ($con -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}
?>