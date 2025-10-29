<?php
// Start PHP session for user tracking
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once 'db_connection.php';

// Generate or get user session ID
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = uniqid('user_', true);
}

// Process form submission
if (isset($_POST['sub'])) {
    // Validate required fields
    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['rating']) || 
        empty($_POST['comment']) || empty($_POST['recommend'])) {
        $form_error = "Please fill in all required fields.";
    } else {
        // Get form data
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $rating = (int)$_POST['rating'];
        $comment = trim($_POST['comment']);
        $recommend = $_POST['recommend'];
        $event_name = isset($_POST['event_name']) ? trim($_POST['event_name']) : '';
        $user_id = $_SESSION['user_id'];
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $form_error = "Please enter a valid email address.";
        }
        // Validate rating
        elseif ($rating < 1 || $rating > 5) {
            $form_error = "Please select a valid rating.";
        }
        else {
            $sql = "INSERT INTO review (name, email, rating, comment, recommend, event_name, user_id, user_session, user_ip) 
                    VALUES ('$name', '$email', $rating, '$comment', '$recommend', '$event_name', '$user_id', '$user_id', '$user_ip')";
            
            $QueryResult = mysqli_query($conn, $sql);
            
            if ($QueryResult) {
                $form_success = "Thank you for your review! It has been submitted successfully.";
                $_POST = array();
            } else {
                $form_error = "Error submitting review. Please try again.";
            }
        }
    }
}

// Handle review update
if (isset($_POST['update_review'])) {
    $review_id = (int)$_POST['review_id'];
    $user_id = $_SESSION['user_id'];
    
    // Verify the review belongs to the current user
    $check_sql = "SELECT review_id FROM review WHERE review_id = $review_id AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $name = trim($_POST['name']);
        $rating = (int)$_POST['rating'];
        $comment = trim($_POST['comment']);
        $event_name = trim($_POST['event_name']);
        $recommend = $_POST['recommend'];
        
        $update_sql = "UPDATE review SET name='$name', rating=$rating, comment='$comment', 
                      event_name='$event_name', recommend='$recommend' 
                      WHERE id = $review_id AND user_id = '$user_id'";
        
        if (mysqli_query($conn, $update_sql)) {
            $form_success = "Review updated successfully!";
        } else {
            $form_error = "Error updating review.";
        }
    } else {
        $form_error = "You can only update your own reviews.";
    }
}

// Handle review deletion
if (isset($_GET['delete_review'])) {
    $review_id = (int)$_GET['delete_review'];
    $user_id = $_SESSION['user_id'];
    
    // Verify the review belongs to the current user
    $check_sql = "SELECT review_id FROM review WHERE review_id = $review_id AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_sql);
    
    if (mysqli_num_rows($check_result) > 0) {
        $delete_sql = "DELETE FROM review WHERE review_id = $review_id AND user_id = '$user_id'";
        if (mysqli_query($conn, $delete_sql)) {
            $form_success = "Review deleted successfully!";
        } else {
            $form_error = "Error deleting review.";
        }
    } else {
        $form_error = "You can only delete your own reviews.";
    }
}

// Retrieve reviews from database for display
$sql = "SELECT review_id, name, rating, comment, event_name, created_at, recommend, user_id 
        FROM review 
        ORDER BY created_at DESC 
        LIMIT 50";
$result = mysqli_query($conn, $sql);
$NumRows = mysqli_num_rows($result);

// Get current user's reviews for editing
$user_id = $_SESSION['user_id'];
$user_reviews_sql = "SELECT review_id, name, rating, comment, event_name, recommend 
                     FROM review 
                     WHERE user_id = '$user_id' 
                     ORDER BY created_at DESC";
