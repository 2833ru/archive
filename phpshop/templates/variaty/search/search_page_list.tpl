<div class="row">
<div class="breadcrumb">
  <div> <a href="/">Главная</a> <span>»</span> Расширенный поиск </div>
</div>
<h2>Расширенный поиск</h2>

<FORM method="post" name="forma_search">
  <table>
    <tr class="hidden-phone">
      <td>Введите слово:<br>
        <INPUT style="width: 550px; margin-bottom:0;"  class="form-control" maxLength="100" name="words" value="@searchString@" type="text">
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
        <input type="Radio" class="logic_radio" value="1" name="pole" @searchSetC@>
        Наименование &nbsp;
        <input type="Radio" class="logic_radio" value="2" name="pole" @searchSetD@ >
        Учитывать все </td>
    </tr>
  </table>
</FORM>
<p><br>
</p>

  @productPageDis@

<div class="navi">@searchPageNav@</div>
</div>
