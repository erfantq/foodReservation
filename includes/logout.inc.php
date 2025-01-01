<?php
ini_set("display_errors",1);
error_reporting(E_ALL);
require_once 'config_session.inc.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_unset();
    session_destroy();
    header("Location: ../index.php");
}