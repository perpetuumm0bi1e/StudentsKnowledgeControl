<?php
session_start();
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к БД
mysqli_select_db($Link, $DBName);
//переменные из гет запроса
$total_score = $_GET['total_score'];
$stud_score = $_GET['stud_score'];
$test_id = $_GET['id'];
$table_name = $_GET['test_name'];
$table_name_results = $table_name . '_results'; //название таблицы с результататми теста
$output_data = '';
$name_words = preg_split("/_/", $table_name);
$name = '';
for ($i = 0; $i < count($name_words) - 1; $i++) {
  $name .= $name_words[$i];
  $name .= ' ';
}
$name .= $name_words[count($name_words) - 1]; //название теста для вывода
$Query = "SELECT * FROM $table_name_results"; //поиск в таблице с результатами
$Result = mysqli_query($Link, $Query);
while ($Rows = mysqli_fetch_array($Result)) {
  if ($Rows['test_id'] == $test_id) {
    $stud_ans = preg_split("/ /", $Rows['stud_answer']);
    for ($i = 0; $i < count($stud_ans); $i++) {
      $Query = "SELECT * FROM $table_name"; //поиск в таблице теста (для отображения вопросов и вариантов ответа)
      $Result1 = mysqli_query($Link, $Query);
      while ($Rows1 = mysqli_fetch_array($Result1)) {
        if ($Rows1['id'] == $Rows['question_id']) {
          $output_data .= '<tr>
  <td>
    <div class="row g-3">
      <div class="col-md-12">
        <label class="form-label mt-3"><b>Вопрос № ' . $Rows['question_id'] . '</b></label>
      </div>
      <div class="col-md-12">
        <label class="form-label mb-5">' . $Rows1['question'] . '</label>
      </div>
      <label class="form-label"><b>Ответ:</b></label>
      <div class="input-group mb-3">
          <div class="input-group-text">';
          if ($stud_ans[$i] == 1) {
            $output_data .= '<input class="form-check-input mt-0" type="checkbox" checked>';
          } else {
            $output_data .= '<input class="form-check-input mt-0" type="checkbox">';
          }
          $output_data .= '</div>
          <input type="text" class="form-control" name="ans_1" value="' . $Rows1['answer_1'] . '" disabled>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-text">
          ';
          if ($stud_ans[$i] == 2) {
            $output_data .= '<input class="form-check-input mt-0" type="checkbox" checked>';
          } else {
            $output_data .= '<input class="form-check-input mt-0" type="checkbox">';
          }
          $output_data .= '</div>
          <input type="text" class="form-control" name="ans_2" value="' . $Rows1['answer_2'] . '" disabled>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-text">';
          if ($stud_ans[$i] == 3) {
            $output_data .= '<input class="form-check-input mt-0" type="checkbox" checked>';
          } else {
            $output_data .= '<input class="form-check-input mt-0" type="checkbox">';
          }
          $output_data .= '</div>
          <input type="text" class="form-control" name="ans_3" value="' . $Rows1['answer_3'] . '" disabled>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-text">';
          if ($stud_ans[$i] == 4) {
            $output_data .= '<input class="form-check-input mt-0" type="checkbox" checked>';
          } else {
            $output_data .= '<input class="form-check-input mt-0" type="checkbox">';
          }
          $output_data .= '</div>
          <input type="text" class="form-control" name="ans_4" value="' . $Rows1['answer_4'] . '" disabled>
        </div>
        <label class="form-label mb-4 mt-4"><b>Балл: ' . $Rows['stud_score'] . ' / ' . $Rows['max_score'] . '</b></label>
    </div>
  </td>
</tr>';
        }
      }
    }
  }
}
$output_data .= '<tr>
<td>
  <div class="row g-3">
    <div class="col-md-12">
      <label class="form-label mt-5 mb-5"><b>Итог: ' . $stud_score . ' / ' . $total_score . '</b></label>
    </div>
  </div>
</td>
</tr>';
include_once('old_result.html');
mysqli_close($Link);
