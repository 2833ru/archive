<h1 class="HTitle4">Расширенный поиск</h1>
<div class="page_nava">
  <div ><a href="/">Главная</a> / <span>Расширенный поиск</span></div>
</div>
<br>
<TABLE cellpadding="0" cellspacing="0" border="0" width="100%">
  <TR>
    <TD colspan="2"><FORM method="post" name="forma_search" action="/search/">
        <table>
          <tr>
            <td> Введите слово:<br>
              <INPUT style="WIDTH:400px" maxLength="100" name="words" value="@searchString@">
            </td>
            <td><br>
              <input type="submit" value="Искать" class="ok">
            </td>
          </tr>
          <tr>
            <td colspan="2"> Выберете каталог:<br>
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
      </FORM></TD>
  </TR>
</TABLE>
<table cellpadding="0" cellspacing="0" border="0" class="catalogOptionTable1">
  <tbody>
    <tr>
      <td align="left" valign="middle"><div class="page_nav_top">@searchPageNav@</div></td>
    </tr>
  </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
  <tbody>
    <tr>
      <td> @productPageDis@ </td>
    </tr>
  </tbody>
</table>
<table cellpadding="0" cellspacing="0" border="0" class="catalogOptionTable2">
  <tbody>
    <tr>
      <td align="left" valign="middle"><div class="page_nav_top">@searchPageNav@</div></td>
    </tr>
  </tbody>
</table>
