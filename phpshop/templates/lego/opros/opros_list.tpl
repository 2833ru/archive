<div class=" visible-lg visible-md">
   
	<div class="left-header">{Голосование}</div>
       
   
    <div class="pageCatalContent">
        <h4>@oprosName@</h4>
        <form action="/opros/" method="post" role="form">
            @oprosContent@
            <div class="d-flex">
                <button type="submit" class="btn btn-primary">{Голосовать}</button>
                <a href="/opros/" class="btn btn-default">{Результаты}</a>
            </div>
        </form>
    </div>
</div>
