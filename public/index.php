<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $price = $_POST['price'] ?? '';
    $moreThan30 = $_POST['more_than_30'] ?? '0';

    if (empty($name) || empty($email) || empty($phone) || empty($price)) {
        die('Пожалуйста, заполните все поля.');
    }

    $token = "..."; // вставьте токен

    $data = [
        'name' => 'Заявка с сайта',
        'price' => (int)$price,
        '_embedded' => [
            'contacts' => [
                [
                    'name' => $name,
                    'custom_fields_values' => [
                        [
                            'field_code' => 'EMAIL',
                            'values' => [['value' => $email]]
                        ],
                        [
                            'field_code' => 'PHONE',
                            'values' => [['value' => $phone]]
                        ],
                    ]
                ]
            ]
        ],
        'custom_fields_values' => [
            [
                'field_id' => 1669485,
                'values' => [['value' => $moreThan30]]
            ]
        ]
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://sergeyvolkov98.amocrm.ru/api/v4/leads/complex',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $token",
            "Content-Type: application/json"
        ],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode([$data]),
    ]);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);
    if ($response === false) {
        die('cURL error: ' . curl_error($ch));
    }    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300) {
        echo "Заявка успешно отправлена!";
    } else {
        echo "Ошибка при отправке: $httpCode<br>";
        echo $response;
    }

    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Форма заявки</title>
</head>
<body>
<form method="POST" id="leadForm">
    <label>Имя: <input type="text" name="name" placeholder="Иванов Иван" required></label><br>
    <label>Email: <input type="email" name="email" placeholder="email@email.com" required></label><br>
    <label>Телефон: <input type="text" name="phone" placeholder="+71112223344" required></label><br>
    <label>Цена: <input type="number" name="price" placeholder="0" required></label><br>
    <input type="hidden" name="more_than_30" value="0" id="timeMarker">
    <button type="submit">Отправить</button>
</form>

<script>
    let moreThan30 = false;
    setTimeout(() => { moreThan30 = true; }, 30000);

    const form = document.getElementById('leadForm');
    const phoneInput = form.querySelector('input[name="phone"]');
    const timeMarker = document.getElementById('timeMarker');

    form.addEventListener('submit', (e) => {
        const phoneValue = phoneInput.value.trim();
        if (!phoneValue.startsWith('+7')) {
            alert('Телефон должен начинаться с +7');
            e.preventDefault();
            return;
        }

        timeMarker.value = moreThan30 ? '1' : '0';
    });
</script>
</body>
</html>

