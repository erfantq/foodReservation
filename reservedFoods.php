<?php
// require 'includes/config_session.inc.php';
require_once 'classes/reservedFoodsView.class.php';
require_once 'includes/config_session.inc.php';

if (!isset($_SESSION["user_id"])) {
  header("Location: index.php");
}

?>

<!DOCTYPE html>
<html lang="fa">
<head>
  <title>Reservation</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  

  <link rel="stylesheet" href="reserve.css">
  
</head>
<body dir="rtl" class="d-flex flex-column min-vh-100">

    <header>

        <?php
        $reservedFoodsView = new ReservedFoodsView();
        $reservedFoodsView->navDisplay();
        ?>

        <div class="align-items-center justify-content-center vh-100" style="display: none !important;" id="mySpinner">
            <div class="hungry-1"></div>
        </div>

    </header>

    <main class="container-fluid d-flex flex-column align-items-center justify-content-center min-vh-100">
        
        <?php
        $reservedFoodsView = new ReservedFoodsView();
        $reservedFoodsView->setReservedFoodDisplay();
        ?>
        
    </main>
    <?php
    $startWeekDate = $_SESSION["week_date_start"];
    $endWeekDate = $_SESSION["week_date_end"];
    ?>
    <script src="reservedFoods.js"></script>
    <script>getWeek('<?php echo $startWeekDate ?>', '<?php echo $endWeekDate ?>')</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>