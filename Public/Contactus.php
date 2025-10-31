<?php
// Start session FIRST
session_start();
require_once "../config/config.php";

// Flash message variables
$message = "";
$type = "";

// Process form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST["fname"] ?? '');
    $telephone = trim($_POST["teleno"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $message_t = trim($_POST["message"] ?? '');

    // Validation
    if (empty($fullname) || empty($telephone) || empty($email) || empty($message_t)) {
        $_SESSION['message'] = "Please fill in all fields.";
        $_SESSION['type'] = "error";
        $_SESSION['old'] = $_POST; // Save input for repopulation
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Please enter a valid email address.";
        $_SESSION['type'] = "error";
        $_SESSION['old'] = $_POST;
    } else {
        $stmt = $conn->prepare("INSERT INTO contact (fullname, telephone, email, message_t) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fullname, $telephone, $email, $message_t);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Thank you! Your message has been sent successfully.";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['message'] = "Database error: " . $stmt->error;
            $_SESSION['type'] = "error";
            $_SESSION['old'] = $_POST;
        }
        $stmt->close();
    }

    $conn->close();

    // Redirect to prevent resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Retrieve flash message
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $type = $_SESSION['type'];
    $old = $_SESSION['old'] ?? [];

    // Clear session data
    unset($_SESSION['message'], $_SESSION['type'], $_SESSION['old']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Festora</title>
    <link rel="stylesheet" href="contact.css">
    <link rel="icon" type="image/png" href="../assests/LOGO.png">
    <link rel="stylesheet" href="Public/assests/css/contactus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .message {
            text-align: center;
            margin: 20px auto;
            padding: 15px;
            border-radius: 8px;
            max-width: 600px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>

    <!-- NAVBAR INCLUDED -->
    <?php require_once '../includes/navbar.php'; ?>

    <div class="pageheader">
        <div class="pageheader-container">
            <h1>Contact Us</h1>
            <p>We'd love to hear from you!</p>
        </div>
    </div>

    <section class="contact">
        <div class="content">
            <h3>Let's Plan Something Amazing Together!</h3>
            <p>We're here to bring your event ideas to life. Whether you're planning a wedding, corporate event, party,
                or any special occasion, our team is ready to support you. Reach out to us with your questions, ideas,
                or booking requests — we're just a message away.</p>
        </div>
    </section>

    <!-- Flash Message -->
    <?php if ($message): ?>
        <div class="message <?php echo $type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="contactInfo">
            <div class="box">
                <div class="icon"><img src="../assests/contactus/address.png" alt="address"></div>
                <div class="text">
                    <h3>Address</h3>
                    <p>Flower Road,<br>Baththaramulla, Colombo,<br>701/2</p>
                </div>
            </div>
            <div class="box">
                <div class="icon"><img src="../assests/contactus/phone.png" alt="phone"></div>
                <div class="text">
                    <h3>Phone</h3>
                    <p>+9478 5567890<br>+11 23456783</p>
                </div>
            </div>
            <div class="box">
                <div class="icon"><img src="../assests/contactus/email.png" alt="email"></div>
                <div class="text">
                    <h3>Email</h3>
                    <p>festoraevents52@gmail.com</p>
                </div>
            </div>
        </div>

        <div class="contactForm">
            <form method="post" action="">
                <h2>Send Message</h2><br><br>

                <label for="fname">Full Name</label><br>
                <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($old['fname'] ?? ''); ?>"
                    required><br><br>

                <label for="teleno">Telephone Number</label><br>
                <input type="text" name="teleno" id="teleno"
                    value="<?php echo htmlspecialchars($old['teleno'] ?? ''); ?>" required><br><br>

                <label for="email">Email</label><br>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                    required><br><br>

                <label for="message">Type your Message...</label><br>
                <textarea name="message" id="message" rows="5" cols="46" required><?php
                echo htmlspecialchars($old['message'] ?? '');
                ?></textarea><br><br>

                <input type="submit" value="Send" id="submit">
                <input type="reset" value="Reset" id="reset">
            </form>
        </div>
    </div>

    <!-- FOOTER INCLUDED -->
    <?php require_once 'includes/footer.php'; ?>

</body>

</html>