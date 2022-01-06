<?php


namespace Api\Classes;

use Api\Api;
use Api\Classes\Auth;

require_once  $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/classes/Auth.php';
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/Database.php';
require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/Api.php';

class Students extends Api
{

    private $table_name = 'students';

    public function getStudents()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->response('NOT ALLOWED', 405);
        }

        $auth = new Auth();

        $tokenApi = json_decode($auth->checkIfLogged());

        if ($tokenApi->status !== 'logged') {
            return $this->response($tokenApi,401);
        }

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

        $show  = "SELECT * FROM " . $this->table_name . " ORDER BY id ASC LIMIT $starting_limit, $limit";

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