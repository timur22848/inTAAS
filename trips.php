<?php


$mysqli = new mysqli("localhost", "root", "", "mukhametkulov_intas");
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$query = "
    SELECT 
        t.id AS trip_id,
        c.name AS courier_name,
        r.name AS region_name,
        t.departure_date AS departure_date,
        t.arrival_date AS arrival_date
    FROM trip t
    JOIN couriers c ON t.courier_id = c.id
    JOIN regions r ON t.region_id = r.id
";
if (!empty($startDate) && !empty($endDate)) {
    $startDateObj = DateTime::createFromFormat('Y-m-d', $startDate);
    $endDateObj = DateTime::createFromFormat('Y-m-d', $endDate);
    if ($startDateObj && $endDateObj) {
        if ($startDateObj <= $endDateObj) {
            $query .= " WHERE t.arrival_date BETWEEN '{$startDate}' AND '{$endDate}'";
        } else {
            echo "<script>alert('Начальная дата не может быть позже конечной.');</script>";
        }
    } else {
        echo "<script>alert('Некорректный формат даты. Пожалуйста, используйте формат ГГГГ-ММ-ДД.');</script>";
    }
}
$result = $mysqli->query($query);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все поездки</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1>Список поездок</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#filterModal">Фильтровать по дате прибытия</button>
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">Фильтрация по дате прибытия</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="get">
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Начальная дата:</label>
                            <input type="date" id="start_date" name="start_date" class="form-control" value="<?= htmlspecialchars($startDate) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">Конечная дата:</label>
                            <input type="date" id="end_date" name="end_date" class="form-control" value="<?= htmlspecialchars($endDate) ?>" required>
                        </div>
                        <button type="submit" class="btn btn-success">Применить</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php if ($result->num_rows > 0): ?>
    <table class="table table-striped table-bordered mt-4">
        <thead class="table-light">
        <tr>
            <th>ID Поездки</th>
            <th>Имя курьера</th>
            <th>Регион</th>
            <th>Дата отправления</th>
            <th>Дата прибытия</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['trip_id']) ?></td>
                <td><?= htmlspecialchars($row['courier_name']) ?></td>
                <td><?= htmlspecialchars($row['region_name']) ?></td>
                <td><?= htmlspecialchars($row['departure_date']) ?></td>
                <td><?= htmlspecialchars($row['arrival_date']) ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-warning mt-4">Нет доступных поездок по заданным критериям.</div>
    <?php endif; ?>
    <?php $mysqli->close(); ?>
</div>
</body>
</html>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>