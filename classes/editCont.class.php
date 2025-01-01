<?php
declare(strict_types = 1);

ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'edit.class.php';
// require_once 'editView.class.php';
require_once '/var/www/html/foodReservationProj/includes/config_session.inc.php';

class EditCont extends Edit {
    private $selectedDayDate;

    public function foods($day, $type) {
        $foods = parent::getFoods($day, $type);
        return $foods;
    }

    public function submitChanages($foodName, $foodPrice, $days, $supply, $targetFile) {
        $day = $_SESSION["day"];
        $type = $_SESSION["type"];

        $error = "";
        switch (true) {
            case self::isInputEmpty($foodName, $foodPrice):
                $error = "هر دو ورودی نام و قیمت باید پر شوند.";
                break;
            case self::isNameLong($foodName):
                $error = "نام غذا حداکثر میتواند ۶۰ کاراکتر باشد";
                break;
        }
        $error = self::uploadImage($targetFile);

        if($error && $error != "noChange") {
            // $editView = new EditView();
            // $editView->displayFoodList($day, $type);
            // return;
            header("Location: ../edit.php?day=" . $day . "&type=" . $type);
            die();
        } 

        $foodId = $_SESSION["selected_food_id"];
        if($error != "noChange")
            parent::setChanges($foodName, $foodPrice, $days, $supply, $targetFile, $foodId);
        else 
            parent::setChanges($foodName, $foodPrice, $days, $supply, "noChange", $foodId);

        // $editView = new EditView();
        // $editView->displayFoodList($day, $type);
        header("Location: ../edit.php?day=" . $day . "&type=" . $type . "&edit=success");
        die();
    }

    public function addFood($foodName, $foodPrice, $days, $supply, $targetFile) {
        $day = $day = $_SESSION["day"];
        $type = $_SESSION["type"];
        
        $error = "";
        switch (true) {
            case self::isInputEmpty($foodName, $foodPrice):
                $error = "هر دو ورودی نام و قیمت باید پر شوند.";
                break;
            case self::isDaysEmpty($days):
                $error = "حداقل یکی از روز های هفته باید انتخاب شود.";
                break;
            case self::isNameLong($foodName):
                $error = "نام غذا حداکثر میتواند ۶۰ کاراکتر باشد";
                break;
        }
        $error = self::uploadImage($targetFile);

        if($error && $error != "noChange") {
            header("Location: ../edit.php" . $day . "&type=" . $type);
            die();
        } 

        $type = $_SESSION["food_type"];
        if($error != "noChange")
            parent::setFood($foodName, $foodPrice, $days, $supply, $targetFile, $type);
        else 
            parent::setFood($foodName, $foodPrice, $days, $supply, "noChange", $type);

        header("Location: ../edit.php" . $day . "&type=" . $type);
        die();
    }

    public function deleteFood($foodId) {
        parent::setDeletedFood($foodId);
    }

    private function uploadImage($targetFile) {
        if($targetFile == "../../foodReservationProj/images/") 
            return "noChange";

        // Check if the file is a valid file type 
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedFileTypes = ['jpg', 'png', 'jpeg', 'webq']; // Add the file extensions you allow
        if (!in_array($fileType, $allowedFileTypes)) {
            return "Sorry, only JPG, PNG, JPEG, and WEBQ files are allowed.";
        } else if ($_FILES["fileToUpload"]["size"] > 5000000) { // Check the file size (5MB limit)
            return "Sorry, your file is too large.";
        }

        // If everything is ok, try to upload the file

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            return "";
        } else {
            return "Sorry, there was an error uploading your file.";
        }
        
    }

    private function isInputEmpty($foodName, $foodPrice) {
        if(empty($foodName) || empty($foodPrice)) {
            return true;
        }
        return false;
    }

    private function isDaysEmpty($days) {
        if(empty($days)) {
            return true;
        }
        return false;
    }

    private function isNameLong($foodName) {
        if(strlen($foodName) > 60) {
            return true;
        }
        return false;
    }
}