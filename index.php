<?php
// index.php

require_once "loadindex.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <?php $ObjLayouts->heading(); ?>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <?php
        $ObjMenus->main_menu();
    ?>
    <!-- <div class="main-container">
        <div class="hero-section">
            <h1>Welcome to Coding Academy</h1>
            <p>Your journey to mastering coding begins here.</p>
            <a href="#courses" class="cta-button">Explore Courses</a>
        </div>
        <div id="courses" class="content-section">
            <h2>Our Courses</h2>
            <div class="course-card">
                <h3>Web Development</h3>
                <p>Learn to build amazing websites from scratch!</p>
            </div>
            <div class="course-card">
                <h3>Data Science</h3>
                <p>Analyze data and derive meaningful insights.</p>
            </div>
            <div class="course-card">
                <h3>Machine Learning</h3>
                <p>Unlock the power of AI and machine learning.</p>
            </div>
        </div>
        <div class="about-section">
            <h2>About Us</h2>
            <p>We are dedicated to providing quality education that empowers individuals to become skilled professionals in the tech industry.</p>
        </div>
    </div> -->

    <?php 
        $ObjContents->sidebar(); 
        $ObjLayouts->footer(); 
    ?>

    <div class="toggle-container">
        <button id="toggle-mode" class="toggle-button"><i class="fas fa-sun"></i></button>
    </div>
    
    <script src="scripts.js"></script>
    <script>
        // Dark mode/light mode toggle functionality
        const toggleButton = document.getElementById('toggle-mode');
        toggleButton.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            toggleButton.innerHTML = document.body.classList.contains('dark-mode') 
                ? '<i class="fas fa-moon"></i>' 
                : '<i class="fas fa-sun"></i>';
        });

        // Scroll effect implementation
        document.addEventListener('DOMContentLoaded', () => {
            const sections = document.querySelectorAll('.content-section, .about-section');
            
            const options = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };
            
            const callback = (entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            };
            
            const observer = new IntersectionObserver(callback, options);
            
            sections.forEach(section => {
                observer.observe(section);
            });
        });
    </script>
</body>
</html>
