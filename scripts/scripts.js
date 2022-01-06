$(document).ready(function () {
    $('.submit').on('click', function (e) {
        var username = $('input[name="username"]').val(),
            password = $('input[name="password"]').val();
        if (username !== '' && password !== '') {
            e.preventDefault();
        }
        var data = '';

        if ($(this).data('action') === 'logout') {
            var token = checkCookie('api_token');
            $.ajax({
                type: "POST",
                headers: {"Authorization": 'Bearer ' + token},
                url: "/api/logout",
                data: {},
                success: function (data) {
                    $.removeCookie("api_token", {path: '/'});
                    window.location.href = data.redirect;
                },
                error: function (a, b) {
                    console.log(a);
                    console.log(b);
                }
            })
        } else {
            data = {
                username: username,
                password: password,
                remember: $('input:checkbox:checked').val(),
            }

            $.ajax({
                type: "POST",
                url: "/api/login",
                data: data,
                success: function (data) {
                    var info = data;
                    var life = info.cookieLife > 1 ? info.cookieLife : '';
                    $.cookie("api_token", info.cookie, {expires: parseInt(life), path: '/'});
                    if (info.status === 'success') {
                        window.location.href = 'users.html';
                    } else if (info.status === 'failed') {
                        $('span.errMsg').html(info.msg);
                        $('span.errMsg').removeClass('hide');
                    }
                },
                error: function (a, b) {
                    console.log(a);
                    console.log(b);
                }
            })
        }
    });

    $('input.initial').on('keypress', function () {
        $('span.errMsg').addClass('hide');
    });
});

function checkIfLogged(loginPage = true) {
    var token = $.cookie("api_token");
    if (!token && !loginPage) {
        window.location.href = 'login.html';
    }

    $.ajax({
        type: "POST",
        headers: {"Authorization": 'Bearer ' + token},
        url: "/api/checkIfLogged",
        data: {},
        success: function (data) {
            var info = data;

            if (info.status === 'logged' && !loginPage) {
                $('.allContent').removeClass('hide');
            }

            if (info.status === 'logged' && loginPage) {
                window.location.href = 'users.html';
            } else if (info.status === 'not logged' && !loginPage) {
                window.location.href = 'login.html';
            }

        },
        error: function (a, b) {
            console.log(a);
            console.log(b);
        }
    })
}

function checkCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function getResult(page = 1) {
    var token = checkCookie('api_token');

    var data = {
        page: page
    }

    $.ajax({
        url: "/api/getStudents",
        type: "GET",
        headers: {"Authorization": 'Bearer ' + token},
        data: data,
        success: function (data) {
            var pagination = $('.pagination'),
                info = data,
                table = $('.table');
            table.html('');
            table.append($('<table></table>'))
            var tableBody = $('.table table');
            $.each(info.res, function (index) {
                var tr = $('<tr></tr>');
                tr.appendTo(tableBody);
                $('<td><div>' + info.res[index].group_name + '</div>' + '<div class="sName">' + info.res[index].name + '</div></td>').appendTo(tr);
                $('<td style="text-align: end;"><div class="empty"> - </div>' + '<div>' + info.res[index].subject + '</div></td>').appendTo(tr);
            });

            pagination.html('');
            for (let i = 1; i <= info.totalPages; i++) {
                var selected = i === parseInt(info.currentPage) ? 'selected' : '';
                pagination.append('<span onclick="getResult(' + i + ')" class="' + selected + '" >' + i + '</span>');
            }
        },
        error: function (a, b) {
            console.log(a);
            console.log(b);
        }
    });
}