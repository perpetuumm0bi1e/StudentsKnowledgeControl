<?php
$error = '';
//переменные из гет запроса
$table_name = $_GET['table_name'];
$score = $_GET['score'];
$total_test_score = $_GET['total_test_score'];
//вспомогательные переменные
$name_words = preg_split("/_/", $table_name); //название теста для вывода (без специальных разделителей)
$name = '';
for ($i = 0; $i < count($name_words) - 1; $i++) {
    $name .= $name_words[$i];
    $name .= ' ';
}
$name .= $name_words[count($name_words) - 1];
include_once('result.html');
