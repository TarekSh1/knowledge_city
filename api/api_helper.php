<?php

use Api\Classes\Auth;
use Api\Classes\Students;

require_once system_path('Classes/Students.php');

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

$path = explode('api/', $_SERVER['REQUEST_URI'])[1];

if ($path === 'checkIfLogged') {
    $authentication = new Auth();
    $result = $authentication->checkIfLogged();
    echo $result;
}

if ($path === 'logout') {
    $authentication = new Auth();
    $result = $authentication->logout();
    echo $result;
}

if (strpos($path, 'getStudents') !== false) {
    $students = new Students();
    $result = $students->getStudents();
    echo $result;
}

if ($path === 'login') {
    if (isset($_POST['password'], $_POST['username'])) {
        $authentication = new Auth();
        $result = $authentication->login();
        echo $result;
    }
}