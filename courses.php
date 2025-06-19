<?php
require 'config.php';
require 'header.php';

$courses = $mysqli->query("SELECT * FROM courses");
?>
<h2 class="mb-4">Список курсов</h2>
<div class="row">
<?php while ($c = $courses->fetch_assoc()): ?>
  <div class="col-md-4 mb-4">
    <div class="card h-100">
      <img src="<?= $c['image_url'] ?>" class="card-img-top" alt="Курс">
      <div class="card-body d-flex flex-column">
        <h5 class="card-title"><?= $c['name'] ?></h5>
        <p class="card-text mb-4"><?= $c['description'] ?></p>
        <a href="course.php?id=<?= $c['id'] ?>" class="btn btn-outline-secondary mb-2">Отзывы</a>
        <a href="application.php?course=<?= $c['id'] ?>" class="btn btn-primary mt-auto">Оформить заявку</a>
      </div>
    </div>
  </div>
<?php endwhile; ?>
</div>
<?php require 'footer.php'; ?>
