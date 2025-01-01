<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'login.class.php';
// require_once '../includes/config_session.inc.php';
require_once '/var/www/html/foodReservationProj/includes/config_session.inc.php';
require_once 'Doc.class.php';

class LoginCont extends Login {

    private $username;
    
    
    public function __construct(string $username) {
        $this->username = $username;
    }

    public function loginUser() {
        $user = parent::getUser($this->username);
        if (isset($_SESSION["errors_login"])) {
            self::goToLogin();
        }

        $errors = [];
        if (self::is_input_empty()) {
            $errors["input_empty"] = "نام کاربری خود را وارد کنید.";
        } else if (self::is_username_invalid()) {
            $errors["username_invalid"] = "نام کاربری وارد شده وجود ندارد.";
        }

        if ($errors) {
            $_SESSION["errors_login"] = $errors;
            self::goToLogin();
        }
        
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_username"] = $user["username"];
        $_SESSION["user_post"] = $user["post"];

        // get the start day of week(sat) and the end of week (wed) for reserving 
        self::getWeek();

        self::fillSelectedDays();

        self::goToReservePage();
        
    }

    private function goToLogin() {
        header("Location: ../index.php");
        die();
    }

    private function is_input_empty() {
        if (empty($this->username)) {
            return true;
        }
        return false;
    }

    private function is_username_invalid() {
        if (parent::getUser($this->username)) {
            return false;
        }
        return true;
    }

    private function getWeek() {

        if(date('w') == 6) {
            $saturdayThisWeek = date('Y-m-d', strtotime('this Saturday'));
            $wednesdayThisWeek = date('Y-m-d', strtotime('next Wednesday'));
        } else {
            if(date('w') == 4 || date('w') == 5) {
                $saturdayThisWeek = date('Y-m-d', strtotime('this Saturday')); 
                $wednesdayThisWeek = date('Y-m-d', strtotime('next Wednesday'));
            }
            else {
                $saturdayThisWeek = date('Y-m-d', strtotime('last Saturday'));
                $wednesdayThisWeek = date('Y-m-d', strtotime('this Wednesday'));
            }
        }

        $_SESSION["week_date_start"] = $saturdayThisWeek;
        $_SESSION["week_date_end"] = $wednesdayThisWeek;

    }

    public function fillSelectedDays() {
        $_SESSION["is_selected"] = [
            "food" => [false, false, false, false, false, false, false],
            "drink" => [false, false, false, false, false, false, false]
        ];
        $selectedDates = parent::getSelectedDates();
        

        foreach ($selectedDates as $date) {
            $dateTime = new DateTime($date["reserved_at"]);
            $dayOfWeek = $dateTime->format('N');
            if($dayOfWeek == 7) $dayOfWeek = 0;

            $type = $date["type"];

            $_SESSION["is_selected"][$type][$dayOfWeek] = true;
        }
    }

    public function goToReservePage() {
        $dayOfWeekNumber = date("w");
        if($dayOfWeekNumber == '4' || $dayOfWeekNumber == '5') {
            $dayOfWeekNumber = '6';
        }
        header("Location: ../reserve.php?day=" . $dayOfWeekNumber);
        die();
    }
}
