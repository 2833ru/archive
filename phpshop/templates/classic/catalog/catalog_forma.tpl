<table width="275"  cellspacing="0" cellpadding="0">
    <tr>
        <td height="10"  width="275" valign="top">
            <DIV style="cursor:pointer;" onClick="pressbutt(@catalogId@, 100, '@catalogTemplates@', 'i', 'm');"
                 class="catalog_forma">
                <table width="275" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="padding-top:15px;padding-left:62px;">
                            @catalogName@
                        </td>
                    </tr>
                </table>
            </DIV>
        </td>
    </tr>
    <tr>
        <td>
            <div id="m@catalogId@" class="podcatalog_forma" style="position:absolute;left:0px;top:0px;bottom:0px;right:0px;visibility:hidden;">
                @catalogPodcatalog@
            </div>
        </td>
    </tr> 
</table>