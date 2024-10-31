<?php
if ( session_status() === PHP_SESSION_NONE ) {
    session_start();
}
header("Access-Control-Allow-Origin: *");
// Отображение ошибок для отладки
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Вставьте реальные данные для подключения к базе данных

$bd_host = "";
$bd_user = "";
$bd_pass = "";
$bd_base = "";

// Проверка CSRF-токена
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    exit('Invalid CSRF token');
}

// Проверка обязательных полей
$requiredFields = ['fname', 'lname', 'phone', 'email'];
$emptyFields = array_filter($requiredFields, fn($field) => empty($_POST[$field]));

if (!empty($emptyFields)) {
    exit('Please fill in all required fields: ' . implode(', ', $emptyFields));
}

// Обработка входных данных
foreach ($_POST as $key => $value) {
    ${$key} = trim($value);
}
$source = $_SERVER['SERVER_NAME'];
$phone = preg_replace('/[^0-9]/', '', $phone); // Убираем все символы, кроме цифр

// Подключение к базе данных через PDO
$db = new PDO("mysql:host=$bd_host;dbname=$bd_base;charset=utf8", $bd_user, $bd_pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Подготовка и выполнение запроса для получения последней записи по номеру телефона
$query = "SELECT date_added, id FROM users WHERE phone = :phone ORDER BY id DESC LIMIT 1";
$stmt = $db->prepare($query);
$stmt->execute([':phone' => $phone]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Логика обработки на основе существующих данных
if ($row && isset($row['date_added'])) {
    // Вычисление разницы в днях
    $diff = abs(strtotime('now') - strtotime($row['date_added']));
    $days = floor($diff / (60 * 60 * 24));
    if ($days >= 4) {
        $message = "rewrite_lead";
        $insertedId = write_lead($db, $fname, $lname, $phone, $email, $utm_source, $source, $utm_campaign, $utm_medium, $utm_content, $get_ip, $get_ua, $param1, $param2);
        $response = [
            'status'  => 'success',
            'message' => $message,
            'id'      => $insertedId
        ];
    } else {
        $message = "duplicate";
        $insertedId = $row['id'];
        $response = [
            'status'  => 'success',
            'message' => $message,
            'id'      => 'u' . $insertedId
        ];
    }
} else {
    $message = "new_lead";
    $insertedId = write_lead($db, $fname, $lname, $phone, $email, $utm_source, $source, $utm_campaign, $utm_medium, $utm_content, $get_ip, $get_ua, $param1, $param2);
    $response = [
        'status'  => 'success',
        'message' => $message,
        'id'      => $insertedId
    ];
}

echo json_encode($response);

// Функция для вставки нового лида
function write_lead($db, $fname, $lname, $phone, $email, $utm_source, $source, $utm_campaign, $utm_medium, $utm_content, $get_ip, $get_ua, $param1, $param2)
{
    try {
        // Начинаем транзакцию
        $db->beginTransaction();

        // Первый запрос: вставка данных в таблицу users
        $query1 = "INSERT INTO users (fname, lname, phone, `e-mail`, utm_source, source, utm_campaign, utm_medium, utm_content, date_added) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt1 = $db->prepare($query1);
        $stmt1->execute([$fname, $lname, $phone, $email, $utm_source, $source, $utm_campaign, $utm_medium, $utm_content]);
        $insertedId = $db->lastInsertId(); // Получаем ID вставленной записи

        // Второй запрос: вставка данных в таблицу offline_info
        $query2 = "INSERT INTO offline_info (leadId, ip, userAgent, param1, param2) VALUES (?, ?, ?, ?, ?)";
        $stmt2 = $db->prepare($query2);
        $stmt2->execute([$insertedId, $get_ip, $get_ua, $param1, $param2]);

        // Фиксируем транзакцию
        $db->commit();

        return 'u' . $insertedId;

    } catch (PDOException $e) {
        // Откат транзакции в случае ошибки
        $db->rollBack();
        echo "Ошибка при выполнении запроса: " . $e->getMessage();
        return false;
    }
}
?>
