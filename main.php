<?php
session_start();
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к БД
mysqli_select_db($Link, $DBName);
if ((!isset($_GET['add_edit'])) && (!isset($_GET['solve'])) && (!isset($_GET['profile']))) { //отображение страницы
    include_once('main.html');
} else {
    if (isset($_GET['solve'])) { //пройти тест
        if ($_SESSION['authorization'] == true) {
            header('Location: take_a_quiz.php');
        } else if ($_SESSION['authorization'] == false) {
            header('Location: authorization.php?error=');
        }
    } else if (isset($_GET['add_edit'])) { //редактирование тестов
        if ($_SESSION['authorization'] == true && $_SESSION['status'] == 'admin') { //авторизован админ
            header("Location: add_edit.php");
        } else if ($_SESSION['authorization'] == true && $_SESSION['status'] == 'student') { //авторизован студент
            echo "<script>alert(\"Необходимо авторизоваться как администратор!\");</script>";
            include_once('main.html');
        } else if ($_SESSION['authorization'] == false) { //не авторизован
            header('Location: authorization.php?error=');
        }
    } else if (isset($_GET['profile'])) { //профиль
        if ($_SESSION['authorization'] == true) { //авторизован -> профиль
            header('Location: profile.php');
        } else if ($_SESSION['authorization'] == false) { //не авторизован -> авторизация
            header('Location: authorization.php?error=');
        }
    }
}
