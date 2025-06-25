<?php
require_once __DIR__ . '/../src/AmoSender.php';
require_once __DIR__ . '/../src/Logger.php';
require_once __DIR__ . '/../src/FormHandler.php';

use Src\AmoSender;
use Src\FormHandler;
use Src\Logger;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    try {
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImE2MWNjNzBiZjdlYTkxY2Y1NDMyMDNkOGMxN2M3M2IzZTkwNTJlMTc0YjYzYzU0MmMzYjgyOWU5NWExYzM0ZmFkYzBkOGNlNmM5OTYxZmI5In0.eyJhdWQiOiI0ZGJhOWExYi1hNDJiLTQ3MGQtOWNhYi02MTMyM2ZkNjc3MDYiLCJqdGkiOiJhNjFjYzcwYmY3ZWE5MWNmNTQzMjAzZDhjMTdjNzNiM2U5MDUyZTE3NGI2M2M1NDJjM2I4MjllOTVhMWMzNGZhZGMwZDhjZTZjOTk2MWZiOSIsImlhdCI6MTc1MDg1NDExOSwibmJmIjoxNzUwODU0MTE5LCJleHAiOjE3NjcxMzkyMDAsInN1YiI6IjEyNjY4NjE0IiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMyNTA4NzE4LCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiN2RlYzI4MDYtN2ZkZC00ZWNmLWE4NTgtMmQ1YTlkYjFhOWM2IiwiYXBpX2RvbWFpbiI6ImFwaS1iLmFtb2NybS5ydSJ9.FXyB-dCFVdgi6E8D8B8rp-pZlOSH_W4mxn7k7Dl7hOleaG-mruhJFE-3QNzZqryEQiwOaw7tptoAg1Y998kNEcdfacFsgCH_gKEEpxySA3mwJL1bnkSfLcWr5gHSLqtcZvoJ9SwoxwMprjCFtpompcKTdORL1E3JZB0bFr2u3RDLjvSxVTCQTE3qnF6OgQqCUSwBwUrQ9bGiEnpK8_tjDkJDYlNbGKdW4Ru_1YCTl3TuNAqLYapHM7auFSwGdDqPzVkJyEBUrd-QZb9ZxbzYMVBTw29CmBKyjsy2G9L-OZYZkGN5FmzH9FkTuy-e-XQxW1IXm0Dfn8H4WCnAp1Carw'; // актуальный access_token
        $sender = new AmoSender($token);
        $handler = new FormHandler($sender);
        $result = $handler->process($_POST);

        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Заявка успешно отправлена!']);
    } catch (Exception $e) {
        Logger::write("Ошибка: " . $e->getMessage());
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <title>Форма заявки</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<div class="form-container">
    <h2>Заявка</h2>
    <form method="POST" id="leadForm">
        <label>
            Имя:
            <input type="text" name="name" placeholder="Иванов Иван" required>
        </label>
        <label>
            Email:
            <input type="email" name="email" placeholder="email@example.com" required>
        </label>
        <label>
            Телефон:
            <input type="text" name="phone" placeholder="+79991234567" required>
        </label>
        <label>
            Цена:
            <input type="number" name="price" placeholder="1000" required>
        </label>
        <input type="hidden" name="more_than_30" value="0" id="timeMarker">
        <button type="submit">Отправить</button>
    </form>
</div>

<script src="/assets/script.js"></script>
</body>
</html>
