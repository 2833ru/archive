//https://code.jquery.com/ui/1.11.3/jquery-ui.min.js'
//https://api.jqueryui.com/droppable/

$().ready(function () {

    $('.list-group-item').draggable({containment: ".list-group", revert: true, revertDuration: 0});

    $(".list-group").droppable({
        accept: ".list-group-item", //принимаем только чёрного
        over: function (event, ui)//если фигура над клеткой- выделяем её границей
        {
            $(this).addClass('hover');
        },
        out: function (event, ui)//если фигура ушла- снимаем границу
        {
            $(this).removeClass('hover');
        },
        drop: function (event, ui)//если бросили фигуру в клетку
        {
            $(this).append(ui.draggable);//перемещаем фигуру в нового предка
            $(this).removeClass('hover');//убираем выделение
            alert(ui.draggable.attr('data-nodeid'));
        }
    });

});