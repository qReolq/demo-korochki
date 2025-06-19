<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id']) && basename($_SERVER['PHP_SELF']) != 'login.php' && basename($_SERVER['PHP_SELF']) != 'register.php') {
    header("Location: login.php");
    exit();
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Корочки.есть</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
    }
    .navbar {
        background-color: #4a69bd;
    }
    .navbar-brand, .nav-link, .navbar-text {
        color: #fff !important;
    }
    .card {
        border: none;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .btn-primary {
        background-color: #4a69bd;
        border-color: #4a69bd;
    }
    .btn-primary:hover {
        background-color: #3c5ca8;
        border-color: #3c5ca8;
    }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg mb-4">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php">Корочки.есть</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Главная</a></li>
        <li class="nav-item"><a class="nav-link" href="courses.php">Курсы</a></li>
        <li class="nav-item"><a class="nav-link" href="application.php">Подать заявку</a></li>
        <?php if (!empty($_SESSION['is_admin'])): ?>
        <li class="nav-item"><a class="nav-link" href="admin.php">Админ</a></li>
        <?php endif; ?>
        <li class="nav-item"><a class="nav-link" href="logout.php">Выйти</a></li>
        <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="login.php">Вход</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Регистрация</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container">
