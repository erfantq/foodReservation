<?php
/*
---- extend this class of which class you want to connect to DB and use connect() method.
*/

class Dbh {
    private static $host = "localhost"; 
    private static $user = "root";
    private static $pwd = "Erfan1234";
    private static $dbName = "foodReserve";

    public function connect() {
        $dsn = "mysql:host=" . Dbh::$host . ";dbname=" . Dbh::$dbName;
        $pdo = new PDO($dsn, Dbh::$user, Dbh::$pwd);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $pdo;
    }

}