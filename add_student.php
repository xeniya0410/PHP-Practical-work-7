<?php
require_once 'db.php';

$message = ''; // переменная для сообщений  для пользователя

// проверяем, была ли отправлена форма методом POST (если нет, код не выполняется)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // получаем данные из $_POST
    $name = trim($_POST['name'] ?? ''); //если поле есть - взять, если нет - пустая строка
    $group = trim($_POST['group_name'] ?? ''); //trim - убирает лишние пробелы по краям
    $rating = $_POST['rating'] ?? null; // null, если не задан

    //проверка, что обязательные поля не пустые (если поля пустые - показываем сообщение и не продолжаем вставку)
    if (empty($name) || empty($group)) {
        $message = '<p style="color: red;">Ошибка: Поля "Имя" и "Группа" обязательны!</p>';
    } else {
        try {
            // используем подготовленное выражение для вставки данных (защита от SQL-инъекций)
            $sql = "INSERT INTO students (name, group_name, rating) VALUES (:name, :group, :rating)"; //:name, :group, :rating - параметры, а не прямые переменные
            $stmt = $pdo->prepare($sql);

            // выполняем запрос, передавая данные массивом
            $stmt->execute([
                'name' => $name,
                'group' => $group,
                'rating' => (float) $rating // приводим к типу float
            ]);

            // перенаправление после успешного добавления
            header('Location: index.php'); 
            exit();

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
