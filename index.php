<?php
// Public/index.php
session_start(); // For login later
$page_title = 'Home';
require_once 'includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Festora - <?= htmlspecialchars($page_title) ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="Public/assests/css/index.css">
    <link rel="stylesheet" href="assets/css/allnav&footer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/png" href="assets/images/LOGO.png">
</head>

<body>

    <!-- =================================== NAVBAR =================================== -->
    <!-- Already included above via require_once '../includes/navbar.php' -->

    <!-- =================================== SLIDER =================================== -->
    <section class="slider-height">
        <div class="slider">
            <div class="list">
                <div class="item"><img src="assests/index/1.jpg" alt="Slide 1"></div>
                <div class="item"><img src="assests/index/slider2.jpg" alt="Slide 2"></div>
                <div class="item"><img src="assests/index/slider3.jpg" alt="Slide 3"></div>
                <div class="item"><img src="assests/index/wedding-couple-dancing.jpg" alt="Wedding"></div>
                <div class="item"><img src="assests/index/newlyweds-their-first-dance.jpg" alt="Dance"></div>
            </div>
            <ul class="dots">
                <li class="active"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div class="text-area">
            <h1>Make Your Event Memorable</h1>
            <p>Let us bring your vision to life with elegance and perfection.</p>
            <button id="explore"><a href="../EventsGallary/gallary.html">Explore</a></button>
        </div>
    </section>

    <!-- =================================== WELCOME =================================== -->
    <section>
        <div class="welcome-text">
            <img src="assests/index/midleimg.png" alt="Welcome">
            <h1>Welcome to <span id="text-primary">Festora</span></h1>
            <p>At Festora,<br> we are dedicated to making your event unforgettable. <br> Our team of experienced event
                planners
                will work with you to create a custom event that reflects your unique style and vision.<br>
                Contact us today to start planning your special day!</p>
        </div>
    </section>

    <!-- =================================== SERVICES EXPLAIN =================================== -->
    <section>
        <div class="explain">
            <div class="service-explain">
                <div class="title01">
                    <img src="assests/index/icon2.png" alt="Icon">
                    <h1>Great Service</h1>
                </div>
                <p>Exceptional event services with seamless planning and unforgettable experiences tailored to your
                    needs</p>
                <button id="btn">Learn More</button>
            </div>

            <div class="service-explain">
                <div class="title01">
                    <img src="assests/index/icon3.png" alt="Icon">
                    <h1>Great People</h1>
                </div>
                <p>Great people, exceptional service—bringing your events to life with passion and precision!</p>
                <button id="btn">Learn More</button>
            </div>

            <div class="service-explain">
                <div class="title01">
                    <img src="assests/index/icon1.png" alt="Icon">
                    <h1>Great Ideas</h1>
                </div>
                <p>Great ideas, flawless execution—turning your vision into unforgettable events!</p>
                <button id="btn">Learn More</button>
            </div>
        </div>
    </section>

    <!-- =================================== SERVICES GRID =================================== -->
    <section class="service-section">
        <div class="Service-text">
            <h1><span id="text-primary">Festora</span> Services</h1>
            <p>We make your events smart & impactful by personalised event management services</p>
            <p id="sub-para">Event services involve planning, organizing, and managing events like weddings,
                conferences, and corporate meetings...</p>
        </div>

        <div class="service-containner">
            <div class="service">
                <div class="service-img"><img src="assests/index/eventimg1.jpg" alt="Wedding"></div>
                <div class="service-text">
                    <h1>Wedding</h1>
                    <p>Our team of experienced wedding planners will work with you to create a custom wedding...</p>
                </div>
            </div>
            <div class="service">
                <div class="service-img"><img src="assests/index/eventimg2.jpg" alt="Seminar"></div>
                <div class="service-text">
                    <h1>Corporate Seminars</h1>
                    <p>Our team of experienced event planners will work with you to create a custom corporate event...
                    </p>
                </div>
            </div>
            <div class="service">
                <div class="service-img"><img src="assests/index/eventimg3.jpg" alt="Birthday"></div>
                <div class="service-text">
                    <h1>Birthday</h1>
                    <p>Our team of experienced event planners will work with you to create a custom birthday party...
                    </p>
                </div>
            </div>
            <div class="service">
                <div class="service-img"><img src="assests/index/eventimg4.jpg" alt="Social"></div>
                <div class="service-text">
                    <h1>Social Events</h1>
                    <p>Our team of experienced event planners will work with you to create a custom social event...</p>
                </div>
            </div>
        </div>
    </section>

    <!-- =================================== COUNTER RIBBON =================================== -->
    <section class="ribbon">
        <div class="ribbon-text">
            <div class="counter-container">
                <div class="counter" data-target="320">0</div>
                <p>Featured Events</p>
            </div>
            <div class="counter-container">
                <div class="counter" data-target="356">0</div>
                <p>Loyal Customers</p>
            </div>
            <div class="counter-container">
                <div class="counter" data-target="694">0</div>
                <p>Good Comments</p>
            </div>
            <div class="counter-container">
                <div class="counter" data-target="367">0</div>
                <p>Trophies Won</p>
            </div>
        </div>
    </section>

    <!-- =================================== IMAGE + TEXT BOX =================================== -->
    <div class="container">
        <div class="image-wrapper">
            <img src="assests/index/eventimg1.jpg" class="image1" alt="Event 1">
            <img src="assests/index/eventimg4.jpg" class="image2" alt="Event 2">
        </div>
        <div class="text-box">
            <h2>Festora – Events That Last</h2>
            <p>We bring your guests closer to you and help you create relationships that last!</p>
            <div class="ptitiles">
                <div class="ptitile1">
                    <img src="assests/index/icon1.png" alt="Icon">
                    <p id="ptitle1">Event Planning & Coordination</p>
                </div>
                <div class="ptitile1">
                    <img src="assests/index/icon1.png" alt="Icon">
                    <p id="ptitle1">Venue Selection & Decoration</p>
                </div>
                <div class="ptitile1">
                    <img src="assests/index/icon1.png" alt="Icon">
                    <p id="ptitle1">Entertainment & Music</p>
                </div>
                <div class="ptitile1">
                    <img src="assests/index/icon1.png" alt="Icon">
                    <p id="ptitle1">Great Transport Service</p>
                </div>
            </div>
        </div>
    </div>

    <!-- =================================== FOOTER =================================== -->
    <?php require_once 'includes/footer.php'; ?>

    <!-- =================================== SCRIPTS =================================== -->
    <script src="Public/js/script.js"></script>
    <script src="Public/js/count.js"></script>

</body>

</html>