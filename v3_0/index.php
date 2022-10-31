<?php
    include("head.php");

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



<body>

    <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Спецодежда</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center justify-content-lg-between" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                
                <?php
                            
                if ($role == 'admin') {
                    echo "<a class='nav-link active' aria-current='page' href='index.php'>Главная</a>";
                    echo "<a class='nav-link' href='crud/index.php'>Панель управления БД</a>";
                    echo "<a class='nav-link' href='report/index.php'>Формирование отчёта</a>";
                    echo "<a class='nav-link' href='auth.php'>Выйти</a>";
                }
                elseif ($role == 'operator') {
                    echo "<a class='nav-link active' aria-current='page' href='index.php'>Главная</a>";
                    echo "<a class='nav-link' href='report/index.php'>Формирование отчёта</a>";
                    echo "<a class='nav-link' href='auth.php'>Выйти</a>";
                }
                elseif ($role == 'guest') {
                    echo "<a class='nav-link active' aria-current='page' href='index.php'>Главная</a>";
                    echo "<a class='nav-link' href='auth.php'>Выйти</a>";
                }
                elseif (isset($_COOKIE['err'])) {
                    echo "<a class='nav-link active' aria-current='page' href='index.php'>О проекте</a>";
                    echo "<a class='nav-link disabled' href='reg.php'>Регистрация</a>";
                    echo "<a class='nav-link disabled' href='auth.php'>Вход</a>";
                    echo "
                    <script type='text/javascript'>
                    function locs(){
                    document.location.href='';
                    }
                    setTimeout('locs()', 60000);
                    </script>
                    ";
                }
                else {
                    echo "<a class='nav-link active' aria-current='page' href='index.php'>О проекте</a>";
                    echo "<a class='nav-link' href='reg.php'>Регистрация</a>";
                    echo "<a class='nav-link' href='auth.php'>Вход</a>";
                }
            ?>
            </div>
            </div>
        </div>
        </nav>
        
    </header>
    
    <main style="width: 100%; margin:0; margin-top:15%">

    <?php
    if (!$_SESSION['role']) {
        include("about.php");    
    }
    else {
        echo " <div class='container text-center text-md-center mt-5'>
        <h4>Вы выполнили вход</h4>
        <ul class='list-group' style='width: 30%; margin:auto;'>
            <li class='list-group-item'>Ваш логин: $login</li>";
        switch ($role) {
            case 'guest':
                echo "<li class='list-group-item'>Ваша роль в системе: Гость</li>";       
                break;
            case 'admin':
                echo "<li class='list-group-item'>Ваша роль в системе: Администратор</li>";       
                break;   
            case 'operator':
                echo "<li class='list-group-item'>Ваша роль в системе: Оператор</li>";       
                break;
        }
        echo "</ul></div>";
    }
    
    ?>

    </main>    

    <footer class="text-center text-lg-start bg-light text-muted">
        <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2021 Copyright:
            <a class="text-reset fw-bold" href="https://romo4ko.ru/">romo4ko.ru</a>
        </div>

    </footer>


</body>
</html>
