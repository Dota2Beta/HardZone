</main>
<footer class="hz-footer mt-5">
    <div class="container py-5">
        <div class="row g-4 align-items-start">
            <div class="col-12 col-lg-3">
                <div class="footer-brand-card">
                    <h5 class="footer-brand mb-2">HARDZONE</h5>
                    <p class="footer-brand-text mb-3">Премиальный магазин игровых и рабочих ПК с профессиональным подбором под ваши задачи.</p>
                    <div class="footer-contacts">
                        <div>8 (800) 775-82-35</div>
                        <a href="<?= url('/feedback.php') ?>">Написать в поддержку</a>
                    </div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <h6 class="footer-title">Разделы</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/index.php') ?>">Главная</a></li>
                    <li><a href="<?= url('/catalog.php') ?>">Каталог</a></li>
                    <li><a href="<?= url('/cart.php') ?>">Корзина</a></li>
                    <li><a href="<?= url('/about.php') ?>">О нас</a></li>
                </ul>
            </div>

            <div class="col-6 col-lg-3">
                <h6 class="footer-title">Пользователь</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/profile.php') ?>">Профиль</a></li>
                    <li><a href="<?= url('/login.php') ?>">Вход</a></li>
                    <li><a href="<?= url('/register.php') ?>">Регистрация</a></li>
                    <li><a href="<?= url('/logout.php') ?>">Выход</a></li>
                </ul>
            </div>

            <div class="col-12 col-lg-3">
                <h6 class="footer-title">Коммуникация</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/reviews.php') ?>">Отзывы</a></li>
                    <li><a href="<?= url('/feedback.php') ?>">Обратная связь</a></li>
                </ul>

                <h6 class="footer-title mt-4">Служебные страницы</h6>
                <ul class="footer-links">
                    <li><a href="<?= url('/errors/404.php') ?>">Ошибка 404</a></li>
                    <li><a href="<?= url('/old-catalog') ?>">Код 301 (пример)</a></li>
                    <li><a href="<?= url('/promo') ?>">Код 302 (пример)</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-note mt-4">
            Добро пожаловать в HardZone: поможем подобрать и настроить систему под игры, стриминг, дизайн, разработку и задачи AI.
        </div>

        <div class="footer-bottom mt-4 pt-3">
            <span>Copyright © 2010-<?= date('Y') ?> HARDZONE.</span>
            <span><a href="<?= url('/about.php') ?>">О компании</a> | <a href="<?= url('/catalog.php') ?>">Каталог товаров</a></span>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= assetUrl('/assets/js/main.js') ?>"></script>
</body>
</html>
