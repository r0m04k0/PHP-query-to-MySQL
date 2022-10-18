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

    <header></header>
    
    <main>
        <!-- Создание формы с параметрами для выбора из базы данных (опции для выбора создаются на основе результата SQL запроса)-->
        <div class="queryForm">

            <form action="" method="get" enctype="multipart/form-data">

                <?php

                $month = !empty($_GET['month']) ? $_GET['month'] : 'all';
                $year = !empty($_GET['year']) ? $_GET['year'] : 'all';

                echo "<p class='title'>Отчет о получении спецодежды по заводу ";
                $result = mysqli_query($connect, "SELECT DISTINCT DATE_FORMAT(дата_получения, '%M') as дата_получения FROM Получение" );
                echo "<select name = 'month' onchange='this.form.submit()'>";
                
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
                
                if ($month != 'all') {
                    echo " месяцa ";    
                }

                $result = mysqli_query($connect, "SELECT DISTINCT DATE_FORMAT(дата_получения, '%Y') as дата_получения FROM Получение" );
                echo "<select name = 'year' onchange='this.form.submit()'>";

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
                
                if ($year != 'all') {
                    echo " года</p>";
                }
                ?>

            </form>
        </div>

        <div class="report">

            <?php

            # Достаю данные из GET-запроса формы в переменные
            $month = !empty($_GET['month']) ? $_GET['month'] : 'all';
            $year = !empty($_GET['year']) ? $_GET['year'] : 'all';

            # Объявление переменной с неполным sql запросом
            $query = "SELECT Работники.фио_работника AS фио_работника, 
            Спецодежда.вид_спецодежды AS вид_спецодежды, 
            Спецодежда.стоимость AS стоимость, 
            Работники.скидка AS скидка, 
            Цехи.наименование_цеха AS наименование_цеха,
            (Спецодежда.стоимость-(Спецодежда.стоимость*Работники.скидка/100)) AS стоимость_со_скидкой
            FROM Работники, Цехи, Спецодежда, Получение 
            WHERE Работники.id = Получение.id_работника AND 
            Работники.id_цеха = Цехи.id AND 
            Спецодежда.id = Получение.id_спецодежды ";

            # Объявление переменной с шапкой таблицы
            $head = "<table border='1'> <tr> 
            <th> Ф.И.О. работника </th>
            <th> Вид спецодежды </th>
            <th> Стоимость единицы, руб. </th>
            <th> Скидка, % </th>
            <th> Стоимость с учётом скидки, руб. </th>
            </tr>";

            if ($month != 'all') {
                $query .= " AND DATE_FORMAT(Получение.дата_получения, '%M') = '$month' ";
            }

            if ($year != 'all') {
                $query .= " AND YEAR(Получение.дата_получения) = '$year' ";
            }
            
            # Считаю количество цехов
            $query_for_count = "SELECT COUNT(Цехи.id) FROM Цехи;";
            $result = mysqli_query($connect, $query_for_count);
            $row=mysqli_fetch_array($result);
            $count_of_departments = $row[0];

            # Обнуляю переменную для подсчёта итога по заводу
            $allsum = 0;

            # Создаю отчёт отдельно для каждого цеха
            for ($i = 1; $i <= $count_of_departments; $i++) {

                # Обнуляю переменную для подсчёта итога по цеху
                $sum = 0;

                # Вывожу заголовок
                echo $head;

                # Добавляю к запросу условие выбора цеха
                $new_query = $query . " AND Цехи.id = '$i+1' ;";

                # Результат запроса в базу данных сохраняю в массив
                $result = mysqli_query($connect, $new_query);
                
                # Проверка результата запроса на ошибки
                if (!$result) {
                    printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
                    exit();
                }

                # Заполнения таблицы полями из запроса
                while ($row=mysqli_fetch_array($result)) 
                {
                    $param1 = $row['фио_работника'];
                    $param2 = $row['вид_спецодежды'];
                    $param3 = $row['стоимость'];
                    $param4 = $row['скидка'];
                    $param5 = $row['стоимость_со_скидкой'];

                    # Подсчёт суммы по цеху
                    $sum += $row['стоимость_со_скидкой'];
                    # Сохраняю наименование цеха
                    $dep_name = $row['наименование_цеха'];

                    # Вывод таблицы со значениями из текущей записи
                    echo "<tr><td>$param1</td><td>$param2</td><td>$param3</td><td>$param4</td><td>$param5</td></tr>";
                }

                echo '</table><br>';
                
                # Вывод дополнительной информации 
                echo "<p class='depName'>Цех: $dep_name</p>";
                echo "<div class='line'><p class='sum'>Итого по цеху:</p><p class='inner'>$sum</p></div>";

                $allsum += $sum;

            }

            echo "<hr style='border-top: dotted 3px;'/>";
            echo "<div class='line'><p class='allsum'>Итого по заводу:</p><p class='inner'>$allsum</p></div>";

            ?>

            </div>

    </main>    

    <footer></footer>

</body>
</html>
