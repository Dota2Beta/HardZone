<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config.php';
require_once __DIR__ . '/../templates/header.php';
?>
<section class="hz-hero p-4 p-md-5 mb-4">
    <span class="chip">о нас</span>
    <h1 class="mt-3 mb-3">О магазине HardZone</h1>
    <p class="lead mb-0">HardZone — учебный интернет-магазин компьютеров. Наша цель — помочь подобрать ПК под ваши задачи: учеба, игры, работа и творчество.</p>
</section>

<div class="row g-4 mb-4">
    <div class="col-md-4"><div class="feature-box">🎯 Подбор ПК под бюджет и задачи</div></div>
    <div class="col-md-4"><div class="feature-box">🧰 Консультации по апгрейду</div></div>
    <div class="col-md-4"><div class="feature-box">✅ Проверенные комплектующие</div></div>
</div>

<div class="card soft-card">
    <div class="card-body p-4">
        <h2 class="mb-3">Почему выбирают нас</h2>
        <ul class="mb-0">
            <li>Прозрачные характеристики и честные цены.</li>
            <li>Помощь в выборе конфигурации без лишних переплат.</li>
            <li>Поддержка после покупки и рекомендации по обслуживанию.</li>
        </ul>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
