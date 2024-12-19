<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyConnect - Student Inquiry Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="../css/index.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <a href="../enquiry system/index.php" style="text-decoration: none; color: inherit;">
                <h1>StudyConnect</h1>
            </div>
            <ul class="nav-links">
                <li><a class="nav-link active " href="#">Home</a></li>
                <li><a class="nav-link" href="#features">Features</a></li>
                <li><a class="nav-link " href="#about">About Us</a></li>
                <li><a class="nav-link" href="#contact-us">Contact</a></li>
            </ul>
        </nav>
        <div class="hero-section">
            <div class='at-container'>
                <div class='at-item mt-1' ><h1 style="font-size: larger;">Empower your Learning...!</h1></div>
            </div>
            <p style="color: white;">Collaborate with peers and connect with professors for academic success.</p>
            <a href="../enquiry system/getstarted.php" class="cta-btn">Get Started</a>
        </div>
    </header>

    <section class="features" id="features">
    <h2>Key Features</h2>
    <div class="feature-grid">
        <div class="feature-box">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Accessible Experts</h3>
            <p>Directly reach out to professors and experts for personalized guidance.</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-users"></i>
            <h3>Peer-to-Peer Learning</h3>
            <p>Collaborate with classmates on challenging topics.</p>
        </div>
        <div class="feature-box">
            <i class="fas fa-book"></i>
            <h3>Resources</h3>
            <p>Access accurate and reliable course materials tailored to your curriculum.</p>
        </div>
    </div>
</section>

    <section class="about-us" id="about">
        <h2>About Us</h2>
        <p>StudyConnect is a platform designed to improve student learning by offering direct access to professors, peer collaboration, and a wealth of valiable resources. Our mission is to create an accessible and supportive academic environment that helps students overcome challenges and succeed in their studies.</p>
    </section>

  <!-- Contact Us Section -->
<section id="contact-us">
    <div class="container">
        <h2>Contact Us</h2>
        <p class="contact-description">Have questions or feedback? Feel free to reach out to us using the form below. We’d love to hear from you!</p>
        
        <form class="contact-form" action="send_email.php" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required placeholder="Your name">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Your email">
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="5" required placeholder="Your message"></textarea>
            </div>
            <button type="submit" class="btn-submit">Send Message</button>
        </form>
    </div>
</section>

            
            <div class="contact-info"></div>
                <div class="contact-card">
                    <h3>Our Address</h3>
                    <p>123 Study Lane, Education City, EC 45678</p>
                </div>
                <div class="contact-card">
                    <h3>Follow Us</h3><br>
                    <a href="https://facebook.com" target="_blank" class="social-icon">
                        <img src="../images/facebook.png" alt="Facebook">
                    </a>
                    <a href="https://twitter.com" target="_blank" class="social-icon">
                        <img src="../images/twitter.png" alt="Twitter">
                    </a>
                    <a href="https://instagram.com" target="_blank" class="social-icon">
                        <img src="../images/instagram.png" alt="Instagram">
                    </a>
                    <a href="https://linkedin.com" target="_blank" class="social-icon">
                        <img src="../images/linkin.png" alt="LinkedIn">
                    </a>
                    <a href="https://wa.me/1234567890" target="_blank" class="social-icon">
                        <img src="../images/whatsapp .png " alt="WhatsApp">
                    </a>
                    <a href="https://www.tiktok.com/@softerengineering" target="_blank" class="social-icon">
                        <img src="../images/tiktok.png" alt="tiktok">
                    </a>
                </div>
        </div>
    </section>

    <footer>
        <p>&copy; 2024 StudyConnect. All Rights Reserved.</p>
    </footer>

    <!-- FontAwesome CDN for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
    <script src="../js/index.js"> </script>
</body>
</html>
