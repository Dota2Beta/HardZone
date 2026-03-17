<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../templates/header.php';
?>
<section class="hz-hero p-4 p-md-5 mb-4">
    <span class="chip">о нас</span>
    <h1 class="mt-3 mb-3">О компании HardZone</h1>
    <p class="lead mb-0">HardZone — современный магазин компьютерной техники. Мы помогаем подобрать надёжные и производительные решения под ваши задачи: игры, работа, обучение и творчество.</p>
</section>

<div class="row g-4 mb-4">
    <div class="col-md-4"><div class="feature-box">🎯 Подбор ПК под бюджет и задачи</div></div>
    <div class="col-md-4"><div class="feature-box">🧰 Консультации по апгрейду</div></div>
    <div class="col-md-4"><div class="feature-box">✅ Проверенные комплектующие</div></div>
</div>

<div class="card soft-card">
    <div class="card-body p-4">
        <h2 class="mb-3">Почему клиенты выбирают HardZone</h2>
        <ul class="mb-0">
            <li>Честные характеристики, прозрачные цены и понятные условия покупки.</li>
            <li>Персональный подбор конфигурации без лишних переплат.</li>
            <li>Поддержка после покупки и рекомендации по апгрейду и обслуживанию.</li>
        </ul>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
