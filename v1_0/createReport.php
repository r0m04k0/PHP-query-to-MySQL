
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

<div class="report">

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

# Достаю данные из GET-запроса формы в переменные
$department = !empty($_GET['department']) ? $_GET['department'] : 'all';
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

# Объявление переменной с заголовком таблицы
if ($department == 'all') {
    $title = "Отчет о получении спецодежды по заводу ";
}
else $title = "Отчет о получении спецодежды по цеху ";


# Условия для дополнения переменной с sql запросом и заголовка, в зависимости от значения переменных GET-запроса
if ($month == 'all' and $year == 'all') {
    $title .= "за всё время.";
}

if ($month != 'all') {
    $query .= " AND DATE_FORMAT(Получение.дата_получения, '%M') = '$month' ";
    $title .= "месяца $month ";
}

if ($year != 'all') {
    $query .= " AND YEAR(Получение.дата_получения) = '$year' ";
    $title .= "за $year год. ";
}

# Вывод заголовка таблицы
echo "<p class='title'> $title </p>";

# Если выбраны все цехи
if ($department == 'all') {
    
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

        # Проверка на пустой результат (не работает)
        // $row=mysqli_fetch_array($result);
        // if ($row == 0) { 
        //     echo '<script> 
        //     alert("Запрос с вашими параметрами вернул пустой результат") 
        //     document.location.href = "index.php"
        //     </script>'; 
        //     exit();
        // }

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

            echo "<tr><td>$param1</td><td>$param2</td><td>$param3</td><td>$param4</td><td>$param5</td></tr>";
        }

        echo '</table><br>';
        
        # Вывод дополнительной информации 
        echo "<p>Цех: $dep_name <p>";
        echo "<p>Итого по цеху: $sum <p>";

        $allsum += $sum;

    }

    echo "<hr style='border-top: dotted 3px;'/>";
    echo "<p style='margin-bottom: 10px'>Итого по заводу: $allsum <p>";

}

# Если выбран только один цех (Почти то же самое)
else {

    # Обнуляю переменную для подсчёта итога по цеху
    $sum = 0;

    # Вывожу заголовок
    echo $head;

    # Добавляю к запросу условие выбора цеха
    $query .= " AND LOWER(Цехи.наименование_цеха) LIKE '$department%' ;";
    
    # Результат запроса в базу данных сохраняю в массив
    $result = mysqli_query($connect, $query);

    # Проверка на ошибки в запросе
    if (!$result) {
        printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
        exit();
    }
    
    # Проверка на пустой результат (не работает)
    // $row=mysqli_fetch_array($result);
    // if ($row == 0) { 
    //     echo '<script> 
    //     alert("Запрос с вашими параметрами вернул пустой результат") 
    //     document.location.href = "index.php"
    //     </script>'; 
    //     exit();
    // }

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
        $dep_name = $row['наименование_цеха'];

        echo "<tr><td>$param1</td><td>$param2</td><td>$param3</td><td>$param4</td><td>$param5</td></tr>";

    }

    echo '</table>';

    # Вывод дополнительной информации 
    echo "<p>Цех: $dep_name <p>";
    echo "<p>Итого по цеху: $sum <p>";

}

?>

</div>

<?php
# Вывод кнопки перехода к форме
echo " <form action='index.php' class='backform'>
    <input type='submit' value='Вернуться к форме' />
</form>";
?>

</body>