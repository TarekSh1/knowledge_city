<?php

namespace Api\Authentication;

use Api\Config\Database;

require_once $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/Database.php';

class Auth
{

    // database connection and table name
    private $conn;
    private $table_name = 'users';


    // constructor with $db as database connection
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * @return string
     */
    public function checkAuth(): string
    {
        $pdo = $this->conn;
        $username = $_POST['username'];
        $sql = "select `password` from " . $this->table_name . " where user_name = :name ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $username]);

        $password = $stmt->fetchColumn();

        if (password_verify($_POST['password'], $password)) {

//            if ($_POST['remember'] !== 'on') {
//                setcookie("username", $username, 0 ,'/');
//            }

            if ($_POST['remember'] === 'on') {
                ini_set('session.cookie_lifetime', 0);
                session_start();
                $_SESSION['username'] = $username;
            }

            $response = [
                'msg' => 'Login successful',
                'redirect' => 'users.html'
            ];

            return json_encode($response);
        }

        $response = [
            'msg' => 'Username or Password incorrect',
        ];

        return json_encode($response);
    }
}