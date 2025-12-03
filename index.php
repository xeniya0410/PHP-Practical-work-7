<?php
// Подключаем соединение с БД
require_once 'db.php'; //require_once гарантирует, что файл подключится только раз и если файла нет — будет ошибка


$searchTerm = $_GET['search'] ?? ''; // Получаем значение поиска, если оно есть
$students = []; // Массив для хранения студентов

if ($searchTerm) {
    // Запрос с фильтрацией по имени (LIKE :search)
    $stmt = $pdo->prepare("SELECT * FROM students WHERE name LIKE :search");
    // Передаем значение с процентами для поиска подстроки
    $stmt->execute(['search' => "%" . $searchTerm . "%"]); //после выполнения запроса $stmt (statement) используется для получения данных
} else {
   
    $stmt = $pdo->query("SELECT * FROM students"); //если поиска нет, то выполняется обычный запрос
}

$students = $stmt->fetchAll(); //fetchAll() достает все строки из результата SQL-запроса

?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Список студентов</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .action-link {
            text-decoration: none;
            color: red;
        }
    </style>
</head>

<body>

    <h1>Список студентов</h1>

    <form method="GET" action="index.php"> //определяет форму на странице, где данные из полей ввода отправляются с помощью метода GET на страницу index.php
        <input type="text" name="search" placeholder="Поиск по имени" //placeholder - подсказка
            value="<?php echo htmlspecialchars($searchTerm); ?>"> //строка выводит в поле ввода введённый ранее текст поиска, одновременно защищая HTML от вредных символов
        //htmlspecialchars() преобразует специальные символы, такие как <, >, & и " , в их эквивалентные HTML-сущности, предотвращая тем самым уязвимости типа «межсайтовый скриптинг» (XSS). 
        <button type="submit">Найти</button>
        <a href="index.php">Сбросить</a>      //кнопка "Сбросить" просто перезагружает страницу без параметров
    </form>

    <p><a href="add_student.php"> Добавить нового студента</a></p>

    <?php if (count($students) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Группа</th>
                    <th>Рейтинг</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['id']); ?></td>
                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                        <td><?php echo htmlspecialchars($student['group_name']); ?></td>
                        <td><?php echo htmlspecialchars($student['rating']); ?></td>
                        <td>
                            <a href="delete.php?id=<?php echo htmlspecialchars($student['id']); ?>"
                                onclick="return confirm('Вы уверены, что хотите удалить студента <?php echo htmlspecialchars($student['name']); ?>?');"
                                class="action-link"> //передаёт ID студента в delete.php, включено JavaScript-подтверждение, если нажать отмена, переход не произойдет
                                Удалить
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Студенты не найдены.</p>
    <?php endif; ?>

</body>


</html>
