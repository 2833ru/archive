
<!doctype html>
<html>
    <head>
        <title>Бланк "Счет на оплату"</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
        <style>
            body { width: 210mm; margin-left: auto; margin-right: auto; border: 1px #efefef solid; font-size: 11pt;}
            table.invoice_bank_rekv { border-collapse: collapse; border: 1px solid; }
            table.invoice_bank_rekv > tbody > tr > td, table.invoice_bank_rekv > tr > td { border: 1px solid; }
            table.invoice_items { border: 1px solid; border-collapse: collapse;}
            table.invoice_items td, table.invoice_items th { border: 1px solid;}
            body {
                background: #ffffff;
                margin: 0;
                font-family: Arial;
                font-size: 8pt;
                font-style: normal;
            }
            tr.R0 {
                font-family: Tahoma;
                font-size: 8pt;
                font-style: normal;
                height: 9pt;
            }
            tr.R0 td.R0C24 {
                font-family: Tahoma;
                font-size: 8pt;
                font-style: normal;
            }
            tr.R0 td.R0C38 {
                font-family: Tahoma;
                font-size: 8pt;
                font-style: normal;
                text-align: right;
            }
            tr.R0 .R4C35 {
                font-family: Tahoma;
                font-size: 8pt;
                font-style: normal;
                color: #0080c0;
            }
            td.R10C19 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                vertical-align: top;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
                border-bottom: #000000 1px solid;
            }
            td.R10C22 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                text-align: left;
                vertical-align: top;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
                border-bottom: #000000 1px solid;
                border-right: #000000 1px solid;
            }
            td.R10C3 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                text-align: left;
                vertical-align: medium;
                border-top: #000000 1px solid;
            }
            td.R13C1 {
                font-family: Arial;
                font-size: 8pt;
                font-style: normal;
                border-left: #000000 1px solid;
                border-bottom: #000000 1px solid;
            }
            tr.R14 {
                height: 34pt;
            }
            tr.R14 td.R14C1 {
                font-family: Arial;
                font-size: 14pt;
                font-style: normal;
                font-weight: bold;
                vertical-align: medium;
                border-bottom: #000000 2px solid;
            }
            tr.R15 {
                font-family: Arial;
                font-size: 8pt;
                font-style: normal;
            }
            tr.R15 td.R15C1 {
                font-family: Arial;
                font-size: 9pt;
                font-style: normal;
                vertical-align: medium;
            }
            tr.R15 td.R15C7 {
                font-family: Arial;
                font-size: 9pt;
                font-style: normal;
                vertical-align: top;
            }
            td.R16C1 {
                font-family: Arial;
                font-size: 9pt;
                font-style: normal;
                vertical-align: medium;
            }
            td.R16C7 {
                font-family: Arial;
                font-size: 9pt;
                font-style: normal;
                font-weight: bold;
                vertical-align: top;
            }
            tr.R17 {
                height: 6pt;
            }
            td.R18C1 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                font-weight: bold;
                text-align: center;
                vertical-align: medium;
                border-left: #000000 2px solid;
                border-top: #000000 2px solid;
            }
            td.R18C3 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                font-weight: bold;
                text-align: center;
                vertical-align: medium;
                border-left: #000000 1px solid;
                border-top: #000000 2px solid;
            }
            td.R18C33 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                font-weight: bold;
                text-align: center;
                vertical-align: medium;
                border-left: #000000 1px solid;
                border-top: #000000 2px solid;
                border-right: #000000 2px solid;
            }
            td.R19C1 {
                text-align: center;
                vertical-align: top;
                border-left: #000000 2px solid;
                border-top: #000000 1px solid;
            }
            td.R19C15 {
                text-align: right;
                vertical-align: top;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
            }
            td.R19C18 {
                text-align: left;
                vertical-align: top;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
            }
            td.R19C3 {
                text-align: left;
                vertical-align: top;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
            }
            td.R19C33 {
                text-align: right;
                vertical-align: top;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
                border-right: #000000 2px solid;
            }
            tr.R20 {
                height: 7pt;
            }
            tr.R20 td.R20C1 {
                vertical-align: top;
                border-top: #000000 2px solid;
            }
            tr.R20 td.R20C20 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                font-weight: bold;
                text-align: right;
                vertical-align: top;
                border-top: #000000 2px solid;
            }
            tr.R20 td.R20C21 {
                border-top: #000000 2px solid;
            }
            td.R21C1 {
                vertical-align: top;
            }
            td.R21C20 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                font-weight: bold;
                text-align: right;
                vertical-align: top;
            }
            td.R22C1 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                font-weight: bold;
                vertical-align: top;
            }
            td.R24C1 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                text-align: left;
            }
            td.R27C1 {
                font-family: Arial;
                font-size: 8pt;
                font-style: normal;
                font-weight: bold;
                text-align: left;
            }
            td.R28C1 {
                font-family: Arial;
                font-size: 8pt;
                font-style: normal;
                font-weight: bold;
                color: #ff0000;
            }
            td.R29C1 {
                font-family: Arial;
                font-size: 8pt;
                font-style: normal;
                font-weight: bold;
            }
            td.R29C10 {
                text-align: right;
                padding-left: 3px;
            }
            td.R29C20 {
                font-family: Arial;
                font-size: 9pt;
                font-style: normal;
                font-weight: bold;
            }
            td.R33C10 {
                text-align: right;
                border-bottom: #ffffff 1px none;
            }
            td.R33C6 {
                border-bottom: #ffffff 1px none;
            }
            td.R40C1 {
                font-family: Arial;
                font-size: 9pt;
                font-style: normal;
                color: #ff0000;
                text-align: left;
            }
            td.R41C1 {
                font-family: Arial;
                font-size: 8pt;
                font-style: normal;
                color: #ffffff;
            }
            tr.R5 {
                height: 249pt;
            }
            tr.R5 td.R5C1 {
                text-align: justify;
                vertical-align: top;
            }
            td.R6C10 {
                font-family: Arial;
                font-size: 9pt;
                font-style: normal;
                font-weight: bold;
                color: #ff0000;
            }
            td.R6C17 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
            }
            td.R7C1 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                text-align: left;
                vertical-align: top;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
            }
            td.R7C19 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                text-align: left;
                vertical-align: medium;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
            }
            td.R7C22 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                text-align: left;
                vertical-align: medium;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
                border-right: #000000 1px solid;
            }
            td.R8C19 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                vertical-align: top;
                border-left: #000000 1px solid;
                border-top: #000000 1px solid;
            }
            td.R8C22 {
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
                text-align: left;
                vertical-align: top;
                border-left: #000000 1px solid;
                border-right: #000000 1px solid;
            }
            td.R9C1 {
                font-family: Arial;
                font-size: 8pt;
                font-style: normal;
                border-left: #000000 1px solid;
            }
            table {
                table-layout: fixed;
                padding: 0 0 0 1px;
                vertical-align: bottom;
                width: 100%;
                font-family: Arial;
                font-size: 10pt;
                font-style: normal;
            }
            td {
                padding-left: 3px;
            }
            .nonprint{margin:10px;}

            @media print{

                .nonprint {
                    display: none;
                }
            }

        </STYLE>
    </head>
    <body>

        <div align="right" class="nonprint">
            <button onclick="window.print();">
                Распечатать
            </button> 
        </div>

        <table  style="margin-top:15px;" width="100%" cellspacing="0">
            <tr CLASS=R0>
                <TD width="20">&nbsp;</TD>
                <td rowspan="5" width="100" valign="top"><IMG SRC = "@logo@" ALT = ""  STYLE = "margin-top:0px;float:left;"></td>
                <td align="right" CLASS=R0C38><SPAN STYLE="white-space:nowrap">@org_name@, @company@</SPAN></td>
                <TD width="20">&nbsp;</TD>
            </tr>
            <tr CLASS=R0>
                <TD>&nbsp;</TD>
                <td align="right" CLASS=R0C38><SPAN STYLE="white-space:nowrap">Юр. адрес:&nbsp;@org_ur_adres@<br>Почтовый адрес:&nbsp;@org_adres@</SPAN></td>
                <TD>&nbsp;</TD>
            </tr>
            <tr CLASS=R0>
                <TD>&nbsp;</TD>
                <td align="right" CLASS=R0C38><SPAN STYLE="white-space:nowrap">Телефон:&nbsp;@telNum@</SPAN></td>
                <TD>&nbsp;</TD>
            </tr>
            <tr CLASS=R0>
                <TD>&nbsp;</TD>
                <td align="right" CLASS=R0C38><SPAN STYLE="white-space:nowrap">E-mail:&nbsp;@adminMail@</SPAN></td>
                <TD>&nbsp;</TD>
            </tr>
            <tr>
                <TD>&nbsp;<br><br><br><br><br><br><br><br></TD>
            </tr>
        </TABLE>

        <table width="100%">
            <tr>
                <td align="center" colspan="2">
                    <div style="width:155mm; font-size:8pt; ">Внимание! Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате  обязательно, в противном случае не гарантируется наличие товара на складе. Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.</div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div style="text-align:center;  font-weight:bold;">
                        Образец заполнения платежного поручения                                                                                                                                            </div>
                </td>
            </tr>
        </table>


        <table width="100%" cellpadding="2" cellspacing="2" class="invoice_bank_rekv">
            <tr>
                <td colspan="2" rowspan="2" style="min-height:13mm; width: 105mm;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="height: 13mm;">
                        <tr>
                            <td valign="top">
                                <div><b>@org_bank@</b></div>
                            </td>
                        </tr>
                        <tr>
                            <td valign="bottom" style="height: 3mm;">
                                <div style="font-size:10pt;">Банк получателя        </div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="min-height:7mm;height:auto; width: 25mm;">
                    <div>БИK</div>
                </td>
                <td rowspan="2" style="vertical-align: top; width: 60mm;">
                    <div style=" height: 7mm; line-height: 7mm; vertical-align: middle;"><b>@org_bic@</b></div>
                    <div><b>@org_bank_acount@</b></div>
                </td>
            </tr>
            <tr>
                <td style="width: 25mm;">
                    <div>Сч. №</div>
                </td>
            </tr>
            <tr>
                <td style="min-height:6mm; height:auto; width: 50mm;">
                    <div>&nbsp;ИНН&nbsp;<b>@org_inn@</b></div>
                </td>
                <td style="min-height:6mm; height:auto; width: 55mm;">
                    <div>КПП&nbsp;<b>@org_kpp@</b></div>
                </td>
                <td rowspan="2" style="min-height:19mm; height:auto; vertical-align: top; width: 25mm;">
                    <div>Сч. №</div>
                </td>
                <td rowspan="2" style="min-height:19mm; height:auto; vertical-align: top; width: 60mm;">
                    <div><b>@org_schet@</b></div>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="min-height:13mm; height:auto;">

                    <table border="0" cellpadding="0" cellspacing="0" style="height: 13mm; width: 105mm;">
                        <tr>
                            <td valign="top">
                                <div><b>@org_name@</b></div>
                            </td>
                        </tr>
                        <tr>
                            <td valign="bottom" style="height: 3mm;">
                                <div style="font-size: 10pt;">Получатель</div>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>
        <br/>

        <div style="font-weight: bold; font-size: 16pt; padding-left:5px;">
            Счет&nbsp;№&nbsp;@ouid@&nbsp;от&nbsp;@date@</div>
        <br/>

        <div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>

        <table width="100%">
            <tr>
                <td style="width: 30mm;">
                    <div style=" padding-left:2px;">Поставщик:    </div>
                </td>
                <td>
                    <div style="font-weight:bold;  padding-left:2px;">
                        @org_name@, &nbsp;ИНН&nbsp;@org_inn@, КПП&nbsp;@org_kpp@, Юр. адрес:&nbsp;@org_ur_adres@, Почтовый адрес:&nbsp;@org_adres@        </div>
                </td>
            </tr>
            <tr>
                <td style="width: 30mm;">
                    <div style=" padding-left:2px;">Покупатель:    </div>
                </td>
                <td>
                    <div style="font-weight:bold;  padding-left:2px;">
                        @person_user@   @person_org@          </div>
                </td>
            </tr>
        </table>


        <table class="invoice_items" width="100%" cellpadding="2" cellspacing="2">
            <thead>

                <tr class=tablerow>
                    <td class=tablerow>№</td>
                    <td width=50% class=tablerow>Наименование</td>
                    <td class=tablerow>Единица измерения&nbsp;</td>
                    <td class=tablerow>Количество</td>
                    <td class=tablerow>Цена</td>
                    <td class=tableright>Сумма</td>
                </tr>
                @cart@
                <tr>
                    <td colspan=5 align=right style="border-top: 1px solid #000000;border-left: 1px solid #000000;">Скидка:</td>
                    <td class=tableright nowrap><b>@discount@%</b></td>
                </tr>
                <tr>
                    <td colspan=5 align=right style="border-top: 1px solid #000000;border-left: 1px solid #000000;">Итого:</td>
                    <td class=tableright nowrap><b>@total@ @currency@</b></td>
                </tr>
                @nds_block_start@
                <tr>
                    <td colspan=5 align=right style="border-top: 1px solid #000000;border-left: 1px solid #000000;">В т.ч. НДС: @nds@%</td>
                    <td class=tableright nowrap><b>@totalnds@ @currency@</b></td>
                </tr>
                @nds_block_end@
                <tr><td colspan=6 style="border: 0px; border-top: 1px solid #000000;">&nbsp;</td></tr>
        </table>
        <p><b>Всего наименований @item@, на сумму @totaltext@
                <br />
            </b></p><br>
        <div style="background-color:#000000; width:100%; font-size:1px; height:2px;">&nbsp;</div>
        <br/>

        <div>Руководитель ______________________ (Фамилия И.О.)</div>
        <br/>

        <div>Главный бухгалтер ______________________ (Фамилия И.О.)</div>
        <br/>

        <div style="width: 85mm;text-align:center;">М.П.</div>
        <br/>


        <div style="width:800px;text-align:left;font-size:10pt;">Счет действителен к оплате в течении трех дней.</div>

        <table width="100%">
            <TR>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
            </TR>
            <TR>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
            </TR>
            <TR>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
            </TR>
            <TR>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
            </TR>
            <TR>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
            </TR>
            <TR>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
            </TR>
            <TR>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
            </TR>
            <TR>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
                <TD>&nbsp;</TD>
            </TR>
        </TABLE>

    </BODY>
</HTML>
