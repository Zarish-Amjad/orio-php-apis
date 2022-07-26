<?php include 'connection.php';

$sql = "SELECT Country_name FROM Country";
$result = $mysqli -> query($sql);
//$myJSON = json_encode($result->fetch_assoc());
//echo $myJSON;
// Numeric array
if ($result->num_rows > 0) {
//     // output data of each row
        $index =0;
    while($row = $result->fetch_assoc()) {
        $country[$index] = $row['Country_name'];
        $index++;
    }
    $myJSON = json_encode($country);
    echo $myJSON;
  } else {
    echo "0 results";
  }

  $mysqli->close();

?>