<?php

use Api\Authentication\Auth;

require_once system_path('authentication/Auth.php');

/**
 * @param $path
 * @return string|string[]
 */
function system_path($path)
{
    return root_path("/api/$path");
}

/**
 * @param $path
 * @return string|string[]
 */
function root_path($path)
{
    return normalize_path($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $path);
}

/**
 * @param $path
 * @return string|string[]
 */
function normalize_path($path)
{
    // replace all slashes to system's slash
    $path = str_replace(['//', '\\\\', '\\/', '/\\', '/', '\\'], DIRECTORY_SEPARATOR, $path);

    // remove unsupported symbols for Windows
    if (PHP_OS_FAMILY === 'Windows') {
        $drive = substr($path, 0, 2);
        $path = substr($path, 2);

        $path = $drive . str_replace(['|', ':', '*', '?', '"', '<', '>'], '_', $path);
    }

    return $path;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'checkIfLogged') {
        ini_set('session.cookie_lifetime', 0);
        session_start();

//        file_put_contents('z3.txt', $_SESSION['username'], 8);
        if (isset($_SESSION["username"])) {
            echo json_encode(['status' => 'logged', 'redirect' => 'users.html']);
        } else {
            echo json_encode(['status' => 'not logged', 'redirect' => 'login.html']);
        }
    }

    if ($_POST['action'] === 'logout') {
        ini_set('session.cookie_lifetime', 0);
        session_start();
        session_destroy();

        if (isset($_COOKIE['username'])) {
            unset($_COOKIE['username']);
            setcookie('username', null);
        }

        echo json_encode(['redirect' => 'login.html']);
    }
}

if ($_POST['action'] === 'checkAuth') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['password'], $_POST['username'])) {
            $authentication = new Auth();
            $result = $authentication->checkAuth();
            echo $result;
        }
    } else {
        echo 'You can not use GET method for authentication';
    }
}