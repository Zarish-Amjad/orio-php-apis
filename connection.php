<?php
echo "test";
$mysqli = mysqli_connect("localhost","root","@Amcoitsystems1234@","orioApi","3306");

// Check connection
if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

?>
