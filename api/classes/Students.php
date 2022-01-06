<?php


namespace Api\Classes;

use Api\Config\Database;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/Database.php';

class Students
{
    // database connection and table name
    private $conn;
    private $table_name = 'students';

    // constructor with $db as database connection
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    protected function response($data, $status = 500) {
        header("HTTP/1.1 " . $status . " " . $this->requestStatus($status));
        return json_encode($data);
    }

    private function requestStatus($code): string
    {
        $status = array(
            200 => 'OK',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        );
        return $status[$code] ?? $status[500];
    }

    public function getStudents()
    {
        $pdo = $this->conn;
        $limit = 5;
        $sql = "SELECT count(*) as total from " . $this->table_name;
        $stmt = $pdo->query($sql);
        $students_info = $stmt->fetchColumn();
        $total_pages = ceil($students_info/$limit);

        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }


        $starting_limit = ($page - 1) * $limit;

        $show  = "SELECT * FROM students ORDER BY id ASC LIMIT $starting_limit, $limit";

        $r = $pdo->prepare($show);
        $r->execute();
        $res = $r->fetchAll();

        $response = [
            'res' => $res,
            'totalPages' => $total_pages,
            'currentPage' => $page
        ];

        return $this->response($response, 200);

    }
}