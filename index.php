<?php

use Api\Classes\Students;

$request = $_SERVER['REQUEST_URI'];

//die($request);

switch ($request) {
    case '/api/logout':
    case '/api/checkIfLogged':
    case (strpos($request, 'getStudents') !== false) :
    case '/api/login' :
        require __DIR__ . '/api/api_helper.php';
        break;

    case '' :
        echo 'Welcome to Knowledge City';
        break;

    default:
        http_response_code(404);
        require __DIR__ . '/404.php';
        break;
}