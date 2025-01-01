<?php
declare(strict_types = 1);
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'reservedFoods.class.php';
require_once 'reservedFoodsCont.class.php';
require_once 'loginCont.class.php';

class ReservedFoodsView extends ReservedFoods {

    private $day;
    private $type;


    public function navDisplay() { 
    
        self::getDayAndType();
        ?>
    
      <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="">غذاهای رزرو شده</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse " id="collapsibleNavbar">
            <ul class="navbar-nav ms-auto">
              <li class="nav-item">
                  <a class="nav-link text-nowrap" href="" id="day6" onclick="getReservedFoods(event, 6)">شنبه</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link text-nowrap" href="" id="day0" onclick="getReservedFoods(event, 0)">یک شنبه</a>
              </li>
              <li class="nav-item">
                  <a class="nav-link text-nowrap" href="" id="day1" onclick="getReservedFoods(event, 1)">دوشنبه</a>
              </li>    
              <li class="nav-item">
                  <a class="nav-link text-nowrap" href="" id="day2" onclick="getReservedFoods(event, 2)">سه شنبه</a>
              </li>    
              <li class="nav-item">
                  <a class="nav-link text-nowrap" href="" id="day3" onclick="getReservedFoods(event, 3)">چهارشنبه</a>
              </li>   
            </ul>
            
            <div class="text-white d-flex align-items-center justify-content-between userInfo">
              <div class="ms-2">
                <a href="" class="btn" onclick="getType(event, 'food')" style="color: rgba(255, 255, 255, 0.55);" id="foodLink">غذا</a>
                <a href="" class="btn" onclick="getType(event, 'drink')" style="color: rgba(255, 255, 255, 0.55);" id="drinkLink">نوشیدنی</a>
              </div>
              <div class="d-flex align-items-center flex-wrap">
                <a href="deadlines.php" class="btn btn-secondary me-2 mb-xl-0 mb-1">ثبت بازه زمانی رزرو</a>
                <a href="<?php echo 'edit.php?day=' . $this->day . '&type=' . $this->type ?>" class="btn btn-secondary me-2 mb-sm-0 mb-1">لیست غذاها</a>
                <a href="<?php echo 'reserve.php?day=' . $this->day . '&type=' . $this->type ?>" class="btn btn-secondary me-2">صفحه اصلی</a>
                <?php echo '<span class="me-2">' . $_SESSION["user_username"] . '</span>' ?>
                <form action="includes/logout.inc.php" method="post" class="d-flex align-center me-2">
                    <button type="submit" class="btn btn-primary">خروج</button>
                </form>
              </div>
            </div> 
          </div>
        </div>
      </nav>
    
      <div class="d-flex align-items-center justify-content-center ">  
        <div class="d-flex-inline align-items-center justify-content-center rounded-bottom bg-dark text-white">
            <button class="btn btn-dark rounded-start-0 rounded-top-0 ms-4" onclick="weekChanger(event, -1)">هفته قبل</button>
            <span id="dateDisplay"></span>
            <button class="btn btn-dark rounded-end-0 rounded-top-0 me-4" onclick="weekChanger(event, 1)">هفته بعد</button>
        </div>
      </div>
    <?php
    }

    public function setReservedFoodDisplay() {
      self::getDayAndType();
      self::getWeek();

      $reservedFoodsCont = new ReservedFoodsCont();

      $reservedFoods = $reservedFoodsCont->selectedDayReservedFoods($this->day, $this->type);

      if($reservedFoods) {
        self::displayReservedFoods($reservedFoods);
      } else {
        self::displayNon();
      }
    }

    private function displayReservedFoods($reservedFoods) { ?>


        <h3 class="mb-3"><?php if($this->type == 'food') echo 'غذاهای رزرو شده'; else echo 'نوشیدنی های رزرو شده' ?></h3>
        <table class="table table-dark text-center">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col"><?php if($this->type == 'food') echo 'نام غذا'; else echo 'نام نوشیدنی' ?></th>
                <th scope="col">نام کارمند</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $count = 0;
                    foreach ($reservedFoods as $reservedFood) { 
                        $count++;
                        echo '
                        <tr id="table-row' . $count . '" style="vertical-align: middle;">
                            <th scope="row">' . $count . '</th>
                            <td id="table-name' . $count . '">' . $reservedFood["foodName"] . '</td>
                            <td id="table-phone' . $count . '">' . $reservedFood["userName"] . '</td>
                        </tr>';
                    
                    }
                ?>
            </tbody>
        </table>
    <?php
    }

    private function displayNon() { ?>
      <div class="bg-dark text-white rounded-pill p-5 border border-3 border-white">
            <h3 class="text text-center">
                در این روز غذایی رزرو نشده است.
            </h3>
      </div>
    <?php
    }

    private function getWeek() {
      if(isset($_GET["week"])) {
        $weekDateStart = $_SESSION["week_date_start"];  
        $weekDateEnd = $_SESSION["week_date_end"];
  
        $newWeekDateStart = $_SESSION["week_date_start"];
        $newWeekDateEnd = $_SESSION["week_date_end"];
        if($_GET["week"] == 1) {
          $newWeekDateStart = date('Y-m-d', strtotime('+7 days', strtotime($weekDateStart)));  
          $newWeekDateEnd = date('Y-m-d', strtotime('+7 days', strtotime($weekDateEnd))); 
        } else if($_GET["week"] == -1) {
          $newWeekDateStart = date('Y-m-d', strtotime('-7 days', strtotime($weekDateStart)));  
          $newWeekDateEnd = date('Y-m-d', strtotime('-7 days', strtotime($weekDateEnd))); 
        }
        $_SESSION["week_date_start"] = $newWeekDateStart;
        $_SESSION["week_date_end"] = $newWeekDateEnd;
  
        $loginCont = new LoginCont($_SESSION["user_username"]);
        $loginCont->fillSelectedDays();
  
      }
    }

    private function getDayAndType() {
        if (isset($_GET["day"])) {
          if($_GET["day"] == 4 || $_GET["day"] == 5) {
            $this->day = 6;
            header("Location: reserve.php?day=6");
            die();
          } else {
            $this->day = $_GET["day"];
          }
        } else {
          $this->day = date("w");
        }
        if (isset($_GET["type"]) && ($_GET["type"] == "food" || $_GET["type"] == "drink")) {
          $this->type = $_GET["type"];
        } else {
          $this->type = 'food';
        }
    }
}