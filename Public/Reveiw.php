<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <title>Reviews - Festora Events</title>
    <link rel="stylesheet" href="form.css">
    <link rel="stylesheet" href="../CommonCSS/allnav&footer.css">
    <link rel="icon" type="image/png" href="../assests/LOGO.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
      /* ============================
   Global Layout & Typography
============================ */
body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
    color: #333;
}

h2 {
    text-align: center;
    color: brown;
    margin-bottom: 30px;
    font-size: 2em;
}

.title {
    font-weight: bold;
    padding: 10px;
    font-size: 50px;
    text-align: center;
    font-family: sans-serif;
    color: #FE9179;
}

/* ============================
   Group / Form Container
============================ */
.group {
    max-width: 600px;
    margin: 20px auto;
    background-color: #f9f9f9;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.sub {
    text-align: center;
    background-color: burlywood;
    padding: 15px;
    margin: 20px 0;
}

.table {
    text-align: center;
    margin: 30px 0;
}

p {
    text-align: left;
    line-height: 1.6;
}

.stars {
    color: brown;
    font-size: 18px;
}

/* ============================
   Form Elements
============================ */
input, select, textarea {
    width: 100%;
    padding: 10px;
    margin: 8px 0 15px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 16px;
    box-sizing: border-box;
}

textarea {
    resize: vertical;
    min-height: 100px;
}

label {
    display: block;
    font-size: 16px;
    margin-bottom: 5px;
    font-weight: bold;
}

.radio-group {
    display: flex;
    gap: 20px;
    margin: 10px 0 20px 0;
}

