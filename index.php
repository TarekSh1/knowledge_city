<?php
ini_set('display_errors', 'On');

if (isset($_SESSION['username'])) {
    header('Location: users.html');
    exit();
}

include 'login.html';
