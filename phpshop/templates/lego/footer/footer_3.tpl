<style>.brands{display:none;}</style>   
<footer class="footer footer-2 footer-3" id="footer-area">
    <!-- Footer Links Starts -->
    <div class="footer-links">
        <!-- Container Starts -->
        <div class="container-fluid">
            <!-- Contact Us Starts -->
            <div class="col-md-3 col-sm-6 col-xs-12" itemscope itemtype="http://schema.org/Organization">
                 <a href=""><img src="images/logo.jpg" alt="" width="180"></a>
                <ul>
                    <li class="footer-map" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">@streetAddress@</span></li>
                    <li class="footer-email"><a href="mailto:@adminMail@" itemprop="email">@adminMail@</a></li>
                    <li class="footer-map" itemprop="telephone">@telNum@</li>
                    <li class="footer-map" itemprop="telephone">@telNum2@</li>
                    <li class="footer-map">@workingTime@</li>
                </ul>
            </div>
            

            <!-- Contact Us Ends -->
            <!-- My Account Links Starts -->
            <div class="col-md-3  col-sm-6 col-xs-12">
                <ul>
                    <li><a href="/users/">@UsersLogin@</a></li>
                    <li><a href="/users/order.html">{Отследить заказ}</a></li>
                    <li><a href="/users/wishlist/">{Отложенные товары}</a></li>
                    <li><a href="/users/message.html">{Связь с менеджерами}</a></li>
                    @php if($_SESSION['UsersId']) echo '
                    <li><a href="?logout=true">{Выйти}</a></li>
                    '; php@
                </ul>
            </div>
            <!-- My Account Links Ends -->
            <div class="clearfix visible-sm"></div>
            <!-- Customer Service Links Starts -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <ul>
                    @bottomMenu@

                </ul>
            </div>
            <!-- Customer Service Links Ends -->
            <!-- Information Links Starts -->
            <div class="col-md-3 col-sm-6 col-xs-12 t-right">

                @sticker_pay@

                @sticker_socfooter@
                @button@
            </div>
            <!-- Information Links Ends -->
        </div>

        <!-- Container Ends -->
    </div>
    <!-- Footer Links Ends -->

</footer>