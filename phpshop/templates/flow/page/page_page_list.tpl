<h1 class="page-title d-none">@pageTitle@</h1> 

<div class="container w-lg-100 mx-lg-auto">

    <div class="d-flex justify-content-md-end align-items-center">
        <span class="d-block small text-cap mr-2">{Поделиться}</span>
        <script src="https://yastatic.net/share2/share.js"></script>
        <div class="ya-share2" data-curtain data-shape="round" data-services="vkontakte,odnoklassniki,telegram"></div>

    </div>

    <!-- Конец блока поделиться -->
    <blockquote class="font-size-2 p-5  @php __hide('pageMainPreview'); php@">
        @pageMainPreview@
    </blockquote>



    <div class="">@pageContent@</div>
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