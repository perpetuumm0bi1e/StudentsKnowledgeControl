<?php
session_start();
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$error = '';
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к БД
mysqli_select_db($Link, $DBName);
//получение значений из гет запроса
$table_name = $_GET['table_name'];
//вспомогательные переменные
$output_data = '';
$tname = ' ';
$name_words = preg_split("/_/", $table_name);
$name = '';
for ($i = 0; $i < count($name_words) - 1; $i++) {
  $name .= $name_words[$i];
  $name .= ' ';
}
$name .= $name_words[count($name_words) - 1]; //название теста для вывода
if (!isset($_GET['save_result']) && !isset($_GET['profile'])) { //отображение страницы
  $_SESSION['test_name'] = $_GET['table_name'];
  $tname = '<input type="text" class="form-control mb-3" name="table_name" value="' . $_SESSION['test_name'] . '" hidden>'; //скрытый инпут с ключом для постоянной передачи ключа с именем теста
  $Query = "SELECT * FROM $table_name";
  $Result = mysqli_query($Link, $Query);
  while ($Rows = mysqli_fetch_array($Result)) {
    //для отображения вопросов на странице:
    $output_data .= '
        <tr>
  <td>
    <div class="row g-3">
      <div class="col-md-12">
        <label class="form-label mt-3"><b>Вопрос №' . $Rows['id'] . '</b></label>
      </div>
      <div class="col-md-12">
        <label class="form-label mb-5">' . $Rows['question'] . '</label>
      </div>
      <label class="form-label"><b>Ответ:</b></label>
      <div class="input-group mb-3">
          <div class="input-group-text">
            <input class="form-check-input mt-0" type="checkbox" name="check_ans_1_' . $Rows['id'] . '">
          </div>
          <input type="text" class="form-control" name="ans_1" value="' . $Rows['answer_1'] . '" disabled>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-text">
            <input class="form-check-input mt-0" type="checkbox" name="check_ans_2_' . $Rows['id'] . '">
          </div>
          <input type="text" class="form-control" name="ans_2" value="' . $Rows['answer_2'] . '" disabled>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-text">
            <input class="form-check-input mt-0" type="checkbox" name="check_ans_3_' . $Rows['id'] . '">
          </div>
          <input type="text" class="form-control" name="ans_3" value="' . $Rows['answer_3'] . '" disabled>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-text">
            <input class="form-check-input mt-0" type="checkbox" name="check_ans_4_' . $Rows['id'] . '">
          </div>
          <input type="text" class="form-control" name="ans_4" value="' . $Rows['answer_4'] . '" disabled>
        </div>
        <label class="form-label mb-4 mt-4"><b>Максимальный балл: ' . $Rows['max_score'] . '</b></label>
    </div>
  </td>
</tr>';
  }
  include_once('test.html');
} else if (isset($_GET['profile'])) { //переход в профиль
  if ($_SESSION['authorization'] == true) {
    header('Location: profile.php');
  } else if ($_SESSION['authorization'] == false) {
    header('Location: authorization.php?error=');
  }
} else { //если тест отправлен
  $total_test_score = 0; //общий возможный балл за тест
  $score = 0; //общий afrnbxtcrbq балл за тест
  $table_name = $_SESSION['test_name'];
  if ($_SESSION['status'] == 'student') { //если студент, сохранение результата
    $Query = 'SELECT * FROM students_table';
    $Result = mysqli_query($Link, $Query);
    while ($Rows = mysqli_fetch_array($Result)) { //вычисление id студента для добавления записи в таблицу с результатами прохождения теста
      if ($Rows['student_login'] == $_SESSION['login'] && $Rows['student_password'] == $_SESSION['password']) {
        $stud_id = $Rows['id'];
      }
    }
    $right = 0; //кол-во правильно выбранных ответов на вопрос
    $Query = "SELECT * FROM test_list";
    $Result = mysqli_query($Link, $Query);
    while ($Rows = mysqli_fetch_array($Result)) {
      if ($Rows['test_name'] == $table_name) {
        $test_id = $Rows['id']; //id теста для запись в таблицу с результататми
        break;
      }
    }
  }
  $Query = "SELECT * FROM $table_name"; //прохождение по всем вопросам теста для проверки ответов
  $Result = mysqli_query($Link, $Query);
  while ($Rows = mysqli_fetch_array($Result)) { //расчет кол-ва баллов
    $stud_score = 0; //балл студента за этот вопрос
    $total_test_score += $Rows['max_score']; //расчёт максималльного балла за тест
    $question_id = $Rows['id']; //id вопроса
    $stud_ans = ''; //ответ студента
    $right_ans = '';
    $element_name = 'check_ans_1_' . "$question_id";
    if (isset($_GET[$element_name])) {
      $stud_ans .= "1";
    }
    $element_name = 'check_ans_2_' . "$question_id";
    if (isset($_GET[$element_name])) {
      $stud_ans .= "2";
    }
    $element_name = 'check_ans_3_' . "$question_id";
    if (isset($_GET[$element_name])) {
      $stud_ans .= "3";
    }
    $element_name = 'check_ans_4_' . "$question_id";
    if (isset($_GET[$element_name])) {
      $stud_ans .= "4";
    }
    if ($Rows['right_answer'] == $stud_ans) { //если выбраны все правильные ответы
      $score += $Rows['max_score']; //увеличение балла за тест
      $stud_score = $Rows['max_score']; //присвоение значения балла за вопрос
    }
    if ($_SESSION['status'] == 'student') {
    $table_name_results = $table_name . '_results'; //название таблицы с результататми прохождения данного теста
    $Query = "SELECT * FROM $table_name_results"; //удаление старого результата из таблицы
    $Result1 = mysqli_query($Link, $Query);
    while ($Table_rows = mysqli_fetch_array($Result1)) {
      if ($Table_rows['test_id'] == $test_id && $Table_rows['question_id'] == $question_id && $Table_rows['stud_id'] == $stud_id) {
        $this_id = $Table_rows['id'];
        $Query = "DELETE FROM $table_name_results
        WHERE id = $this_id;";
        mysqli_query($Link, $Query);
        break;
      }
    }
    $max_score = $Rows['max_score'];
    //запись результата в таблицу
    $Query = "INSERT INTO $table_name_results VALUES(
      0, 
      $test_id, 
      $question_id, 
      $stud_id, 
      '$stud_ans', 
      $stud_score, 
      $max_score)";
    mysqli_query($Link, $Query);
    }
  }
  if ($_SESSION['status'] == 'student') { //запись баллов в таблицу, если тест проходил студент
    $Query = 'SELECT * FROM students_table';
    $Result = mysqli_query($Link, $Query);
    while ($Rows = mysqli_fetch_array($Result)) { //увеличение опыта у студента
      if ($Rows['student_login'] == $_SESSION['login'] && $Rows['student_password'] == $_SESSION['password']) {
        $id = $Rows['id'];
        $new_score = $Rows['score'] + $score; //увеличение общего кол-ва опыта студента
        $Query = "UPDATE students_table
                SET score = $new_score
                where id = $id";
        mysqli_query($Link, $Query);
        break;
      }
    }
    $stud_tests_table = $_SESSION['login'] . '_tests'; //название таблицы со списком пройденных тестов
    $Query = "SELECT * FROM $stud_tests_table"; //удаление старого результата из таблицы
    $Result2 = mysqli_query($Link, $Query);
    while ($Table_rows1 = mysqli_fetch_array($Result2)) {
      if ($Table_rows1['test_id'] == $test_id) {
        $this_id = $Table_rows1['id'];
        $Query = "DELETE FROM $stud_tests_table
        WHERE id = $this_id;";
        mysqli_query($Link, $Query);
        break;
      }
    }
    //добавление записи в таблицу с историей прохождения тестов студентом
    $Query = "INSERT INTO $stud_tests_table VALUES(
      0, 
      $test_id, 
      $score, 
      $total_test_score)";
    mysqli_query($Link, $Query);
  }
  header("Location: ./result.php?table_name=$table_name&score=$score&total_test_score=$total_test_score"); //переход на страницу с отображением результата
}
mysqli_close($Link);
