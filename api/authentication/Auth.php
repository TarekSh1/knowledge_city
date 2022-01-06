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
    public function login(): string
    {
        $pdo = $this->conn;
        $username = $_POST['username'];
        $sql = "select * from " . $this->table_name . " where user_name = :name ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $username]);

        $user_info = $stmt->fetch();

        if (password_verify($_POST['password'], $user_info['password'])) {
            session_start();
            $_SESSION['username'] = $username;

            if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
                $token = $this->generateRandomToken();
                $this->storeTokenForUser($token, $user_info['id']);
                $cookie = $user_info['id'] . '_!!' . $user_info['user_name'] . '_!!' .$token;
                $mac = hash_hmac('sha256', $cookie, 'hashRules');
                $cookie .= '_!!' . $mac;

                setcookie('token', $cookie, strtotime('+30 days'), '/');
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

    private function generateRandomToken()
    {
        return hash('sha512', time());
    }

    /**
     * @param $token
     * @param $userId
     * @return void
     */
    private function storeTokenForUser($token, $userId): void
    {
        $pdo = $this->conn;
        $sql = "UPDATE users SET token = :token WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['token' => $token, 'id' => $userId]);
    }

    /**
     * @return false|string
     */
    public function loginViaToken()
    {
        $cookie = $_COOKIE['token'] ?? '';
        if ($cookie) {
            list ($user, $username, $cookieToken, $mac) = explode('_!!', $cookie);
            if (!hash_equals(hash_hmac('sha256', $user . '_!!' . $username . '_!!' . $cookieToken, 'hashRules'), $mac)) {
                return json_encode(['msg' => 'Lol, Are you trying to hack my client?']);
            }
            $usertoken = $this->fetchTokenByUserName($user);

            if (hash_equals($usertoken, $cookieToken)) {
                session_start();
                $_SESSION['username'] = $username;

                $response = [
                    'status' => 'logged',
                    'redirect' => 'users.html'
                ];

                return json_encode($response);
            }
        }
        $response = [
            'status' => 'no saved token',
        ];

        return json_encode($response);
    }

    private function fetchTokenByUserName($userId)
    {
        $pdo = $this->conn;
        $sql = "select token from " . $this->table_name . " where id = :userId ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);

        return $stmt->fetchColumn();
    }
}