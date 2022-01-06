<?php

namespace Api\Classes;

use Api\Api;
use PDO;
use stdClass;

require_once ($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/config/Database.php');
require_once ($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'api/Api.php');

class Auth extends Api
{

    // database connection and table name

    private $table_name = 'users';

    private function generateRandomToken(): string
    {
        return hash('sha512', time());
    }

    /**
     * @return string
     */
    public function login(): string
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $this->response('NOT ALLOWED', 405);
        }

        $pdo = $this->conn;
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "select * from " . $this->table_name . " where user_name = :name ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['name' => $username]);

        $user_info = $stmt->fetch();

        if (password_verify($password, $user_info['password'])) {
            $token = $this->generateRandomToken();
            $this->storeApiTokenForUser($token, $user_info['id']);
            $cookie = $user_info['id'] . '_!!' . $user_info['user_name'] . '_!!' . $token;
            $mac = hash_hmac('sha256', $cookie, 'hashRules');
            $cookie .= '_!!' . $mac;
            $cookieLife = 1;

            if (isset($_POST['remember']) && $_POST['remember'] === 'on') {
                $cookieLife = 5 * 365;
            }

            $response = [
                'status' => 'success',
                'msg' => 'Login successful',
                'cookie' => $cookie,
                'cookieLife' => $cookieLife,
            ];

        } else {
            $response = [
                'status' => 'failed',
                'msg' => 'Username or Password incorrect',
            ];
        }

        return $this->response($response, 200);
    }

    /**
     * @param $apiToken
     * @param $userId
     * @return void
     */
    private function storeApiTokenForUser($apiToken, $userId): void
    {
        $pdo = $this->conn;
        $sql = "UPDATE users SET api_token = :apiToken WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['apiToken' => $apiToken, 'id' => $userId]);
    }

    /**
     * @param $cookie
     * @return stdClass
     */
    private function getAllParams($cookie): stdClass
    {
        list ($user, $username, $cookieApiToken, $mac) = explode('_!!', $cookie);
        $userId = str_replace('Bearer ','', $user);
        $data = new stdClass();

        if (hash_equals(hash_hmac('sha256', $userId . '_!!' . $username . '_!!' . $cookieApiToken, 'hashRules'), $mac)) {
            $data->status = 'success';
            $data->user = $userId;
            $data->cookieApiToken = $cookieApiToken;
            $data->mac = $mac;

            return $data;
        }

        $data->status = 'failed';
        return $data;
    }


    /**
     * @return false|string
     */
    public function checkApiToken()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $this->response('NOT ALLOWED', 405);
        }

        $tokenApi = $this->getTokenApiFromHeaders();

        if ($crackingCookie = $this->getAllParams($tokenApi)) {

            if ($crackingCookie->status === 'success') {
                $userApiToken = $this->fetchApiTokenByUserName($crackingCookie->user);

                if (hash_equals($userApiToken, $crackingCookie->cookieApiToken)) {
                    $response = [
                        'status' => 'logged',
                        'redirect' => 'users.html'
                    ];

                    return $this->response($response, 200);
                }
            }
        }

        $response = [
            'status' => 'not logged',
        ];

        return $this->response($response, 200);
    }

    public function logout()
    {
        $tokenApi = $this->getTokenApiFromHeaders();
        if ( $crackingCookie = $this->getAllParams($tokenApi)) {
            $pdo = $this->conn;
            $sql = "UPDATE users SET api_token = '' WHERE api_token = :api_token";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['api_token' => $crackingCookie->cookieApiToken]);

            return $this->response(['redirect' => 'login.html'], 200);
        }

        return $this->response(['msg' => 'something went wrong'], 500);
    }

    private function getTokenApiFromHeaders()
    {
        $headers = getallheaders();
        return str_replace('bearer ','', $headers['Authorization']);
    }
    public function checkIfLogged()
    {
        $tokenApi = $this->getTokenApiFromHeaders();

        if ( $crackingCookie = $this->getAllParams($tokenApi) ) {
            $pdo = $this->conn;
            $sql = "select api_token from " . $this->table_name . " where api_token = :apiToken ";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['apiToken' => $crackingCookie->cookieApiToken]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $response = [
                    'status' => 'logged',
                ];
            } else {
                $response = [
                    'status' => 'not logged',
                ];
            }

        } else {
            $response = ['status' => 'not logged'];
        }

        return $this->response($response, 200);

    }

    private function fetchApiTokenByUserName($userId)
    {
        $pdo = $this->conn;
        $sql = "select api_token from " . $this->table_name . " where id = :userId ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['userId' => $userId]);

        return $stmt->fetchColumn();
    }
}