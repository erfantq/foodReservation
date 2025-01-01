<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'includes/config_session.inc.php';

class LoginView {
    public function checkLoginErrors() {
        if (isset($_SESSION["errors_login"])) {
            $errors = $_SESSION["errors_login"];

            echo '<br>';

            foreach ($errors as  $error) {
                echo "<p class='text-danger text-center'>" . $error . "</p>";
            }
            unset($_SESSION["errors_login"]);
        }
    }
}