<?php 
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'dbh.class.php';

class Edit extends Dbh {
    protected function getFoods($day, $type) {  // gets foods even if not in supply
        $query = "SELECT * FROM foods WHERE `days` LIKE :day AND `type`=:type ORDER BY supply DESC;";

        $pdo = parent::connect();

        $day = "%" . $day . "%";
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":day", $day);
        $stmt->bindParam(":type", $type);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $pdo = null;
        $stmt = null;
        
        return $result;
    }

    protected function setChanges($foodName, $foodPrice, $days, $supply, $targetFile, $foodId) {
        if($targetFile != "noChange") {
            $query = "UPDATE foods SET `name`=:foodName, `price`=:foodPrice, `days`=:days, `supply`=:supply, img_url=:targetFile WHERE id=:foodId;";
        } else {
            $query = "UPDATE foods SET `name`=:foodName, `price`=:foodPrice, `days`=:days, `supply`=:supply WHERE id=:foodId;";
        }
        $pdo = parent::connect();

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":foodName", $foodName);
        $stmt->bindParam(":foodPrice", $foodPrice);
        $stmt->bindParam(":days", $days);
        $stmt->bindParam(":supply", $supply);
        if($targetFile !== "noChange")
            $stmt->bindParam(":targetFile", $targetFile);
        $stmt->bindParam(":foodId", $foodId);

        $stmt->execute();

        $pdo = null;
        $stmt = null;
    }

    protected function setFood($foodName, $foodPrice, $days, $supply, $targetFile, $type) {
        if($targetFile != "noChange") {
            $query = "INSERT INTO foods (`name`, `price`, `days`, `supply`, img_url, `type`) VALUES (:foodName, :foodPrice, :days, :supply, :targetFile, :type);";
        } else {
            $query = "INSERT INTO foods (`name`, `price`, `days`, `supply`, `type`) VALUES (:foodName, :foodPrice, :days, :supply, :type);";
        }
        $pdo = parent::connect();
        
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":foodName", $foodName);
        $stmt->bindParam(":foodPrice", $foodPrice);
        $stmt->bindParam(":days", $days);
        $stmt->bindParam(":supply", $supply);
        if($targetFile !== "noChange")
            $stmt->bindParam(":targetFile", $targetFile);
        $stmt->bindParam(":type", $type);

        $stmt->execute();

        $pdo = null;
        $stmt = null;
    }

    protected function setDeletedFood($foodId) {
        $query = "DELETE FROM foods WHERE id=:foodId;";

        $pdo = parent::connect();

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(":foodId", $foodId);
        $stmt->execute();

        $pdo = null;
        $stmt = null;
    }
    
}