$user_reviews_result = mysqli_query($conn, $user_reviews_sql);
$user_reviews_count = mysqli_num_rows($user_reviews_result);
?>
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
        ody {
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

/* Success and Error Messages */
.alert {
    padding: 15px;
    margin: 20px 0;
    border-radius: 5px;
    text-align: center;
}

.alert-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
        .review-actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }
        
        .btn-edit, .btn-delete {
            padding: 5px 10px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-edit {
            background-color: #007bff;
            color: white;
        }
        
        .btn-edit:hover {
            background-color: #0056b3;
        }
        
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        
        .btn-delete:hover {
            background-color: #c82333;
        }
        
        .edit-form {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        
        .user-reviews {
            margin: 30px 0;
        }
        
        .user-reviews h3 {
            color: #333;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
    </style>
</head>

<body>
    <!-- Your existing navigation and header code remains the same -->
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
                    <a href="review.php">Reviews</a>
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

    <!-- Display success/error messages -->
    <?php if (isset($form_success)): ?>
        <div class="alert alert-success">
            <?php echo $form_success; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($form_error)): ?>
        <div class="alert alert-error">
            <?php echo $form_error; ?>
        </div>
    <?php endif; ?>

    <!-- Review Submission Form -->
    <div class="group">
        <form id="reviewForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h1>Share Your Experience</h1>
            <p>We would like to hear from you about your experience with our services.</p>
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Your full name" required maxlength="100" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required maxlength="100" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            
            <label for="rating">Overall Rating:</label>
            <select id="rating" name="rating" required>
                <option value="">Select Rating</option>
                <option value="5" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 5) ? 'selected' : ''; ?>>★★★★★ - Excellent</option>
                <option value="4" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 4) ? 'selected' : ''; ?>>★★★★☆ - Very Good</option>
                <option value="3" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 3) ? 'selected' : ''; ?>>★★★☆☆ - Good</option>
                <option value="2" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 2) ? 'selected' : ''; ?>>★★☆☆☆ - Fair</option>
                <option value="1" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 1) ? 'selected' : ''; ?>>★☆☆☆☆ - Poor</option>
            </select>
            
            <label for="comment">Your Opinion:</label>
            <textarea id="comment" name="comment" placeholder="Share your experience" rows="5" required maxlength="1000"><?php echo isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : ''; ?></textarea>
            
            <label for="event_name">Event Name (Optional):</label>
            <input type="text" id="event_name" name="event_name" placeholder="Which event did you attend?" maxlength="100" value="<?php echo isset($_POST['event_name']) ? htmlspecialchars($_POST['event_name']) : ''; ?>">
            
            <label>Would you recommend us to others?</label>
            <div class="radio-group">
                <input type="radio" id="yes" name="recommend" value="yes" required <?php echo (isset($_POST['recommend']) && $_POST['recommend'] == 'yes') ? 'checked' : ''; ?>>
                <label for="yes" class="radio-label">Yes</label>
                
                <input type="radio" id="no" name="recommend" value="no" required <?php echo (isset($_POST['recommend']) && $_POST['recommend'] == 'no') ? 'checked' : ''; ?>>
                <label for="no" class="radio-label">No</label>
            </div>
            
            <button type="submit" name="sub">Submit Review</button>
        </form>
    </div>

    <!-- User's Reviews Section for Editing -->
    <?php if ($user_reviews_count > 0): ?>
    <div class="group user-reviews">
        <h3>Your Reviews (<?php echo $user_reviews_count; ?>)</h3>
        <p>You can edit or delete your reviews below:</p>
        
        <?php while ($user_review = mysqli_fetch_assoc($user_reviews_result)): ?>
        <div class="edit-form">
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="hidden" name="review_id" value="<?php echo $user_review['review_id']; ?>">
                
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user_review['name']); ?>" required>
                
                <label>Rating:</label>
                <select name="rating" required>
                    <option value="5" <?php echo $user_review['rating'] == 5 ? 'selected' : ''; ?>>★★★★★ - Excellent</option>
                    <option value="4" <?php echo $user_review['rating'] == 4 ? 'selected' : ''; ?>>★★★★☆ - Very Good</option>
                    <option value="3" <?php echo $user_review['rating'] == 3 ? 'selected' : ''; ?>>★★★☆☆ - Good</option>
                    <option value="2" <?php echo $user_review['rating'] == 2 ? 'selected' : ''; ?>>★★☆☆☆ - Fair</option>
                    <option value="1" <?php echo $user_review['rating'] == 1 ? 'selected' : ''; ?>>★☆☆☆☆ - Poor</option>
                </select>
                
                <label>Comment:</label>
                <textarea name="comment" rows="3" required><?php echo htmlspecialchars($user_review['comment']); ?></textarea>
                
                <label>Event Name:</label>
                <input type="text" name="event_name" value="<?php echo htmlspecialchars($user_review['event_name']); ?>">
                
                <label>Recommend:</label>
                <div class="radio-group">
                    <input type="radio" name="recommend" value="yes" <?php echo $user_review['recommend'] == 'yes' ? 'checked' : ''; ?>> Yes
                    <input type="radio" name="recommend" value="no" <?php echo $user_review['recommend'] == 'no' ? 'checked' : ''; ?>> No
                </div>
                
                <div style="display: flex; gap: 10px; margin-top: 15px;">
                    <button type="submit" name="update_review" class="btn-edit">Update Review</button>
                    <a href="?delete_review=<?php echo $user_review['id']; ?>" 
                       class="btn-delete" 
                       onclick="return confirm('Are you sure you want to delete this review?')">
                        Delete Review
                    </a>
                </div>
            </form>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>

    <!-- Dynamic Reviews from Database -->
    <div class="dynamic-reviews">
        <h2>Customer Reviews</h2>
        <div id="reviewsContainer">
            <?php if ($result && $NumRows > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="review-item">
                        <div class="review-header">
                            <span class="review-name"><?php echo htmlspecialchars($row['name']); ?></span>
                            <span class="review-rating">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= $row['rating'] ? '★' : '☆';
                                }
                                echo ' (' . $row['rating'] . '/5)';
                                ?>
                            </span>
                        </div>
                        
                        <?php if (!empty($row['event_name'])): ?>
                            <div class="review-event">Event: <?php echo htmlspecialchars($row['event_name']); ?></div>
                        <?php endif; ?>
                        
                        <div class="review-comment"><?php echo nl2br(htmlspecialchars($row['comment'])); ?></div>
                        
                        <?php if ($row['recommend'] === 'yes'): ?>
                            <div class="review-recommend">✓ Recommends our services</div>
                        <?php endif; ?>
                        
                        <div class="review-date"><?php echo date('F j, Y', strtotime($row['created_at'])); ?></div>
                        
                        <!-- Show edit/delete buttons only for user's own reviews -->
                        <?php if (isset($row['user_id']) && $row['user_id'] === $_SESSION['user_id']): ?>
                        <div class="review-actions">
                            <small>Your review - </small>
                            <a href="?edit_review=<?php echo $row['review_id']; ?>" class="btn-edit">Edit</a>
                            <a href="?delete_review=<?php echo $row['review_id']; ?>" 
                               class="btn-delete" 
                               onclick="return confirm('Are you sure you want to delete this review?')">
                                Delete
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-reviews"><p>No reviews yet. Be the first to share your experience!</p></div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Rest of your existing code (static reviews, footer, etc.) remains the same -->
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
        // Auto-scroll to user reviews section when editing
        <?php if (isset($_GET['edit_review'])): ?>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('.user-reviews').scrollIntoView({ behavior: 'smooth' });
        });
        <?php endif; ?>

        // Add confirmation for delete links
        document.addEventListener('DOMContentLoaded', function() {
            const deleteLinks = document.querySelectorAll('.btn-delete');
            deleteLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this review?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>
</html>
<?php
// Close database connection
mysqli_close($conn);
?>