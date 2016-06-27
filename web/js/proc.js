bootbox.setDefaults({locale: "ru"});

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function ChooseItemGrid(url, targetelement, fromgrid) {
    if (url !== undefined && targetelement !== undefined && fromgrid !== undefined) {
        console.debug(url)
        console.debug(targetelement)
        console.debug(fromgrid)

        // js script

        var $grid = $('#' + fromgrid);

        console.debug($('input:radio[name = "' + fromgrid + '_check"]:checked').val())

        var param = {
            selectelement: targetelement,
            idvalue: $('input:radio[name = "' + fromgrid + '_check"]:checked').val()
        };

        /* $grid.on('grid.radiochecked', function (ev, key, val) {
         console.debug("Key = " + key + ", Val = " + val);
         });*/

// keys is an array consisting of the keys associated with the selected rows

        window.location.href = url + '?' + $.param(param);
    } else
        console.error("Не переданы обязательные параметры в ChooseItemGrid");

}

function SetSession(thiselem) {
    var field = $(thiselem).attr("name");

    $.ajax({
        url: "?r=site%2Fsetsession",
        type: "post",
        data: {modelclass: field.substring(0, field.indexOf("[")), field: field.substring(field.indexOf("[") + 1, field.indexOf("]")), value: $(thiselem).val()},
        async: false,
        error: function (data) {
            console.error("Ошибка SetSession");
        }
    });
}

function SetSessionEach(thiselems_array) {
    var data = [];
    $.each(thiselems_array, function (i, elem) {
        var field = elem.attr("name");
        data.push({
            modelclass: field.substring(0, field.indexOf("[")),
            field: field.substring(field.indexOf("[") + 1, field.indexOf("]")),
            value: elem.val()
        });
    });

    $.ajax({
        url: "?r=site%2Fsetsession",
        type: "post",
        data: {data: JSON.stringify(data)},
        async: false,
        error: function (data) {
            console.error("Ошибка SetSession");
        }
    });
}

function InitWindowGUID() {
    $.ajax({
        url: "?r=site%2Fsetwindowguid",
        type: "post",
        data: {guid: window.name, path: window.location.pathname, search: window.location.search},
        dataType: "json",
        success: function (obj) {
            window.name = obj.guid;
            if (obj.gohome)
                $.ajax({
                    url: "?r=site%2Fgohome",
                });
        },
        error: function (data) {
            console.error("Ошибка setWindowGUID");
        }
    });
}

// dopfields - Дополнительные поля таблицы (например те, что без фильтра)
function ExportExcel(model, url, button, dopfields) {
    var inputarr = $('input[name^="' + model + '"], select[name^="' + model + '"]');
    var inputdata = {};
    var labelvalues = {};
    if ($.type(button) === "undefined")
        button = "";

    if (inputarr.length) {
        inputarr.each(function (index) {
            if ($(this).attr("name") !== "") {
                var attr = ($(this).attr("name")).match(/\[(.*)\]/);

                inputdata[$(this).attr("name")] = $(this).val();

                if (attr !== null)
                    if ($("a[data-sort = '" + attr[1] + "']").length)
                        labelvalues[attr[1]] = $.trim($("a[data-sort = '" + attr[1] + "']").text());
                    else if ($("a[data-sort = '-" + attr[1] + "']").length)
                        labelvalues[attr[1]] = $.trim($("a[data-sort = '-" + attr[1] + "']").text());
            }
        });

        $.extend(inputdata, dopfields);

        var selectvalues = {};
        $('select[name^="' + model + '"]').each(function () {
            var namekey = $(this).attr("name");
            selectvalues[namekey] = [];
            if ($(this).attr("name") !== "") {
                $(this).children("option").each(function () {
                    if ($(this).val() !== "")
                        selectvalues[namekey][$(this).val()] = $(this).text();
                });
            }
        });

        var data = {inputdata: JSON.stringify(inputdata), selectvalues: JSON.stringify(selectvalues), labelvalues: JSON.stringify(labelvalues)};

        $.ajax({
            url: url + '&' + $.param(data),
            type: "post",
            data: {buttonloadingid: button}, /* buttonloadingid - id кнопки, для дизактивации кнопки во время выполнения запроса */
            async: true,
            success: function (response) {
                /* response - Путь к новому файлу  */
                window.location.href = "files/" + response; /* Открываем файл */
                /* Удаляем файл через 5 секунд*/
                setTimeout(function () {
                    $.ajax({
                        url: "?r=site%2Fdelete-excel-file",
                        type: "post",
                        data: {filename: response},
                        async: true
                    });
                }, 5000);
            },
            error: function (data) {
                console.error('Ошибка');
            }
        });


    }
}