.radio-label {
    display: inline;
    font-weight: normal;
    margin-left: 5px;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

button:hover {
    background-color: #45a049;
}

/* ============================
   Dynamic Reviews Section
============================ */
.dynamic-reviews {
    max-width: 1200px;
    margin: 40px auto;
    padding: 20px;
}

.dynamic-reviews h2 {
    text-align: center;
    color: #333;
    margin-bottom: 30px;
    font-size: 2em;
}

.review-item {
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    border-left: 4px solid #4CAF50;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    flex-wrap: wrap;
}

.review-name {
    font-weight: bold;
    font-size: 1.2em;
    color: #333;
}

.review-rating {
    color: #FFD700;
    font-size: 1.1em;
}

.review-event {
    font-style: italic;
    color: #666;
    margin-bottom: 10px;
}

.review-comment {
    color: #444;
    line-height: 1.6;
    margin: 15px 0;
}

.review-recommend {
    color: #28a745;
    font-weight: bold;
    margin: 10px 0;
}

.review-date {
    color: #999;
    font-size: 0.9em;
    text-align: right;
}

.no-reviews {
    text-align: center;
    padding: 40px;
    color: #666;
}

.loading {
    text-align: center;
    padding: 20px;
    color: #666;
}

/* ============================
   Reviews Display Wrapper
============================ */
.reviews-display {
    max-width: 800px;
    margin: 30px auto;
    padding: 20px;
}

    </style>
</head>

<body>
    <section class="nav-containner">
        <div class="nav-name">
            <img src="../assests/LOGO.png" alt="Logo" id="logo">
            <h1>Festora</h1>
        </div>
        <div class="nav">
            <a href="../index.html" id="dropbtn">Home</a>
            <div class="dropdown">
                <a href="../event/events.html" id="dropbtn">Events</a>
                <div class="dropdown-content">
                    <a href="../EventsGallary/gallary.html">Gallary</a>
                    <a href="../Organizers/Organizers.html">Our Team</a>
                    <a href="../Network/network.html">Our Network</a>
                    <a href="form.html">Reviews</a>
                </div>
            </div>
            <a href="../Contactus/contact.html" id="dropbtn">Contact us</a>
            <a href="../About Us/About Us.html" id="dropbtn">About us</a>
            <div class="dropdown">
                <a href="../Booking/booking.html" id="dropbtn">Booking</a>
                <div class="dropdown-content">
                    <a href="../Payments/paymetnt._page.html">Payments</a>
                </div>
            </div>
            <a href="../services/Services.html" id="dropbtn">Our Service</a>
        </div>
    </section>

    <div class="pageheader">
        <div class="pageheader-container">
            <h1 id="headid">Reviews</h1>
            <p>Transforming your events into unforgettable experiences with Us.</p>
        </div>
    </div>

    <div class="sub">
        <h1>Your Party, Our Passion!</h1>
        <h2>&#9733;&#9733;&#9733;&#9733;&#9733;</h2>
        <h3>4.96 out of 5 stars</h3>
    </div>

    <!-- Review Submission Form -->
    <div class="group">
        <form id="reviewForm" action="../Backend/submit_review.php" method="POST">
            <h1>Share Your Experience</h1>
            <p>We would like to hear from you about your experience with our services.</p>
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Your full name" required maxlength="100">
            
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required maxlength="100">
            
            <label for="rating">Overall Rating:</label>
            <select id="rating" name="rating" required>
                <option value="">Select Rating</option>
                <option value="5">★★★★★ - Excellent</option>
                <option value="4">★★★★☆ - Very Good</option>
                <option value="3">★★★☆☆ - Good</option>
                <option value="2">★★☆☆☆ - Fair</option>
                <option value="1">★☆☆☆☆ - Poor</option>
            </select>
            
            <label for="comment">Your Opinion:</label>
            <textarea id="comment" name="comment" placeholder="Share your experience" rows="5" required maxlength="1000"></textarea>
            
            <label for="event_name">Event Name (Optional):</label>
            <input type="text" id="event_name" name="event_name" placeholder="Which event did you attend?" maxlength="100">
            
            <label>Would you recommend us to others?</label>
            <div class="radio-group">
                <input type="radio" id="yes" name="recommend" value="yes" required>
                <label for="yes" class="radio-label">Yes</label>
                
                <input type="radio" id="no" name="recommend" value="no" required>
                <label for="no" class="radio-label">No</label>
            </div>
            
            <button type="submit" name="sub">Submit Review</button>
        </form>
    </div>

    <!-- Dynamic Reviews from Database -->
    <div class="dynamic-reviews">
        <h2>Customer Reviews</h2>
        <div id="reviewsContainer">
            <div class="loading">Loading reviews...</div>
        </div>
    </div>

    <!-- Display static reviews (Featured) -->
    <div class="table">
        <h2 style="text-align: center; margin-bottom: 30px;">Featured Reviews</h2>
        <table>
            <tr>
                <th><img src="../assests/reviews/download (1).jpg" height="60px" width="60px">
                    <br>
                    <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <b>Henry</b><br>
                    <b>5.0 out of 5 stars</b>
                    <p><b>I recently used this event management system to organize my pre school concert. I was grateful with this platform. There are several features in this platform such as the interface is clean, easy to navigate, and everything was done without any problem.</b></p>
                </th>
                <th>
                    <img src="../assests/reviews/download (2).jpg" height="60px" width="60px">
                    <br>
                    <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <b>Maria</b><br>
                    <b>5.0 out of 5 stars</b>
                    <p><b>Great with organization of multiple events ... I would not be able to do a proper job were it not for this software.</b></p>
                </th>
                <th>
                    <img src="../assests/reviews/download (3).jpg" height="60px" width="60px">
                    <br>
                    <div class="stars">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
                    <b>Jack</b><br>
                    <b>5.0 out of 5 stars</b>
                    <p><b>Organizing and centralizing all notes and details, including contracts, payment, etc., such that everything is accessible to all office staff</b></p>
                </th>
            </tr>
        </table>
    </div>

    <section class="footer">
        <div class="footer-text">
            <p>© 2025 Festora Events – The Events Specialists All Rights Reserved.</p>
            <div class="terms">
                <a href="#">Terms of Use</a> |
                <a href="#">Privacy Policy</a>
            </div>
            <div class="social-buttons">
                <a href="https://facebook.com/" class="fa fa-facebook"></a>
                <a href="https://twitter.com/" class="fa fa-twitter"></a>
                <a href="https://google.com/" class="fa fa-google"></a>
                <a href="https://linkedin.com/" class="fa fa-linkedin"></a>
                <a href="https://youtube.com/" class="fa fa-youtube"></a>
            </div>
        </div>
    </section>

    <script>
        // Show success/error message from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            alert('Thank you for your review! It has been submitted successfully.');
            // Clear the URL parameters
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        if (urlParams.get('error') === '1') {
            alert('Error submitting review. Please check your information and try again.');
            // Clear the URL parameters
            window.history.replaceState({}, document.title, window.location.pathname);
        }
        
        // Load dynamic reviews
        window.addEventListener('DOMContentLoaded', function() {
            fetch('../Backend/display_reviews.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('reviewsContainer').innerHTML = data;
                })
                .catch(error => {
                    console.error('Error loading reviews:', error);
                    document.getElementById('reviewsContainer').innerHTML = 
                        '<div class="no-reviews"><p>Unable to load reviews at this time.</p></div>';
                });
        });
    </script>
</body>
</html>