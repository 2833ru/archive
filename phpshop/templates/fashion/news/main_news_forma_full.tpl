      

<div class="container w-lg-60 mx-lg-auto" style="min-height: 700px;">
<!-- Анонс новости -->

<blockquote class="bg-soft-primary border-0 text-center text-dark font-size-2 p-5 my-5">
   @newsKratko@
</blockquote>


<!-- Блок поделиться -->
		<div class="border-top border-bottom py-4 mb-5">
          <div class="row align-items-md-center">
            <div class="col-md-7 mb-5 mb-md-0">
              <div class="media align-items-center">
                <div class="avatar avatar-circle">
                  <img class="avatar-img" src="images/avatar.jpg" alt="@name@">
                </div>
                <div class="media-body font-size-1 ml-3">
                  <span class="h6">@company@</span>
                  <span class="d-block text-muted">@newsData@</span>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="d-flex justify-content-md-end align-items-center">
                <span class="d-block small text-cap mr-2">Поделиться</span>
                <script src="https://yastatic.net/share2/share.js"></script>
<div class="ya-share2" data-curtain data-shape="round" data-services="vkontakte,odnoklassniki,telegram"></div>

              </div>
            </div>
          </div>
        </div>     
<!-- Конец блока поделиться -->
<div class="mb-4 mb-sm-8 text-center">
<img class="img-fluid w-100 rounded-lg @php __hide('newsIcon'); php@" src="@newsIcon@" alt="@newsZag@" loading="lazy">
</div>

<!-- Вывод новости -->
<div class="my-4 my-sm-8">@newsPodrob@</div>
<!-- Конец новости  -->

<div class="spec content-product">@odnotipDisp@</div>

</div>