/**
 * Показываем индикатор ожидания на кнопке
 * 
 * param.buttonelem - Кнопка, над которой проводятся манипуляции
 * 
 */
function LoadingButtonShow(param) {
    if (typeof param !== "undefined" && ("buttonelem" in param)) {
        param.buttonelem.attr("disabled", true);
        param.buttonelem.html('<img src="images/progress.gif">');
    }
}

/**
 * Скрываем индикатор ожидания на кнопке
 * 
 * param.buttonelem - Кнопка, над которой проводятся манипуляции
 * param.text - Текст кнопки, который был до показа индикатора
 */
function LoadingButtonHide(param) {
    if (typeof param !== "undefined" && ("buttonelem" in param)) {
        param.buttonelem.attr("disabled", false);
        if (("text" in param))
            param.buttonelem.html(param.text);
    }
}

/* Событие до выполнения ajax запроса, изменить состояние кнопки на ожидание */
$(document).ajaxSend(function (event, xhr, settings) {
    if (("data" in settings) && Object.prototype.toString.call(settings.data) == '[object String]' && settings.data.indexOf('buttonloadingid') >= 0 && settings.data.match(/buttonloadingid=(\w+)(&|$)/i) !== null) {
        var buttonloadingid = "#" + settings.data.match(/buttonloadingid=(\w+)(&|$)/i)[1];
        if ($(buttonloadingid).length) {
            $(buttonloadingid)[0].label = $(buttonloadingid).html();
            LoadingButtonShow({buttonelem: $(buttonloadingid)});
        }
    }
});

/*  Событие после выполнения ajax запроса, изменить состояние кнопки из ожидания в обычное */
$(document).ajaxComplete(function (event, xhr, settings) {
    if (("data" in settings) && Object.prototype.toString.call(settings.data) == '[object String]' && settings.data.indexOf('buttonloadingid') >= 0 && settings.data.match(/buttonloadingid=(\w+)(&|$)/i) !== null) {
        var buttonloadingid = "#" + settings.data.match(/buttonloadingid=(\w+)(&|$)/i)[1];
        if ($(buttonloadingid).length) {
            if ($(buttonloadingid).hasClass("wait_label_load_after_ajax"))
                setTimeout(function () {
                    LoadingButtonHide({buttonelem: $(buttonloadingid), text: $(buttonloadingid)[0].label});
                }, 2000);
            else
                LoadingButtonHide({buttonelem: $(buttonloadingid), text: $(buttonloadingid)[0].label});
        }
    }
});

/* Диалог подтверждения перед выполнением Ajax запроса*/
function ConfirmDialogToAjax(message, url, data, funcafteraccess) {
    if (typeof (data) === "undefined")
        data = {};
    if (typeof (message) == "undefined")
        message = "Вы уверены что хотите выполнить это действие?";
    if (typeof (url) != "undefined") {
        bootbox.confirm(message, function (result) {
            if (result) {
                $.ajax({
                    url: url,
                    type: "post",
                    data: data,
                    success: function () {
                        if ((typeof (funcafteraccess) === "function"))
                            funcafteraccess.apply($(this));
                    },
                    error: function () {
                        console.error("ConfirmDialogToAjax: " + url);
                    }
                });
            }
        });
    }
}

