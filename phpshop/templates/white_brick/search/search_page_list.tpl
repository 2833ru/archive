<h2>Расширенный поиск</h2>
<FORM method="post" name="forma_search">
    <table>
        <tr>
            <td>Введите слово:<br>
                <INPUT style="WIDTH:390px" maxLength="100" name="words" value="@searchString@">
            </td>
            <td><br>
                <input type="submit" value="Искать" class="ok">
            </td>
        </tr>
        <tr>
            <td colspan="2">Выберите каталог:<br>
                @searchPageCategory@ </td>
        </tr>
        <tr>
            <td colspan="2" id="sort"><table cellpadding="0" cellspacing="0">
                    <tr>@searchPageSort@</tr>
                </table></td>
        </tr>
        <tr>
            <td colspan="2"><b>Область поиска:</b>
                <input type="Radio" value="1" name="pole" @searchSetC@>
                Наименование &nbsp;
                <input type="Radio" value="2" name="pole" @searchSetD@ >
                Учитывать все </td>
        </tr>
    </table>
</FORM>
<p><br>
</p>
<div class="product-grid">
    <table cellpadding="0" cellspacing="0" border="0" >
        @productPageDis@
    </table>
</div>
<div class="navi">@searchPageNav@</div>
