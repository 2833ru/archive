<footer class="footer footer-1 footer-3" id="footer-area">
    <!-- Footer Links Starts -->
    <div class="footer-links">
        <!-- Container Starts -->
        <div class="container-fluid">
            <!-- Contact Us Starts -->
            <div class="col-md-3 col-sm-6 col-xs-12" itemscope itemtype="http://schema.org/Organization">
                <h5>{Контакты}</h5>
                <ul>
                    <li class="footer-map" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">@streetAddress@</span></li>
                    <li class="footer-email"><a href="mailto:@adminMail@" itemprop="email">@adminMail@</a></li>
                    <li class="footer-map" itemprop="telephone">@telNum@</li>
                    <li class="footer-map" itemprop="telephone">@telNum2@</li>
                    <li class="footer-map">@workingTime@</li>
                </ul>
                @sticker_socfooter@
                @sticker_pay@


            </div>
            <!-- Contact Us Ends -->
            <!-- My Account Links Starts -->
            <div class="col-md-3  col-sm-6 col-xs-12">
                <h5>{Мой кабинет}</h5>
                <ul>
                    <li><a href="/users/">@UsersLogin@</a></li>
                    <li><a href="/users/order.html">{Отследить заказ}</a></li>
                    <li><a href="/users/wishlist/">{Отложенные товары}</a></li>

                    @php if($_SESSION['UsersId']) echo '
                    <li><a href="/users/message.html">{Связь с менеджерами}</a></li>
                    <li><a href="?logout=true">{Выйти}</a></li>
                    '; php@
                </ul>
            </div>
            <!-- My Account Links Ends -->
            <div class="clearfix visible-sm"></div>
            <!-- Customer Service Links Starts -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <h5>{Меню}</h5>
                <ul>
                    <li><a href="/price/" title="Прайс-лист">{Прайс-лист}</a></li>
                    <li><a href="/news/" title="Новости">{Новости}</a></li>
                    <li><a href="/gbook/" title="Отзывы">{Отзывы}</a></li>
                    <li><a href="/map/" title="Карта сайта">{Карта сайта}</a></li>
                    <li><a href="/forma/" title="Форма связи">{Форма связи}</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12 t-right">
                <h5>{Информация}</h5>
                <ul>
                    @bottomMenu@

                </ul>
                @button@
            </div>



            <!-- Customer Service Links Ends -->

        </div>

        <!-- Container Ends -->
    </div>
    <!-- Footer Links Ends -->

</footer>