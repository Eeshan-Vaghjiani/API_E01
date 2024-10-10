<?php
// structure/layouts.php

class layouts {
    public function heading() {
        ?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Coding Academy</title>
        <?php
    }

    public function footer() {
        ?>
        <footer class="footer">
            <p>&copy; <?php echo date("Y"); ?> Coding Academy. All rights reserved.</p>
            <p>
                <a href="privacy.php">Privacy Policy</a> |
                <a href="terms.php">Terms of Service</a>
            </p>
            <p>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </p>
        </footer>
        <?php
    }
}
?>
