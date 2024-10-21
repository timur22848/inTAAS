<?php
$mysqli = new mysqli("localhost", "root", "", "mukhametkulov_intas");
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

function generateRandomDate($startDate, $endDate) {
    $startTimestamp = strtotime($startDate);
    $endTimestamp = strtotime($endDate);
    $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);

    return date("Y-m-d", $randomTimestamp);
}

function fillTrips($mysqli) {
    $couriers = range(22, 31);
    $regions = range(22, 31);
    $tripCount = 100;
    for ($i = 0; $i < $tripCount; $i++) {
        $courierId = $couriers[array_rand($couriers)];
        $regionId = $regions[array_rand($regions)];
        $departureDate = generateRandomDate('2023-01-01', '2023-12-31');
        $arrivalDate = date("Y-m-d", strtotime($departureDate . ' + ' . rand(1, 3) . ' days'));

        $stmt = $mysqli->prepare("INSERT INTO trip (courier_id, region_id, departure_date, arrival_date) VALUES (?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("iiss", $courierId, $regionId, $departureDate, $arrivalDate);
            $stmt->execute();
            $stmt->close();
        }
    }
}


fillCouriers($mysqli);
fillRegions($mysqli);
fillTrips($mysqli);

$mysqli->close();