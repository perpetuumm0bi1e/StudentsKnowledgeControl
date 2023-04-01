<?php
session_start();
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к серверу
mysqli_select_db($Link, $DBName);
$output_data = ''; //вспомогательная переменная для отображения списка тестов
if ($_SESSION['authorization'] == true && $_SESSION['status'] == 'admin') { //дополнительная проверка того, что авторизован администратор
    if (!isset($_GET['create_new_test']) && !isset($_GET['profile']) && !isset($_GET['search'])) { //отображение страницы со списком тестов
        $Query = 'SELECT * FROM test_list';
        $Result = mysqli_query($Link, $Query);
        while ($Rows = mysqli_fetch_array($Result)) {
            $output_data .= '<tr><td><a class="btn btn-link link-dark" href="./edit.php?table_name=' . $Rows['test_name'] . '&action=edit_&error=">' . $Rows['test_name'] . '</a></td></tr>';
        }
        require_once('add_edit.html');
    } else if (isset($_GET['profile'])) { //Переход в профиль
        header('Location: profile.php');
    } else if (isset($_GET['create_new_test'])) { //создание нового теста
        $name = $_GET['new_name']; //название теста из формы
        $name_words = preg_split('/[\s,]+/', $name);
        $t_name = '';
        for ($i = 0; $i < count($name_words) - 1; $i++) {
            $t_name .= $name_words[$i];
            $t_name .= '_';
        }
        $t_name .= $name_words[count($name_words) - 1]; //создание названия таблицы из названия теста
        $_SESSION['test_name'] = $t_name; //обозначение в переменной сессии, с какой таблицей работает администратор
        $creator = $_SESSION['login']; //создатель теста
        $Query = "INSERT INTO test_list VALUES(0, '$creator', '$t_name')"; //добавление записи в таблицу со всеми тестами
        mysqli_query($Link, $Query);
        //создание таблицы теста
        $Query = "CREATE TABLE $t_name (id INT(4) PRIMARY KEY AUTO_INCREMENT,
        question VARCHAR(500),
        answer_1 VARCHAR(500),
        answer_2 VARCHAR(500),
        answer_3 VARCHAR(500),
        answer_4 VARCHAR(500),
        max_score INT(2),
        right_answer VARCHAR(4))";
        mysqli_query($Link, $Query);
        $t_name_result = $t_name . '_results'; //название таблицы с результатами прохождения данного теста
        //создание таблицы с результатами
        $Query = "CREATE TABLE $t_name_result (id INT(4) PRIMARY KEY AUTO_INCREMENT,
        test_id int(4),
        question_id INT(2),
        stud_id INT(4),
        stud_answer VARCHAR(50),
        stud_score INT(2),
        max_score INT(2))";
        mysqli_query($Link, $Query);
        header("Location: ./edit.php?table_name=$t_name&action=edit&error=");  //переход на страницу редактирования теста
    } else if (isset($_GET['search'])) { //поиск по названию
        $output_data = '';
        $name_words = preg_split('/[\s,]+/', $_GET['name_for_search']);
        $t_name = '';
        for ($i = 0; $i < count($name_words) - 1; $i++) {
            $t_name .= $name_words[$i];
            $t_name .= '_';
        }
        $t_name .= $name_words[count($name_words) - 1];
        $Query = 'SELECT * FROM test_list';
        $Result = mysqli_query($Link, $Query);
        while ($Rows = mysqli_fetch_array($Result)) {
            if ($Rows['test_name'] == $t_name) {
                $output_data .= '<tr><td><a class="btn btn-link link-dark" href="./edit.php?table_name=' . $Rows['test_name'] . '&action=edit_&error=">' . $Rows['test_name'] . '</a></td></tr>';
                break;
            }
        }
        if ($output_data == ''){
            $output_data .= '<tr><td><p>Не найдено</p></td></tr>';
        }
        require_once('add_edit.html');
    }
} else if ($_SESSION['authorization'] == true && $_SESSION['status'] == 'student') { //если на эту страницу пытается попасть студент
    echo "<script>alert(\"Необходимо авторизоваться как администратор!\");</script>";
} else if ($_SESSION['authorization'] == false) { //если на эту страницу пытаются попасть без авторизации
    header('Location: authorization.php?error=');
}
mysqli_close($Link);
