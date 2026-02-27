<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../src/auth.php';

requireAuth();
$user = currentUser();

require_once __DIR__ . '/../templates/header.php';
?>
<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="mb-3">Профиль</h2>
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><strong>Логин:</strong> <?= htmlspecialchars($user['username']) ?></li>
            <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></li>
            <li class="list-group-item"><strong>Телефон:</strong> <?= htmlspecialchars($user['phone']) ?></li>
            <li class="list-group-item"><strong>Роль:</strong> <?= htmlspecialchars($user['role']) ?></li>
        </ul>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
