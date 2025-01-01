<?php
require_once 'includes/config_session.inc.php';
require_once 'classes/deadlineView.class.php';

if (!isset($_SESSION["user_id"])) {
  header("Location: index.php");
}
?>

<!doctype html>
<html lang="fa">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">
  </head>
  <body dir="rtl" class="d-flex flex-column min-vh-100">
    <header>
      <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
          <div class="container-fluid">
            <a class="navbar-brand" href="">تعیین ددلاین ها</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="collapsibleNavbar">
              <div class="text-white d-flex align-items-center justify-content-between me-auto"> 
                <div class="d-flex align-items-center flex-wrap">
                  <a href="edit.php" class="btn btn-secondary me-2 mb-sm-0 mb-1 mt-sm-0 mt-1">لیست غذاها</a>
                  <a href="reservedFoods.php" class="btn btn-secondary me-2 mb-sm-0 mb-1 mt-sm-0 mt-1">غذاهای رزرو شده</a>
                  <a href="reserve.php" class="btn btn-secondary me-2 mb-sm-0 mb-1 mt-sm-0 mt-1">صفحه اصلی</a>
                  <?php echo '<span class="me-2">' . $_SESSION["user_username"] . '</span>' ?>
                  <form action="includes/logout.inc.php" method="post" class="d-flex align-center me-2">
                      <button type="submit" class="btn btn-primary">خروج</button>
                  </form>
                    
                </div>

              </div>

            </div>
            
          </div>
      </nav>
    </header>

    <main class="container-fluid flex-grow-1 d-flex flex-column justify-content-center align-items-center">
      <div class="d-flex align-items-center justify-content-center p-3 text-bg-dark shadow rounded" style="height: 30rem; width: 25rem;">
        <form action="includes/deadline.inc.php" method="post" class="w-100">
          <div class="form-group mb-2">
            <label class="" for="day_of_week">روز هفته:</label>
            <select class="form-select" aria-label="Default select example" name="day_of_week" id="day_of_week">
                <option value="6">شنبه</option>
                <option value="0">یکشنبه</option>
                <option value="1">دوشنبه</option>
                <option value="2">سه شنبه</option>
                <option value="3">چهارشنبه</option>
            </select>
          </div>
          <div class="form-group mb-4">
            <label for="start_time">زمان شروع:</label>
            <input type="time" class="form-control" name="start_time" id="start_time" required>
          </div>
          <div class="form-group mb-4">
            <label for="deadline">زمان پایان:</label>
            <input type="time" class="form-control" name="deadline" id="deadline" required>
          </div>
          <div class="text-center">
            <button class="btn btn-secondary" type="submit">ثبت ددلاین روز انتخاب شده</button>
          </div>
        </form>
      </div>

      <?php

        $deadlineView = new DeadlineView();
        $deadlineView->statusView();
        
      ?>
    </main>

    <script>
        setTimeout(function() {
        var alert = bootstrap.Alert.getOrCreateInstance(document.getElementById('myAlert'));
        alert.close();
        }, 3000); 
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  </body>

