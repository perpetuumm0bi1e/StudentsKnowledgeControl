<?php
$DBHost = "localhost";
$DBUser = "root";
$DBPassword = "";
$DBName = "StudentsKnowledgeControl";
$Link = mysqli_connect($DBHost, $DBUser, $DBPassword); //подключение к серверу

//создание БД
$Query = "CREATE DATABASE $DBName";
mysqli_query($Link, $Query);
mysqli_select_db($Link, $DBName); //выбор БД

//создание таблицы с администраторами
$Query = "CREATE TABLE admin_table (id INT(2) PRIMARY KEY AUTO_INCREMENT,
admin_login VARCHAR(30),
admin_password VARCHAR(30),
admin_key_word VARCHAR(30))";
mysqli_query($Link, $Query);

//вставка данных об администраторах
$Query = "INSERT INTO admin_table VALUES
(0, 'admin', 'pswd', 'лес'),
(0, 'admin2', 'pswd', 'река')";
mysqli_query($Link, $Query);

//создание таблицы со студентами
$Query = "CREATE TABLE students_table (id INT(2) PRIMARY KEY AUTO_INCREMENT,
student_login VARCHAR(30),
student_password VARCHAR(30),
student_key_word VARCHAR(30),
student_surname VARCHAR(30),
student_name VARCHAR(30),
student_patronymic VARCHAR(30),
score INT(4))";
mysqli_query($Link, $Query);

//вставка данных о студентах
$Query = "INSERT INTO students_table VALUES
(0, 'ivan123', 'pswd', 'key', 'Иванов','Иван','Иванович', 0), 
(0, 'elena321', 'pswd', 'key', 'Макухина','Елена','Алексеевна', 0),
(0, 'perpetuumm0bi1e', '842600', 'малмыж', 'Кусанова','Катерина','Алексеевна', 180)";
mysqli_query($Link, $Query);
//создание таблицы со списком пройденных тестов для нового студента (при регистрации нового студента создается автоматически)
$stud_tests_table = 'ivan123_tests';
$Query = "CREATE TABLE $stud_tests_table (id INT(4) PRIMARY KEY AUTO_INCREMENT,
                    test_id INT(4),
                    stud_score INT(4),
                    total_score INT(4))";
mysqli_query($Link, $Query);
$stud_tests_table = 'elena321_tests';
$Query = "CREATE TABLE $stud_tests_table (id INT(4) PRIMARY KEY AUTO_INCREMENT,
                    test_id INT(4),
                    stud_score INT(4),
                    total_score INT(4))";
mysqli_query($Link, $Query);
$stud_tests_table = 'perpetuumm0bi1e_tests';
$Query = "CREATE TABLE $stud_tests_table (id INT(4) PRIMARY KEY AUTO_INCREMENT,
                    test_id INT(4),
                    stud_score INT(4),
                    total_score INT(4))";
mysqli_query($Link, $Query);
//создание таблицы с тестами
$Query = "CREATE TABLE test_list (id INT(2) PRIMARY KEY AUTO_INCREMENT,
creator_login VARCHAR(30),
test_name VARCHAR(1000))";
mysqli_query($Link, $Query);

//////////////////////////////////////
//заполнение некоторым кол-вом тестов:
$Query = "INSERT INTO test_list VALUES
(1, 'admin', 'Основы_PHP'),
(5, 'admin', 'Проверочная_работа_по_физике'),
(6, 'admin', 'Тест_по_химии'),
(8, 'admin', 'Предмет_астрономии'),
(9, 'admin', 'Основные_понятия_и_законы_термодинамики'),
(10, 'admin', 'Тестирование_по_теоретической_механике');";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE предмет_астрономии (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    question varchar(500),
    answer_ varchar(500),
    answer_2 varchar(500),
    answer_3 varchar(500),
    answer_4 varchar(500),
    max_score int(2),
    right_answer varchar(4))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO предмет_астрономии VALUES
(1, 'Наука о небесных светила, о законах их движения, строения и развития, а также о строении и развитии Вселенной в целом называется…', 'Астрометрия', 'Астрофизика', 'Астрономия ', 'Нет правильного ответа', 1, '3'),
(2, 'Гелиоцентричну модель мира разработал…', 'Хаббл Эдвин', 'Николай Коперник', 'Тихо Браге', 'Клавдий Птолемей', 1, '2'),
(3, 'К планетам земной группы относятся…', 'Меркурий, Венера, Уран, Земля', 'Марс, Земля, Венера, Меркурий', 'Венера, Земля, Меркурий, Фобос', 'Меркурий, Земля, Марс, Юпитер', 1, '2'),
(4, 'Второй от Солнца планета называется…', 'Венера ', 'Меркурий', 'Земля', 'Марс', 1, '1'),
(5, 'Нижняя точка пересечения отвесной линии с небесной сферой называется…', 'Точках юга', 'Точках севера', 'Зенит', 'Надир', 1, '4');";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE предмет_астрономии_results (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    test_id int(4),
    question_id int(2),
    stud_id int(4),
    stud_answer varchar(50),
    stud_score int(2),
    max_score int(2))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO предмет_астрономии_results VALUES
