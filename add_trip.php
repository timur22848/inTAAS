<?php
$mysqli = new mysqli("localhost", "root", "", "mukhametkulov_intas");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$courier_id = $_POST['courier'];
$region_id = $_POST['region'];
$departure_date = $_POST['departure_date'];


$result = $mysqli->query("SELECT active FROM couriers WHERE id = $courier_id");
$courier_row = $result->fetch_assoc();

if (!$courier_row) {
    echo "Курьер не найден!";
    $mysqli->close();
    exit;
}


$current_date = date('Y-m-d');
$trip_check = $mysqli->query("SELECT arrival_date FROM trip WHERE courier_id = $courier_id AND arrival_date >= '$current_date'");

if ($trip_check && $trip_check->num_rows > 0) {
    echo "Этот курьер сейчас занят и не может быть выбран.";
    $mysqli->close();
    exit;
}


$result = $mysqli->query("SELECT travel_duration FROM regions WHERE id = $region_id");
$row = $result->fetch_assoc();
$travel_duration = $row['travel_duration'];

$arrival_date = date('Y-m-d', strtotime($departure_date . ' + ' . $travel_duration . ' days'));

$insert_result = $mysqli->query("INSERT INTO trip (courier_id, region_id, departure_date, arrival_date) 
                                   VALUES ('$courier_id', '$region_id', '$departure_date', '$arrival_date')");
$cou = $mysqli->query("UPDATE `couriers` SET `active` = '0' WHERE `id` = $courier_id;");

if ($insert_result) {
    echo "поездка успешно добавлена";
} else {
    echo "ошибка при добавлении поездки: " . $mysqli->error;
}

$mysqli->close();
