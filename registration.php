<?php
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к серверу
mysqli_select_db($Link, $DBName);
//вспомогательная переменная для отображения списка тестов
$output_data = '';
$error = $_GET['error'];

if (!isset($_POST['create_account'])) { //отображение страницы
    include_once('registration.html');
} else {
    if (!isset($_POST['new_surname']) || !isset($_POST['new_name']) || !isset($_POST['new_patronymic']) || !isset($_POST['new_login']) || !isset($_POST['new_password_1']) || !isset($_POST['new_password_2']) || !isset($_POST['new_key_word'])) {
        $error = '<div class="alert alert-danger" role="alert"> Ошибка: введены не все данные!</div>';
        header("Location: ./registration.php?error=$error");
    } else {
        if (!preg_match("/^[a-z0-9-_]{2,20}$/i", $_POST['new_login'])) { //проверка корректности логина
            $error = '<div class="alert alert-danger" role="alert"> Ошибка: некорректно введён логин!</div>';
            header("Location: ./registration.php?error=$error");
        } else {
            $new_surname = $_POST['new_surname'];
            $new_name = $_POST['new_name'];
            $new_patronymic = $_POST['new_patronymic'];
            $Query = "SELECT * FROM students_table";
            $Result = mysqli_query($Link, $Query);
            $detected_account = false; //метка о существовании аккаунта с таким именем
            while ($Rows = mysqli_fetch_array($Result)) {
                if ($Rows['student_login'] == $_POST['new_login']) {
                    $detected_account = true;
                    break;
                }
            }
            if ($detected_account == true) { //если аккаунт с таким именем существует
                $error = '<div class="alert alert-danger" role="alert"> Ошибка: пользователь с таким логином уже зарегестрирован!</div>';
                header("Location: ./registration.php?error=$error");
            } else {
                $new_login = $_POST['new_login'];
                if ($_POST['new_password_1'] != $_POST['new_password_2']) { //проверка паролей на предмет совпадения
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: пароли не совпадают!</div>';
                    header("Location: ./registration.php?error=$error");
                } else if (!preg_match("/^[a-z0-9-_]{2,20}$/i", $_POST['new_password_1'])) { //проверка корректности пароля
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: указан некорректный пароль!</div>';
                    header("Location: ./registration.php?error=$error");
                } else {
                    $new_password = $_POST['new_password_1'];
                    $new_key_word = $_POST['new_key_word'];
                    //добавление нового аккаунта в БД
                    $Query = "INSERT INTO students_table VALUES(
                        0, 
                        '$new_login', 
                        '$new_password', 
                        '$new_key_word', 
                        '$new_surname', 
                        '$new_name', 
                        '$new_patronymic', 
                        0)";
                    mysqli_query($Link, $Query);
                    $stud_tests_table = $new_login . '_tests'; //создание таблицы со списком пройденных тестов для нового студента
                    $Query = "CREATE TABLE $stud_tests_table (id INT(4) PRIMARY KEY AUTO_INCREMENT,
                    test_id INT(4),
                    stud_score INT(4),
                    total_score INT(4))";
                    mysqli_query($Link, $Query);
                    header('Location: authorization.php?error=');
                }
            }
        }
    }
}
mysqli_close($Link);
