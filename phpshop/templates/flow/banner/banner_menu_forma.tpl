<div class="navbar-banner" style="background-image: url(@banerImage@);">
    <div class="navbar-banner-content">
        <div class="mb-4 text-white">
            <span class="d-block h2 text-white" style="-webkit-filter: invert(@banerColor@%);filter: invert(@banerColor@%);">@banerTitle@</span>
        @banerContent@
        </div>
        <a class="btn btn-primary btn-sm transition-3d-hover @php __hide('banerLink'); php@" href="@banerLink@">@banerDescription@ <i class="fas fa-angle-right fa-sm ml-1"></i></a>
    </div>
</div>