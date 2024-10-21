<?php

$mysqli = new mysqli("localhost", "root", "", "mukhametkulov_intas");


if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$result = $mysqli->query("SELECT * FROM regions");
$regions = [];

while ($row = $result->fetch_assoc()) {
    $regions[] = $row;
}


echo json_encode($regions);
$mysqli->close();
?>
