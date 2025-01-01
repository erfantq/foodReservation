<?php
declare(strict_types = 1);
ini_set("display_errors",1);
error_reporting(E_ALL);
require_once 'reserve.class.php';
require_once 'reserveCont.class.php';
require_once 'Doc.class.php';
require_once 'includes/config_session.inc.php';
require_once 'classes/loginCont.class.php';

class ReserveView extends Reserve {

  private $type;
  private $day;


  public function navDisplay() { 
    
    self::getDayAndType();
    ?>

  <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="">رزرو غذا</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse " id="collapsibleNavbar">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
              <a class="nav-link text-nowrap" href="" id="day6" onclick="getFoods(event, 6)">شنبه</a>
          </li>
          <li class="nav-item">
              <a class="nav-link text-nowrap" href="" id="day0" onclick="getFoods(event, 0)">یک شنبه</a>
          </li>
          <li class="nav-item">
              <a class="nav-link text-nowrap" href="" id="day1" onclick="getFoods(event, 1)">دوشنبه</a>
          </li>    
          <li class="nav-item">
              <a class="nav-link text-nowrap" href="" id="day2" onclick="getFoods(event, 2)">سه شنبه</a>
          </li>    
          <li class="nav-item">
              <a class="nav-link text-nowrap" href="" id="day3" onclick="getFoods(event, 3)">چهارشنبه</a>
          </li>   
        </ul>
        
        <div class="text-white d-flex align-items-center justify-content-between userInfo">
          <div class="ms-2">
            <a href="" class="btn" onclick="getType(event, 'food')" style="color: rgba(255, 255, 255, 0.55);" id="foodLink">غذا</a>
            <a href="" class="btn" onclick="getType(event, 'drink')" style="color: rgba(255, 255, 255, 0.55);" id="drinkLink">نوشیدنی</a>
          </div>
          <div class="d-flex align-items-center flex-wrap">

            <?php
              if($_SESSION["user_post"] == "admin" || $_SESSION["user_post"] == "manager") { ?>
                <a href="deadlines.php" class="btn btn-secondary me-2 mb-xl-0 mb-1">ثبت بازه زمانی رزرو</a>
                <a href="<?php echo 'edit.php?day=' . $this->day . '&type=' . $this->type ?>" class="btn btn-secondary me-2 mb-sm-0 mb-1">لیست غذاها</a>
                <a href="<?php echo 'reservedFoods.php?day=' . $this->day . '&type=' . $this->type ?>" class="btn btn-secondary me-2">غذاهای رزرو شده</a>
            <?php
              }
            ?>
            
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


  public function setFoodDisplay() {
    
    self::getDayAndType();
    
    self::getWeek();

    $reserveCont = new ReserveCont();
    $canResereve = $reserveCont->canReserve($this->day);

    if($canResereve) {
      self::setResereveDisplay();
    } else {
      // self::reserveExpDisplay();
      self::setResereveDisplay();

    }

  }

  private function setResereveDisplay() { 

    if(isset($_GET["cancel"])) {
      $reserveCont = new ReserveCont();
      $typeTemp = $_GET["type"];
      $cancelDateTemp = $_GET["cancel"];
      $dayTemp = $_GET["day"];
      $_SESSION["is_selected"][$typeTemp][$dayTemp] = false;
      $reserveCont->cancelFood($typeTemp, $cancelDateTemp);
    }

    if(!isset($_GET["selected"])  && $_SESSION["is_selected"][$this->type][$this->day] == false) {

      if(isset($_GET["week"]) || !isset($_SESSION["foods"][$this->type][$this->day]) || $_SESSION["foods"][$this->type][$this->day] == null) {
        $reserveCont = new ReserveCont();
        $foods = $reserveCont->foods($this->day, $this->type);
        $_SESSION["foods"][$this->type][$this->day] = $foods;
      }
      
      $foods = $_SESSION["foods"][$this->type][$this->day];

      self::foodsDisplay($foods);

    } else {  // if some food is selected
      if(isset($_GET["selected"])) {
        
        $day = $_GET["day"];
        $foodNumber = $_GET["selected"];
        $submitFood = new ReserveCont();
        $submitFood->submitSelectedFood($day, $foodNumber, $this->type);
        $foods = $_SESSION["foods"][$this->type][$this->day];

        self::selectedDisplay($foodNumber);

      } else {
        self::selectedDisplay();
      }
    }
  }

  private function reserveExpDisplay() { ?>
    <div class="d-flex align-items-center justify-content-center">
      <div class="bg-dark text-white rounded-pill p-5 border border-3 border-white w-50">
              <h3 class="text text-center">
                  امکان رزرو وجود ندارد.
              </h3>
      </div>
    </div>
  <?php
  }

  private function selectedDisplay($foodNumber = -1) {  
    // $reservedFood = parent::getReservedFood(); 

    if($foodNumber != -1) {
      $foods = $_SESSION["foods"][$this->type][$this->day];
      $foodName = $foods[$foodNumber]["name"];
      $foodPrice = $foods[$foodNumber]["price"];
    } else {
      $reserveCont = new ReserveCont();
      $foodInfo = $reserveCont->reservedFood($this->day, $this->type);

      $foodName = $foodInfo["name"];
      $foodPrice = $foodInfo["price"];
      
    }

    if($this->type == 'food') {
      $foodType = "غذای";
    } else {
      $foodType = "نوشیدنی"; 
    }
    // var_dump($foodName);
    // var_dump($foodPrice);
    ?>

    <div class="d-flex flex-column align-items-center justify-content-center">
      <h3 class="mb-3"><?php echo 'شما '. $foodType .' این روز خود را رزرو کرده اید' ?></h3>

      <div class="card d-flex flex-column align-items-center justify-content-between p-2 rounded bg-dark text-white selectedFoodInfo">
        
        <div class="d-flex align-items-center justify-content-between w-100">
          <p><?php echo $foodType . ' ' . 'انتخاب شده' . ': ' . '<span class="" style="color: rgb(220, 53, 69);">' . $foodName . '</span>' ?></p>
          <p><?php echo 'قیمت: ' . $foodPrice . ' ' . '﷼' ?></p>
        </div>
        <div class="d-flex align-items-center justify-content-center">
          <button class="btn rounded btn-primary ms-1" style="width: 7rem;" onclick="cancelFood(event)">لغو غذا</button>
        </div>
      
      </div>
    </div>

  <?php  
  }

  private function foodsDisplay($foods) { ?>
    <div class="row justify-content-evenly elementsTop">
      <?php 
          for ($i = 0; $i < sizeof($foods); $i++) { ?>
            <div class="col-sm-5 col-lg-3 ms-1 shadow rounded px-0 py-0 mb-3 card" id="<?php echo 'food' . $i ?>">
              <img src="<?php echo $foods[$i]["img_url"] ?>" alt="food-img" class="w-100">
              
              <div class="d-flex justify-content-between mt-3 mx-2">
                <p> <?php echo $foods[$i]["name"] ?>  </p>
                <p><?php echo $foods[$i]["price"] . '﷼' ?></p>
              </div>
              <button type="submit" class="btn btn-dark w-100 mb-0" onclick="getChoosedFood(this.id)" id="<?php echo 'choose' . $i ?>">انتخاب</button>
            </div>
      <?php
        }
      ?>
    </div>
 <?php
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


}