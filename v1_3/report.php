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


        <div class="report">

            <?php

            # Достаю данные из GET-запроса формы в переменные
            $month = !empty($_GET['month']) ? $_GET['month'] : 'all';
            $year = !empty($_GET['year']) ? $_GET['year'] : 'all';

            
            $title = "<p class='title'>Отчет о получении спецодежды по заводу ";

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

            # Добавление условий SQL-запроса в зависимости от значения переменных GET-запроса
            if ($month != 'all') {
                $query .= " AND DATE_FORMAT(Получение.дата_получения, '%M') = '$month' ";
                $title .= " $month "; 
            }
            else $title .= " весь год ";
            if ($year != 'all') {
                $query .= " AND YEAR(Получение.дата_получения) = '$year' ";
                $title .= " $year года ";
            }
            else $title .= " всё время ";
            $query .= " ORDER BY наименование_цеха ASC ;";

            echo $title;
            # Обнуляю переменную для подсчёта итога по заводу
            $allsum = 0;
        
            # Обнуляю переменную для подсчёта итога по цеху
            $sum = 0;

            # Результат запроса в базу данных сохраняю в массив
            $result = mysqli_query($connect, $query);
            
            # Проверка результата запроса на ошибки
            if (!$result) {
                printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
                exit();
            }
            
            # Создание массива для получения очерёдности цехов
            $i = 0;
            while ($row=mysqli_fetch_array($result)) {   
                $dep_names[$i] = $row['наименование_цеха'];
                $i++;
            }
            
            # Результат запроса в базу данных сохраняю в массив
            $result = mysqli_query($connect, $query);

            $row=mysqli_fetch_array($result);
        if ($row == 0) { 
            echo '<script> 
            alert("Запрос с вашими параметрами вернул пустой результат") 
            document.location.href = "index.php"
            </script>'; 
            exit();
        }
        $result = mysqli_query($connect, $query);

            $i = 0;
            # Вывожу заголовок таблицы
            echo $head;
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
                    
                # Вывод дополнительной информации при переходе на следующий цех
                if ($dep_names[$i+1] != $dep_name) {
                    echo '</table><br>';
                    echo "<p class='depName'>Цех: $dep_name</p>";
                    echo "<div class='line'><p class='sum'>Итого по цеху:</p><p class='inner'>$sum</p></div>";
                    $allsum += $sum;
                    $sum = 0;
                    # Если не последний
                    if (end($dep_names) != $dep_name) {
                        # Вывожу заголовок таблицы
                        echo $head;
                    }
                }
                $i++;
            }

            echo "<hr style='border-top: dotted 3px;'/>";
            echo "<div class='line'><p class='allsum'>Итого по заводу:</p><p class='inner'>$allsum</p></div>";

            ?>

            </div>

    </main>    

    <footer></footer>

</body>
</html>
