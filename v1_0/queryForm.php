<!-- Создание формы с параметрами для выбора из базы данных (опции для выбора создаются на основе результата SQL запроса)-->
<div class="queryForm">
    <p class="formTitle">Создание отчета <br>о получении спецодежды</p>
    <form action="createReport.php" method="get" enctype="multipart/form-data">
        
        Цех
        <?php
        $result = mysqli_query($connect, "SELECT DISTINCT id, наименование_цеха FROM Цехи" );
        echo "<select name = 'department'>";
        echo "<option selected = 'all' value = 'all'> Все цехи </option>";
        while ($row=mysqli_fetch_array($result)) {
            echo "<option value = $row[наименование_цеха]> $row[наименование_цеха] </option>";
        }
        echo "</select>";
        ?>
        <br>

        Месяц
        <?php
        $result = mysqli_query($connect, "SELECT DISTINCT DATE_FORMAT(дата_получения, '%M') as дата_получения FROM Получение" );
        echo "<select name = 'month'>";
        echo "<option selected = 'all' value = 'all'> Весь год </option>";
        while ($row=mysqli_fetch_array($result)) {
            echo "<option value = $row[дата_получения]> $row[дата_получения] </option>";
        }
        echo "</select>";
        ?>
        <br>

        Год
        <?php
        $result = mysqli_query($connect, "SELECT DISTINCT DATE_FORMAT(дата_получения, '%Y') as дата_получения FROM Получение" );
        echo "<select name = 'year'>";
        echo "<option selected = 'all' value = 'all'> Всё время </option>";
        while ($row=mysqli_fetch_array($result)) {
            echo "<option value = $row[дата_получения]> $row[дата_получения] </option>";
        }
        echo "</select>";
        ?>

        <br>
        <input type="submit" name="submit" value="Создать отчёт" />
    </form>
</div>