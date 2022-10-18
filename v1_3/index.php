<?php
    include("connect.php")
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

    <header></header>
    
    <main>
        <!-- Создание формы с параметрами для выбора из базы данных (опции для выбора создаются на основе результата SQL запроса)-->
        <div class="queryForm">

            <form action="report.php" method="get" enctype="multipart/form-data">
                
                <?php

                $month = !empty($_GET['month']) ? $_GET['month'] : 'all';
                $year = !empty($_GET['year']) ? $_GET['year'] : 'all';
                
                $result = mysqli_query($connect, "SELECT DISTINCT DATE_FORMAT(дата_получения, '%M') as дата_получения FROM Получение" );
                echo "<select name = 'month'>";
                
                if ($month != 'all') {
                    echo "<option value='$month' selected>$month</option>";
                    echo "<option value = 'all'> Весь год </option>";    
                }
                else echo "<option value = 'all' selected> Весь год </option>";

                while ($row=mysqli_fetch_array($result)) {
                    $resultDate = $row[дата_получения];
                    if ($resultDate != $month) {
                        echo "<option value = $resultDate> $resultDate </option>";
                    }
                }
                echo "</select>";

                $result = mysqli_query($connect, "SELECT DISTINCT DATE_FORMAT(дата_получения, '%Y') as дата_получения FROM Получение" );
                echo "<select name = 'year'>";

                if ($year != 'all') {
                    echo "<option value='$year' selected>$year</option>";
                    echo "<option value = 'all'> Всё время </option>";    
                }
                else echo "<option value = 'all' selected> Всё время </option>";

                while ($row=mysqli_fetch_array($result)) {
                    $resultDate = $row[дата_получения];
                    if ($resultDate != $year) {
                        echo "<option value = $resultDate> $resultDate </option>";
                    }
                }
                echo "</select>";

                echo "<input type='submit'>"
                
                ?>

            </form>
        </div>

        
    </main>    

    <footer></footer>

</body>
</html>
