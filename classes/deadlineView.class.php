<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once 'deadline.class.php';

class DeadlineView extends Deadline {

    public function statusView() {
        if(isset($_GET["setStatus"])) {
            if($_GET["setStatus"] == "success") {
                self::successView();
            } else if($_GET["setStatus"] == "failed") {
                self::failedView();
            }
        }
    }
    
    private function successView() { ?>
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" id="myAlert" style="position: absolute; bottom: 0; right: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill me-1" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            بازه زمانی با موفقیت ثبت شد.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>                                                    
        </div>
    <?php
    }
    
    private function failedView() {  ?>
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" id="myAlert" style="position: absolute; bottom: 0; right: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill me-1" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            مشکلی پیش آمد. دوباره امتحان کنید.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>                                                    
        </div>
    <?php
    }
}