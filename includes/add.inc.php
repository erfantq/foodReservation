<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'config_session.inc.php';
require_once '../classes/editCont.class.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $foodName = htmlspecialchars($_POST["foodName"]);
    $foodPrice = htmlspecialchars($_POST["foodPrice"]);

    $satCheck = $_POST["satCheck"];
    $sunCheck = $_POST["sunCheck"];
    $monCheck = $_POST["monCheck"];
    $tueCheck = $_POST["tueCheck"];
    $wedCheck = $_POST["wedCheck"];

    $days = $satCheck . ', ' . $sunCheck . ', ' . $monCheck . ', ' . $tueCheck . ', ' . $wedCheck;

    $supply = $_POST["supply"];

    $targetDirectory = "../../foodReservationProj/images/";


    // Get the file name
    $targetFile = $targetDirectory . basename($_FILES["fileToUpload"]["name"]);

    $editCont = new EditCont();
    $editCont->addFood($foodName, $foodPrice, $days, $supply, $targetFile);

}