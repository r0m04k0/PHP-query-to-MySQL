<?php
    # Объявление параметров для подключения к базе данных
    $server = 'localhost'; 
    $username = 'root'; 
    $password = ''; 
    $dbname = 'СпецодеждаDB';
    # Подключение к серверу mysql
    $connect = mysqli_connect($server, $username, $password, $dbname);
    # Выбор базы данных
    mysqli_select_db($connect, $dbname);
    # Установка русской локали для названий дней недели
    mysqli_query($connect, "SET lc_time_names = 'ru_RU';" );
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="icon" href="favicon.ico">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <script type="text/javascript" src="script.js"></script>
    <title>Лабораторная работа №2</title>
</head>


<body>

    <header>
        

    </header>
    
    <main>
    <!-- Включение в структуру страницы блока формы -->
    <? include("queryForm.php"); ?>

    </main>    

    <footer>
    </footer>

</body>
</html>
