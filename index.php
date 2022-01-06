<?php

use Api\Classes\Students;

$request = $_SERVER['REQUEST_URI'];

switch ($request) {
    case '/api/logout':
    case '/api/checkIfLogged':
    case (strpos($request, 'getStudents') !== false) :
    case '/api/login' :
    require_once (__DIR__ . '/api/api_helper.php');
        break;

    case '' :
        echo 'Welcome to Knowledge City';
        break;

    default:
        http_response_code(404);
        require_once (__DIR__ . '/404.php');
        break;
}