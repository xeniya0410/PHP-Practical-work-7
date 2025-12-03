<?php
require_once 'db.php';

// принимаем ID из $_GET['id'], если id нет → $id = null
$id = $_GET['id'] ?? null;

// проверяем, что id корректный (целое число и не пустой)
if (!is_numeric($id) || $id <= 0) {
    die("Ошибка: Некорректный ID студента."); //если ID неверный → прекращаем выполнение через die()
}

try {
    
    $sql = "DELETE FROM students WHERE id = :id"; //удаляет строку из таблицы students, где поле id равно переданному ID  (:id - именованный параметр)
    $stmt = $pdo->prepare($sql); //prepare() создает объект подготовленного запроса

    //выполняем запрос
    $stmt->execute(['id' => $id]);

    // после удаления делаем редирект обратно на главную
    header('Location: index.php');
    exit();

} catch (\PDOException $e) {
    // в случае ошибки выводим сообщение
    die("Ошибка при удалении записи: " . $e->getMessage());
}

?>
