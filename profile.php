<?php
session_start();
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к бд
mysqli_select_db($Link, $DBName);
//вспомогательные переменные
$score = '';
$fio = '';
$status = '';
$avatar = '';
$notification = '';
$output_data = '';
$extra_data = '';
if ($_SESSION['status'] == 'student') { //профиль студента
    $Query = 'SELECT * FROM students_table';
    $Result = mysqli_query($Link, $Query);
    while ($Rows = mysqli_fetch_array($Result)) { //фио
        if ($Rows['student_login'] == $_SESSION['login'] && $Rows['student_password'] == $_SESSION['password']) {
            $score_value = $Rows['score'];
            $surname = $Rows['student_surname'];
            $name = $Rows['student_name'];
            $patronymic = $Rows['student_patronymic'];
            break;
        }
    } //для отображения опыта:
    $score = '
        <div>
            <label class="form-label mt-4"><b>Всего баллов: </b></label>
            <label class="form-label mt-3">' . $score_value . '</label>
        </div>';
    $stud_tests_table_name = $_SESSION['login'] . '_tests'; //название таблицы со списком тестов пройденных студентом
    $Query = "SELECT * FROM $stud_tests_table_name";
    $Result = mysqli_query($Link, $Query);
    while ($Rows1 = mysqli_fetch_array($Result)) {
        $test_id = $Rows1['test_id'];
        $Query = "SELECT * FROM test_list";
        $Result1 = mysqli_query($Link, $Query);
        while ($Table_rows = mysqli_fetch_array($Result1)) {
            if ($Table_rows['id'] == $test_id) {
                $test_name = $Table_rows['test_name'];
                break;
            }
        }
        $stud_score = $Rows1['stud_score'];
        $total_score = $Rows1['total_score'];
        $output_data .= '<tr>
            <td>
            <a class="btn btn-link link-dark" href="./old_result.php?id=' . $test_id . '&test_name=' . $test_name . '&stud_score=' . $stud_score . '&total_score=' . $total_score . '">' . $test_name . '</a>
            </td>
            <td>
            <p>' . $stud_score . '</p>
            </td>
            <td>
            <p>' . $total_score . '</p>
            </td>
            </tr>';
    }
    if ($output_data != '') {
        $extra_data = '<div class="row g-3 mt-5" style="position:relative; top: 90px;">
    <h3 class="d-flex justify-content-evenly mt-5 mb-5">Пройденные тесты</h3>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Название</th>
                <th scope="col">Ваш балл</th>
                <th scope="col">Максимальный балл</th>
            </tr>
        </thead>
        <tbody>' . $output_data . '
        </tbody>
    </table>
</div>';
    }
    $avatar = '
    <img src="https://i.ibb.co/7jkBpXQ/student.png" class="rounded"
        style="width: 180px; height: 180px;">';
    $status = 'Студент';
    $fio = '<div>
    <label class="form-label mt-4"><b>ФИО: </b></label>
        ' . $surname . ' ' . $name . ' ' . $patronymic . '
        </span></label>
        </div>';
}
if ($_SESSION['status'] == 'admin') { //профиль админа
    $Query = 'SELECT * FROM test_list';
    $Result = mysqli_query($Link, $Query);
    while ($Rows = mysqli_fetch_array($Result)) {
        if ($Rows['creator_login'] == $_SESSION['login'])
            $output_data .= '<tr>
            <td>
            <a class="btn btn-link link-dark" href="./edit.php?table_name=' . $Rows['test_name'] . '&action=edit_&error=">' . $Rows['test_name'] . '</a>
            </td>
            </tr>';
    }
    if ($output_data != '') {
        $extra_data = '<div class="row g-3 mt-5" style="position:relative; top: 90px;">
    <h3 class="d-flex justify-content-evenly mt-5 mb-2">Созданные тесты</h3>
    <table class="table">
        <thead>
            <tr>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>' . $output_data . '
        </tbody>
    </table>
</div>';
    }
    $avatar = '
    <img src="https://i.ibb.co/t4K8d94/admin.png" class="rounded"
        style="width: 180px; height: 180px;">';
    $status = 'Администратор';
}
if (!isset($_GET['log_out']) && !isset($_GET['change_password'])) {
    include_once('profile.html'); //отображение страницы
} else {
    if (isset($_GET['log_out'])) { //выход из профиля
        session_destroy();
        header('Location: main.php');
    } else if (isset($_GET['change_password'])) { //смена пароля
        $new_password = $_GET['new_password'];
        if ($_SESSION['status'] == 'admin') { //смена пароля администратора
            $Query = 'SELECT * FROM admin_table';
            $Result = mysqli_query($Link, $Query);
            while ($Rows = mysqli_fetch_array($Result)) {
                if ($Rows['admin_login'] == $_SESSION['login'] && $Rows['admin_password'] == $_SESSION['password']) {
                    $id = $Rows['id'];
                    $Query = "UPDATE admin_table
                SET admin_password = $new_password
                where id = $id;";
                    mysqli_query($Link, $Query);
                    $_SESSION['password'] = $new_password;
                    header('Location: profile.php');
                    break;
                }
            }
        } else if ($_SESSION['status'] == 'student') { //смена пароля студента
            $Query = 'SELECT * FROM students_table';
            $Result = mysqli_query($Link, $Query);
            while ($Rows = mysqli_fetch_array($Result)) {
                if ($Rows['student_login'] == $_SESSION['login'] && $Rows['student_password'] == $_SESSION['password']) {
                    $id = $Rows['id'];
                    $Query = "UPDATE students_table
                SET student_password = $new_password
                where id = $id;";
                    mysqli_query($Link, $Query);
                    $_SESSION['password'] = $new_password;
                    header('Location: profile.php');
                    break;
                }
            }
        }
    }
}
mysqli_close($Link);
