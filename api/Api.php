<?php

namespace Api;
use Api\Config\Database;

abstract class Api
{
    public $conn;

    protected $method = ''; //GET|POST|PUT|DELETE
    public $requestUri = [];
    public $requestParams = [];

    protected $action = ''; //Название метод для выполнения


    public function __construct()
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
        header("Content-Type: application/json");

        //Массив GET параметров разделенных слешем
        $this->requestUri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
        $this->requestParams = $_REQUEST;

        $database = new Database();
        $this->conn = $database->getConnection();
    }

    protected function response($data, $status = 500)
    {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        return json_encode($data);
    }

    private function requestStatus($code)
    {
        $status = array(
            200 => 'OK',
            401 => 'Unauthorized response',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return ($status[$code]) ? $status[$code] : $status[500];
    }
}