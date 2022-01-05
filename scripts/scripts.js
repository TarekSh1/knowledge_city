$(document).ready(function (){
    $('.submit').on('click', function (e) {
        e.preventDefault();
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
                action: 'checkAuth',
                username: $('input[name="username"]').val(),
                password: $('input[name="password"]').val(),
                remember: $('input:checkbox:checked').val(),
            }

            console.log(data);

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
            } else if (info.status ==='not logged' && !loginPage) {
                window.location.href = info.redirect;
            }
        },
        error: function (a,b) {
            console.log(a);
            console.log(b);
        }
    })
}