(1, 8, 1, 3, '3', 1, 1),
(2, 8, 2, 3, '2', 1, 1),
(3, 8, 3, 3, '2', 1, 1),
(4, 8, 4, 3, '1', 1, 1),
(5, 8, 5, 3, '4', 1, 1);";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE проверочная_работа_по_физике (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    question varchar(500),
    answer_1 varchar(500),
    answer_2 varchar(500),
    answer_3 varchar(500),
    answer_4 varchar(500),
    max_score int(2),
    right_answer varchar(4))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO проверочная_работа_по_физике VALUES
(1, 'Опыт Эрстеда показывает, что:', 'проводник с током действует на электрические заряды', 'движущиеся заряды в проводнике создают магнитное поле', 'два проводника с током не взаимодействуют между собой', 'проводник с током не действует на магнитную стрелку', 5, '2'),
(2, 'Магнитное поле существует вокруг:', 'только неподвижных электрических зарядов', 'как неподвижных, так и движущихся электрических зарядов', 'всех тел', 'только движущихся электрических зарядов', 5, '4'),
(3, 'Чтобы изменить магнитные полюса катушки с током на противоположные, необходимо:', 'вставить в катушку железный сердечник', 'изменить силу тока', 'изменить направление тока в катушке', 'изменить число витков в катушке', 5, '3'),
(4, 'Чтобы ослабить магнитное действие катушки с током, необходимо:', 'изменить направление тока в катушке', 'уменьшить число витков в катушке', 'вставить в катушку стеклянный сердечник', 'вставить в катушку железный сердечник', 5, '2'),
(5, 'Магнитные полюсы:', 'разноимённые притягиваются, одноименные отталкиваются', 'разноимённые отталкиваются, одноименные притягиваются', 'разноимённые притягиваются, одноименные притягиваются', 'разноимённые отталкиваются, одноименные отталкиваются', 5, '1');";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE проверочная_работа_по_физике_results (
     id INT(4) PRIMARY KEY AUTO_INCREMENT,
    test_id int(4),
    question_id int(2),
    stud_id int(4),
    stud_answer varchar(50),
    stud_score int(2),
    max_score int(2))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO проверочная_работа_по_физике_results VALUES
(1, 5, 1, 3, '2', 5, 5),
(2, 5, 2, 3, '4', 5, 5),
(3, 5, 3, 3, '3', 5, 5),
(4, 5, 4, 3, '2', 5, 5),
(5, 5, 5, 3, '1', 5, 5);";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE основные_понятия_и_законы_термодинамики (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    question varchar(500),
    answer_1 varchar(500),
    answer_2 varchar(500),
    answer_3 varchar(500),
    answer_4 varchar(500),
    max_score int(2),
    right_answer varchar(4))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO основные_понятия_и_законы_термодинамики VALUES
(1, 'Температура тела А равна 100 К, температура тела Б равна 100 °С. Какое из тел имеет более высокую температуру?', 'тело А', 'тело Б', 'тела А и Б имеют одинаковую температуру', 'сравнивать значения температуры нельзя, так как они даны в разных единицах', 5, '2'),
(2, 'В электрическом чайнике нагревание воды происходит в основном за счёт', 'излучения и конвекции', 'конвекции и теплопроводности', 'теплопроводности', 'конвекции', 5, '3'),
(3, 'Внутренняя энергия тела не зависит от', 'скорости его движения как целого', 'взаимодействия его молекул', 'скорости движения его молекул', 'его температуры', 5, '1'),
(4, 'Как изменяется внутренняя энергия пара в процессе конденсации при температуре конденсации?', 'кинетическая энергия молекул пара увеличивается, потенциальная — уменьшается', 'кинетическая энергия молекул пара не изменяется, потенциальная — увеличивается', 'кинетическая энергия молекул пара уменьшается, потенциальная — не изменяется', 'кинетическая энергия молекул пара не изменяется, потенциальная — уменьшается', 5, '3'),
(5, 'Чему равно изменение внутренней энергии газа, если над ним совершена работа 300 Дж и газу передано количество теплоты 100 Дж?', '100 Дж', '200 Дж', '300 Дж', '400 Дж', 5, '4');";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE основные_понятия_и_законы_термодинамики_results (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    test_id int(4),
    question_id int(2),
    stud_id int(4),
    stud_answer varchar(50),
    stud_score int(2),
    max_score int(2))";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE основы_php (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    question varchar(500),
    answer_1 varchar(500),
    answer_2 varchar(500),
    answer_3 varchar(500),
    answer_4 varchar(500),
    max_score int(2),
    right_answer varchar(2))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO основы_php VALUES
