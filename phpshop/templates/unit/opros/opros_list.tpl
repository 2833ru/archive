<div class="panel panel-default visible-lg visible-md">
    <div class="panel-heading">
        <h3 class="panel-title">{Голосование}</h3>
    </div>
    <div class="panel-body">
        <h4>@oprosName@</h4>
        <form action="/opros/" method="post">
            @oprosContent@
            <div class="d-flex">
                <button type="submit" class="btn btn-primary" style="margin-right:5px">{Голосовать}</button>
                <a href="/opros/" class="btn btn-default">{Результаты}</a>
            </div>
        </form>
    </div>
</div>
