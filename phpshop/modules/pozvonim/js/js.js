var pozvonim = {
    working      : false,
    register     : function (el) {
        if (!pozvonim.working)
        {
            pozvonim.working = 1;
            pozvonim.ajax('adm_module.php', {
                phone               : document.getElementById('phone').value,
                email               : document.getElementById('email').value,
                host                : encodeURI(document.getElementById('host').value),
                token               : document.getElementById('token').value,
                editID              : 'ok',
                'actionList[editID]': 'actionRegister'
            }, function (data) {
                if (data != 'ok')
                {
                    alert(data);
                    pozvonim.working = 0;
                } else
                {
                    location.reload();
                }
            });
        }
        return false;
    },
    restore      : function (el) {
        if (!pozvonim.working && confirm('Вы действительно хотите запросить восстановление своего секретного кода?'))
        {
            pozvonim.working = 1;
            var email = document.getElementById('email').value;
            if (email.trim().length < 1)
            {
                alert('Для восстановления необходим email');
            }
            pozvonim.ajax('adm_module.php', {
                email               : email,
                editID              : 'ok',
                'actionList[editID]': 'actionRestore'
            }, function (data) {
                if (data != 'ok')
                {
                    alert(data);
                    pozvonim.working = 0;
                } else
                {
                    location.reload();
                }
            });
        }
        return false;
    },
    saveCode     : function () {
        if (!pozvonim.working)
        {
            pozvonim.working = 1;
            pozvonim.ajax('adm_module.php', {
                code                : document.getElementById('code').value,
                editID              : 'ok',
                'actionList[editID]': 'actionCode'
            }, function (data) {
                if (data != 'ok')
                {
                    alert(data);
                    pozvonim.working = 0;
                } else
                {
                    location.reload();
                }
            });
        }
        return false;
    },
    ajax         : function (url, data, successCallback) {
        var request;
        if (window.XMLHttpRequest)
        {
            request = new XMLHttpRequest();
        } else if (window.ActiveXObject)
        {
            request = new ActiveXObject("Microsoft.XMLHTTP");
        } else
        {
            return;
        }
        request.onreadystatechange = function () {

            if (request.readyState == 4)
            {
                if (request.status == 200)
                {
                    if (successCallback)
                    {
                        successCallback(request.responseText);
                    }
                } else if (request.status == 404)
                {
                    alert("Ошибка: запрашиваемый скрипт не найден!");
                }
                else alert("Ошибка: сервер вернул статус: " + request.status);
            }

        }
        request.open("POST", url, true);
        request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        if (data)
        {
            var postData = [];
            var keys = Object.keys(data);
            for (var n in keys)
            {
                name = keys[ n ];
                postData.push(name + '=' + data[ name ]);
            }
            if (postData.length > 0)
            {
                request.send(postData.join('&'));
            }
        } else
        {
            request.send();
        }
    },
    print_console: function (text) {
        document.getElementById("console").innerHTML += text;
    }

}