(1, 'PHP - это...', 'веб-сайт', 'скриптовый язык, работающий на стороне сервера', 'домашняя страница', 'язык разметки', 10, '2'),
(2, 'Можно ли запустить PHP на Linux?', 'да', 'нет', 'только на некоторых версиях ', 'только при правильной настройке', 10, '1'),
(3, 'Какой символ должен быть в конце каждого оператора?', 'точка', 'точка с запятой', 'двоеточие', 'запятая', 10, '2'),
(4, 'С помощью какой функции можно считать данные?', 'readtext()', 'readline()', 'read_line()', 'read()', 10, '2'),
(5, 'Какие сочетания символов указывает на комментарий?', '/* ... */', '**', '<!-- ... -->', '//', 10, '14');";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE основы_php_results (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    test_id int(4),
    question_id int(2),
    stud_id int(4),
    stud_answer varchar(50),
    stud_score int(2),
    max_score int(2))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO основы_php_results VALUES
(6, 1, 1, 1, '2', 10, 10),
(7, 1, 2, 1, '3', 0, 10),
(8, 1, 3, 1, '', 0, 10),
(9, 1, 4, 1, '2', 10, 10),
(10, 1, 5, 1, '14', 10, 10),
(21, 1, 1, 3, '2', 10, 10),
(22, 1, 2, 3, '1', 10, 10),
(23, 1, 3, 3, '2', 10, 10),
(24, 1, 4, 3, '2', 10, 10),
(25, 1, 5, 3, '14', 10, 10);";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE тест_по_химии (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    question varchar(500),
    answer_1 varchar(500),
    answer_2 varchar(500),
    answer_3 varchar(500),
    answer_4 varchar(500),
    max_score int(2),
    right_answer varchar(4))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO тест_по_химии VALUES
(1, 'Какую разновидность кристаллической решетки имеют щелочи?', 'атомную', 'молекулярную', 'ионную', 'металлическую', 1, '3'),
(2, 'Укажите общее число совместных электронных пар в молекуле азота:', '2', '3', '1', '4', 1, '2'),
(3, 'Узлы кристаллической решетки Cu содержат:', 'молекулы', 'атомы', 'атомы и ионы', 'ионы', 1, '3'),
(4, 'Белый фосфор имеет тип химической связи:', 'двойную ионную', 'тройную ковалентную полярную', 'металлическую', 'одинарную ковалентную неполярную', 1, '4'),
(5, 'Для ковалентной разновидности связи характерна: ', 'высокая температура плавления', 'ненаправленность', 'ненасыщенность', 'полярность', 1, '4'),
(6, 'Укажите вещество с ковалентной полярной связью:', 'кислород', 'йодоводород', 'хлор', 'бромид натрия', 1, '2'),
(7, 'Как называются отрицательно заряженные ионы?', 'электроны', 'нейтроны', 'анионы', 'катионы', 1, '3'),
(8, 'Металлическая разновидность химической связи формируется между атомами:', 'мышьяка', 'цезия', 'водорода', 'азота', 1, '2');";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE тест_по_химии_results (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    test_id int(4),
    question_id int(2),
    stud_id int(4),
    stud_answer varchar(50),
    stud_score int(2),
    max_score int(2))";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE тестирование_по_теоретической_механике (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    question varchar(500),
    answer_1 varchar(500),
    answer_2 varchar(500),
    answer_3 varchar(500),
    answer_4 varchar(500),
    max_score int(2),
    right_answer varchar(4))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO тестирование_по_теоретической_механике VALUES
(1, 'Ускорение - есть...', 'первая производная от скорости по времени', 'вторая производная от скорости по времени', 'первая производная от радиус-вектора по времени', 'вторая производная от радиус-вектора по времени', 1, '14'),
(2, 'Виды сил в механическом движении?', 'сила упругости', 'сила притяжения', 'сила тяготения', 'сила трения', 1, '1234'),
(3, 'Что такое деформация?', 'изменение формы тела', 'изменение размера тела', 'изменение вида тела', 'изменение скорости тела', 1, '1'),
(4, 'Назовите виды деформации', 'сжатие ', 'перелом', 'кручение ', 'изгиб ', 1, '134'),
(5, 'Причина деформации?', 'тепловое расширение', 'действие внешних сил', 'действие внутренних сил', 'движение частиц тела относительно друг друга', 1, '4'),
(6, 'Следствие деформации?', 'возникновение силы тяготения', 'возникновение силы упругости', 'возникновение силы трения', 'возникновение механической силы', 1, '2'),
(7, 'Сухое трение разделяют на?', 'трение скольжения', 'трение соприкосновения', 'трение качения', 'трение вращения', 1, '3'),
(8, 'Чем определяется коэффициент деформации?', 'длиной пружины', 'толщиной пружины', 'жесткостью пружины', 'сжатием пружины', 1, '3');";
mysqli_query($Link, $Query);
$Query = "CREATE TABLE тестирование_по_теоретической_механике_results (
    id INT(4) PRIMARY KEY AUTO_INCREMENT,
    test_id int(4),
    question_id int(2),
    stud_id int(4),
    stud_answer varchar(50),
    stud_score int(2),
    max_score int(2))";
mysqli_query($Link, $Query);
$Query = "INSERT INTO perpetuumm0bi1e_tests VALUES
(2, 8, 5, 5),
(4, 5, 25, 25),
(6, 1, 50, 50);";
mysqli_query($Link, $Query);
//отключение от сервера
mysqli_close($Link);
