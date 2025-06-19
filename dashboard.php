<?php require 'config.php'; require 'header.php';
$user_id = $_SESSION['user_id'];

$result = $mysqli->query("SELECT c.name, c.description, c.image_url, a.start_date, a.payment_method, a.status FROM applications a JOIN courses c ON a.course_id = c.id WHERE a.user_id = $user_id");

echo "<h2 class='mb-4'>Ваши заявки</h2><div class='row'>";

while ($row = $result->fetch_assoc()) {
    echo "<div class='col-md-4 mb-4'>
        <div class='card'>
            <img src='{$row['image_url']}' class='card-img-top' alt='Курс'>
            <div class='card-body'>
                <h5 class='card-title'>{$row['name']}</h5>
                <p class='card-text'>{$row['description']}</p>
                <p class='card-text'><small>Дата начала: {$row['start_date']} | Оплата: {$row['payment_method']}</small></p>
                <span class='badge bg-info'>{$row['status']}</span>
            </div>
        </div>
    </div>";
}
echo "</div>";
require 'footer.php'; ?>
