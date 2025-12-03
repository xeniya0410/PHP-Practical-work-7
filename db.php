<?php
$host = '127.0.0.1';
$db = 'practice_db';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';
$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; //строка подключения - DSN (Data Source Name) необход для pdo
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, //любые ошибки PDO будут вызывать исключение
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, //fetch() будет возвращать строки в виде ассоциативного массива
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options); //если все прошло успешно, в переменной $pdo появится рабочее соединение с базой данных
} catch (\PDOException $e) {
    die("Ошибка подключения: " . $e->getMessage());
}

?>
