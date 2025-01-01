<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'edit.class.php';
require_once 'editCont.class.php';
// require_once 'includes/config_session.inc.php';
require_once '/var/www/html/foodReservationProj/includes/config_session.inc.php';


class EditView extends Edit {

    private $day;
    private $type;


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
                <a href="deadlines.php" class="btn btn-secondary me-2 mb-xl-0 mb-1">ثبت بازه زمانی رزرو</a>
                <a href="<?php echo 'reservedFoods.php?day=' . $this->day . '&type=' . $this->type ?>" class="btn btn-secondary me-2 mb-sm-0 mb-1">غذاهای رزرو شده</a>
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

      
    <?php
    }

    public function displayFoodList($day = -1, $type = -1) {
    
      self::getDayAndType($day, $type);

      self::getWeek();

      if(!isset($_GET["selected"]) && !isset($_GET["add"]) && !isset($_GET["deleted"])) {
        
        if(isset($_GET["edit"]) || (!isset($_SESSION["foods"][$this->type][$this->day]) || $_SESSION["foods"][$this->type][$this->day] == null)) {

          $editCont = new EditCont();
          $foods = $editCont->foods($this->day, $this->type);
          
          $_SESSION["foods"][$this->type][$this->day] = $foods;
        }
        $foods = $_SESSION["foods"][$this->type][$this->day];

        self::foodsDisplay($foods);
      } else if(isset($_GET["selected"])) {

        $day = $_GET["day"];
        $foodNumber = $_GET["selected"];

        $foods = $_SESSION["foods"][$this->type][$this->day];
        
        $_SESSION["selected_food_id"] = $foods[$foodNumber]["id"];

        $_SESSION["food_type"] = $this->type;
        
        $_SESSION["day"] = $this->day;
        $_SESSION["type"] = $this->type;


        self::selectedDisplay($foodNumber);

      } else if(isset($_GET["add"])) {

        $_SESSION["food_type"] = $this->type;
        $_SESSION["day"] = $this->day;
        $_SESSION["type"] = $this->type;
        self::addDisplay();

      } else if(isset($_GET["deleted"])) {

        $foodNumber = $_GET["deleted"];
        $foods = $_SESSION["foods"][$this->type][$this->day];
        $foodId = $foods[$foodNumber]["id"];

        $editCont = new EditCont();
        $editCont->deleteFood($foodId);

        array_splice($foods, $foodNumber, 1);

        $_SESSION["foods"][$this->type][$this->day] = $foods;

        self::foodsDisplay($foods);
      }
    }

    private function addDisplay() {
      if($this->type == 'food') {
        $foodType = "غذا";
      } else {
        $foodType = "نوشیدنی"; 
      }
      ?>

      <div class="d-flex align-items-center justify-content-center">
        <div class="card d-flex flex-column align-items-center justify-content-center p-5 rounded bg-dark text-white">
        
          <form action="includes/add.inc.php" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3">
              <label class="" for="foodName"><?php echo 'نام ' . $foodType ?></label>
              <input type="text" placeholder="نام" class="form-control rounded" name="foodName" id="foodName" maxlength="60" required>
            </div>
            <div class="form-group mb-3">
              <label class="" for="foodName">قیمت (﷼)</label>
              <input type="number" placeholder="قیمت" class="form-control rounded" name="foodPrice" id="foodPrice" required>
            </div>
            <div class="form-group mb-3">
              <label class="" for="">روزهای هفته</label>
              
                <div class="">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="form-check">
                      <label class="form-check-label" for="satCheck">
                        شنبه
                      </label>
                      <input class="form-check-input" type="checkbox" value="6" name="satCheck" id="satCheck">
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="sunCheck">
                        یک شنبه
                      </label>
                      <input class="form-check-input" type="checkbox" value="0" name="sunCheck" id="sunCheck">
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="monCheck">
                        دوشنبه
                      </label>
                      <input class="form-check-input" type="checkbox" value="1" name="monCheck" id="monCheck">
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-evenly">
                    <div class="form-check">
                      <label class="form-check-label" for="tueCheck">
                        سه شنبه
                      </label>
                      <input class="form-check-input" type="checkbox" value="2" name="tueCheck" id="tueCheck">
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="wedCheck">
                        چهارشنبه
                      </label>
                      <input class="form-check-input" type="checkbox" value="3" name="wedCheck" id="wedCheck">
                    </div>
                  </div>
                </div>
              
            </div>
            <div class="form-group mb-3 ">
              <label class="" for="">موجودی</label>
              <div class="d-flex align-items-center justify-content-evenly">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="supply" id="exist1" value="1" checked>
                  <label class="form-check-label" for="exist1">
                    موجود
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="supply" id="notExist1" value="0">
                  <label class="form-check-label" for="notExist1">
                    ناموجود
                  </label>
                </div>
              </div>

            </div>
            
            <div class="form-group mb-3">
              <label for="fileToUpload">آپلود عکس</label>
              <div class="d-flex align-items-center justify-content-center text-center">

                <input type="file" id="fileToUpload" name="fileToUpload">
              </div>
            </div>
            <button type="submit" class="btn rounded btn-primary ms-1" style="width: 7rem;">ثبت</button>
            
          </form>
          <button class="btn btn-danger w-25 mt-2 rounded" onclick="getFoods(event)">لغو</button>
        
        </div>
      </div>
    
    <?php
    }

    private function selectedDisplay($foodNumber) {

      $foods = $_SESSION["foods"][$this->type][$this->day];
      $foodName = $foods[$foodNumber]["name"];
      $foodPrice = $foods[$foodNumber]["price"];
      $foodSupply = $foods[$foodNumber]["supply"];
      $foodDays = $foods[$foodNumber]["days"];

      if($this->type == 'food') {
        $foodType = "غذا";
      } else {
        $foodType = "نوشیدنی"; 
      }
      ?>

      <div class="d-flex align-items-center justify-content-center">
        <div class="card d-flex flex-column align-items-center justify-content-center p-5 rounded bg-dark text-white">
        
          <form action="includes/edit.inc.php" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3">
              <label class="" for="foodName"><?php echo 'نام ' . $foodType ?></label>
              <input type="text" value="<?php echo $foodName ?>" class="form-control rounded" name="foodName" id="foodName" maxlength="60" required>
            </div>
            <div class="form-group mb-3">
              <label class="" for="foodName">قیمت (﷼)</label>
              <input type="number" value="<?php echo $foodPrice ?>" class="form-control rounded" name="foodPrice" id="foodName" required>
            </div>
            <div class="form-group mb-3">
              <label class="" for="">روزهای هفته</label>
              
                <div class="">
                  <div class="d-flex align-items-center justify-content-between">
                    <div class="form-check">
                      <label class="form-check-label" for="satCheck">
                        شنبه
                      </label>
                      <input <?php if(strpos($foodDays, '6') !== false) echo 'checked' ?> class="form-check-input" type="checkbox" value="6" name="satCheck" id="satCheck">
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="sunCheck">
                        یک شنبه
                      </label>
                      <input <?php if(strpos($foodDays, '0') !== false) echo 'checked' ?> class="form-check-input" type="checkbox" value="0" name="sunCheck" id="sunCheck">
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="monCheck">
                        دوشنبه
                      </label>
                      <input <?php if(strpos($foodDays, '1') !== false) echo 'checked' ?> class="form-check-input" type="checkbox" value="1" name="monCheck" id="monCheck">
                    </div>
                  </div>
                  <div class="d-flex align-items-center justify-content-evenly">
                    <div class="form-check">
                      <label class="form-check-label" for="tueCheck">
                        سه شنبه
                      </label>
                      <input <?php if(strpos($foodDays, '2') !== false) echo 'checked' ?> class="form-check-input" type="checkbox" value="2" name="tueCheck" id="tueCheck">
                    </div>
                    <div class="form-check">
                      <label class="form-check-label" for="wedCheck">
                        چهارشنبه
                      </label>
                      <input <?php if(strpos($foodDays, '3') !== false) echo 'checked' ?> class="form-check-input" type="checkbox" value="3" name="wedCheck" id="wedCheck">
                    </div>
                  </div>
                </div>
              
            </div>
            <div class="form-group mb-3 ">
              <label class="" for="">موجودی</label>
              <div class="d-flex align-items-center justify-content-evenly">
                <div class="form-check">
                  <input <?php if($foodSupply == 1) echo 'checked' ?> class="form-check-input" type="radio" name="supply" id="exist1" value="1">
                  <label class="form-check-label" for="exist1">
                    موجود
                  </label>
                </div>
                <div class="form-check">
                  <input <?php if($foodSupply == 0) echo 'checked' ?> class="form-check-input" type="radio" name="supply" id="notExist1" value="0">
                  <label class="form-check-label" for="notExist1">
                    ناموجود
                  </label>
                </div>
              </div>

            </div>
            
            <div class="form-group mb-3">
              <label for="fileToUpload">تغییر عکس</label>
              <div class="d-flex align-items-center justify-content-center text-center">

                <input type="file" id="fileToUpload" name="fileToUpload">
              </div>
            </div>
            <button type="submit" class="btn rounded btn-primary ms-1" style="width: 7rem;">ثبت</button>
            
          </form>
          <button class="btn btn-danger w-25 mt-2 rounded" onclick="getFoods(event)">لغو</button>
        
        </div>
      </div>
    
    <?php  
    }

    private function foodsDisplay($foods) { ?>
      <div class="row justify-content-evenly elementsTop mt-1"> 
        <div class="col-sm-5 col-lg-3 ms-1 shadow rounded px-0 py-0 mb-3 card d-flex align-items-center justify-content-center" onclick="addFood(event)" style="cursor: pointer;">
          <svg xmlns="http://www.w3.org/2000/svg" width="180" height="180" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
          </svg>  
        </div>
        <?php 
            for ($i = 0; $i < sizeof($foods); $i++) { ?>
              <div class="col-sm-5 col-lg-3 ms-1 shadow rounded px-0 py-0 mb-3 card" id="<?php echo 'food' . $i ?>">
                <img src="<?php echo $foods[$i]["img_url"] ?>" alt="food-img" class="w-100">
                
                <div class="d-flex justify-content-between mt-3 mx-2">
                  <p> <?php echo $foods[$i]["name"] ?>  </p>
                  <p><?php echo $foods[$i]["price"] . '﷼' ?></p>
                </div>
                <?php if($foods[$i]["supply"] == 1) {
                 echo '<p class="text-success">موجود</p>';
                } else {
                  echo '<p class="text-danger">ناموجود</p>';
                }?>

                <div class="d-flex">
                  <button type="submit" class="btn btn-dark w-50 mb-0 rounded-start-0" onclick="getChoosedFood(this.id)" id="<?php echo 'choose' . $i ?>" style="border-left: 1px solid white;">ویرایش</button>
                  <button class="btn btn-dark w-50 mb-0 rounded-end-0" id="<?php echo 'delete' . $i ?>" onclick="getDeletedFood(event, this.id)">حذف کامل از لیست</button>
                </div>
              </div>
        <?php
          }
        ?>
      </div>
   <?php
    }

    private function getDayAndType($day = -1, $type = -1) {
      
      if($day ==  -1 && $type == -1) {
        if (isset($_GET["day"])) {
          if($_GET["day"] == 4 || $_GET["day"] == 5) {
            $this->day = 6;
            header("Location: edit.php?day=6");
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
      } else {
        $this->day = $day;
        $this->type = $type;
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
  
      }
    }
}

