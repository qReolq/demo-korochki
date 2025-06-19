<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header("Location: login.php");
    exit();
}

# Обработка смены статуса
if (isset($_POST['change_status'])) {
    $app_id = (int) $_POST['app_id'];
    $new_status = $_POST['new_status'];

    $stmt = $mysqli->prepare("UPDATE applications SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $app_id);
    $stmt->execute();
    $stmt->close();
    header("Location: admin.php");
    exit();
}

require 'header.php';

$result = $mysqli->query("
SELECT a.id, a.start_date, a.payment_method, a.status, u.fio, u.phone, u.email, c.name AS course_name
FROM applications a
JOIN users u ON a.user_id = u.id
JOIN courses c ON a.course_id = c.id
ORDER BY a.id DESC
");

echo "<h2 class='mb-4'>Все заявки пользователей</h2>
<table class='table table-bordered table-striped'>
<thead class='table-primary'>
    <tr>
        <th>ID</th>
        <th>Пользователь</th>
        <th>Контакты</th>
        <th>Курс</th>
        <th>Дата начала</th>
        <th>Оплата</th>
        <th>Статус</th>
        <th>Изменить статус</th>
    </tr>
</thead>
<tbody>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
        <td>{$row['id']}</td>
        <td>{$row['fio']}</td>
        <td>{$row['phone']}<br>{$row['email']}</td>
        <td>{$row['course_name']}</td>
        <td>{$row['start_date']}</td>
        <td>{$row['payment_method']}</td>
        <td><span class='badge bg-info'>{$row['status']}</span></td>
        <td>
            <form method='POST' class='d-flex'>
                <input type='hidden' name='app_id' value='{$row['id']}'>
                <select name='new_status' class='form-select form-select-sm me-2'>
                    <option " . ($row['status'] == 'Новая' ? 'selected' : '') . ">Новая</option>
                    <option " . ($row['status'] == 'Идет обучение' ? 'selected' : '') . ">Идет обучение</option>
                    <option " . ($row['status'] == 'Обучение завершено' ? 'selected' : '') . ">Обучение завершено</option>
                </select>
                <button type='submit' name='change_status' class='btn btn-primary btn-sm'>Обновить</button>
            </form>
        </td>
    </tr>";
}
echo "</tbody></table>";

require 'footer.php';
?>
