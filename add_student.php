<?php
require_once 'db.php';

$message = ''; // Сообщение для пользователя

// 1. Проверяем, была ли отправлена форма методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 2. Получаем данные из $_POST
    $name = trim($_POST['name'] ?? '');
    $group = trim($_POST['group_name'] ?? '');
    $rating = $_POST['rating'] ?? null; // Null, если не задан

    // Простая валидация
    if (empty($name) || empty($group)) {
        $message = '<p style="color: red;">Ошибка: Поля "Имя" и "Группа" обязательны!</p>';
    } else {
        try {
            // 3. Используем подготовленное выражение для вставки данных (защита от SQL-инъекций!)
            $sql = "INSERT INTO students (name, group_name, rating) VALUES (:name, :group, :rating)";
            $stmt = $pdo->prepare($sql);

            // Выполняем запрос, передавая данные массивом
            $stmt->execute([
                'name' => $name,
                'group' => $group,
                'rating' => (float) $rating // Приводим к типу float/real
            ]);

            // 4. Успешное добавление
            $message = '<p style="color: green;">Студент добавлен!</p>';

            // Очищаем форму после успешного добавления (можно перенаправить, но пока выведем сообщение)
            // header('Location: index.php'); // Можно использовать для редиректа
            // exit();

        } catch (\PDOException $e) {
            $message = '<p style="color: red;">Ошибка при добавлении: ' . $e->getMessage() . '</p>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Добавить студента</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        form div {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"] {
            width: 300px;
            padding: 8px;
            border: 1px solid #ccc;
        }
    </style>
</head>

<body>
    <h1>Добавить нового студента</h1>

    <?php echo $message; ?>

    <p><a href="index.php">← Вернуться к списку студентов</a></p>

    <form method="POST" action="add_student.php">
        <div>
            <label for="name">Имя:</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div>
            <label for="group_name">Группа:</label>
            <input type="text" id="group_name" name="group_name" required>
        </div>
        <div>
            <label for="rating">Рейтинг (от 0 до 5):</label>
            <input type="number" step="0.1" min="0" max="5" id="rating" name="rating">
        </div>
        <button type="submit">Добавить</button>
    </form>
</body>

</html>