<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'reserve.class.php';
require_once 'Doc.class.php';
require_once '/var/www/html/foodReservationProj/includes/config_session.inc.php';


class ReserveCont extends Reserve {



    public function foods($day, $type) {
        $foods = parent::getFoods($day, $type);
        return $foods;
    }

    public function submitSelectedFood($day, $foodNumber, $type) {
        
        
        $userId = $_SESSION["user_id"];

        $foods = $_SESSION["foods"][$type][$day];
        $foodid = $foods[$foodNumber]["id"];

        $startWeekDate = $_SESSION["week_date_start"];

        $startWeekTimestamp = strtotime($startWeekDate);

        $dayName = Doc::DOW[$day];

        $targetTimestamp = strtotime($dayName, $startWeekTimestamp);

        $targetDate = date('Y-m-d', $targetTimestamp);

        parent::setSelectedFood($userId, $foodid, $targetDate, $startWeekDate, $type);

        $_SESSION["is_selected"][$type][$day] = true;
       
    }

    public function reservedFood($day, $type) {
        $dayName = Doc::DOW[$day];

        $startDate = $_SESSION["week_date_start"];

        // Find the first desired day after the given date
        if($day != 6)
            $nextDesiredDay = date('Y-m-d', strtotime("next $dayName", strtotime($startDate)));
        else
            $nextDesiredDay = date('Y-m-d', strtotime("this $dayName", strtotime($startDate)));

        $foodId = parent::getReservesdFoodId($nextDesiredDay, $type);
        $foodInfo = parent::getReservedFoodInfo($foodId["food_id"], $type);
        
        return $foodInfo;  // `name`, `price`
    }

    public function cancelFood($type, $cancelDate) {
        parent::deleteReservedFood($type, $cancelDate);
    }

    public function canReserve($dayOfWeek) {
        $startWeekDate = $_SESSION["week_date_start"];
        $endWeekDate = $_SESSION["week_date_end"];
        $currentDate = date('Y-m-d');
        if($dayOfWeek != date("w") || $currentDate < $startWeekDate || $currentDate > $endWeekDate) {
            return false;
        }

        $timeline = parent::getTimeline($dayOfWeek);

        if($timeline) {
            $deadline = $timeline['deadline'];
            $startTime = $timeline['start_time'];
            date_default_timezone_set('asia/tehran');
            $currentTime = date("H:i:s");
    
            if ($currentTime < $deadline && $currentTime > $startTime) {
                return true;
            }
            return false;
        } 
        return true;
    }


    // public function change_date_format($dateInput){
    //     //change date language here
    //     $dateInput = str_replace("-", "/", $dateInput);;
    //     $date = $dateInput;
    //     $date = explode('/', $date);
    //     $farsi_date = self::g2p($date[0],$date[1],$date[2]);
    //     return $farsi_date[0].'/'.$farsi_date[1].'/'.$farsi_date[2];
    // }
    // private function g2p($g_y, $g_m, $g_d)
    // {
    //     $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    //     $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
    
    //     $gy = $g_y-1600;
    //     $gm = $g_m-1;
    //     $gd = $g_d-1;
    
    //     $g_day_no = 365*$gy+floor(($gy+3)/4)-floor(($gy+99)/100)+floor(($gy+399)/400);
    
    //     for ($i=0; $i < $gm; ++$i){
    //         $g_day_no += $g_days_in_month[$i];
    //     }
    
    //     if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0))){
    //         /* leap and after Feb */
    //         ++$g_day_no;
    //     }
    
    //     $g_day_no += $gd;
    //     $j_day_no = $g_day_no-79;
    //     $j_np = floor($j_day_no/12053);
    //     $j_day_no %= 12053;
    //     $jy = 979+33*$j_np+4*floor($j_day_no/1461);
    //     $j_day_no %= 1461;
    
    //     if ($j_day_no >= 366) {
    //         $jy += floor(($j_day_no-1)/365);
    //         $j_day_no = ($j_day_no-1)%365;
    //     }
    //     $j_all_days = $j_day_no+1;
    
    //     for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) {
    //         $j_day_no -= $j_days_in_month[$i];
    //     }
    
    //     $jm = $i+1;
    //     $jd = $j_day_no+1;
    
    //     return array($jy, $jm, $jd, $j_all_days);
    // }
}