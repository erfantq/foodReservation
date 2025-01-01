<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once '../classes/loginCont.class.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars($_POST["username"]);

    $loginCont = new LoginCont($username);

    $loginCont->loginUser();

}