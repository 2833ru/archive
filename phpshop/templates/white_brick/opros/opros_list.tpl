<div class="box">
    <div class="box-heading"><h2>Опрос</h2></div>

    <div class="box-content">
        <div class="box-category">
            <div style="clear:both"></div>
            @oprosName@ 
            <div class="lmblock" style="padding:15px 0px;"> <form action="/opros/" method="post">
                    <table cellpadding="2">
                        @oprosContent@
                    </table>
                    <div style="padding-top:10px; text-align:center;">
                        <div class="btn-group">
                            <button type="submit" value="" class="btn btn-default "><i class=""></i>Голосовать</button><a href="/opros/" class="btn">Результаты</a> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

