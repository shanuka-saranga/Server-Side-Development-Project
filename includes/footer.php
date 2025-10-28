<?php
// includes/footer.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Public/assests/css/footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .site-footer {
            background-color: #1a1a1a;
            color: #ccc;
            padding: 60px 20px 20px;
            font-family: 'Arial', sans-serif;
            margin-top: 80px;
            position: relative;
            overflow: hidden;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 40px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            color: #ff6b00;
            font-size: 28px;
            font-weight: bold;
            text-decoration: none;
            margin-bottom: 15px;
        }

        .footer-logo img {
            width: 40px;
            height: 40px;
            margin-right: 10px;
            border-radius: 8px;
        }

        .footer-about p {
            font-size: 14px;
            line-height: 1.7;
            color: #aaa;
            margin-bottom: 20px;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: #333;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #ff6b00;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(255, 107, 0, 0.4);
        }

        .footer-title {
            color: #ff6b00;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 3px;
            background: #ff6b00;
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #aaa;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
            display: flex;
            align-items: center;
        }

        .footer-links a:hover {
            color: #ff6b00;
        }

        .footer-links a i {
            margin-right: 8px;
            font-size: 12px;
            color: #ff6b00;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            font-size: 14px;
            color: #aaa;
        }

        .contact-item i {
            color: #ff6b00;
            font-size: 16px;
            margin-right: 12px;
            margin-top: 2px;
        }

        .footer-bottom {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #333;
            text-align: center;
            font-size: 13px;
            color: #777;
        }

        .footer-bottom a {
            color: #ff6b00;
            text-decoration: none;
        }

        .footer-bottom a:hover {
            text-decoration: underline;
        }

        /* Responsive */
        @media (max-width: 992px) {
            .footer-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 600px) {
            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .footer-title::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .contact-item {
                justify-content: center;
            }
        }

        /* Wave Effect */
        .footer-wave {
            position: absolute;
            top: -30px;
            left: 0;
            width: 100%;
            height: 30px;
            background: #1a1a1a;
        }

        .footer-wave::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent 48%, #ff6b00 50%, transparent 52%);
            background-size: 20px 20px;
            opacity: 0.1;
            animation: wave 10s linear infinite;
        }

        @keyframes wave {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 100px 0;
            }
        }

        /* ========= FOOTER STYLES ========= */
    </style>
</head>

<body>

    <footer class="site-footer">
        <!-- Wave Background -->
        <div class="footer-wave"></div>

        <div class="footer-container">

            <!-- About Festora -->
            <div class="footer-about">
                <a href="../index.php" class="footer-logo">
                    <img src="../Public/assets/images/LOGO.png" alt="Festora Logo">
                    Festora
                </a>
                <p>
                    We create unforgettable events with passion, precision, and creativity.
                    From weddings to corporate galas, your vision is our mission.
                </p>
                <div class="social-links">
                    <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-links">
                    <li><a href="../index.php"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="../event/events.html"><i class="fas fa-chevron-right"></i> Events</a></li>
                    <li><a href="../Booking/booking.html"><i class="fas fa-chevron-right"></i> Book Now</a></li>
                    <li><a href="../Contactus/contact.html"><i class="fas fa-chevron-right"></i> Contact</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h3 class="footer-title">Our Services</h3>
                <ul class="footer-links">
                    <li><a href="../services/Services.html"><i class="fas fa-chevron-right"></i> Wedding Planning</a>
                    </li>
                    <li><a href="../services/Services.html"><i class="fas fa-chevron-right"></i> Corporate Events</a>
                    </li>
                    <li><a href="../services/Services.html"><i class="fas fa-chevron-right"></i> Birthday Parties</a>
                    </li>
                    <li><a href="../services/Services.html"><i class="fas fa-chevron-right"></i> Social Gatherings</a>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h3 class="footer-title">Get in Touch</h3>
                <div class="contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <strong>Address</strong><br>
                        123 Event Street, Celebration City, EC 12345
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <strong>Phone</strong><br>
                        <a href="tel:+1234567890" style="color:#aaa;">+1 (234) 567-890</a>
                    </div>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <strong>Email</strong><br>
                        <a href="mailto:info@festora.com" style="color:#aaa;">info@festora.com</a>
                    </div>
                </div>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="footer-bottom">
            <p>
                © <span id="year"></span> <strong>Festora</strong>. All Rights Reserved.
                Designed with <i class="fas fa-heart" style="color:#ff6b00;"></i> by
                <a href="#" target="_blank">FestoraTeam</a>
            </p>
        </div>
    </footer>

    <script>
        // Auto-update year
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>

</body>

</html>