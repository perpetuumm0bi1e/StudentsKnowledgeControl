<?php
session_start();
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к БД
mysqli_select_db($Link, $DBName);
//значения из гет запроса
$action = $_GET['action'];
$table_name = $_GET['table_name'];
$error = $_GET['error'];
//вспомогательные переменнные
$output_data = '';
$tname = '';
$name_words = preg_split("/_/", $table_name);  //название теста для вывода
$name = '';
for ($i = 0; $i < count($name_words) - 1; $i++) {
    $name .= $name_words[$i];
    $name .= ' ';
}
$name .= $name_words[count($name_words) - 1];
$this_action = preg_split("/_/", $action); //действие
if ($this_action[0] == 'delete') { //удаление вопроса
    $_SESSION['test_name'] = $_GET['table_name'];
    $tname = '<input type="text" class="form-control mb-3" name="table_name" value="' . $_SESSION['test_name'] . '" hidden>
    <input type="text" class="form-control mb-3" name="error" value="" hidden>'; //скрытый инпут с ключом для постоянной передачи ключа с именем теста
    $Query = "SELECT * FROM $table_name";
    $Result = mysqli_query($Link, $Query);
    while ($Rows = mysqli_fetch_array($Result)) {
        if ($Rows['id'] == $this_action[1]) {
            $Query = "DELETE FROM $table_name
            WHERE id = $this_action[1]";
            mysqli_query($Link, $Query);
            break;
        }
    }
    header("Location: ./edit.php?table_name=$table_name&action=edit_&error="); //открытие страницы после удаления вопроса
} else if ($this_action[0] == 'edit') { //редактирование вопросов
    if ((!isset($_GET['create_new_question'])) && (!isset($_GET['save_test'])) && (!isset($_GET['delete_test'])) && !isset($_GET['profile'])) { //отображение списка вопросов
        $_SESSION['test_name'] = $_GET['table_name'];
        $tname = '<input type="text" class="form-control mb-3" name="table_name" value="' . $_SESSION['test_name'] . '" hidden>
        <input type="text" class="form-control mb-3" name="error" value="" hidden>'; //скрытый инпут с ключом для постоянной передачи ключа с именем теста
        $Query = "SELECT * FROM $table_name";
        $Result = mysqli_query($Link, $Query);

        while ($Rows = mysqli_fetch_array($Result)) {
            $answers = preg_split("//", $Rows['right_answer']);
            $output_data .= '<tr>
        <td><div class="row g-3">
            <div class="col-md-12">
                <label class="form-label mt-3"><b>Вопрос:</b></label>
                <textarea class="form-control" name = "question_' . $Rows['id'] . '" rows="2" >' . $Rows['question'] . '</textarea>
            </div>
            <label class="form-label">Варианты ответа:</label>
            <div class="input-group">
                <div class="input-group-text">';
            $selected = false;
            for ($i = 0; $i < count($answers); $i++) {
                if ($answers[$i] == '1') {
                    $output_data .= '<input class="form-check-input mt-0" type="checkbox" name="check_ans_1_' . $Rows['id'] . '" checked >';
                    $selected = true;
                    break;
                }
            }
            if ($selected == false)
                $output_data .= '<input class="form-check-input mt-0" type="checkbox" name="check_ans_1_' . $Rows['id'] . '" >';
            $output_data .= '
                </div>
                <input type="text" class="form-control" name="ans_1_' . $Rows['id'] . '" value="' . $Rows['answer_1'] . '" >
              </div>
              <div class="input-group">
                <div class="input-group-text">';
            $selected = false;
            for ($i = 0; $i < count($answers); $i++) {
                if ($answers[$i] == '2') {
                    $output_data .= '<input class="form-check-input mt-0" type="checkbox" name="check_ans_2_' . $Rows['id'] . '" checked >';
                    $selected = true;
                    break;
                }
            }
            if ($selected == false)
                $output_data .= '<input class="form-check-input mt-0" type="checkbox" name="check_ans_2_' . $Rows['id'] . '" >';
            $output_data .= '
                </div>
                <input type="text" class="form-control" name="ans_2_' . $Rows['id'] . '" value="' . $Rows['answer_2'] . '">
              </div>
              <div class="input-group">
                <div class="input-group-text">';
            $selected = false;
            for ($i = 0; $i < count($answers); $i++) {
                if ($answers[$i] == '3') {
                    $output_data .= '<input class="form-check-input mt-0" type="checkbox" name="check_ans_3_' . $Rows['id'] . '" checked >';
                    $selected = true;
                    break;
                }
            }
            if ($selected == false)
                $output_data .= '<input class="form-check-input mt-0" type="checkbox" name="check_ans_3_' . $Rows['id'] . '" >';
            $output_data .= '
                </div>
                <input type="text" class="form-control" name="ans_3_' . $Rows['id'] . '" value="' . $Rows['answer_3'] . '" >
              </div>
              <div class="input-group">
                <div class="input-group-text">';
            $selected = false;
            for ($i = 0; $i < count($answers); $i++) {
                if ($answers[$i] == '4') {
                    $output_data .= '<input class="form-check-input mt-0" type="checkbox" name="check_ans_4_' . $Rows['id'] . '" checked >';
                    $selected = true;
                    break;
                }
            }
            if ($selected == false)
                $output_data .= '<input class="form-check-input mt-0" type="checkbox" name="check_ans_4_' . $Rows['id'] . '" >';
            $output_data .= '
                </div>
                <input type="text" class="form-control" name="ans_4_' . $Rows['id'] . '" value="' . $Rows['answer_4'] . '" >
              </div>
            <div class="col-6">
                <label class="form-label">Максимальный балл:</label>
                <input type="number" class="form-control mb-3" name="max_score_' . $Rows['id'] . '" value="' . $Rows['max_score'] . '" >
            </div>
            <div class="d-flex bd-highlight mb-4">
            <a class="form-control btn btn-outline-dark me-auto p-2 bd-highlight me-5" href="./edit.php?table_name=' . $_SESSION['test_name'] . '&action=delete_' . $Rows['id'] . '">Удалить</a>
            </div>
            </div>
        </div>
        <input type="text" class="form-control mb-3" name="table_name" value="' . $_SESSION['test_name'] . '" hidden>
        </td>
        </tr>';
        }
        include_once('edit.html');
    } else if (isset($_GET['profile'])) { //переход в профиль
        if ($_SESSION['authorization'] == true) {
            header('Location: profile.php');
        } else if ($_SESSION['authorization'] == false) {
            header('Location: authorization.php?error=');
        }
    } else {
        if (isset($_GET['delete_test'])) { //удаление теста
            $Query = "DROP TABLE $table_name";
            mysqli_query($Link, $Query);
            $Query = "SELECT * FROM test_list";
            $Result = mysqli_query($Link, $Query);
            while ($Rows = mysqli_fetch_array($Result)) {
                if ($Rows['test_name'] == $table_name)
                    $id = $Rows['id'];
            }
            $Query = "DELETE FROM test_list
            WHERE id = $id";
            mysqli_query($Link, $Query);
            header('Location: add_edit.php');
        } else if (isset($_GET['create_new_question'])) { //добавление вопроса
            $continue = true;
            while ($continue) {
                $successfull_insert = false;
                $right_answer = '';
                $question_text = '';
                $max_score = 0;
                $answer_1 = '';
                $answer_2 = '';
                $answer_3 = '';
                $answer_4 = '';
                $question_text = $_GET['new_question'];
                if ($question_text == '') {
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: не введён вопрос!</div>';
                    header("Location: ./edit.php?table_name=$table_name&action=edit_&error=$error");
                    break;
                }
                $max_score = (int)$_GET['max_score'];
                if ($max_score > 10 || $max_score < 1) {
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: некорректно указан максимальный балл!</div>';
                    header("Location: ./edit.php?table_name=$table_name&action=edit_&error=$error");
                    break;
                }
                $answer_1 = $_GET['ans_1'];
                $answer_2 = $_GET['ans_2'];
                $answer_3 = $_GET['ans_3'];
                $answer_4 = $_GET['ans_4'];
                if ($answer_1 == '' || $answer_2 == '' || $answer_3 == '' || $answer_4 == '') {
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: не введён вариант ответа!</div>';
                    header("Location: ./edit.php?table_name=$table_name&action=edit_&error=$error");
                    break;
                }
                if (isset($_GET['check_ans_1'])) {
                    $right_answer .= '1';
                }
                if (isset($_GET['check_ans_2'])) {
                    $right_answer .= '2';
                }
                if (isset($_GET['check_ans_3'])) {
                    $right_answer .= '3';
                }
                if (isset($_GET['check_ans_4'])) {
                    $right_answer .= '4';
                }
                if ($right_answer == '') {
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: Не указан правильный ответ!</div>';
                    header("Location: ./edit.php?table_name=$table_name&action=edit_&error=$error");
                    break;
                }
                $Query = "INSERT INTO $table_name VALUES(
                    0, 
                    '$question_text', 
                    '$answer_1', 
                    '$answer_2', 
                    '$answer_3', 
                    '$answer_4', 
                    $max_score, 
                    '$right_answer')";
                mysqli_query($Link, $Query);
                $successfull_insert = true;
                break;
            }
            if ($successfull_insert == true) {
                header("Location: ./edit.php?table_name=$table_name&action=edit_&error=$error");
            }
        } else if (isset($_GET['save_test'])) { //сохранение изменений (перезапись всех вопросов)
            $successfull_update = false;
            $table_name = $_SESSION['test_name'];
            $Query = "SELECT * FROM $table_name"; //прохождение по всем элементам таблицы этого теста
            $Result = mysqli_query($Link, $Query);
            while ($Rows = mysqli_fetch_array($Result)) {
                $id = $Rows['id'];
                $new_question_name = 'question_' . $Rows['id'];
                $new_question = $_GET[$new_question_name];
                if ($new_question == '') {
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: не введён вопрос!</div>';
                    header("Location: ./edit.php?table_name=$table_name&action=edit_&error=$error");
                    break;
                }
                $new_max_score_name = 'max_score_' . $Rows['id'];
                $new_max_score = (int)$_GET[$new_max_score_name];
                if ($new_max_score > 10 || $new_max_score < 1) {
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: некорректно указан максимальный балл!</div>';
                    header("Location: ./edit.php?table_name=$table_name&action=edit_&error=$error");
                    break;
                }
                $new_ans_1_name = 'ans_1_' . $Rows['id'];
                $new_ans_1 = $_GET[$new_ans_1_name];
                $new_ans_2_name = 'ans_2_' . $Rows['id'];
                $new_ans_2 = $_GET[$new_ans_2_name];
                $new_ans_3_name = 'ans_3_' . $Rows['id'];
                $new_ans_3 = $_GET[$new_ans_3_name];
                $new_ans_4_name = 'ans_4_' . $Rows['id'];
                $new_ans_4 = $_GET[$new_ans_4_name];
                if ($new_ans_1 == '' || $new_ans_2 == '' || $new_ans_3 == '' || $new_ans_4 == '') {
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: не введён вариант ответа!</div>';
                    header("Location: ./edit.php?table_name=$table_name&action=edit_&error=$error");
                    break;
                }
                $new_right_answer = '';
                $new_check_ans_1_name = 'check_ans_1_' . $Rows['id'];
                if (isset($_GET[$new_check_ans_1_name]))
                    $new_right_answer .= '1';
                $new_check_ans_2_name = 'check_ans_2_' . $Rows['id'];
                if (isset($_GET[$new_check_ans_2_name]))
                    $new_right_answer .= '2';
                $new_check_ans_3_name = 'check_ans_3_' . $Rows['id'];
                if (isset($_GET[$new_check_ans_3_name]))
                    $new_right_answer .= '3';
                $new_check_ans_4_name = 'check_ans_4_' . $Rows['id'];
                if (isset($_GET[$new_check_ans_4_name]))
                    $new_right_answer .= '4';
                //запись правильного ответа
                if ($new_right_answer == '') {
                    $error = '<div class="alert alert-danger" role="alert"> Ошибка: Не указан правильный ответ!</div>';
                    header("Location: ./edit.php?table_name=$table_name&action=edit_&error=$error");
                    break;
                }
                $Query = "UPDATE $table_name
                    SET 
                    question = '$new_question',
                    answer_1 = '$new_ans_1',
                    answer_2 = '$new_ans_2',
                    answer_3 = '$new_ans_3',
                    answer_4 = '$new_ans_4',
                    max_score = $new_max_score,
                    right_answer = '$new_right_answer'
                    where id = $id";
                mysqli_query($Link, $Query);
                $successfull_update = true;
            }
            if ($successfull_update == true) {
                header("Location: ./add_edit.php");
            }
        }
    }
}
mysqli_close($Link);
