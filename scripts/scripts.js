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
    var data = {
        action: 'checkIfLogged',
    }

    $.ajax({
        type: "POST",
        url: "/api/ajax/ajax_helper.php",
        data: data,
        success: function (data) {
            var info = JSON.parse(data);

            if (info.status === 'logged' && !loginPage) {
                setTimeout(function () {
                    $('.allContent').removeClass('hide');
                    getResult();
                }, 200)
            }

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

function getResult(page = 1) {
    var data = {
        action: 'getStudents',
        page: page
    }

    $.ajax({
        url: "/api/ajax/ajax_helper.php",
        type: "GET",
        data:  data,
        success: function(data){
           var pagination = $('.pagination'),
               info = JSON.parse(data),
               table = $('.table');
           table.html('');
           console.log(info.res);
           table.append($('<table></table>'))
            var tableBody = $('.table table');
            $.each(info.res, function(index) {
                console.log(info.res[index].name);
                var tr = $('<tr></tr>');
                tr.appendTo(tableBody);
                $('<td><div>' + info.res[index].group_name + '</div>' + '<div class="sName">' + info.res[index].name +'</div></td>').appendTo(tr);
                $('<td style="text-align: end;"><div class="empty"> - </div>' + '<div>' + info.res[index].subject +'</div></td>').appendTo(tr);
            });

            pagination.html('');
            for (let i = 1; i <= info.totalPages; i++) {
                var selected = i === parseInt(info.currentPage) ? 'selected' : '';
                pagination.append('<span onclick="getResult('+ i +')" class="'+selected+'" >' + i + '</span>');
            }
        },
        error: function(a, b)
        {
            console.log(a);
            console.log(b);
        }
    });
}