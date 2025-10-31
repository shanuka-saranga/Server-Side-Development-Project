<?php
// Start PHP session for user tracking
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
require_once '../config/config.php';

// Generate or get user session ID
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = uniqid('user_', true);
}

// Process form submission
if (isset($_POST['sub'])) {
    // Validate required fields
    if (
        empty($_POST['name']) || empty($_POST['email']) || empty($_POST['rating']) ||
        empty($_POST['comment']) || empty($_POST['recommend'])
    ) {
        $form_error = "Please fill in all required fields.";
    } else {
        // Get form data
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $rating = (int) $_POST['rating'];
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
        } else {
            $sql = "INSERT INTO review (name, email, rating, comment, recommend, event_name, user_id, user_ip) 
                    VALUES ('$name', '$email', $rating, '$comment', '$recommend', '$event_name', '$user_id', '$user_ip')";

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
    $review_id = (int) $_POST['review_id'];
    $user_id = $_SESSION['user_id'];

    // Verify the review belongs to the current user
    $check_sql = "SELECT review_id FROM review WHERE review_id = $review_id AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if (mysqli_num_rows($check_result) > 0) {
        $name = trim($_POST['name']);
        $rating = (int) $_POST['rating'];
        $comment = trim($_POST['comment']);
        $event_name = trim($_POST['event_name']);
        $recommend = $_POST['recommend'];

        $update_sql = "UPDATE review SET name='$name', rating=$rating, comment='$comment', 
                      event_name='$event_name', recommend='$recommend' 
                      WHERE review_id = $review_id AND user_id = '$user_id'";

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
    $review_id = (int) $_GET['delete_review'];
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
    <link rel="icon" type="image/png" href="../assests/LOGO.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="assests/css/review.css">
</head>

<body>
    <?php include_once '../includes/navbar.php'; ?>

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
            <input type="text" id="name" name="name" placeholder="Your full name" required maxlength="100"
                value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">

            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" placeholder="Enter your email" required maxlength="100"
                value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">

            <label for="rating">Overall Rating:</label>
            <select id="rating" name="rating" required>
                <option value="">Select Rating</option>
                <option value="5" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 5) ? 'selected' : ''; ?>>
                    ★★★★★ - Excellent</option>
                <option value="4" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 4) ? 'selected' : ''; ?>>
                    ★★★★☆ - Very Good</option>
                <option value="3" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 3) ? 'selected' : ''; ?>>
                    ★★★☆☆ - Good</option>
                <option value="2" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 2) ? 'selected' : ''; ?>>
                    ★★☆☆☆ - Fair</option>
                <option value="1" <?php echo (isset($_POST['rating']) && $_POST['rating'] == 1) ? 'selected' : ''; ?>>
                    ★☆☆☆☆ - Poor</option>
            </select>

            <label for="comment">Your Opinion:</label>
            <textarea id="comment" name="comment" placeholder="Share your experience" rows="5" required
                maxlength="1000"><?php echo isset($_POST['comment']) ? htmlspecialchars($_POST['comment']) : ''; ?></textarea>

            <label for="event_name">Event Name (Optional):</label>
            <input type="text" id="event_name" name="event_name" placeholder="Which event did you attend?"
                maxlength="100"
                value="<?php echo isset($_POST['event_name']) ? htmlspecialchars($_POST['event_name']) : ''; ?>">

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
                            <option value="5" <?php echo $user_review['rating'] == 5 ? 'selected' : ''; ?>>★★★★★ - Excellent
                            </option>
                            <option value="4" <?php echo $user_review['rating'] == 4 ? 'selected' : ''; ?>>★★★★☆ - Very Good
                            </option>
                            <option value="3" <?php echo $user_review['rating'] == 3 ? 'selected' : ''; ?>>★★★☆☆ - Good</option>
                            <option value="2" <?php echo $user_review['rating'] == 2 ? 'selected' : ''; ?>>★★☆☆☆ - Fair</option>
                            <option value="1" <?php echo $user_review['rating'] == 1 ? 'selected' : ''; ?>>★☆☆☆☆ - Poor</option>
                        </select>

                        <label>Comment:</label>
                        <textarea name="comment" rows="3"
                            required><?php echo htmlspecialchars($user_review['comment']); ?></textarea>

                        <label>Event Name:</label>
                        <input type="text" name="event_name"
                            value="<?php echo htmlspecialchars($user_review['event_name']); ?>">

                        <label>Recommend:</label>
                        <div class="radio-group">
                            <input type="radio" name="recommend" value="yes" <?php echo $user_review['recommend'] == 'yes' ? 'checked' : ''; ?>> Yes
                            <input type="radio" name="recommend" value="no" <?php echo $user_review['recommend'] == 'no' ? 'checked' : ''; ?>> No
                        </div>

                        <div style="display: flex; gap: 10px; margin-top: 15px;">
                            <button type="submit" name="update_review" class="btn-edit">Update Review</button>
                            <a href="?delete_review=<?php echo $user_review['review_id']; ?>" class="btn-delete"
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
                                <a href="?delete_review=<?php echo $row['review_id']; ?>" class="btn-delete"
                                    onclick="return confirm('Are you sure you want to delete this review?')">
                                    Delete
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-reviews">
                    <p>No reviews yet. Be the first to share your experience!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
<?php require_once '../includes/footer.php'; ?>   

    <script>
        // Auto-scroll to user reviews section when editing
        <?php if (isset($_GET['edit_review'])): ?>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelector('.user-reviews').scrollIntoView({ behavior: 'smooth' });
            });
        <?php endif; ?>

        // Add confirmation for delete links
        document.addEventListener('DOMContentLoaded', function () {
            const deleteLinks = document.querySelectorAll('.btn-delete');
            deleteLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    if (!confirm('Are you sure you want to delete this review?')) {
                        e.preventDefault();
                    }
                });
            });
        });
    </script>
</body>

<?php require_once '../includes/footer.php'; ?>

</html>
<?php
// Close database connection
mysqli_close($conn);
?>