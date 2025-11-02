<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <link rel="stylesheet" href="../Public/assests/css/events.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <!-- //navigation bar -->
    <?php require_once '../includes/navbar.php'; ?>

    <!-- My Items -->

    <p id="myPara">We specialize in organizing seamless and unforgettable events, from corporate meetups to vibrant
        social gatherings. Whether it's a business conference, a product launch, a wedding, or a community festival, we
        ensure every detail is perfectly planned and executed. Let us bring your vision to life with expertise,
        creativity, and a commitment to excellence, making your event truly memorable.
        <br><br>
        <b>Here are some categories we cover—check below!</b>
    </p>
    <div class="myAllItems">
        <div class="row">
            <div class="column">
                <div class="card">
                    <div class="cardPhoto">
                        <button class="btn" value=""><a href="../EventsGallary/gallary.html#All">See More</a></button>
                        <img src="../assests/event/socialEvents.jpg" alt="" width="100%" class="photo">
                    </div>
                    <div class="container">
                        <h2>Social Meetings</h2><br>
                        <p>We organize meetups, networking events, and gatherings, ensuring a smooth and engaging
                            experience for all.</p>
                    </div>
                </div>
            </div>

            <div class="column">
                <div class="card">
                    <div class="cardPhoto">
                        <button class="btn" value=""><a href="../EventsGallary/gallary.html#bparty">See
                                More</a></button>
                        <img src="../assests/event/birthdayParties.png" alt="" width="100%" class="photo">
                    </div>
                    <div class="container">
                        <h2>Birthday Parties</h2><br>
                        <p>From themed decorations to entertainment and catering, we create unforgettable birthday
                            celebrations for all ages.</p>
                    </div>
                </div>
            </div>

            <div class="column">
                <div class="card">
                    <div class="cardPhoto">
                        <button class="btn" value=""><a href="../EventsGallary/gallary.html#All">See More</a></button>
                        <img src="../assests/event/sportsEvents.jpg" alt="" width="100%" class="photo">
                    </div>
                    <div class="container">
                        <h2>Sport Events</h2><br>
                        <p>From local tournaments to large sports events, we handle everything from venue booking to
                            crowd management.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="column">
                <div class="card">
                    <div class="cardPhoto">
                        <button class="btn" value=""><a href="../EventsGallary/gallary.html#weddings">See
                                More</a></button>
                        <img src="../assests/event/weddingEvents.png" alt="" width="100%" class="photo">
                    </div>
                    <div class="container">
                        <h2>Wedding Events</h2><br>
                        <p>We create your dream wedding with expert planning, stunning décor, and seamless coordination
                            for a perfect day.</p>
                    </div>
                </div>
            </div>

            <div class="column">
                <div class="card">
                    <div class="cardPhoto">
                        <button class="btn" value=""><a href="../EventsGallary/gallary.html#Architecture">See
                                More</a></button>
                        <img src="../assests/event/cooporateEvents.jpg" alt="" width="100%" class="photo">
                    </div>
                    <div class="container">
                        <h2>Architecture Events</h2><br>
                        <p>From conferences to team-building, we ensure seamless execution that reflects your brand’s
                            professionalism.</p>
                    </div>
                </div>
            </div>

            <div class="column">
                <div class="card">
                    <div class="cardPhoto">
                        <button class="btn" value=""><a href="../EventsGallary/gallary.html#All">See More</a></button>
                        <img src="../assests/event/exhibitios.jpg" alt="" width="100%" class="photo">
                    </div>
                    <div class="container">
                        <h2>Exhibitions</h2><br>
                        <p>We manage booth setups, logistics, and event flow, ensuring your trade show or exhibition is
                            a complete success.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <br><br>
    <!-- Countdown -->
    <div class="center">
        <div class="countdownBox">

            <video autoplay loop muted class="videoBG">
                <source src="../assests/event/eventVideo2.mp4">
            </video>

            <div class="title_countdown">
                <h2 id="counth1">The Next Biggest Event</h2>
            </div>
            <div class="num_countdown">
                <div class="time">
                    <h2 id="day"></h2>
                    <small>Days</small>
                </div>

                <div class="time">
                    <h2 id="hour"></h2>
                    <small>Hours</small>
                </div>

                <div class="time">
                    <h2 id="min"></h2>
                    <small>Miniutes</small>
                </div>

                <div class="time">
                    <h2 id="sec"></h2>
                    <small>Seconds</small>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <!-- footer -->



    <script>
        const day = document.getElementById('day');
        const hour = document.getElementById('hour');
        const min = document.getElementById('min');
        const sec = document.getElementById('sec');

        const upcommingYear = new Date(`May 30 2025 00:00:00`);

        function countdown() {
            const currentTime = new Date();
            const diff = upcommingYear - currentTime;

            const d = Math.floor(diff / 1000 / 60 / 60 / 24);
            const h = Math.floor(diff / 1000 / 60 / 60) % 24;
            const m = Math.floor(diff / 1000 / 60) % 60;
            const s = Math.floor(diff / 1000) % 60;

            day.innerHTML = d < 10 ? '0' + d : d;
            hour.innerHTML = h < 10 ? '0' + h : h;
            min.innerHTML = m < 10 ? '0' + m : m;
            sec.innerHTML = s < 10 ? '0' + s : s;
        }

        setInterval(countdown, 1000);

    </script>
</body>
<?php require_once '../includes/footer.php'; ?>

</html>