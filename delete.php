<?php
require_once 'db.php';

// 1. Принимаем ID из $_GET['id']
$id = $_GET['id'] ?? null;

// Проверяем, что ID корректный (целое число и не пустой)
if (!is_numeric($id) || $id <= 0) {
    die("Ошибка: Некорректный ID студента.");
}

try {
    // 2. Подготавливаем запрос DELETE
    $sql = "DELETE FROM students WHERE id = :id";
    $stmt = $pdo->prepare($sql);

    // 3. Выполняем его
    $stmt->execute(['id' => $id]);

    // 4. После удаления делаем редирект обратно на главную
    header('Location: index.php');
    exit();

} catch (\PDOException $e) {
    // В случае ошибки выводим сообщение
    die("Ошибка при удалении записи: " . $e->getMessage());
}
?>