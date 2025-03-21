<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurs Walut</title>
</head>
<body>
    <form action="" method="post">
        @csrf
        <input type="text" name="waluta" placeholder="Podaj kod waluty (np. EUR)">
        <input type="submit" value="Pokaż">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $waluta = $_POST['waluta']; 
        $url = "https://api.nbp.pl/api/exchangerates/rates/A/$waluta/?format=json"; 

        $json = file_get_contents($url);

        if ($json) {
            $dane = json_decode($json, true);
            $kurs = $dane['rates'][0]['mid']; 

            echo "<p>Kurs waluty <strong>$waluta</strong>: <strong>$kurs PLN</strong></p>";
        } else {
            echo "<p>Nie znaleziono kursu dla podanego kodu waluty.</p>";
        }
    }
    ?>
</body>
</html>
