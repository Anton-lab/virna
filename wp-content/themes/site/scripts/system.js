$(document).ready(function () {
    $("[name='phone']").mask("+38 (999) 999-9999");
    $('.validation-field input[type="text"],.validation-field input[type="number"],.validation-field textarea').on("blur", function () {
        $(this).val() ? $(this).parent().addClass('active') : $(this).parent().removeClass('active');
    }).each(function () {
        $(this).val() ? $(this).parent().addClass('active') : $(this).parent().removeClass('active');
        if ($(this).attr("placeholder") && $(this).attr("placeholder").indexOf('*') != -1) {
            var placeholder = $(this).attr("placeholder");
            $(this).next(".placeholder").text(placeholder);
        }
    });

    $(".custom-form").each(function () {
        $(this).children().validate({
            rules: {
                name: "required",
                phone: "required",
                comment: "required",
            },
            messages: {
                name: "Обязательное поле",
                phone: "Обязательное поле",
                comment: "Обязательное поле",
            },
            submitHandler: function (form) {
                $.ajax({
                    url: '/wp-admin/admin-ajax.php',
                    type: 'post',
                    data: $(form).serialize(),
                    success: function (response) {
                        let {data} = response;
                        $(form).find('.form-success').addClass('green').html(data.form_title);
                        $(form).trigger("reset");
                    }
                });
                return false;
            }
        });
    });

    $('.nav a').each(function () {
        let location = window.location.href;
        let link = this.href;
        if (location === link) {
            $(this).addClass('active');
        }
    });

    $('.search-form__input').keyup(function () {
        if ($(this).val().length > 2) {
            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                type: 'POST',
                data: {
                    'action': 'ajax_search',
                    'term': $(this).val()
                },
                success: function (results) {
                    $('.search_list').fadeIn(200).html(results);
                }
            });
        } else {
            $('.search_list').fadeOut(200);
        }
    });

    $(document).on('click', '#search_submit', function () {
        $(this).parents('form').find('input[type=submit]').click();
    });

    $(document).mouseup(function (e) {
        if (($('.search-form__input').has(e.target).length === 0) && ($('.search_list').has(e.target).length === 0)) {
            $('.search_list').fadeOut(200);
        }
    });
});

jQuery.fn.ForceNumericOnly =
    function () {
        let ua = navigator.userAgent.toLowerCase();
        let isAndroid = ua.indexOf("android") > -1;
        return this.each(function () {
            if (isAndroid) {
                $(this).attr('type', 'number');
            } else {
                var regex = /([0-9( )+-])/g;
                $(this).on('keydown keypress', function (e) {
                    if (!(e.key.match(regex) || e.key === "Backspace" || e.key === "ArrowRight" || e.key === "ArrowLeft" || e.key === "Delete")) {
                        e.preventDefault();
                    }
                });
            }
        });
    };