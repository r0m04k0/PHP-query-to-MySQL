<?php

    include("connect.php");

    function Login($login, $password, $remember, $role, $id) {
        
        $_SESSION['login'] = $login;
        $_SESSION['role'] = $role;
        $_SESSION['id'] = $id;

        if ($remember) {
            setcookie('login', $login, time()+3600*24*7);
            setcookie('role', $role, time()+3600*24*7);
            setcookie('id', $id, time()+3600*24*7);
        }

        return true;
    }

    function Logout() {
        
        setcookie('login', '', time()-1);
        setcookie('role', '', time()-1);
        setcookie('id', '', time()-1);

        unset($_SESSION['login']);
        unset($_SESSION['role']);
        unset($_SESSION['id']);
    }

    session_start();
    $enter_site = false;
    Logout();

    if (count($_POST) > 0) {

        $captcha = $_POST['captcha'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $remember = $_POST['remember'] == 'true';

        if ($captcha != $_SESSION['code']) {
            
            if (!isset($_COOKIE['err'])) {
                setcookie('err', 1, time()+60);
            }
            else {
                if ($_COOKIE['err'] < 2) {
                    setcookie('err', ($_COOKIE['err'] + 1), time()+60);
                }
                else {
                    echo "<script>
                    alert(' Вы превысили число попыток, форма станет снова доступна через минуту ');
                    document.location.href = 'index.php';
                    </script>";
                    exit();
                }
            }

            echo "<script>
            alert(' Введённый код не совпадает с капчей ');
            document.location.href = 'auth.php';
            </script>";
            exit();
        }

        $password = sha1($password);
        
        $result = mysqli_query($connect, "SELECT password, role, id FROM users WHERE login = '$login';" );
        $row=mysqli_fetch_array($result);

        if ($row['password'] != $password) {        
            echo "<script>
            alert(' Вы ввели неверные данные. Повторите попытку входа. ');
            document.location.href = 'auth.php';
            </script>";
            exit();
        }

        $id = $row['id'];
        $enter_site = Login($login, $password, $remember, $row['role'], $id );

        }
        
        if ($enter_site) {
            
            mysqli_query($connect, "INSERT INTO wisits (user_id) VALUES ($id); " );

            if ($_SESSION['role'] == 'admin') {
                header('Location: index.php');
                //header('Location: ./crud/index.php');    
            }
            elseif ($_SESSION['role'] == 'operator') {
                header('Location: index.php');
                //header('Location: ./report/index.php');
            }
            else {
                echo "<script>
                alert(' Пожалуйста, подождите, пока администратор выдаст Вам необходимые права ');
                document.location.href = 'index.php';
                </script>";
            }
            
            exit();
        }
        

?>

<?php
    include("head.php");
?>



<body>

    <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Спецодежда</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
            <a class="nav-link " aria-current="page" href="index.php">О проекте</a>
                <a class="nav-link " href="reg.php">Регистрация</a>
                <a class="nav-link active" href="auth.php">Вход</a>
                <!-- <a class="nav-link disabled" href="#">Формирование ведомости</a> -->
            </div>
            </div>
        </div>
        </nav>
        
    </header>
    
    <main class="">

    <form class="row g-3" action="" method="post">

        <div class="col-md-5">
            <label for="validationCustom02" class="form-label">Логин</label>
            <input type="text" class="form-control" id="validationCustom02" name="login" required>
        </div>
  
        <div class="col-md-5">
            <label for="validationCustom02" class="form-label">Пароль</label>
            <input type="text" class="form-control" id="validationCustom02" name="password" required>
        </div>

        <div class="col-md-4">
            <label for="validationCustom02" class="form-label">Введите код с картинки</label>
            <input type="text" class="form-control" id="validationCustom02" name="captcha" required>
        </div>

        <div class="col-md-8">
            <label style='opacity: 0' for="validationCustom02" class="form-label">Капча</label>
            <img class="form-control" style="padding: 0;" src="captcha.php"/>
        </div>

        <div class="col-md-4 mt-5">
            <button style='width: 200px' class="btn btn btn-dark" type="submit">Войти</button>
        </div>

        <div class="col-md-8 mt-5">
            <button class="btn btn-outline-dark" style='width: 200px' onClick="location.href='forgot.php'">Забыли пароль?</button>
            
        </div>

        <div class="col-md-4">
            <div class="form-check">
            <input class="form-check-input" type="checkbox" id="invalidCheck" checked name="remember" value='true'>
            <label class="form-check-label" for="invalidCheck">
                Запомнить меня
            </label>
            </div>
        </div>
    
    </form>



    </main>    

    <footer class="text-center text-lg-start bg-light text-muted">
        <div class="text-center p-4" style="background-color: rgba(0, 0, 0, 0.05);">
            © 2021 Copyright:
            <a class="text-reset fw-bold" href="https://romo4ko.ru/">romo4ko.ru</a>
        </div>
    </footer>

</body>
</html>
