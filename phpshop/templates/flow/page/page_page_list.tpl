<h1 class="h3 page-title d-none">@pageTitle@</h1> 

<div class="container w-lg-90 mx-lg-auto">
	          <div class="h2">@pageTitle@</div>

<!-- Блок поделиться -->
		<div class="border-top border-bottom py-4 m-5 @php __hide('pageMainPreview'); php@">
          <div class="row align-items-md-center">
            <div class="col-md-7 mb-5 mb-md-0">
              <div class="media align-items-center">
                <div class="avatar avatar-circle">
                  <img class="avatar-img" src="images/avatar.jpg" alt="@name@">
                </div>
                <div class="media-body font-size-1 ml-3">
                  <span class="h6">@company@</span>
                  <span class="d-block text-muted">@pageData@</span>
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
<blockquote class="font-size-2 p-5 my-5 @php __hide('pageMainPreview'); php@">
@pageMainPreview@
</blockquote>


<div class="mb-4 mb-sm-8">
<img class="img-fluid w-100 rounded-lg @php __hide('pageMainIcon'); php@" src="@pageMainIcon@" alt="@pageTitle@">
</div>
       
<div class=" m-3">@pageContent@</div>
</div>

</div>

@odnotipDisp@

<!-- Page Section -->
<div class="border-top space-lg-2 @php __hide('pageLast'); php@">
    
    <div class="space-0 d-none d-sm-block">
        <div class="row row-center">
            @pageLast@
        </div>
    </div>
   
</div>

<!-- End Page Section -->
