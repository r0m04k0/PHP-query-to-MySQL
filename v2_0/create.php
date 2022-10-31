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

        <div class="createForm">

            <form action="" method="post" enctype="multipart/form-data">

                <?php

                    $table = !empty($_POST['table']) ? $_POST['table'] : 'none';

                    if ($table == "receiving") {
    
                        echo "<div class='editString'>Ф.И.О. работника: "; 
                        $result = mysqli_query($connect, "SELECT фио_работника, id FROM Работники" );
                        echo "<select name = 'param1'>";

                        while ($row=mysqli_fetch_array($result)) {
                            $value = $row['фио_работника'];
                            $id = $row['id'];
                            echo "<option value = $id> $value </option>";
                        }
                        echo "</select> </div>";


                        echo "<div class='editString'>Вид спецодежды: "; 
                        $result = mysqli_query($connect, "SELECT DISTINCT вид_спецодежды, id FROM Спецодежда" );
                        echo "<select name = 'param2'>";

                        while ($row=mysqli_fetch_array($result)) {
                            $value = $row['вид_спецодежды'];
                            $id = $row['id'];
                            echo "<option value = $id> $value </option>";
                        }
                        echo "</select></div>";

                        echo "<div class='editString'> <input type='checkbox' checked name='param3' value='1'><label> Роспись </label> </div>";

                        echo "<input class='placeholder' type='text' name='table' value='$table'/>";
                    
                    }

                    elseif ($table == "overalls") {

                        echo "<div class='editString'>Вид спецодежды: "; 
                        echo "<input type='text' name='param1'></div>";

                        echo "<div class='editString'>Срок ношения: "; 
                        echo "<input class='smallinput' type='text' name='param2'></div>";

                        echo "<div class='editString'>Стоимость единицы: "; 
                        echo "<input class='smallinput' type='text' name='param3'></div>";

                        echo "<input class='placeholder' type='text' name='table' value='$table'/>";

                    }

                    elseif ($table == "workshops") {

                        echo "<div class='editString'>Наименование цеха: "; 
                        echo "<input type='text' name='param1'></div>";

                        echo "<div class='editString'>Начальник цеха: "; 
                        $result = mysqli_query($connect, "SELECT фио_работника, id FROM Работники" );
                        echo "<select name = 'param2'>";

                        while ($row=mysqli_fetch_array($result)) {
                            $value = $row['фио_работника'];
                            $id = $row['id'];
                            echo "<option value = $id> $value </option>";
                        }
                        echo "</select> </div>";

                        echo "<input class='placeholder' type='text' name='table' value='$table'/>";

                    }

                    elseif ($table == "workers") {

                        echo "<div class='editString'>Ф.И.О. работника: "; 
                        echo "<input type='text' name='param1'></div>";

                        echo "<div class='editString'>Должность: "; 
                        echo "<input type='text' name='param2'></div>";

                        echo "<div class='editString'>Цех: "; 
                        $result = mysqli_query($connect, "SELECT DISTINCT наименование_цеха, id FROM Цехи" );
                        echo "<select name = 'param3'>";

                        while ($row=mysqli_fetch_array($result)) {
                            $value = $row['наименование_цеха'];
                            $id = $row['id'];
                            echo "<option value = $id> $value </option>";
                        }
                        echo "</select></div>";

                        echo "<div class='editString'>Скидка на спецодежду: "; 
                        echo "<input class='smallinput' type='text' name='param4'> %</div>";

                        echo "<input class='placeholder' type='text' name='table' value='$table'/>";

                    }

                    if ($_POST['param1']) {
                        
                        $table = !empty($_POST['table']) ? $_POST['table'] : 'none';

                        if ($table == "receiving") { 

                            $param1 = !empty($_POST['param1']) ? $_POST['param1'] : 'NULL';
                            $param2 = !empty($_POST['param2']) ? $_POST['param2'] : 'NULL';
                            $param3 = !empty($_POST['param3']) ? $_POST['param3'] : 'NULL';
                            
                            $query = "INSERT INTO Получение (id_работника, id_спецодежды, дата_получения, роспись)
                            VALUES ($param1, $param2, CURRENT_DATE(), $param3) ; ";

                        }

                        elseif ($table == "overalls") { 
                            
                            $param1 = !empty($_POST['param1']) ? $_POST['param1'] : 'NULL';
                            $param2 = !empty($_POST['param2']) ? $_POST['param2'] : 'NULL';
                            $param3 = !empty($_POST['param3']) ? $_POST['param3'] : 'NULL';
                            
                            $query = "INSERT INTO Спецодежда (вид_спецодежды, срок_носки, стоимость)
                            VALUES ('$param1', $param2, $param3) ; ";

                        }

                        elseif ($table == "workshops") { 
                            
                            $param1 = !empty($_POST['param1']) ? $_POST['param1'] : 'NULL';
                            $param2 = !empty($_POST['param2']) ? $_POST['param2'] : 'NULL';
                            
                            $query = "INSERT INTO Цехи (наименование_цеха, начальник_цеха)
                            VALUES ('$param1', $param2) ; ";
                        
                        }

                        elseif ($table == "workers") { 

                            $param1 = !empty($_POST['param1']) ? $_POST['param1'] : 'NULL';
                            $param2 = !empty($_POST['param2']) ? $_POST['param2'] : 'NULL';
                            $param3 = !empty($_POST['param3']) ? $_POST['param3'] : 'NULL';
                            $param4 = !empty($_POST['param4']) ? $_POST['param4'] : 'NULL';

                            $query = "INSERT INTO Работники (фио_работника, должность, id_цеха, скидка)
                            VALUES ('$param1', '$param2', $param3, $param4) ; ";
                        }

                        $result = mysqli_query($connect, $query);

                        if (!$result) {
                            printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
                            exit();
                        }

                        $new_url = 'index.php';
                        header('Location: '.$new_url);

                    }

                    echo "<input style='width: max-content' class='smthbtn' type='submit' value='Добавить'>";

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
