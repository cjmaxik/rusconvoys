/**
 * Code
 */

$(function () {
    $('.lazy').Lazy({
        scrollDirection: 'vertical',
        effect:          'fadeIn',
        effectTime:      1000,
        threshold:       0,
        visibleOnly:     true,
        onError:         function (element) {
            console.log('error loading ' + element.data('src'));
        }
    });
});

$(document).ajaxStart(function () {
    NProgress.start();
});

$(document).ajaxStop(function () {
    NProgress.done();
});

function getCookie (name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? true : false;
}

function setCookie (name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires * 1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for (var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}

$(document).ready(function ($) {
    NProgress.done();

    $('[data-toggle="tooltip"]').tooltip();
    $('.collapsible').collapsible();

    if (!getCookie('betaMessage')) {
        $('div#betaMessage').show();
    }

    $('div#betaMessage').on('closed.bs.alert', function () {
        setCookie('betaMessage', 'yep', {expires: 60 * 60 * 24 * 3});
    })

    $('a.navbar-brand').on('mouseenter', function () {
        $(this).find('i.animate-truck').addClass('animated lightSpeedIn');
    }).on('mouseleave', function () {
        $(this).find('i.animate-truck').removeClass('animated lightSpeedIn');
    });

    $('#tag_color').on('change', function (event) {
        event.preventDefault();
        var this_div = $('#tag_color');
        var that_div = $('input#tag');

        this_div.removeClass().addClass('' + $(this).val());
        that_div.removeClass().addClass('form-control tag-color-input ' + $(this).val());
    });

    $('button.delete-comment').on('click', function (event) {
        event.preventDefault();

        var id = $(this).data('id');

        swal({
            title:               "О нет!",
            text:                "Точно удалить данный комментарий?",
            type:                "warning",
            showCancelButton:    true,
            cancelButtonText:    "Нет",
            showLoaderOnConfirm: true,
            confirmButtonText:   "Да",
            confirmButtonClass:  'btn btn-primary',
            cancelButtonClass:   'btn btn-danger',
            customClass:         'modal-content',
            buttonsStyling:      false,
        }).then(function (result) {
            $.ajax({
                url:      URL_comment_deletePost,
                type:     'POST',
                dataType: 'json',
                data:     {
                    id:     id,
                    _token: window.Laravel.csrfToken
                },
                success:  function (data) {
                    console.log(data);

                    var comment = 'div#comment' + id;

                    if (data.state == 'ok') {
                        $($('template#deleted-comment').html()).insertAfter(comment);
                        $(comment).slideUp('slow');
                        // swal({
                        //  title: "Комментарий удален!",
                        //  type: "success"
                        // });
                    }
                }
            })
        })
    });

    $('form#comments_newPost textarea#text').on('input', function () {
        var text = $('textarea#text').val();
        console.log(text);
        if (text.length <= 9) {
            $('button#comments_new').parent('div').slideUp();
        } else {
            $('button#comments_new').parent('div').slideDown();
        }
    })

    $('form#comments_newPost').on('submit', function (event) {
        var text = $('textarea#text').val();

        if (text.length <= 9) {
            event.preventDefault();
            swal({
                title: "Комментарий должен быть больше 10 символов!",
                type:  "warning"
            });
        } else {
            $('button#comments_new')
                .removeClass('btn-primary')
                .addClass('btn-danger')
                .prop('disabled', true)
                .html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
        }
    });

    $('a.participate').on('click', function (event) {
        event.preventDefault();

        var type = $(this).data('type');
        var convoy_id = $(this).data('convoy');

        $.ajax({
            url:      URL_convoy_participationPost,
            type:     'POST',
            data:     {
                type:      type,
                convoy_id: convoy_id,
                _token:    window.Laravel.csrfToken
            },
            success:  function (data) {
                console.log(data);

                if (data === 'nope') {
                    $('a#data-nope').fadeOut('fast');
                    $('a#data-yep').fadeIn('fast');
                    $('a#data-thinking').fadeIn('fast');
                } else if (data === 'yep') {
                    $('a#data-yep').fadeOut('fast');
                    $('a#data-nope').fadeIn('fast');
                    $('a#data-thinking').fadeIn('fast');
                } else if (data === 'thinking') {
                    $('a#data-thinking').fadeOut('fast');
                    $('a#data-yep').fadeIn('fast');
                    $('a#data-nope').fadeIn('fast');
                }

                window.location.reload();
            },
            complete: function () {
                $('button#comments_new')
                    .addClass('btn-default')
                    .removeClass('btn-danger')
                    .prop('disabled', false)
                    .html('Отправить <i class="fa fa-paper-plane right" aria-hidden="true"></i>');
            }
        })
    });

    $('a#cancel_convoy').on('click', function (event) {
        event.preventDefault();

        swal({
            title:               "Отмена конвоя?",
            text:                "Если ты действительно не можешь провести конвой, укажи причину. Мы оповестим всех участников.",
            type:                "warning",
            input:               'text',
            showLoaderOnConfirm: true,
            showCancelButton:    true,
            showLoaderOnConfirm: true,
            confirmButtonColor:  "#DD6B55",
            confirmButtonText:   "Уверен!",
            cancelButtonText:    "Отмена",
            confirmButtonClass:  'btn btn-primary',
            cancelButtonClass:   'btn btn-danger',
            customClass:         'modal-content',
            buttonsStyling:      false,
            inputClass:          'form-control',
            preConfirm:          function (text) {
                return new Promise(function (resolve, reject) {
                    if (!text) {
                        reject('Ты должен ввести причину отмены конвоя!');
                    } else {
                        $.ajax({
                            url:     URL_convoy_cancel_post,
                            type:    'POST',
                            data:    {
                                id:      convoy_id,
                                message: text,
                                _token:  window.Laravel.csrfToken
                            },
                            success: function (data) {
                                if (data === 'OK') {
                                    swal({
                                        title:             "Конвой был успешно отменен",
                                        text:              "Через 3 секунды ты будешь перенаправлен на главную страницу.",
                                        type:              "success",
                                        showConfirmButton: false,
                                        allowOutsideClick: false,
                                        allowEscapeKey:    false,
                                        allowEnterKey:     false
                                    })

                                    setTimeout(function () {
                                        window.location.href = '/';
                                    }, 3000);
                                }
                            }
                        });

                        setTimeout(function () {
                            resolve();
                        }, 20000)
                    }
                })
            },
            allowOutsideClick:   false
        }).then(function (meme) {
            swal({text: "Хм... Что-то слишком долго. Обнови страницу и попробуй еще раз."});
        });
    });
});
