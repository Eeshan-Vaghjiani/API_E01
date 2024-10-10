<?php
class menus {
    public function main_menu() {
        ?>
        <div class="topnav">
            <a href="./">Home</a>
            <a href="aboutus.php">About Us</a>
            <a href="">Our Projects</a>
            <a href="">Our Portfolio</a>
            <a href="">Blog</a>
            <a href="">Contact Us</a>
            <?php $this->main_right_side_menu(); ?>
        </div>
        <?php
    }

    public function main_right_side_menu() {
        session_start(); // Ensure the session is started

        if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
            // If the user is logged in and 2FA is successful
            ?>
            <div class="topnav-right">
                <a href="..\API_E01\includes\logout.php">Logout</a>
            </div>
            <?php
        } else {
            // If the user is not logged in
            ?>
            <div class="topnav-right">
                <a href="..\API_E01\includes\signup.php">Sign Up</a>
                <a href="..\API_E01\includes\login.php">Sign In</a>
            </div>
            <?php
        }
    }
}
?>
