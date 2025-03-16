<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wetter</title>
    <link rel="stylesheet" href="{{ asset('wetter.css') }}">
</head>
<body>
    <form action="" method="get">
        <input type="text" name="miasto" placeholder="Podaj miasto">
        <input type="submit" value="Sprawdź pogodę">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['miasto'])) {
        $miasto = htmlspecialchars($_GET['miasto']) ?? 'Rzeszow'; 
        $key = "d059278b3bcbd8199a39b09baad5b520";

        $url = "http://api.openweathermap.org/data/2.5/weather?q=$miasto&appid=$key&units=metric&lang=pl";
        $dane_json = file_get_contents($url);
        $daned = json_decode($dane_json, true);

        $kodKraju = $daned['sys']['country'];
        $flagaUrl = "https://flagsapi.com/{$kodKraju}/shiny/64.png";  
        echo "<h2>Pogoda dla: {$miasto} :) <img id=\"kraj\" src='{$flagaUrl}' alt='{$kodKraju}' style='height: 30px; width: 40px;'></h2><br>";


        if (isset($daned['cod']) && $daned['cod'] != 200) {
            echo "<p>Błąd: " . htmlspecialchars($daned['message']) . "</p>";
        } else {   
            echo'<p>'.'Dzisiaj'.'</p>';
            echo '<div>';
            $kodKraju = $daned['sys']['country'];
            $flagaUrl = "https://flagsapi.com/{$kodKraju}/shiny/64.png";  
            $ikona = "http://openweathermap.org/img/wn/" . $daned['weather'][0]['icon'] . ".png";
            $timezoneOffset = $daned['timezone'];  
            $localTime = time() + $timezoneOffset;
            $localTimeFormatted = date('Y-m-d H:i', $localTime); 
            
            echo "<h3>Data i godzina:</h3> {$localTimeFormatted}";
            echo "<h3>Temperatura:</h3> {$daned['main']['temp']}°C"; 
            echo "<h3>Zachmurzenie:</h3> {$daned['clouds']['all']}%"; 
            echo "<h3>Widoczność:</h3> " . ($daned['visibility'] ?? 'Brak danych') . " m"; 
            echo "<h3>Prędkość wiatru:</h3> {$daned['wind']['speed']} m/s"; 
            echo "<h3><img src='{$ikona}' alt='Pogoda'></h3>";
            echo '</div>';

            

        }
    }
        
    ?>




<?php

$miasto = $_GET['miasto'] ?? 'Rzeszow'; 

if (!isset($_GET['miasto'])) {
    echo '<h2>Prognoza dla miasta Rzeszów</h2><br>';
}

$key = "d059278b3bcbd8199a39b09baad5b520";
$url = "http://api.openweathermap.org/data/2.5/forecast?q={$miasto}&appid={$key}&units=metric&lang=pl";
$dane_json = file_get_contents($url);
$danen = json_decode($dane_json, true);

if (isset($danen['cod']) && $danen['cod'] != 200) {
    echo "<p>Błąd: " . htmlspecialchars($danen['message']) . "</p>";
} else {
    $dailyData = [];
    $today = date("Y-m-d"); // Dzisiejsza data

    foreach ($danen['list'] as $forecast) {
        $date = date("Y-m-d", $forecast['dt']);
        
        // Pomijamy dzisiejszą prognozę, zaczynamy od jutra
        if ($date <= $today) {
            continue;
        }

        if (!isset($dailyData[$date])) {
            $dailyData[$date] = [
                'temperature_min' => $forecast['main']['temp_min'],
                'temperature_max' => $forecast['main']['temp_max'],
                'cloudiness' => $forecast['clouds']['all'],
                'visibility' => isset($forecast['visibility']) ? $forecast['visibility'] : 'Brak danych',
                'wind_speed' => $forecast['wind']['speed'],
                'weather_icons' => [],
            ];
        } else {
            $dailyData[$date]['temperature_min'] = min($dailyData[$date]['temperature_min'], $forecast['main']['temp_min']);
            $dailyData[$date]['temperature_max'] = max($dailyData[$date]['temperature_max'], $forecast['main']['temp_max']);
        }

        $weatherIconCode = $forecast['weather'][0]['icon'];
        if (!in_array($weatherIconCode, $dailyData[$date]['weather_icons'])) {
            $dailyData[$date]['weather_icons'][] = $weatherIconCode;
        }
    }

    foreach ($dailyData as $date => $data) {
        $weatherIconCode = $data['weather_icons'][0];
        $weatherIconUrl = "http://openweathermap.org/img/wn/{$weatherIconCode}@2x.png";  

        echo "<div>";
        echo "<h3>Data: {$date}</h3>";
        echo "<h3>Temperatura minimalna: {$data['temperature_min']}°C</h3>";
        echo "<h3>Temperatura maksymalna: {$data['temperature_max']}°C</h3>";
        echo "<h3>Zachmurzenie: {$data['cloudiness']}%</h3>";
        echo "<h3>Widoczność: {$data['visibility']} m</h3>";
        echo "<h3>Prędkość wiatru: {$data['wind_speed']} m/s</h3>";
        echo "<h3><img src='{$weatherIconUrl}' alt='Pogoda'></h3>";
        echo "</div>";
    }
}

?>




<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<footer">Aby sprawdzic pogode.wpisz miasto</footer>
</body>
</html>
