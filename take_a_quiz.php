<?php
session_start();
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к БД
mysqli_select_db($Link, $DBName);
if ($_SESSION['authorization'] == true) { //если пользователь авторизован
    if (!isset($_GET['if_of_the_test_in_db']) && !isset($_GET['profile']) && !isset($_GET['search'])) {
        $output_data = '';
        $Query = 'SELECT * FROM test_list';
        $Result = mysqli_query($Link, $Query);
        while ($Rows = mysqli_fetch_array($Result)) {
            $name_words = preg_split("/_/", $Rows['test_name']); //название теста для вывода
            $name = '';
            for ($i = 0; $i < count($name_words) - 1; $i++) {
                $name .= $name_words[$i];
                $name .= ' ';
            }
            $name .= $name_words[count($name_words) - 1];
            $output_data .= '<tr><td><a class="btn btn-link link-dark" href="./test.php?table_name=' . $Rows['test_name'] . '">' . $Rows['test_name'] . '</a></td></tr>'; //отображение тестов из таблицы
        }
        require_once('take_a_quiz.html');
    } else if (isset($_GET['profile'])) { //переход в профиль
        if ($_SESSION['authorization'] == true) {
            header('Location: profile.php');
        } else if ($_SESSION['authorization'] == false) {
            header('Location: authorization.php?error=');
        }
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
                $output_data .= '<tr><td><a class="btn btn-link link-dark" href="./test.php?table_name=' . $Rows['test_name'] . '">' . $Rows['test_name'] . '</a></td></tr>';
                break;
            }
        }
        if ($output_data == ''){
            $output_data .= '<tr><td><p>Не найдено</p></td></tr>';
        }
        require_once('take_a_quiz.html');
    }
} else if ($_SESSION['authorization'] == false) { //если пользователь не авторизован
    header('Location: authorization.php?error=');
}
