<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once '../classes/deadlineCont.class.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $dayOfWeek = $_POST['day_of_week'];
    $startTime = $_POST["start_time"];
    $deadline = $_POST['deadline'];

    $deadlineCont = new DeadlineCont($dayOfWeek, $startTime, $deadline);
    $deadlineCont->setDeadLine();
}