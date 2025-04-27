<?php
// Подключаем файл стилей
echo "<link rel='stylesheet' href='style.css'>";

// Параметры подключения к удаленной базе данных
$host = 'example.com'; // Замените на адрес вашего удаленного хоста (например, sql.example.com)
$dbname = 'if0_38845073_dima'; // Имя вашей базы данных
$username = 'if0_38845073'; // Имя пользователя базы данных
$password = 'dimkalubimka999'; // Пароль пользователя базы данных

// Подключение к базе данных с обработкой ошибок
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Устанавливаем режим обработки ошибок
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Ошибка подключения: " . $e->getMessage();
    exit;
}

// Получение сообщений из базы данных
$statement = $pdo->query("SELECT * FROM comments ORDER BY id DESC");
$comments = $statement->fetchAll(PDO::FETCH_ASSOC);

// Выводим каждый комментарий
foreach ($comments as $comment) {
    echo "<div class='comment'>";
    echo "<p><strong>{$comment['name']} {$comment['familiya']}</strong> написал(а):</p>";
    echo "<p>{$comment['comment']}</p>";
    echo "<hr>";
    echo "</div>";
}

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $familiya = $_POST['familiya'];
    $email = $_POST['email'];
    $messag = $_POST['message'];

    echo "<p>Сообщение от: $name $familiya ($email): <br><div>$messag</div></p>";

    // Подготовленный запрос для вставки данных
    $sql = 'INSERT INTO `comments` (`name`, `familiya`, `email`, `comment`) 
        VALUES (:name, :familiya, :email, :comment)';
    $stmt = $pdo->prepare($sql);

    // Выполнение запроса
    if ($stmt->execute([ ':name' => $name, ':familiya' => $familiya, ':email' => $email, ':comment' => $messag ])) {
        echo "Данные успешно добавлены";
    } else {
        echo "Ошибка: " . $pdo->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Супер-пупер сервер!</title>
</head>
<body>

<header>
    <h1>Супер-пупер сервер!</h1>
</header>

<h2>Отправьте сообщение себе!</h2>
<h3>Оффлайн сообщение!</h3>

<!-- Форма отправки сообщения -->
<form method="post">
    <label>Имя:</label><br>
    <input required name="name" type="text"><br><br>

    <label>Фамилия:</label><br>
    <input name="familiya" placeholder="(не обязательно)" type="text"><br><br>

    <label>Почта:</label><br>
    <input required name="email" type="email"><br><br>

    <label>Сообщение:</label><br>
    <textarea required name="message" rows="5" cols="30"></textarea><br><br>

    <input type="submit"><br>
</form>

<h2>Полученные сообщения:</h2>

<!-- Здесь будут выводиться комментарии -->
<div id="comments">
    <!-- Комментарии будут выводиться через PHP -->
</div>

</body>
</html>
