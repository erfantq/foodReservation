<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'dbh.class.php';

class ReservedFoods extends Dbh {

    protected function getReservedFoods($targetDate, $type) {

        $query = "SELECT
                      foods.name AS foodName,
                      users.username AS userName
                    FROM foods
                    INNER JOIN reservations
                      ON foods.id=reservations.food_id 
                    INNER JOIN users
                      ON reservations.user_id=users.id
                      WHERE reservations.reserved_at=:targetDate AND reservations.type=:type
                    ORDER BY users.username;";
        
        $pdo = parent::connect();

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":targetDate", $targetDate);
        $stmt->bindParam(":type", $type);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;

    }
}