
<h2>Новинки каталога</h2>

<div id="carousel-featured-0" class="es-carousel-wrapper">
    <div class="es-carousel"><ul>@subCatProdList2@</ul></div>
</div>

<script type="text/javascript">
    $('#carousel-featured-0').elastislide({
        speed: 450, // animation speed
        easing: '', // animation easing effect
        // the minimum number of items to show. When we resize the window, this will make sure minItems are always shown (unless of course minItems is higher than the total number of elements)
        minItems: 1
    });
    //Fix to adjust on windows resize
    $(window).triggerHandler('resize.elastislide');
</script>