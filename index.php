<?php



?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Расписание поездок</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<h2 class="trips"><a href="trips.php" class="link-dark link-offset-2 link-underline-opacity-10 link-underline-opacity-100-hover">Все поездки</a></h2>
<div class="sqq">
    <form id="tripForm">
        <h1>Добавить поездку</h1>
        <label for="courier">Курьер:</label>
        <select id="courier" name="courier"></select><br>
        <label for="region">Регион:</label>
        <select id="region" name="region"></select><br>
        <label for="departure_date">Дата выезда:</label>
        <input type="date" id="departure_date" name="departure_date"><br>
        <input type="submit" value="Добавить поездку">
    </form>
    <div id="schedule"></div>
</div>
<style>
    a {
        color: black;
    }
    .trips {
        text-decoration: none;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }
    .sqq {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }
    #tripForm {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        width: 300px;
    }
    label {
        display: block;
        margin-bottom: 5px;
    }
    select, input[type="date"], input[type="submit"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type="submit"] {
        background-color: #0374fd;
        color: white;
        border: none;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        transition: all 300ms ease;
        background-color: #5d5af8;
    }
</style>
<script>
    $(document).ready(function() {
        loadCouriers();
        loadRegions();
        $('#tripForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "add_trip.php",
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    alert("Произошла ошибка при добавлении поездки. Пожалуйста, попробуйте еще раз.");
                }
            });
        });
        loadSchedule();
    });

    function loadCouriers() {
        $.ajax({
            url: 'get_couriers.php',
            method: 'GET',
            success: function(data) {
                const couriers = JSON.parse(data);
                $('#courier').empty();
                for (let courier of couriers) {
                    if (courier.active === "1") {
                        $('#courier').append(`<option value="${courier.id}">${courier.name}</option>`);
                    } else {
                        $('#courier').append(`<option disabled>${courier.name} (занят)</option>`);
                    }
                }
            }
        });
    }

    function loadRegions() {
        $.ajax({
            url: 'get_regions.php',
            method: 'GET',
            success: function(data) {
                const regions = JSON.parse(data);
                for (let region of regions) {
                    $('#region').append(`<option value="${region.id}">${region.name}</option>`);
                }
            }
        });
    }

    function loadSchedule() {
        $.ajax({
            url: 'get_schedule.php',
            method: 'GET',
            success: function(data) {
                $('#schedule').html(data);
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN