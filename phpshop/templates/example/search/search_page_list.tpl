<h4 style="font-size: 15px;color: #000000">Расширенный поиск</h4>

<div class="page_nava">
  <a href="/">Главная</a> / Расширенный поиск
</div>

<table cellspacing="0" cellpadding="0" border="0" width="100%">
  <tr>
    <td colspan="2"><form method="post" name="forma_search" action="/search/">
        <table>
          <tr>
            <td> Введите слово:<br>
              <input style="width:300px" maxLength="100" name="words" value="@searchString@">
            </td>
            <td><br>
              <input type="submit" value="Искать">
            </td>
          </tr>
          <tr>
            <td colspan="2"> Выберите каталог:<br>
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
      </FORM></td>
  </tr>
  <tr>
    <td><div style="padding: 7px"> @searchPageNav@ </div></td>
  </tr>
  <tr>
    <td>
  <!-- Вывод [search/main_search_forma_2.tpl] -->
    @productPageDis@
  <!-- Вывод [search/main_search_forma_2.tpl] -->
    </td>
  </tr>
  <tr>
    <td><div style="padding: 7px"> @searchPageNav@ </div></td>
  </tr>
</table>
