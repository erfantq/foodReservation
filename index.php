<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'classes/loginView.class.php';
require_once 'includes/config_session.inc.php';

if (isset($_SESSION["user_id"])) {
  $day = date("w");
  header("Location: reserve.php?day=" . $day);
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
  <body dir="rtl">
    <div class="container d-flex flex-column align-items-center justify-content-center min-vh-100">
      <div class="shadow rounded myForm">
        <div class="text-bg-dark rounded-top p-3"> 
          <h3 class="p-4">پرتال ورود به سامانه رزرو غذا</h3>
        </div>
        <div class="formWrapper">
          <form action="includes/login.inc.php" method="post" class="p-3">
            <div class="form-group">
              <label for="username">نام کاربری</label>
              <input type="text" class="form-control focus-ring mt-1" name="username" id="username" placeholder="نام کاربری" style="--bs-focus-ring-color: rgb(33, 33, 33,0.7);">
            </div>
            <div class="form-group text-center mt-2">
              <input type="submit" value="ورود" class="btn btn-dark">
            </div>
          </form>
        </div>
        <?php
          $loginViewErr = new LoginView();
          $loginViewErr->checkLoginErrors();
        ?>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>