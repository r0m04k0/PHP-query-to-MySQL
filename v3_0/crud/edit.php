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

    <header></header>
    
    <main>
        
        <div class="editForm">

            <form action="" method="post" enctype="multipart/form-data">

                <?php

                    $table = !empty($_POST['table']) ? $_POST['table'] : 'none';
                    $rowID = !empty($_POST['rowID']) ? $_POST['rowID'] : 'none';

                    if ($table == "receiving") {

                        $query = "SELECT Работники.фио_работника AS фио_работника,
                        Спецодежда.вид_спецодежды AS вид_спецодежды,
                        Получение.роспись AS роспись,
                        Получение.дата_получения AS дата_получения
                        FROM Работники, Спецодежда, Получение
                        WHERE Работники.id = Получение.id_работника AND
                        Спецодежда.id = Получение.id_спецодежды AND
                        Получение.id = $rowID;";

                        $result = mysqli_query($connect, $query);
                        $row=mysqli_fetch_array($result);

                        $param1 = $row['фио_работника'];
                        $param2 = $row['вид_спецодежды'];
                        $param3 = $row['роспись'];
                        $param4 = $row['дата_получения'];                        

                        echo "<div class='editString'>Ф.И.О. работника: "; 
                        $result = mysqli_query($connect, "SELECT фио_работника, id FROM Работники" );
                        echo "<select name = 'param1'>";

                        while ($row=mysqli_fetch_array($result)) {
                            $value = $row['фио_работника'];
                            $id = $row['id'];
                            if ($value != $param1) {
                                echo "<option value = $id> $value </option>";
                            }
                            else {echo "<option selected value = $id> $value </option>";}
                        }
                        echo "</select> </div>";


                        echo "<div class='editString'>Вид спецодежды: "; 
                        $result = mysqli_query($connect, "SELECT DISTINCT вид_спецодежды, id FROM Спецодежда" );
                        echo "<select name = 'param2'>";

                        while ($row=mysqli_fetch_array($result)) {
                            $value = $row['вид_спецодежды'];
                            $id = $row['id'];
                            if ($value != $param2) {
                                echo "<option value = $id> $value </option>";
                            }
                            else {echo "<option selected value = $id> $value </option>";}
                        }
                        echo "</select></div>";

                        if ($param3 == 1) {
                            echo "<div class='editString'> <input type='checkbox' checked name='param3' value='1'><label> Роспись </label> </div>";
                        }
                        else {
                            echo "<div class='editString'> <input type='checkbox' name='param3' value='1'><label> Роспись </label> </div>";
                        }

                        # Дата

                        echo "<input class='placeholder' type='text' name='table' value='$table'/>";
                        echo "<input class='placeholder' type='text' name='rowID' value='$rowID'/>";

                    }

                    elseif ($table == "overalls") {

                        $query = "SELECT Спецодежда.вид_спецодежды AS вид_спецодежды,
                        Спецодежда.срок_носки AS срок_носки,
                        Спецодежда.стоимость AS стоимость
                        FROM Спецодежда
                        WHERE Спецодежда.id = $rowID;";

                        $result = mysqli_query($connect, $query);
                        $row=mysqli_fetch_array($result);

                        $param1 = $row['вид_спецодежды'];
                        $param2 = $row['срок_носки'];
                        $param3 = $row['стоимость'];

                        echo "<div class='editString'>Вид спецодежды: "; 
                        echo "<input type='text' name='param1' value='$param1'></div>";

                        echo "<div class='editString'>Срок ношения: "; 
                        echo "<input class='smallinput' type='text' name='param2' value=$param2></div>";

                        echo "<div class='editString'>Стоимость единицы: "; 
                        echo "<input class='smallinput' type='text' name='param3' value=$param3></div>";

                        echo "<input class='placeholder' type='text' name='table' value='$table'/>";
                        echo "<input class='placeholder' type='text' name='rowID' value='$rowID'/>";

                    }

                    elseif ($table == "workshops") {

                        $query = "SELECT Цехи.наименование_цеха AS наименование_цеха,
                        Работники.фио_работника AS начальник_цеха
                        FROM Цехи, Работники
                        WHERE Работники.id = Цехи.начальник_цеха AND Цехи.id = $rowID;";

                        $result = mysqli_query($connect, $query);
                        $row=mysqli_fetch_array($result);

                        $param1 = $row['наименование_цеха'];
                        $param2 = $row['начальник_цеха'];

                        echo "<div class='editString'>Наименование цеха: "; 
                        echo "<input type='text' name='param1' value='$param1'></div>";

                        echo "<div class='editString'>Начальник цеха: "; 
                        $result = mysqli_query($connect, "SELECT фио_работника, id FROM Работники" );
                        echo "<select name = 'param2'>";

                        while ($row=mysqli_fetch_array($result)) {
                            $value = $row['фио_работника'];
                            $id = $row['id'];
                            if ($value != $param2) {
                                echo "<option value = $id> $value </option>";
                            }
                            else {echo "<option selected value = $id> $value </option>";}
                        }
                        echo "</select> </div>";

                        echo "<input class='placeholder' type='text' name='table' value='$table'/>";
                        echo "<input class='placeholder' type='text' name='rowID' value='$rowID'/>";

                    }

                    elseif ($table == "workers") {

                        $query = "SELECT Работники.фио_работника AS фио_работника,
                        Работники.должность AS должность,
                        Цехи.наименование_цеха AS наименование_цеха,
                        Работники.скидка AS скидка
                        FROM Цехи, Работники
                        WHERE Работники.id_цеха = Цехи.id AND Работники.id = $rowID;";

                        $result = mysqli_query($connect, $query);
                        $row=mysqli_fetch_array($result);

                        $param1 = $row['фио_работника'];
                        $param2 = $row['должность'];
                        $param3 = $row['наименование_цеха'];
                        $param4 = $row['скидка'];

                        echo "<div class='editString'>Ф.И.О. работника: "; 
                        echo "<input type='text' name='param1' value='$param1'></div>";

                        echo "<div class='editString'>Должность: "; 
                        echo "<input type='text' name='param2' value='$param2'></div>";

                        echo "<div class='editString'>Цех: "; 
                        $result = mysqli_query($connect, "SELECT DISTINCT наименование_цеха, id FROM Цехи" );
                        echo "<select name = 'param3'>";

                        while ($row=mysqli_fetch_array($result)) {
                            $value = $row['наименование_цеха'];
                            $id = $row['id'];
                            if ($value != $param3) {
                                echo "<option value = $id> $value </option>";
                            }
                            else {echo "<option selected value = $id> $value </option>";}
                        }
                        echo "</select></div>";

                        echo "<div class='editString'>Скидка на спецодежду: "; 
                        echo "<input class='smallinput' type='text' name='param4' value=$param4> %</div>";

                        echo "<input class='placeholder' type='text' name='table' value='$table'/>";
                        echo "<input class='placeholder' type='text' name='rowID' value='$rowID'/>";

                    }

                    elseif ($table == "users") {

                        $query = "SELECT users.login AS login, 
                        users.lastname AS lastname,
                        users.firstname AS firstname,
                        users.email AS email, 
                        users.role AS role,
                        users.date AS date
                        FROM users 
                        WHERE users.id = $rowID;";

                        $result = mysqli_query($connect, $query);
                        $row=mysqli_fetch_array($result);

                        $login = $row['login'];
                        $firstname = $row['firstname'];
                        $lastname = $row['lastname'];
                        $email = $row['email'];
                        $role = $row['role'];
                        $date = $row['date'];

                        echo "<div class='editString'>Логин: "; 
                        echo "<input type='text' name='param1' value='$login'></div>";

                        echo "<div class='editString'>Имя: "; 
                        echo "<input type='text' name='param2' value='$firstname'></div>";

                        echo "<div class='editString'>Фамилия: "; 
                        echo "<input type='text' name='param3' value='$lastname'></div>";
                        
                        echo "<div class='editString'>Почта: "; 
                        echo "<input type='text' name='param4' value='$email'></div>";

                        echo "<div class='editString'>Роль в системе: "; 
                        echo "<select name = 'param5'>";
                        
                        switch ($role) {
                            case 'guest':
                                echo "<option selected value = 'guest'> Гость </option>";
                                echo "<option value = 'admin'> Администратор </option>";
                                echo "<option value = 'operator'> Оператор </option>";
                                break;
                            case 'admin':
                                echo "<option value = 'guest'> Гость </option>";
                                echo "<option selected value = 'admin'> Администратор </option>";
                                echo "<option value = 'operator'> Оператор </option>";
                                break;   
                            case 'operator':
                                echo "<option value = 'guest'> Гость </option>";
                                echo "<option value = 'admin'> Администратор </option>";
                                echo "<option selected value = 'operator'> Оператор </option>";
                                break;
                        }

                        echo "</select></div>";

                        echo "<div class='editString'>Дата регистрации: "; 
                        echo "<input type='text' name='param6' value='$date'></div>";
                        
                        echo "<div class='editString'>Установить новый пароль: "; 
                        echo "<input type='text' name='param7'></div>";                        

                        echo "<input class='placeholder' type='text' name='table' value='$table'/>";
                        echo "<input class='placeholder' type='text' name='rowID' value='$rowID'/>";

                    }

                    if ($_POST['param1']) {
                        
                        $table = !empty($_POST['table']) ? $_POST['table'] : 'none';
                        $rowID = !empty($_POST['rowID']) ? $_POST['rowID'] : 'none';

                        if ($table == "receiving") { 

                            $param1 = !empty($_POST['param1']) ? $_POST['param1'] : 'NULL';
                            $param2 = !empty($_POST['param2']) ? $_POST['param2'] : 'NULL';
                            $param3 = !empty($_POST['param3']) ? $_POST['param3'] : 'NULL';
                            
                            $query = "UPDATE Получение
                            SET id_работника = $param1, id_спецодежды = $param2, дата_получения = CURRENT_DATE(), роспись = $param3
                            WHERE Получение.id = $rowID; ";
                        
                        }

                        elseif ($table == "overalls") { 
                            
                            $param1 = !empty($_POST['param1']) ? $_POST['param1'] : 'NULL';
                            $param2 = !empty($_POST['param2']) ? $_POST['param2'] : 'NULL';
                            $param3 = !empty($_POST['param3']) ? $_POST['param3'] : 'NULL';
                            
                            $query = "UPDATE Спецодежда
                            SET вид_спецодежды = '$param1', срок_носки = $param2, стоимость = $param3
                            WHERE Спецодежда.id = $rowID; ";

                        }

                        elseif ($table == "workshops") { 
                            
                            $param1 = !empty($_POST['param1']) ? $_POST['param1'] : 'NULL';
                            $param2 = !empty($_POST['param2']) ? $_POST['param2'] : 'NULL';
                            
                            $query = "UPDATE Цехи
                            SET наименование_цеха = '$param1', начальник_цеха = $param2 
                            WHERE Цехи.id = $rowID; ";
                        
                        }

                        elseif ($table == "workers") { 

                            $param1 = !empty($_POST['param1']) ? $_POST['param1'] : 'NULL';
                            $param2 = !empty($_POST['param2']) ? $_POST['param2'] : 'NULL';
                            $param3 = !empty($_POST['param3']) ? $_POST['param3'] : 'NULL';
                            $param4 = !empty($_POST['param4']) ? $_POST['param4'] : 'NULL';

                            $query = "UPDATE Работники 
                            SET фио_работника = '$param1', должность = '$param2', id_цеха = $param3, скидка = $param4
                            WHERE Работники.id = $rowID; ";
                        }

                        elseif ($table == "users") { 

                            $param1 = !empty($_POST['param1']) ? $_POST['param1'] : 'NULL';
                            $param2 = !empty($_POST['param2']) ? $_POST['param2'] : 'NULL';
                            $param3 = !empty($_POST['param3']) ? $_POST['param3'] : 'NULL';
                            $param4 = !empty($_POST['param4']) ? $_POST['param4'] : 'NULL';
                            $param5 = !empty($_POST['param5']) ? $_POST['param5'] : 'NULL';
                            $param6 = !empty($_POST['param6']) ? $_POST['param6'] : 'NULL';
                            $param7 = !empty($_POST['param7']) ? $_POST['param7'] : 'NULL';

                            if ($param7 == 'NULL') {
                                $query = "UPDATE users 
                            SET login = '$param1', firstname = '$param2', lastname = '$param3', email = '$param4', role = '$param5', date = '$param6'
                            WHERE users.id = $rowID; ";
                            }
                            else {
                                $param7 = sha1($param7);
                                $query = "UPDATE users 
                            SET login = '$param1', firstname = '$param2', lastname = '$param3', email = '$param4', role = '$param5', date = '$param6', password = '$param7'
                            WHERE users.id = $rowID; ";
                            }

                            
                        }

                        $result = mysqli_query($connect, $query);

                        if (!$result) {
                            printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
                            exit();
                        }

                        $new_url = 'index.php';
                        header('Location: '.$new_url);

                    }

                    echo "<input style='width: max-content' class='smthbtn' type='submit' value='Изменить'>";



                ?> 

            </form>

            <form style='display:inline-block' action='./index.php'>
                <input class='smthbtn' type='submit' value='Назад'>
            </form>

        </div>

    </main>    

    <footer></footer>

</body>
</html>
