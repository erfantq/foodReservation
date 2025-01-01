<?php
declare(strict_types = 1);

ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'deadline.class.php';
// require_once 'editView.class.php';
require_once '/var/www/html/foodReservationProj/includes/config_session.inc.php';

class DeadlineCont extends Deadline {
    private $dayOfWeek;
    private $startTime;
    private $deadline;

    public function __construct($dayOfWeek, $startTime, $deadline) {
        $this->dayOfWeek = $dayOfWeek;
        $this->startTime = $startTime;
        $this->deadline= $deadline;
    }

    public function setDeadLine() {
        try {
            parent::insertDeadline($this->dayOfWeek, $this->startTime,$this->deadline);
            self::goToDeadlines('success');
        } catch (Exception $e) {
            self::goToDeadlines('failed');
        }
    }

    private function goToDeadlines($status) {
        header("Location: ../deadlines.php?setStatus=" . $status);
        die();
    }
}