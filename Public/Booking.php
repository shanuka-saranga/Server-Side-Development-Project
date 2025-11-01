<?php
session_start();
require_once '../config/config.php';
require_once '../includes/navbar.php';

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: ../Public/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['submit'])) {
    $userName = mysqli_real_escape_string($conn, $_POST['userName']);
    $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $userTel = mysqli_real_escape_string($conn, $_POST['userTel']);
    $eventType = mysqli_real_escape_string($conn, $_POST['event_type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $guestCount = intval($_POST['guestCount']);
    $eventStart = mysqli_real_escape_string($conn, $_POST['event_start']);
    $eventEnd = mysqli_real_escape_string($conn, $_POST['event_end']);
    $eventDesc = mysqli_real_escape_string($conn, $_POST['eventDesc']);
    $sql = "INSERT INTO booking (user_id,full_name, email, phone, event_type, location, guest_count, event_start, event_end, event_description)
            VALUES ('$user_id','$userName', '$userEmail', '$userTel', '$eventType', '$location', '$guestCount', '$eventStart', '$eventEnd', '$eventDesc')";

    if ($conn->query($sql) === TRUE) {
        $success = true;
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CommonCSS/allnav&footer.css">
    <link rel="stylesheet" href="../Public/assests/css/Booking.css">
    <link rel="icon" type="image/png" href="../assests/LOGO.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Book Now</title>
</head>

<body>
    <div class="pageheader">
        <div class="pageheader-container">
            <h1 id="headid">Booking</h1>
            <p>Sri Lankan Luxury Destination Surprise Romantic Events Planner</p>
        </div>
    </div>

    <section>
        <div class="container">
            <div class="progress-bar">
                <div class="step active">1. Details</div>
                <div class="step">2. Event</div>
                <div class="step">3. Confirm</div>
            </div>

            <form id="bookingForm" method="post" action="">
                <div class="form-step active" id="step-1">
                    <h2>Step 1: Your Details</h2>
                    <input type="text" placeholder="Full Name :" id="userName" name="userName" required />
                    <input type="email" placeholder="Email :" id="userEmail" name="userEmail" required />
                    <input type="tel" placeholder="Phone :" id="userTel" name="userTel" required />
                    <button type="button" onclick="nextStep()">Next</button>
                </div>

                <div class="form-step" id="step-2">
                    <h2>Step 2: Event Info</h2>
                    <select id="userEvent" name="event_type" required>
                        <option value="">Select Event Type</option>
                        <option value="Wedding">Wedding</option>
                        <option value="Birthday">Birthday</option>
                        <option value="Corporate">Corporate</option>
                        <option value="Concert">Concert</option>
                    </select>
                    <select id="userLocation" name="location" required>
                        <option value="">Select Event location</option>
                        <option value="Galle">Galle</option>
                        <option value="Matara">Matara</option>
                        <option value="Hambantota">Hambantota</option>
                        <option value="Colombo">Colombo</option>
                    </select>
                    <input type="number" placeholder="Number of Guests" id="guestCount" name="guestCount" required
                        min="1" max="1000" />
                    <input type="datetime-local" id="eventStart" name="event_start" required />
                    <input type="datetime-local" id="eventEnd" name="event_end" required />
                    <textarea placeholder="Tell us about your event" id="eventDesc" name="eventDesc"></textarea>
                    <button type="button" onclick="prevStep()">Back</button>
                    <button type="button" onclick="nextStep()">Next</button>
                </div>

                <div class="form-step" id="step-3">
                    <h2>Step 3: Confirm</h2>
                    <div class="summary-card">
                        <p><strong>Name:</strong> <span id="sumName"></span></p>
                        <p><strong>Email:</strong> <span id="sumEmail"></span></p>
                        <p><strong>Phone:</strong> <span id="sumPhone"></span></p>
                        <p><strong>Event:</strong> <span id="sumEvent"></span></p>
                        <p><strong>Guests:</strong> <span id="sumGuests"></span></p>
                        <p><strong>Start:</strong> <span id="sumStart"></span></p>
                        <p><strong>End:</strong> <span id="sumEnd"></span></p>
                        <p><strong>Details:</strong> <span id="sumDesc"></span></p>
                    </div>
                    <button type="button" onclick="prevStep()">Back</button>
                    <button type="submit" name="submit">Confirm Booking</button>
                </div>
            </form>

            </script>
            <?php if (isset($success) && $success): ?>
                <script>
                    window.onload = function () {
                        alert("🎉 Your booking has been submitted successfully!");
                    };
                </script>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <script>
                    window.onload = function () {
                        alert("❌ There was an error submitting your booking.");
                    };
                </script>
            <?php endif; ?>
        </div>
    </section>
    <script src="js/Booking.js"></script>
</body>

</html>

<?php require_once '../includes/footer.php'; ?>