<?php
// structure/contents.php

class contents {
    public function main_content() {
        ?>
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <h1>Master the Art of Coding – One Lesson at a Time</h1>
                <p>Turn your passion for coding into a career with our expert-led tutorials.</p>
                <a href="includes/signup.php" class="cta-button">Get Started for Free</a>
                <a href="courses.php" class="cta-button secondary">Explore Courses</a>
            </div>
        </section>

        <!-- Popular Courses Section -->
        <section class="section popular-courses" id="courses">
            <h2 class="section-title">Top Courses to Get You Started</h2>
            <div class="course-cards">
                <div class="course-card">
                    <img src="images/course1.jpg" alt="Python for Beginners">
                    <h3>Python for Beginners</h3>
                    <p>Learn Python from the ground up and start building your own applications.</p>
                    <a href="course1.php" class="cta-button">Enroll Now</a>
                </div>
                <div class="course-card">
                    <img src="images/course2.jpg" alt="JavaScript Essentials">
                    <h3>JavaScript Essentials</h3>
                    <p>Master the fundamentals of JavaScript and enhance your web development skills.</p>
                    <a href="course2.php" class="cta-button">Enroll Now</a>
                </div>
                <div class="course-card">
                    <img src="images/course3.jpg" alt="Web Development">
                    <h3>Web Development</h3>
                    <p>Build responsive and dynamic websites using the latest web technologies.</p>
                    <a href="course3.php" class="cta-button">Enroll Now</a>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="section why-choose-us" id="why-choose-us">
            <h2 class="section-title">Why Learn with Coding Academy?</h2>
            <div class="benefits">
                <div class="benefit">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <h3>Expert Tutors</h3>
                    <p>Learn from professionals with real-world experience.</p>
                </div>
                <div class="benefit">
                    <i class="fas fa-briefcase"></i>
                    <h3>Career Growth</h3>
                    <p>Boost your career with in-demand skills.</p>
                </div>
                <div class="benefit">
                    <i class="fas fa-users"></i>
                    <h3>Community Support</h3>
                    <p>Get answers and insights from fellow learners.</p>
                </div>
            </div>
        </section>

        <!-- Student Testimonials Section -->
        <section class="section testimonials" id="testimonials">
            <h2 class="section-title">What Our Students Say</h2>
            <div class="testimonial-cards">
                <div class="testimonial-card">
                    <img src="images/testimonial1.jpg" alt="Student 1">
                    <p>"Coding Academy transformed my career! The courses are well-structured and the instructors are top-notch."</p>
                    <h4>Jane Doe</h4>
                    <span>Python for Beginners</span>
                </div>
                <div class="testimonial-card">
                    <img src="images/testimonial2.jpg" alt="Student 2">
                    <p>"The JavaScript Essentials course gave me the skills I needed to land my first developer job."</p>
                    <h4>John Smith</h4>
                    <span>JavaScript Essentials</span>
                </div>
            </div>
        </section>

        <!-- Featured Blogs Section -->
        <section class="section featured-blogs" id="blogs">
            <h2 class="section-title">Latest in Coding & Tech</h2>
            <div class="blog-cards">
                <div class="blog-card">
                    <img src="images/blog1.jpg" alt="Blog Post 1">
                    <h3>Top 10 Python Libraries You Should Know</h3>
                    <p>Discover the most essential Python libraries that can boost your development workflow.</p>
                    <a href="blog1.php" class="cta-button">Read More</a>
                </div>
                <div class="blog-card">
                    <img src="images/blog2.jpg" alt="Blog Post 2">
                    <h3>Getting Started with JavaScript ES6</h3>
                    <p>Learn the new features introduced in ES6 and how they can improve your JavaScript code.</p>
                    <a href="blog2.php" class="cta-button">Read More</a>
                </div>
                <div class="blog-card">
                    <img src="images/blog3.jpg" alt="Blog Post 3">
                    <h3>Building Responsive Websites with Flexbox</h3>
                    <p>Master Flexbox to create flexible and responsive web layouts with ease.</p>
                    <a href="blog3.php" class="cta-button">Read More</a>
                </div>
            </div>
        </section>
        <?php
    }

    public function sidebar() {
        ?>
        <aside class="sidebar">
            <h2>Stay Updated</h2>
            <p>Subscribe to our newsletter to receive the latest updates and news about our courses.</p>
            <form action="subscribe.php" method="POST">
                <input type="email" name="email" placeholder="Your Email" required>
                <button type="submit" class="cta-button">Subscribe</button>
            </form>
        </aside>
        <?php
    }
}
?>
