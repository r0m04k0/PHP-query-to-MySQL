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
?>

<?php
    include("../head.php");
?>

<body>

    <header></header>
    
    <main>

    <?php

    if ($role != 'admin' && $role != 'operator') {
        echo "<p style='margin-top: 10%; text-align:center'>У вас нет доступа к этой странице. Если вы уже зарегистрировались, подождите, 
        пока администратор не даст вам права доступа, если нет, то <a href='../reg.php'>зарегестрируйтесь</a></p>";
        exit();
    }

    if ($role == 'operator') {
        
        $login = $_SESSION['login'];
        $id = $_SESSION['id'];
        $result = mysqli_query($connect, "SELECT COUNT(*) AS num, MAX(date) AS last_date FROM wisits WHERE user_id = $id;" );
        $row=mysqli_fetch_array($result);
        $num = $row['num'];
        $last_date = $row['last_date'];

        echo "<ul class='list-group mt-2'>";
        if ($num == 1) {
            echo "<li class='list-group-item'> Добро пожаловать! </li>";
        }
        else {
            echo "<li class='list-group-item'> Вы зашли $num раз(a) </li>";
            echo "<li class='list-group-item'> Последнее посещение: $last_date </li>";
        }
        echo "</ul>";
    }

    ?>

        <!-- Создание формы с параметрами для выбора из базы данных (опции для выбора создаются на основе результата SQL запроса)-->
        <div class="queryForm">

            <form  class="d-grid gap-2" action="report.php" method="get" enctype="multipart/form-data">
                
                <?php

                $month = !empty($_GET['month']) ? $_GET['month'] : 'all';
                $year = !empty($_GET['year']) ? $_GET['year'] : 'all';
                
                $result = mysqli_query($connect, "SELECT DISTINCT DATE_FORMAT(дата_получения, '%M') as дата_получения FROM Получение" );
                echo "<select class='form-select' name = 'month'>";
                
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
                echo "<select class='form-select' name = 'year'>";

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

                echo "<input class='btn btn-dark' type='submit'>";
                
                ?>

            </form>

            <?php 
            if ($role == 'admin') {
                echo "<form class='d-grid mt-2 gap-2' action='../crud/index.php'>
                    <input class='btn btn-dark' type='submit' value='К панели управлнения'>
                </form>";
            }
            ?>

            <form class="d-grid mt-2 gap-2" action='../index.php'>
                <input class='btn btn-dark' type='submit' value='На главную'>
            </form>

        </div>

        
    </main>    

    <footer></footer>

</body>
</html>
