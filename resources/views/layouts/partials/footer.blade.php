<footer class="page-footer brand-gradient">
    <div class="container">
        <div class="row">

            <div class="col-md-8">
                <h5 class="title">Проект "{{ config('app.name') }}"</h5>
                <p>Привет! На&nbsp;данном сайте ты&nbsp;можешь найти предстоящие конвой в&nbsp;TruckersMP&nbsp;&mdash; неофициальном мультиплеере для Euro Truck Simulator 2&nbsp;и&nbsp;American Truck
                   Simulator.</p>
                <p>Проект "Конвои по-русски" будет закрыт 31 августа 2017 года.</strong><br> Спасибо за то, что были с нами.</p>
            </div>

            <div class="col-md-4 text-right">
                <h5 class="title">Навигация</h5>
                <ul>
                    <li>
                        <a href="{{ route('rules', [], false) }}">Правила Портала</a>
                    </li>

                    <li>
                        <a href="{{ route('convoy_all', [], false) }}">Все конвои</a>
                    </li>

                    <li>
                        <a href="{{ route('convoy_archive', [], false) }}">Архив конвоев</a>
                    </li>

                    <li>
                        <a href="{{ route('about', [], false) }}">О проекте</a>
                    </li>

                    <hr>

                    <li>
                        <a href="https://firstvds.ru/?from=538100" target="_blank" rel="noreferrer nofollow noopener">Хостинг от&nbsp;FirstVDS</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="footer-copyright">
        <div class="container-fluid">
            © 2016-2017 <a href="{{ route('index') }}">Конвои по-русски</a> by CJMAXiK.
            <small><em>Версия {{ config('app.build') }}.</em></small>
        </div>
    </div>
</footer>
