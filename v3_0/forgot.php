<?php
    include("connect.php");

    session_start();

    if (count($_POST) > 0) {

        $email = $_POST['email'];
        $login = $_POST['login'];
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
            document.location.href = 'forgot.php';
            </script>";
            exit();
        }
        
        $result = mysqli_query($connect, "SELECT email, login FROM users WHERE login = '$login' AND email = '$email';" );
        $row=mysqli_fetch_array($result);
        
        if ($row == 0) {
            echo "<script>
            alert(' Пользователя с такими данными не существует ');
            document.location.href = 'forgot.php';
            </script>";
            exit();
        }
        
        else {    

            $secretCode = sha1(rand(1000, 9999));
            $_SESSION['secretcode'] = $secretCode;
            $_SESSION['timelogin'] = $login;

            $to  = "$email";  
            $subject = "Сброс пароля"; 

            $message = "<p> С веб-приложения Спецодежда поступила заявка на смену пароля Вашего аккаунта.</p>
            <p>Чтобы сменить пароль, перейдите по <a href = 'isis/v3_0/editPassword.php?secretcode=$secretCode&login=$login'>ссылке.</p> </br> 
            <p>Если вы этого не делали, проигнорируйте данное письмо.</p> </br>";

            $headers  = "From: webmaster@example.com \r\n Content-type: text/html; charset=windows-1251 \r\n";  

            $result = mail($to, $subject, $message, $headers); 



            if ($result) {
                echo "<script>
                alert(' Вам на почту прийдёт письмо со ссылкой для смены пароля ');
                document.location.href = 'index.php';
                </script>";    
            }
            else {
                echo "<script>
                    alert(' Произошла ошибка ');
                    document.location.href = 'index.php';
                    </script>";
            }
        }
        
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
                    <a class="nav-link" aria-current="page" href="index.php">О проекте</a>
                    <a class="nav-link" href="reg.php">Регистрация</a>
                    <a class="nav-link" href="auth.php">Вход</a>
                    <!-- <a class="nav-link disabled" href="#">Формирование ведомости</a> -->
                </div>
            </div>
        </div>
    </nav>
        
    </header>
    
    <main>

        <form class="row g-3" action='' method='post' enctype='multipart/form-data'>
                
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

            <div class="col-md-4">
            <label for="validationCustom02" class="form-label">Введите код с картинки</label>
            <input type="text" class="form-control" id="validationCustom02" name="captcha" required>
        </div>

        <div class="col-md-4">
            <label style='opacity: 0' for="validationCustom02" class="form-label">Капча</label>
            <img class="form-control" style="padding: 0;" src="captcha.php"/>
        </div>

            <div class="col-8 mt-5">
                <button class="btn btn-dark" type="submit">Восстановить пароль</button>
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
