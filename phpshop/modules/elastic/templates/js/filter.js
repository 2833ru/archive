$(document).ready(function () {
    var ElasticaFilterInstance = new ElasticaFilter();
    ElasticaFilterInstance.init();

    $('#faset-filter-body').on('click', 'input', function () {
        ElasticaFilterInstance.updateFilter($(this));
    });

    // Дублирование логики с шаблонов, что бы вклинить обновление с бд эластика
    if (window.location.hash != "" && $("#sorttable table td").html()) {

        var filter_str = window.location.hash.split(']').join('][]');

        // Загрузка результата отборки
        filter_load(filter_str);

        // Проставление чекбоксов
        $.ajax({
            type: "POST",
            url: '?' + filter_str.split('#').join(''),
            data: {
                ajaxfilter: true
            },
            success: function (data) {
                if (data) {
                    $("#faset-filter-body").html(data);
                    $("#faset-filter-body").html($("#faset-filter-body").find('td').html());
                    if (ElasticaFilterInstance.updatedOnLoad === false) {
                        ElasticaFilterInstance.updateFilter(false);
                    }
                }
            }
        });
    }

    if (ElasticaFilterInstance.updatedOnLoad === false) {
        ElasticaFilterInstance.updateFilter(false);
    }
});

var ElasticaFilter = function () {
    var self = this;
    self.showCount = false;
    self.disableValues = false;
    self.hideValues = false;
    self.updatedOnLoad = false;

    self.init = function () {
        self.showCount = Number($('[data-show-counts]').attr('data-show-counts')) === 1;
        self.disableValues = Number($('[data-filter-update]').attr('data-filter-update')) === 1;
        self.hideValues = Number($('[data-filter-update]').attr('data-filter-update')) === 2;

        self.bindEvents();
    };

    self.bindEvents = function () {
    };

    self.updateFilter = function (current) {
        var selected = self.getSelected();

        if (selected.length === 0) {
            return;
        }

        $.ajax({
            type: "POST",
            url: ROOT_PATH + "/phpshop/modules/elastic/ajax/filter.php",
            dataType: 'json',
            data: {
                filter: selected,
                categories: $.parseJSON($('input[name="elastic-categories"]').val())
            },
            success: function (data) {
                if(data['success']) {
                    $('#faset-filter-body input').each(function (index, element) {
                        var name = $(element).attr('data-name').split('-');
                        var label = $(element).closest('label');

                        if(data['counts'].hasOwnProperty(name[1])) {
                            if(self.disableValues) {
                                $(label).removeClass('elastic-disabled');
                                $(element).attr('disabled', false);
                            }
                            if(self.hideValues) {
                                $(label).removeClass('elastic-hidden');
                                $(element).removeClass('elastic-hidden');
                            }
                            if(self.showCount) {
                                $(label).find('.elastic-count').html(data['counts'][name[1]]);
                            }
                        } else {
                            if(current === false || current.attr('data-name').split('-')[0] !== name[0]) {
                                if(self.disableValues) {
                                    $(label).addClass('elastic-disabled');
                                    $(element).attr('disabled', true);
                                }
                                if(self.hideValues) {
                                    $(label).addClass('elastic-hidden');
                                    $(element).addClass('elastic-hidden');
                                }
                                $(element).attr('checked', false);
                                if(self.showCount) {
                                    $(label).find('.elastic-count').html(0);
                                }
                            }
                        }
                    });

                    if(selected.length === 1) {
                        $('#faset-filter-body input').each(function (index, element) {
                            var name = $(element).attr('data-name').split('-');
                            var label = $(element).closest('label');

                            if(selected[0].split('-')[0] === name[0]) {
                                if(self.disableValues) {
                                    $(element).attr('disabled', false);
                                    $(label).removeClass('elastic-disabled');
                                }
                                if(self.hideValues) {
                                    $(element).removeClass('elastic-hidden');
                                    $(label).removeClass('elastic-hidden');
                                }
                                if(self.showCount) {
                                    $(label).find('.elastic-count').html($(label).find('.elastic-count').attr('data-base-count'));
                                }
                            }
                        });
                    }
                }
            }
        });

        self.updatedOnLoad = true;
    };

    self.getSelected = function () {
        var selected = [];
        $('#faset-filter-body input:checked').each(function (index, element) {
            selected.push($(element).attr('data-name'));
        });

        return selected;
    };
};