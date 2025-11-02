<?php
session_start();
require_once '../config/config.php';
require_once '../includes/navbar.php';


if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id']) || !is_numeric($_SESSION['user_id'])) {
    header("Location: ../Public/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = false;
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    
    $userName = mysqli_real_escape_string($conn, $_POST['userName']);
    $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $userTel = mysqli_real_escape_string($conn, $_POST['userTel']);
    $eventType = mysqli_real_escape_string($conn, $_POST['event_type']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $guestCount = intval($_POST['guestCount']);
    $eventStart = str_replace('T', ' ', $_POST['event_start']); 
    $eventEnd = str_replace('T', ' ', $_POST['event_end']);
    $eventDesc = mysqli_real_escape_string($conn, $_POST['eventDesc']);

    
    if (
        !empty($userName) && !empty($userEmail) && !empty($userTel) &&
        !empty($eventType) && !empty($location) && $guestCount > 0
    ) {
        
        $sql = "INSERT INTO booking 
                (user_id, full_name, email, phone, event_type, location, guest_count, event_start, event_end, event_description)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isssssisss",
            $user_id,
            $userName,
            $userEmail,
            $userTel,
            $eventType,
            $location,
            $guestCount,
            $eventStart,
            $eventEnd,
            $eventDesc
        );

        if ($stmt->execute()) {
            $success = true;
            $booking_id = $stmt->insert_id; 
        } else {
            $error = "Database Error: " . $conn->error;
        }
        $stmt->close();
    } else {
        $error = "⚠️ Please fill all required fields correctly.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Public/assests/css/Booking.css">
    <link rel="icon" type="image/png" href="../assests/LOGO.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Book Now</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: 'Poppins', sans-serif;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #ff6b00;
            margin-bottom: 10px;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        button {
            background-color: #ff6b00;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #e35a00;
        }

        .progress-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .step {
            flex: 1;
            text-align: center;
            padding: 10px;
            color: white;
            background-color: #ccc;
            border-radius: 6px;
        }

        .step.active {
            background-color: #ff6b00;
        }

        .summary-card {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .success-msg {
            color: green;
            font-weight: bold;
            text-align: center;
        }

        .error-msg {
            color: red;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>

    <section>
        <div class="container">
            <?php if ($success): ?>
                <div class="success-msg">🎉 Your booking has been submitted successfully!</div>
            <?php elseif (!empty($error)): ?>
                <div class="error-msg"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="progress-bar">
                <div class="step active">1. Details</div>
                <div class="step">2. Event</div>
                <div class="step">3. Confirm</div>
            </div>

            <form id="bookingForm" method="post" action="">
                <!-- Step 1 -->
                <div class="form-step active" id="step-1">
                    <h2>Step 1: Your Details</h2>
                    <input type="text" placeholder="Full Name" id="userName" name="userName" required>
                    <input type="email" placeholder="Email" id="userEmail" name="userEmail" required>
                    <input type="tel" placeholder="Phone" id="userTel" name="userTel" required>
                    <button type="button" onclick="nextStep()">Next</button>
                </div>

                <!-- Step 2 -->
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
                        <option value="">Select Event Location</option>
                        <option value="Galle">Galle</option>
                        <option value="Matara">Matara</option>
                        <option value="Hambantota">Hambantota</option>
                        <option value="Colombo">Colombo</option>
                    </select>
                    <input type="number" placeholder="Number of Guests" id="guestCount" name="guestCount" required
                        min="1" max="1000">
                    <input type="datetime-local" id="eventStart" name="event_start" required>
                    <input type="datetime-local" id="eventEnd" name="event_end" required>
                    <textarea placeholder="Tell us about your event" id="eventDesc" name="eventDesc"></textarea>
                    <button type="button" onclick="prevStep()">Back</button>
                    <button type="button" onclick="nextStep()">Next</button>
                </div>

                <!-- Step 3 -->
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
        </div>
    </section>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        function showStep(step) {
            document.querySelectorAll(".form-step").forEach((el, i) => {
                el.classList.toggle("active", i + 1 === step);
            });
            document.querySelectorAll(".step").forEach((el, i) => {
                el.classList.toggle("active", i + 1 <= step);
            });
        }

        function nextStep() {
            if (currentStep < totalSteps) currentStep++;
            showStep(currentStep);
            fillSummary();
        }

        function prevStep() {
            if (currentStep > 1) currentStep--;
            showStep(currentStep);
        }

        function fillSummary() {
            document.getElementById("sumName").textContent = document.getElementById("userName").value;
            document.getElementById("sumEmail").textContent = document.getElementById("userEmail").value;
            document.getElementById("sumPhone").textContent = document.getElementById("userTel").value;
            document.getElementById("sumEvent").textContent = document.getElementById("userEvent").value;
            document.getElementById("sumGuests").textContent = document.getElementById("guestCount").value;
            document.getElementById("sumStart").textContent = document.getElementById("eventStart").value;
            document.getElementById("sumEnd").textContent = document.getElementById("eventEnd").value;
            document.getElementById("sumDesc").textContent = document.getElementById("eventDesc").value;
        }
    </script>
</body>

</html>

<?php require_once '../includes/footer.php'; ?>