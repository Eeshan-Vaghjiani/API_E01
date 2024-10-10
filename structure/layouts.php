<?php
    class layouts {
        
        // Method to generate the page header and opening HTML tags
        public function heading() {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>ICS Portal</title>  <!-- Updated title for clarity -->
                <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- Bootstrap CSS -->
                <link rel="stylesheet" href="css/style.css">         <!-- Custom CSS -->
            </head>
            <body>
                <div class="container">
                    <!-- Header Section with Site Branding -->
                    <header class="py-3 mb-4 border-bottom">

                    </header>
                    
                    <!-- Main Content Area Start -->
                    <main>
            <?php
        }

        // Method to generate the page footer and closing HTML tags
        public function footer() {
            ?>
                    
                    <!-- Footer Section -->
                    <footer class="pt-3 mt-4 text-body-secondary border-top">
                        <p>&copy; ICS <?php echo date("Y"); ?> - All Rights Reserved</p>
                    </footer>
                </div> <!-- Close Container -->

                <!-- Bootstrap JS and dependencies -->
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
                    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
                    crossorigin="anonymous"></script>
            </body>
            </html>
            <?php
        }
    }
?>
