<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'dbh.class.php';

class Deadline extends Dbh {

    protected function insertDeadline($dayOfWeek, $startTime, $deadline) {
        $query = "INSERT INTO reservation_deadlines (day_of_week, start_time, deadline) VALUES (:dayOfWeek, :startTime,:deadline)
                    ON DUPLICATE KEY UPDATE deadline=:deadline, start_time=:startTime;";

        try {
            $pdo = parent::connect();

            $stmt = $pdo->prepare($query);
            $stmt->bindParam(":dayOfWeek", $dayOfWeek);
            $stmt->bindParam(":startTime", $startTime);
            $stmt->bindParam(":deadline", $deadline);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error Processing Request", 1);
        }

        $pdo = null;
        $stmt = null;
    }
}