<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'dbh.class.php';

class Reserve extends Dbh {

    protected function getFoods($day, $type) {
        // parent::setCacheConfig();
        // $cacheInstance = parent::$cache->getItem('reserve?day=' . $day . '&type=' . $type);
        // if(is_null($cacheInstance->get())) {
            $query = "SELECT * FROM foods WHERE `days` LIKE :day AND supply > 0 AND `type`=:type;";
    
            $pdo = parent::connect();
    
            $day = "%" . $day . "%";
            
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":day", $day);
            $stmt->bindParam(":type", $type);
            $stmt->execute();
    
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // $cacheInstance->set($result)->expiresAfter(300);
            // parent::$cache->save($cacheInstance);
            
            $pdo = null;
            $stmt = null;

        // } else {
        //     $result = $cacheInstance->get();
        // }
        
        return $result;
    }

    protected function setSelectedFood($userId, $foodId, $reservedAt, $startWeekDate, $type) {
      
        $query = "INSERT INTO reservations (user_id, food_id, reserved_at, start_week, `type`) VALUES (:userId, :foodId, :reservedAt, :startWeek, :type);";
    

        $pdo = parent::connect();

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":foodId", $foodId);
        $stmt->bindParam(":reservedAt", $reservedAt);
        $stmt->bindParam(":startWeek", $startWeekDate);
        $stmt->bindParam(":type", $type);
        $stmt->execute();

        $pdo = null;
        $stmt = null;
    }

    
    protected function getReservesdFoodId($date, $type) {
        $query = "SELECT food_id FROM reservations WHERE reserved_at=:date AND user_id=:userId AND `type`=:type;";

        $userId = $_SESSION["user_id"];

        $pdo = parent::connect();

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":date", $date);
        $stmt->bindParam(":userId", $userId);
        $stmt->bindParam(":type", $type);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;

        return $result;

    }

    protected function getReservedFoodInfo($id, $type) {
        $query = "SELECT `name`, `price` FROM foods WHERE id=:id AND `type`=:type;";

        $pdo = parent::connect();

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":type", $type);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;

        return $result;
    }

    protected function deleteReservedFood($type, $cancelDate) {
        $query = "DELETE FROM reservations WHERE `type`=:type AND reserved_at=:cancelDate;";

        $pdo = parent::connect();

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":cancelDate", $cancelDate);
        $stmt->execute();

        $pdo = null;
        $stmt = null;
    }

    protected function getTimeline($dayOfWeek) {
        $query = "SELECT deadline, start_time FROM reservation_deadlines WHERE day_of_week=:dayOfWeek;";

        $pdo = parent::connect();

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":dayOfWeek", $dayOfWeek);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $pdo = null;
        $stmt = null;

        return $result;
    }
}
