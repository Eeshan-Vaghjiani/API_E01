<?php
// structure/menus.php

class menus {
    public function main_menu() {
        ?>
        <div class="topnav">
            <a href="./">Home</a>
            <a href="aboutus.php">About Us</a>
            <a href="projects.php">Our Projects</a>
            <a href="portfolio.php">Our Portfolio</a>
            <a href="blog.php">Blog</a>
            <a href="contact.php">Contact Us</a>
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
                <a href="#" onclick="logout()">Logout</a>
            </div>

            <script>
                function logout() {
                    // Redirect to the logout script
                    window.location.href = "includes/logout.php";
                }
            </script>
            <?php
        } else {
            // If the user is not logged in
            ?>
            <div class="topnav-right">
                <a href="includes/signup.php">Sign Up</a>
                <a href="includes/login.php">Sign In</a>
            </div>
            <?php
        }
    }
}
?>
