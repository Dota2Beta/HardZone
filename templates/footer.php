</main>
<footer class="hz-footer mt-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Игровые ПК</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/catalog.php') ?>">Все модели</a></li>
                    <li><a href="<?= url('/catalog.php') ?>">В наличии</a></li>
                    <li><a href="<?= url('/catalog.php') ?>">Конфигуратор</a></li>
                    <li><a href="<?= url('/catalog.php') ?>">Подбор</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Серверы</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/catalog.php') ?>">GPU-серверы</a></li>
                    <li><a href="<?= url('/catalog.php') ?>">Для бизнеса</a></li>
                    <li><a href="<?= url('/catalog.php') ?>">ИИ и ML</a></li>
                </ul>
                <h6 class="footer-title mt-4">Аксессуары</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/catalog.php') ?>">Мониторы</a></li>
                    <li><a href="<?= url('/catalog.php') ?>">Клавиатуры</a></li>
                    <li><a href="<?= url('/catalog.php') ?>">Мышки</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6 class="footer-title">Услуги</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/about.php') ?>">Модернизация</a></li>
                    <li><a href="<?= url('/about.php') ?>">Трейд-ин</a></li>
                    <li><a href="<?= url('/about.php') ?>">Профилактика</a></li>
                    <li><a href="<?= url('/about.php') ?>">Аренда ПК</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6 class="footer-title">Покупателям</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/cart.php') ?>">Этапы заказа</a></li>
                    <li><a href="<?= url('/cart.php') ?>">Оплата</a></li>
                    <li><a href="<?= url('/about.php') ?>">Доставка</a></li>
                    <li><a href="<?= url('/about.php') ?>">Гарантия</a></li>
                    <li><a href="<?= url('/about.php') ?>">Подарочные сертификаты</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-3">
                <h6 class="footer-title">Компания</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/about.php') ?>">О нас</a></li>
                    <li><a href="<?= url('/reviews.php') ?>">Отзывы</a></li>
                    <li><a href="<?= url('/feedback.php') ?>">Контакты</a></li>
                    <li><a href="<?= url('/about.php') ?>">Вакансии</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-note mt-4">
            <span>Приглашаем вас в наш <a href="<?= url('/about.php') ?>">шоу-рум</a>. Позвонить 8 (800) 775-82-35 или <a href="<?= url('/feedback.php') ?>">написать нам</a>.</span>
        </div>

        <div class="footer-bottom mt-4 pt-3">
            <span>Copyright © 2010-<?= date('Y') ?> HARDZONE.</span>
            <span><a href="#">Правовая информация</a> | <a href="#">Карта сайта</a></span>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= url('/assets/js/main.js') ?>"></script>
</body>
</html>
