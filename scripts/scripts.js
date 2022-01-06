$(document).ready(function () {
    $('.submit').on('click', function (e) {
        var username = $('input[name="username"]').val(),
            password = $('input[name="password"]').val();
        if (username !== '' && password !== '') {
            e.preventDefault();
        }
        var data = '';

        if ($(this).data('action') === 'logout') {
            data = {
                action: 'logout',
                users: true
            }
            $.ajax({
                type: "POST",
                url: "/api/ajax/ajax_helper.php",
                data: data,
                success: function (data) {
                    console.log(data);
                    var info = JSON.parse(data);
                    window.location.href = info.redirect;
                },
                error: function (a, b) {
                    console.log(a);
                    console.log(b);
                }
            })
        } else {
            data = {
                action: 'login',
                username: username,
                password: password,
                remember: $('input:checkbox:checked').val(),
            }

            $.ajax({
                type: "POST",
                url: "/api/ajax/ajax_helper.php",
                data: data,
                success: function (data) {
                    var info = JSON.parse(data);
                    if (info.redirect) {
                        window.location.href = info.redirect;
                    }
                },
                error: function (a, b) {
                    console.log(a);
                    console.log(b);
                }
            })
        }
    })
});

function checkIfLogged(loginPage = true) {
    var data = {
        action: 'checkIfLogged',
    }

    $.ajax({
        type: "POST",
        url: "/api/ajax/ajax_helper.php",
        data: data,
        success: function (data) {
            var info = JSON.parse(data);
            if (info.status === 'logged' && loginPage) {
                window.location.href = info.redirect;
            } else if (info.status === 'not logged' && !loginPage) {
                window.location.href = info.redirect;
            } else {
                if (checkToken('token')) {
                    loginViaToke(loginPage);
                }
            }
        },
        error: function (a, b) {
            console.log(a);
            console.log(b);
        }
    })
}

function checkToken(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function loginViaToke(loginPage = true) {
    var data = {
        action: 'loginViaToken',
    }

    $.ajax({
        type: "POST",
        url: "/api/ajax/ajax_helper.php",
        data: data,
        success: function (data) {
            var info = JSON.parse(data);
            if (info.status === 'logged' && loginPage) {
                window.location.href = info.redirect;
            }
        },
        error: function (a, b) {
            console.log(a);
            console.log(b);
        }
    })
}