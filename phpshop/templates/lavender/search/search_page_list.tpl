<div class="page_nava">
  <div> <a href="/">Главная</a> / Расширенный поиск </div>
</div>
<h2>Расширенный поиск</h2>
<FORM method="post" name="forma_search">
  <table>
    <tr>
      <td>Введите слово:<br>
        <INPUT style="WIDTH:550px" maxLength="100" name="words" value="@searchString@">
      </td>
      <td><br>
        <input type="submit" value="Искать" class="ok">
      </td>
    </tr>
    <tr>
      <td colspan="2">Выберете каталог:<br>
        @searchPageCategory@ </td>
    </tr>
    <tr>
      <td colspan="2" id="sort"><table cellpadding="0" cellspacing="0">
          <tr>@searchPageSort@</tr>
        </table></td>
    </tr>
    <tr>
      <td colspan="2"><b>Логика поиска:</b>
        <input type="Radio" value="1" name="set" @searchSetA@>
        и &nbsp;
        <input type="Radio" value="2" name="set" @searchSetB@ >
        или
        &nbsp;&nbsp;&nbsp;/ &nbsp;&nbsp;<b>Область:</b>
        <input type="Radio" value="1" name="pole" @searchSetC@>
        Наименование &nbsp;
        <input type="Radio" value="2" name="pole" @searchSetD@ >
        Учитывать все </td>
    </tr>
  </table>
</FORM>
<p><br>
</p>
<table cellpadding="0" cellspacing="0" border="0" >
  @productPageDis@
</table>
<div class="navi">@searchPageNav@</div>
