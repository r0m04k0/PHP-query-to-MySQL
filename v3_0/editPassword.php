<?php
    include("connect.php");\

    session_start();

    if (count($_GET) > 0) {

        $secretCode = $_GET['secretcode'];
        $login = $_GET['login'];

        if ($_SESSION['secretcode'] != $secretCode || $_SESSION['timelogin'] != $login) {
            $new_url = 'index.php';
            header('Location: '.$new_url);    
        }

    }
    else {
        $new_url = 'index.php';
        header('Location: '.$new_url);
    }

    if (count($_POST) > 0) {

        $password = $_POST['password'];

        $password = sha1($password);
        $query = "UPDATE users 
        SET password = '$password'
        WHERE users.login = '$login'; ";

        $result = mysqli_query($connect, $query);

        if (!$result) {
            printf("Ошибка запроса к базе данных: %s\n", mysqli_error($connect));
            exit();
        }

        unset($_SESSION['secretcode']);
        unset($_SESSION['timelogin']);

        echo "<script>
                alert(' Пароль успешно изменён! ');
                document.location.href = 'index.php';
                </script>";   

    }


    include("head.php");

?>
 



<body>

    <header></header>
    
    <main>


    <form class="row g-3" action='' method='post' enctype='multipart/form-data'>
                
        <div class="col-5">
            <label for="validationCustom02" class="form-label">Новый пароль</label>
            <input pattern=".{8,20}" required title="От 8 до 20 символов" type="text" class="form-control" id="validationCustom02" name="password" required>
            <div class="valid-feedback">
            Looks good!
            </div>
        </div>

        <div class="col-8 mt-5">
                <button class="btn btn-dark" type="submit">Изменить пароль</button>
        </div>

    </form>
        
    </main>    

    <footer></footer>

</body>
</html>
