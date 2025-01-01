<?php
require_once 'classes/reserveView.class.php';
require_once 'classes/loginCont.class.php';


?>

<div class="row align-items-center justify-content-center align-content-center">
  
      <div class="col-12 align-items-center justify-content-center text-center elements">
        <?php
        
          $reserveView = new ReserveView();
          $reserveView->setFoodDisplay();
        ?>
      </div>
</div>