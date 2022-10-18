<?php
# Запрос для каждого цеха
# $new_url = 'v1_0/index.php';
# Динамическое обновление + Запрос для каждого цеха
# $new_url = 'v1_1/index.php';
# Динамическое обновление (один запрос)
# $new_url = 'v1_2/index.php';
# Один запрос, отдельная форма, 
$new_url = 'v1_3/index.php';
header('Location: '.$new_url);
?>