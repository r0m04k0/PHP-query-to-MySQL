<?php
    include("../connect.php");

    session_start();

    if (!isset($_SESSION['login']) && isset($_COOKIE['login'])) {
        $_SESSION['login'] = $_COOKIE['login'];
    }
    $login = $_SESSION['login'];

    if (!isset($_SESSION['role']) && isset($_COOKIE['role'])) {
        $_SESSION['role'] = $_COOKIE['role'];
    }
    $role = $_SESSION['role'];

    if (!isset($_SESSION['id']) && isset($_COOKIE['id'])) {
        $_SESSION['id'] = $_COOKIE['id'];
    }
    $id = $_SESSION['id'];

    if ($role != 'admin') {
        echo "<p style='margin-top: 10%; text-align:center'>У вас нет доступа к этой странице. Если вы уже зарегистрировались, подождите, 
        пока администратор не даст вам права доступа, если нет, то <a href='../reg.php'>зарегестрируйтесь</a></p>";
        exit();
    }

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
    <title>Лабораторная работа №4</title>
</head>

<body>
    
    <main>

        <aside>

        <div class="logo-container"><img class="logo" src="./files/logo.png" alt=""></div>

            <p class="aside title">Таблицы</p>
            <img class="table-svg" src='files/table.svg' alt="">
            <a href="index.php?table=receiving">Получение</a><br>
            <img class="table-svg" src='files/table.svg' alt="">
            <a href="index.php?table=overalls">Спецодежда</a><br>
            <img class="table-svg" src='files/table.svg' alt="">
            <a href="index.php?table=workshops">Цехи</a><br>
            <img class="table-svg" src='files/table.svg' alt="">
            <a href="index.php?table=workers">Работники</a><br><br>
            <img class="table-svg" src='files/table.svg' alt="">
            <a href="index.php?table=users">Модерация пользователей</a><br><br>
            <img class="table-svg" src='files/table.svg' alt="">
            <a href="../report/index.php">Формирование ведомости</a><br>

            <form style='position: absolute; bottom: 50px; left: 70px' action='../index.php'>
                <input class='smthbtn' type='submit' value='На главную'>
            </form>

        </aside>

        <section>

        <header class="title">База данных "Спецодежда"</header>

            <?php
            $rowID = !empty($_POST['rowID']) ? $_POST['rowID'] : 'none';
            $table = !empty($_POST['table']) ? $_POST['table'] : 'none';

            if ($rowID != 'none' && $table != 'none') {

                if ($table == "receiving") { 
                    $query = "DELETE FROM Получение
                                WHERE Получение.id = $rowID;";
                }

                elseif ($table == "overalls") { 
                    $query = "DELETE FROM Спецодежда
                                WHERE Спецодежда.id = $rowID;";
                }

                elseif ($table == "workshops") { 
                    $query = "DELETE FROM Цехи
                                WHERE Цехи.id = $rowID;";
                }

                elseif ($table == "workers") { 
                    $query = "DELETE FROM Работники
                                WHERE Работники.id = $rowID;";
                }

                elseif ($table == "users") { 
                    $query = "DELETE FROM users
                                WHERE users.id = $rowID;";
                }

                $result = mysqli_query($connect, $query);

                // $new_url = 'index.php';
                // header('Location: '.$new_url);

            }
            ?>

            <?php

                if ($_GET["table"] == "receiving") {
                    
                    $query = "SELECT Работники.фио_работника AS фио_работника, 
                    Спецодежда.вид_спецодежды AS вид_спецодежды, 
                    Получение.дата_получения AS дата_получения, 
                    Получение.роспись AS роспись,
                    Получение.id AS id 
                    FROM Работники, Спецодежда, Получение 
                    WHERE Работники.id = Получение.id_работника AND  
                    Спецодежда.id = Получение.id_спецодежды  ORDER BY дата_получения ASC ;";

                    $head = "<table border='1'> <tr> 
                    <th> Ф.И.О. работника </th>
                    <th> Вид спецодежды </th>
                    <th> Дата получения </th>
                    <th> Роспись </th>
                    <th> Изменить </th>
                    <th> Удалить </th>
                    </tr>";

                    $result = mysqli_query($connect, $query);
                    
                    # Проверка результата запроса на ошибки
                    if (!$result) {
                        printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
                        exit();
                    }

                    echo $head;
                    
                    while ($row=mysqli_fetch_array($result)) {
                        
                        $param1 = $row['фио_работника'];
                        $param2 = $row['вид_спецодежды'];
                        $param3 = $row['дата_получения'];
                        $param4 = $row['роспись'];

                        if ($param4 == 1) {
                            $param4 = "Есть";
                        }
                        else $param4 = "Нет";

                        $rowID = $row['id'];
                        
                        $edit = "<form action='edit.php' method='post' enctype='multipart/form-data' style='text-align: center'>
                                    <input class='placeholder' type='text' name='table' value='receiving'/>
                                    <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                    <input class='imageButton' type='image' src='files/edit.svg'/>
                                </form>";

                        $delete = "<form action='' method='post' enctype='multipart/form-data' style='text-align: center' onsubmit='return confirm();'>
                                        <input class='placeholder' type='text' name='table' value='receiving'/>
                                        <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                        <input class='imageButton' type='image' src='files/delete.svg'/>
                                    </form>";

                        echo "<td>$param1</td> <td>$param2</td> <td>$param3</td> <td>$param4</td> <td>$edit</td> <td>$delete</td> </tr>";

                    }

                    viewButtons("receiving");

                }
                
                elseif ($_GET["table"] == "overalls") {

                    $query = "SELECT Спецодежда.вид_спецодежды AS вид_спецодежды, 
                    Спецодежда.срок_носки AS срок_носки, 
                    Спецодежда.стоимость AS стоимость,
                    Спецодежда.id AS id 
                    FROM Спецодежда ;";

                    $head = "<table border='1'> <tr> 
                    <th> Вид спецодежды </th>
                    <th> Срок ношения спецодежды</th>
                    <th> Стоимость единицы </th>
                    <th> Изменить </th>
                    <th> Удалить </th>
                    </tr>";

                    $result = mysqli_query($connect, $query);
                    
                    # Проверка результата запроса на ошибки
                    if (!$result) {
                        printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
                        exit();
                    }

                    echo $head;
                    
                    while ($row=mysqli_fetch_array($result)) {
                        
                        $param1 = $row['вид_спецодежды'];
                        $param2 = $row['срок_носки'] . " месяцев";
                        $param3 = $row['стоимость'] . " рублей";

                        $rowID = $row['id'];
                        
                        $edit = "<form action='edit.php' method='post' enctype='multipart/form-data' style='text-align: center'>
                                    <input class='placeholder' type='text' name='table' value='overalls'/>
                                    <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                    <input class='imageButton' type='image' src='files/edit.svg'/>
                                </form>";

                        $delete = "<form action='' method='post' enctype='multipart/form-data' style='text-align: center' onsubmit='return confirm();'>
                                        <input class='placeholder' type='text' name='table' value='overalls'/>
                                        <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                        <input class='imageButton' type='image' src='files/delete.svg'/>
                                    </form>";

                        echo "<td>$param1</td> <td>$param2</td> <td>$param3</td> <td>$edit</td> <td>$delete</td> </tr>";

                    }

                    viewButtons("overalls");
                    
                }

                elseif ($_GET["table"] == "workshops") {

                    $query = "SELECT Цехи.наименование_цеха AS наименование_цеха, 
                    Работники.фио_работника AS начальник_цеха, 
                    Цехи.id AS id 
                    FROM Цехи, Работники
                    WHERE Цехи.начальник_цеха = Работники.id;";

                    $head = "<table border='1'> <tr> 
                    <th> Цех </th>
                    <th> Начальник цеха </th>
                    <th> Изменить </th>
                    <th> Удалить </th>
                    </tr>";

                    $result = mysqli_query($connect, $query);
                    
                    # Проверка результата запроса на ошибки
                    if (!$result) {
                        printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
                        exit();
                    }

                    echo $head;
                    
                    while ($row=mysqli_fetch_array($result)) {
                        
                        $param1 = $row['наименование_цеха'];
                        $param2 = $row['начальник_цеха'];

                        $rowID = $row['id'];
                        
                        $edit = "<form action='edit.php' method='post' enctype='multipart/form-data' style='text-align: center'>
                                    <input class='placeholder' type='text' name='table' value='workshops'/>
                                    <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                    <input class='imageButton' type='image' src='files/edit.svg'/>
                                </form>";

                        $delete = "<form action='' method='post' enctype='multipart/form-data' style='text-align: center' onsubmit='return confirm();'>
                                        <input class='placeholder' type='text' name='table' value='workshops'/>
                                        <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                        <input class='imageButton' type='image' src='files/delete.svg'/>
                                    </form>";

                        echo "<td>$param1</td> <td>$param2</td> <td>$edit</td> <td>$delete</td> </tr>";

                    }
                    
                    viewButtons("workshops");

                }

                elseif ($_GET["table"] == "workers") {

                    
                    $query = "SELECT Работники.фио_работника AS фио_работника, 
                    Работники.должность AS должность,
                    Цехи.наименование_цеха AS наименование_цеха,
                    Работники.скидка AS скидка, 
                    Работники.id AS id
                    FROM Работники, Цехи 
                    WHERE Работники.id_цеха = Цехи.id 
                    ORDER BY фио_работника ASC ;";

                    # Объявление переменной с шапкой таблицы
                    $head = "<table border='1'> <tr> 
                    <th> Ф.И.О. работника </th>
                    <th> Должность </th>
                    <th> Цех </th>
                    <th> Скидка </th>
                    <th> Изменить </th>
                    <th> Удалить </th>
                    </tr>";

                    $result = mysqli_query($connect, $query);
                    
                    # Проверка результата запроса на ошибки
                    if (!$result) {
                        printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
                        exit();
                    }

                    echo $head;
                    
                    while ($row=mysqli_fetch_array($result)) {
                        
                        $param1 = $row['фио_работника'];
                        $param2 = $row['должность'];
                        $param3 = $row['наименование_цеха'];
                        $param4 = $row['скидка'] . "%";

                        $rowID = $row['id'];
                        
                        $edit = "<form action='edit.php' method='post' enctype='multipart/form-data' style='text-align: center'>
                                    <input class='placeholder' type='text' name='table' value='workers'/>
                                    <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                    <input class='imageButton' type='image' src='files/edit.svg'/>
                                </form>";

                        $delete = "<form action='' method='post' enctype='multipart/form-data' style='text-align: center' onsubmit='return confirm();'>
                                        <input class='placeholder' type='text' name='table' value='workers'/>
                                        <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                        <input class='imageButton' type='image' src='files/delete.svg'/>
                                    </form>";

                        echo "<td>$param1</td> <td>$param2</td> <td>$param3</td> <td>$param4</td> <td>$edit</td> <td>$delete</td> </tr>";

                        viewButtons("workers");

                    }

                    
                }

                elseif ($_GET["table"] == "users") {

                    
                    $query = "SELECT users.login AS login, 
                    users.lastname AS lastname,
                    users.firstname AS firstname,
                    users.email AS email, 
                    users.role AS role,
                    users.date AS date, 
                    users.id AS id
                    FROM users 
                    ORDER BY date ASC ;";

                    $head = "<table border='1'> <tr> 
                    <th> Логин </th>
                    <th> Имя </th>
                    <th> Фамилия </th>
                    <th> Почта </th>
                    <th> Роль в системе </th>
                    <th> Дата регистрации </th>
                    <th> Изменить </th>
                    <th> Удалить </th>
                    </tr>";

                    $result = mysqli_query($connect, $query);
                    
                    # Проверка результата запроса на ошибки
                    if (!$result) {
                        printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
                        exit();
                    }

                    echo $head;
                    
                    while ($row=mysqli_fetch_array($result)) {
                        
                        $login = $row['login'];
                        $firstname = $row['firstname'];
                        $lastname = $row['lastname'];
                        $email = $row['email'];
                        $role = $row['role'];
                        $date = $row['date'];

                        $rowID = $row['id'];
                        
                        $edit = "<form action='edit.php' method='post' enctype='multipart/form-data' style='text-align: center'>
                                    <input class='placeholder' type='text' name='table' value='users'/>
                                    <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                    <input class='imageButton' type='image' src='files/edit.svg'/>
                                </form>";

                        $delete = "<form action='' method='post' enctype='multipart/form-data' style='text-align: center' onsubmit='return confirm();'>
                                        <input class='placeholder' type='text' name='table' value='users'/>
                                        <input class='placeholder' type='text' name='rowID' value='$rowID'/>
                                        <input class='imageButton' type='image' src='files/delete.svg'/>
                                    </form>";

                        echo "<td>$login</td> <td>$firstname</td> <td>$lastname</td> <td>$email</td> <td>$role</td> <td>$date</td> <td>$edit</td> <td>$delete</td> </tr>";

                        viewButtons("users");

                    }

                    
                }

                function viewButtons($table) {
                    echo "
                    <div class='buttonMenu'>
                    <form style='display:inline-block' action='./index.php'>
                        <input class='smthbtn' type='submit' value='Очистить'>
                    </form>

                    <form style='display:inline-block' method='post' enctype='multipart/form-data' action='create.php' style='margin-top: 20px'>
                        <input class='placeholder' name='table' type='text' value='$table'>
                        <input class='smthbtn' type='submit' value='Добавить новую запись'>
                    </form>
                    </div>
                    ";
                }

            ?>

        </section>

    </main>    

    <footer></footer>

</body>
</html>
