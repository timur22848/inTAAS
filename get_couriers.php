<?php

$mysqli = new mysqli("localhost", "root", "", "mukhametkulov_intas");

$result = $mysqli->query("SELECT * FROM couriers");
$couriers = [];

while ($row = $result->fetch_assoc()) {
    $couriers[] = $row;
}

echo json_encode($couriers);
$mysqli->close();
