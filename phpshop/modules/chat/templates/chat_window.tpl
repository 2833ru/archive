<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML>
    <HEAD>
        <TITLE>PHPShop.Chat Free</TITLE>
        <META http-equiv="Content-Type" content="text-html; charset=windows-1251">
        <LINK href="./templates/skin/@chat_mod_skin@.css" type="text/css" rel="stylesheet">
        <SCRIPT language="JavaScript" type="text/javascript" src="./lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <SCRIPT language="JavaScript" type="text/javascript" src="./ajax/phpshopchat.js"></SCRIPT>
        <SCRIPT src="./lib/soundmanager2/soundmanager2-nodebug-jsmin.js"></SCRIPT>
        <SCRIPT>
            soundManager.setup({
                url: './lib/soundmanager2/',
                debugMode: false,
                onready: function() {
      
                    mySound = soundManager.createSound({
                        id: 'aSound',
                        url: '@chat_mod_sound@'
                    });
                }
            });

        

        </SCRIPT>


    </HEAD>
    <BODY onLoad="PHPShopChat_ping();PHPShopChat_email();">
    <table width="100%" height="100%">
        <tr valign="top" >
            <td height="70%"><b>Переписка</b>:</td>
            <td height="70%"> <div name="chat_mod_content" id="chat_mod_content">@chat_mod_content@</div>
            </td>
        <tr>
        <tr valign="top" >
            <td height="10%"><b>Вопрос</b>:</td>
            <td height="10%">
                <textarea name="chat_mod_user_text" id="chat_mod_user_text" @chat_mod_disable@></textarea>
                <div> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Смеется onclick="emoticon(':-D');" alt=Смеется src="templates/smiley/grin.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Улыбается onclick="emoticon(':)');" alt=Улыбается src="templates/smiley/smile3.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Грустный onclick="emoticon(':(');" alt=Грустный src="templates/smiley/sad.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title="В шоке" onclick="emoticon(':shock:');" alt="В шоке" src="templates/smiley/shok.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Самоуверенный onclick="emoticon(':cool:');" alt=Самоуверенный src="templates/smiley/cool.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Стесняется onclick="emoticon(':blush:');" alt=Стесняется src="templates/smiley/blush2.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Танцует onclick="emoticon(':dance:');" alt=Танцует src="templates/smiley/dance.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Счастлив onclick="emoticon(':rad:');" alt=Счастлив src="templates/smiley/happy.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title="Под столом" onclick="emoticon(':lol:');" alt="Под столом" src="templates/smiley/lol.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title="В замешательстве" onclick="emoticon(':huh:');" alt="В замешательстве" src="templates/smiley/huh.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Загадочный onclick="emoticon(':rolly:');" alt=Загадочный src="templates/smiley/rolleyes.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Злой onclick="emoticon(':thuf:');" alt=Злой src="templates/smiley/threaten.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title="Показывает язык" onclick="emoticon(':tongue:');" alt="Показывает язык" src="templates/smiley/tongue.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Умничает onclick="emoticon(':smart:');" alt=Умничает src="templates/smiley/umnik2.gif" border=0> 
                    <IMG onmouseover="this.style.cursor='pointer';" title=Запутался onclick="emoticon(':wacko:');" alt=Запутался src="templates/smiley/wacko.gif" border=0> 
                </div>
            </td>
        </tr>
        <tr valign="top">
            <td></td>
            <td height="100">
                <div id="help"><img src="./templates/help.png" align="absmiddle" hspace="3">Используйте сочетание клавиш <b>CTRL + Enter</b> для быстрого ответа</div>
                <div id="exit">
                    <button onclick="PHPShopChat_exit();">
                        <img src="./templates/exit.gif" align="absmiddle" hspace="5">Выход
                    </button>
                </div>
                <div id="post">
                    <button onclick="PHPShopChat_write('@chat_mod_disable@');">
                        <img src="./templates/post.gif" align="absmiddle" hspace="5" id="chat_mod_send_button_icon"><span id="chat_mod_send_button_text">Отправить</span>
                    </button>
                    <input type="hidden"  value="@chat_mod_time@" id="chat_mod_time" name="chat_mod_time">
                </div>

            </td>
        </tr>
    </table>
</BODY>
</HTML>