/* Диалог подтверждения удаления записи посредством pjax и обновлением грида*/
function ConfirmDeleteDialogToAjax(message, url, gridpjax, data, funcafteraccess) {
    if (typeof (data) === "undefined")
        data = {};
    if (typeof (message) === "undefined")
        message = "Вы уверены что хотите выполнить это действие?";
    if (typeof (url) !== "undefined") {
        bootbox.confirm(message, function (result) {
            if (result) {
                if (typeof (gridpjax) === "undefined" && $("div[data-pjax-container]").length == 1)
                    gridpjax = $("div[data-pjax-container]").attr("id");
                else if (typeof (gridpjax) !== "undefined")
                    gridpjax = gridpjax + "-pjax";

                $.ajax({
                    url: url,
                    type: "post",
                    data: data,
                    success: function () {

                        if (typeof (gridpjax) !== "undefined")
                            $.pjax.reload({container: "#" + gridpjax});

                        if ((typeof (funcafteraccess) === "function"))
                            funcafteraccess.apply($(this));
                    },
                    error: function (err) {
                        if (err.status == "500" && (err.responseText).indexOf("Integrity constraint violation") >= 0)
                            bootbox.alert("Удаление записи невозможно, т. к. она имеется в других таблицах!");
                        else if ((err.responseText).indexOf("Internal Server Error (#500): ") >= 0)
                            bootbox.alert((err.responseText).substring(30));
                        else
                            bootbox.alert(err.responseText);
                    }
                });
            }
        });
    }
}

/* Функция отправляет запрос на присвоение значения из справочника 
 * URL - URL действия присвоения значения
 * ValueID - Значение первичного ключа, выбраной записи
 * 
 * */
function AssignValueFromGrid(URL, ValueID) {
    var assigndata = {};
    if (typeof (URL) === "string" && typeof (ValueID) === "string") {
        $.ajax({
            url: URL,
            type: "post",
            data: {assigndata: ValueID},
            success: function (data) {
                console.debug(data)
            }
        });
    }
}

function GetScrollFilter(ThisElement) {
    var filterurl = $(ThisElement).parent("form").attr("action");

    var tmpsc = localStorage.getItem('scrollfilter');
    if (tmpsc !== null) {
        var tmpsc_obj = JSON.parse(tmpsc);

        if (filterurl in tmpsc_obj) {
            $(ThisElement).animate({
                scrollTop: tmpsc_obj[filterurl]
            }, 500);
        }
    }
}

function SetScrollFilter(ThisElement) {
    var tmpsc = localStorage.getItem('scrollfilter');
    var filterurl = $(ThisElement).parent("form").attr("action");
    if (tmpsc !== null) {
        var tmpsc_obj = JSON.parse(tmpsc);
        tmpsc_obj[filterurl] = $(ThisElement).scrollTop();
        localStorage.setItem('scrollfilter', JSON.stringify(tmpsc_obj));
    } else {
        var tmpsc_obj = {};
        tmpsc_obj[filterurl] = $(ThisElement).scrollTop();
        localStorage.setItem('scrollfilter', JSON.stringify(tmpsc_obj));
    }
}

$(document).ready(function () {
    $("input[type='text'].form-control.krajee-datepicker").mask('99.99.9999');
    $("input.form-control.setsession, select.form-control.setsession, textarea.form-control.setsession").change(function () {
        SetSession(this);
    });

    InitWindowGUID();

    if ($.inArray(window.location.search, ["", "?r=site%2Findex"]) >= 0) {
        localStorage.removeItem('scroll');
        localStorage.removeItem('scrollfilter');
    }
    else {
        var tmpsc = localStorage.getItem('scroll');
        if (tmpsc !== null) {
            var tmpsc_obj = JSON.parse(tmpsc);
            if (window.location.search in tmpsc_obj)
                $("html,body").animate({
                    scrollTop: tmpsc_obj[window.location.search]
                }, 500);
        }

    }

    $(window).scroll(function () {
        var tmpsc = localStorage.getItem('scroll');
        if (tmpsc !== null) {
            var tmpsc_obj = JSON.parse(tmpsc);
            tmpsc_obj[window.location.search] = $(document).scrollTop();
            localStorage.setItem('scroll', JSON.stringify(tmpsc_obj));
        } else {
            var tmpsc_obj = {};
            tmpsc_obj[window.location.search] = $(document).scrollTop();
            localStorage.setItem('scroll', JSON.stringify(tmpsc_obj));
        }

    });
});