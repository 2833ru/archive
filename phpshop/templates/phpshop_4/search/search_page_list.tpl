<div class="plashka_center">Расширенный поиск</div>

<div class="page_nava">
<div>
<a href="/">Главная</a> /  Расширенный поиск
</div></div>
<br>
<TABLE cellpadding="0" cellspacing="0" width="512" class="text_1">
					<TR>
						<TD colspan="2">	
						<FORM method="post" name="forma_search" action="/search/">	
						<table>
					
<tr>
	<td>
	Введите слово:<br>
	<INPUT style="WIDTH:400px" maxLength="100" name="words" value="@searchString@">
	</td>
	<td>
	<br>
	<input type="submit" value="Искать">
	
	</td>
</tr>
<tr>
	<td colspan="2">
	Выберите каталог:<br>
	@searchPageCategory@
	</td>
	
</tr>
<tr>
	<td colspan="2" id="sort">
	<table cellpadding="0" cellspacing="0"><tr>@searchPageSort@</tr></table>
	</td>
	
</tr>
 <tr>
   <td colspan="2"><b>Логика поиска:</b>
<input type="Radio" value="1" name="set" @searchSetA@>и &nbsp;<input type="Radio" value="2" name="set" @searchSetB@ >или
&nbsp;&nbsp;&nbsp;/ &nbsp;&nbsp;<b>Область:</b> <input type="Radio" value="1" name="pole" @searchSetC@>Наименование &nbsp;<input type="Radio" value="2" name="pole" @searchSetD@ >Учитывать все

</td>
</tr>  

</table></FORM>
						</TD>
					</TR>
					<TR>
						<TD class="page_nav">@searchPageNav@
						</TD>
					</TR>
					<TR>
						<TD>	@productPageDis@
						</TD>
					</TR>
					
					<TR>
						<TD width="100%">
							<DIV class="page_nav"> @searchPageNav@
							</DIV>
						</TD>
					</TR>
				</TABLE>