<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'reservedFoods.class.php';
require_once 'Doc.class.php';
require_once '/var/www/html/foodReservationProj/includes/config_session.inc.php';

class ReservedFoodsCont extends ReservedFoods {
    
    public function selectedDayReservedFoods($day, $type) {

        $startWeekDate = $_SESSION["week_date_start"];

        $startWeekTimestamp = strtotime($startWeekDate);

        $dayName = Doc::DOW[$day];

        $targetTimestamp = strtotime($dayName, $startWeekTimestamp);
        
        $targetDate = date('Y-m-d', $targetTimestamp);

        $result = parent::getReservedFoods($targetDate, $type);

        return $result;
    }
 }