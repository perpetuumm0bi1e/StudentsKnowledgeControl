<?php
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$error = $_GET['error'];
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к серверу
mysqli_select_db($Link, $DBName);

if (!isset($_POST['go']) && !isset($_POST['new_account']) && !isset($_POST['forgot_password'])) {  //отображение страницы
    include_once('authorization.html');
} else {
    $successfull_authorization = false; //метка о выполнении авторизации
    if (isset($_POST['go'])) { //нажатие кнопки "войти"
        if (isset($_POST['account_type'])) {
            if ($_POST['account_type'] == 'admin') { //если аккаунт администратора
                $Query = 'SELECT * FROM admin_table';
                $Result = mysqli_query($Link, $Query);
                while ($Rows = mysqli_fetch_array($Result)) {
                    if ($Rows['admin_login'] == $_POST['login'] && $Rows['admin_password'] == $_POST['password']) {
                        $successfull_authorization = true;
                        session_start();
                        $_SESSION['authorization'] = true;
                        $_SESSION['status'] = 'admin';
                        $_SESSION['login'] = $_POST['login'];
                        $_SESSION['password'] = $_POST['password'];
                        break;
                    }
                }
            } else if ($_POST['account_type'] == 'student') { //если аккаунт студента
                $Query = 'SELECT * FROM students_table';
                $Result = mysqli_query($Link, $Query);
                while ($Rows = mysqli_fetch_array($Result)) {
                    if ($Rows['student_login'] == $_POST['login'] && $Rows['student_password'] == $_POST['password']) {
                        $successfull_authorization = true;
                        session_start();
                        $_SESSION['authorization'] = true;
                        $_SESSION['status'] = 'student';
                        $_SESSION['login'] = $_POST['login'];
                        $_SESSION['password'] = $_POST['password'];
                        $_SESSION['score'] = $Rows['score'];
                        break;
                    }
                }
            }
        }
        if ($successfull_authorization == false) { //если аккаунт не найден
            $error = '<div class="alert alert-dark" role="alert">Некорректные данные. Попробуйте снова.</div>'; //если неверные данные
            header("Location: ./authorization.php?error=$error");
        } else { //перенаправление в ЛК после успешного входа
            header('Location: profile.php');
        }
    } else if (isset($_POST['forgot_password'])) { //восстановление пароля
        if (isset($_POST['account_type_forgot_password']) && isset($_POST['login_forgot_password']) && isset($_POST['new_password_1_forgot_password']) && 
        isset($_POST['new_password_2_forgot_password']) && isset($_POST['key_word_forgot_password'])) {
            $detected_accout = false; //метка о нахождении аккаунта с таким логином
            if ($_POST['account_type_forgot_password'] == 'student') { //поиск аккаунта студента
                $Query = 'SELECT * FROM students_table';
                $Result = mysqli_query($Link, $Query);
                while ($Rows = mysqli_fetch_array($Result)) {
                    if ($Rows['student_login'] == $_POST['login_forgot_password']) {
                        $key_word = $Rows['student_key_word'];
                        $id = $Rows['id'];
                        $detected_accout = true;
                        break;
                    }
                }
            } else if ($_POST['account_type_forgot_password'] == 'admin') { //поиск аккаунта админа
                $Query = 'SELECT * FROM admin_table';
                $Result = mysqli_query($Link, $Query);
                while ($Rows = mysqli_fetch_array($Result)) {
                    if ($Rows['admin_login'] == $_POST['login_forgot_password']) {
                        $key_word = $Rows['admin_key_word'];
                        $id = $Rows['id'];
                        $detected_accout = true;
                        break;
                    }
                }
            }
            if ($detected_accout == false) { //не найден
                $error = '<div class="alert alert-dark" role="alert">Пользователь с таким логином не найден!</div>';
                header("Location: ./authorization.php?error=$error");
            } else if ($detected_accout == true && $key_word != $_POST['key_word_forgot_password']) { //неправильное кодовое слово
                $error = '<div class="alert alert-dark" role="alert">Кодовое слово не совпадает!</div>';
                header("Location: ./authorization.php?error=$error");
            } else if ($detected_accout == true && $key_word == $_POST['key_word_forgot_password']) { //все ок
                //изменение пароля:
                if ($_POST['new_password_1_forgot_password'] == $_POST['new_password_2_forgot_password']) { //введенные пароли совпадают
                    $new_password = $_POST['new_password_1_forgot_password'];
                    if ($_POST['account_type_forgot_password'] == 'student') { //изменение у студента
                        $Query = "UPDATE students_table
                    SET student_password = '$new_password'
                    where id = $id";
                    } else if ($_POST['account_type_forgot_password'] == 'admin') { //изменение у админа
                        $Query = "UPDATE admin_table
                    SET admin_password = '$new_password'
                    where id = $id";
                    }
                    mysqli_query($Link, $Query);
                    mysqli_close($Link);
                    header("Location: ./authorization.php?error=");
                } else { //разные пароли
                    $error = '<div class="alert alert-dark" role="alert">Пароли не совпадают!</div>';
                    header("Location: ./authorization.php?error=$error");
                }
            }
        } else { //не все данные указаны
            $error = '<div class="alert alert-dark" role="alert">Для восстановления пароля необходимо ввести все данные!</div>';
            header("Location: ./authorization.php?error=$error");
        }
    } else if (isset($_POST['new_account'])) { //создание нового аккаунта 
        header("Location: ./registration.php?error=");
    } else if (!isset($_POST['account_type'])) { //если не указан тип аккаунта
        $error = '<div class="alert alert-dark" role="alert">Выберите тип аккаунта.</div>';
        header("Location: ./authorization.php?error=$error");
    }
}
