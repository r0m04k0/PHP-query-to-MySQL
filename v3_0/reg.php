<?php
    include("connect.php");\

    session_start();

    if (count($_POST) > 0) {

        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $email = $_POST['email'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $captcha = $_POST['captcha'];

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
            document.location.href = 'reg.php';
            </script>";
            exit();
        }
    
        $password = sha1($password);
        
        $result = mysqli_query($connect, "SELECT email, login FROM users ;" );
        while ($row=mysqli_fetch_array($result)) {
            if ($row['email'] == $email) {        
                echo "<script>
                alert(' Пользователь с такой почтой уже существует ');
                document.location.href = 'reg.php';
                </script>";
                exit;
            }
            elseif ($row['login'] == $login) {        
                echo "<script>
                alert(' Пользователь с таким логином уже существует ');
                document.location.href = 'reg.php';
                </script>";
                exit;
            }
        }
        
        $query = "INSERT INTO users (login, password, lastname, firstname, email)
        VALUES ('$login', '$password', '$lastname', '$firstname', '$email') ; ";
    
        $result = mysqli_query($connect, $query);
    
        echo "<script>
            alert(' Регистрация прошла успешно, ожидайте, администратор выдаст Вам необходимые права. ');
            document.location.href = 'index.php';
        </script>";
    
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
                    <a class="nav-link active" href="reg.php">Регистрация</a>
                    <a class="nav-link" href="auth.php">Вход</a>
                    <!-- <a class="nav-link disabled" href="#">Формирование ведомости</a> -->
                </div>
            </div>
        </div>
    </nav>
        
    </header>
    
    <main>

        <form class="row g-3" action='' method='post' enctype='multipart/form-data'>
            <div class="col-md-4">
                <label for="validationCustom01" class="form-label">Имя</label>
                <input type="text" class="form-control" id="validationCustom01" name="firstname" required>
                <div class="valid-feedback">
                    Looks good!
                </div>
            </div>
            
            <div class="col-4">
                <label for="validationCustom02" class="form-label">Фамилия</label>
                <input type="text" class="form-control" id="validationCustom02" name="lastname" required>
                <div class="valid-feedback">
                Looks good!
                </div>
            </div>
                
            <div class="col-4">
                <label for="exampleFormControlInput1" class="form-label">Email</label>
                <input type="email" class="form-control" id="exampleFormControlInput1" name="email" placeholder="name@example.com" required>
            </div>
                
            <div class="col-md-5">
                <label for="validationCustom02" class="form-label">Логин</label>
                <input type="text" class="form-control" id="validationCustom02" name="login" required>
                <div class="valid-feedback">
                Looks good!
                </div>
            </div>
            
            <div class="col-5">
                <label for="validationCustom02" class="form-label">Пароль</label>
                <input pattern=".{8,20}" required title="От 8 до 20 символов" type="text" class="form-control" id="validationCustom02" name="password" required>
                <div class="valid-feedback">
                Looks good!
                </div>
            </div>

            <div class="col-md-4">
            <label for="validationCustom02" class="form-label">Введите код с картинки</label>
            <input type="text" class="form-control" id="validationCustom02" name="captcha" required>
        </div>

        <div class="col-md-4">
            <label style='opacity: 0' for="validationCustom02" class="form-label">Капча</label>
            <img class="form-control" style="padding: 0;" src="captcha.php"/>
        </div>

        
        <div class="col-md-4 mt-4">
                <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" checked value="" id="invalidCheck" required>
                <label class="form-check-label" for="invalidCheck">
                    Согласие на обработку персональных данных
                </label>
                <div class="invalid-feedback">
                    Чтобы зарегестрироваться, вы должны согласиться
                </div>
                </div>
            </div>

            <div class="col-8 mt-5">
                <button class="btn btn-dark" type="submit">Зарегестрироваться</button>
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
