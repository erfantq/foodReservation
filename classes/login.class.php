<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'dbh.class.php';
require_once 'Doc.class.php';

class Login extends Dbh {
    protected function getUser(string $username) {
        $query = "SELECT * FROM users WHERE username=:username;";
        try {
        $pdo = parent::connect();

        $stmt =  $pdo->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(Exception $e) {
            $errors["unexpected"] = "Unexpected error!";
            $_SESSION["errors_login"] = $errors;
        }

        $pdo = null;
        $stmt = null;

        return $result;
    }

    protected function getSelectedDates() {
        $query = "SELECT reserved_at, `type` FROM reservations WHERE user_id=:userId AND reserved_at BETWEEN :startDate AND :endDate;";

        $userId = $_SESSION["user_id"];
        $startDate = $_SESSION["week_date_start"];
        $endDate = $_SESSION["week_date_end"];

        // var_dump($startDate);
        // var_dump($endDate);
        // die();

        $pdo = parent::connect();

        $stmt =  $pdo->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":startDate", $startDate);
        $stmt->bindParam(":endDate", $endDate);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // var_dump($result);
        // die();

        $pdo = null;
        $stmt = null;

        return $result;
    }
}