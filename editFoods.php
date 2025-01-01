<?php
require_once 'classes/editView.class.php';
// require_once 'classes/loginCont.class.php';
if (!isset($_SESSION["user_id"])) {
  header("Location: index.php");
}

?>

<div class="row align-items-center justify-content-center align-content-center">
  
      <div class="col-12 align-items-center justify-content-center text-center elements">
        <?php
        
          $editView = new EditView();
          $editView->displayFoodList();
        ?>
      </div>
</div>