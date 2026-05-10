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
        $_SESSION['old'] = $_POST;
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
        $conn->close();
    }

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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact Us - Festora</title>
    <link rel="stylesheet" href="../Public/assests/css/contactus.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        /* Modern Global Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: #f9fafb;
            color: #333;
        }

        /* Header Banner */
        .pageheader {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            text-align: center;
            padding: 80px 20px;
        }

        .pageheader h1 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .pageheader p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Intro Section */
        .contact {
            padding: 60px 20px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .contact h3 {
            font-size: 2rem;
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .contact p {
            font-size: 1.1rem;
            color: #555;
        }

        /* Flash Message */
        .message {
            text-align: center;
            margin: 20px auto;
            padding: 16px 24px;
            border-radius: 12px;
            max-width: 600px;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success {
            background: #e6f7ee;
            color: #107e3e;
            border: 1px solid #b3e6c7;
        }

        .error {
            background: #fdf2f2;
            color: #c53030;
            border: 1px solid #fbbaba;
        }

        /* Main Container */
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 40px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto 60px;
        }

        .contactInfo,
        .contactForm {
            background: white;
            border-radius: 16px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            flex: 1;
            min-width: 300px;
            max-width: 500px;
        }

        .contactInfo h2,
        .contactForm h2 {
            font-size: 1.8rem;
            margin-bottom: 25px;
            color: #2c3e50;
            text-align: center;
        }

        .box {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 25px;
        }

        .box .icon {
            width: 50px;
            height: 50px;
            background: #f0f7ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .box .icon img {
            width: 24px;
            height: 24px;
            object-fit: contain;
        }

        .box h3 {
            margin-bottom: 6px;
            color: #2c3e50;
        }

        .box p {
            color: #555;
            line-height: 1.5;
        }

        /* Form Styling */
        .contactForm label {
            display: block;
            margin: 16px 0 8px;
            font-weight: 600;
            color: #2c3e50;
        }

        .contactForm input,
        .contactForm textarea {
            width: 100%;
            padding: 14px;
            border: 1px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .contactForm input:focus,
        .contactForm textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
        }

        .contactForm textarea {
            resize: vertical;
            min-height: 120px;
        }

        .contactForm .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .contactForm input[type="submit"],
        .contactForm input[type="reset"] {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .contactForm input[type="submit"] {
            background: #2575fc;
            color: white;
        }

        .contactForm input[type="submit"]:hover {
            background: #1a67e0;
            transform: translateY(-2px);
        }

        .contactForm input[type="reset"] {
            background: #f1f5f9;
            color: #4b5563;
        }

        .contactForm input[type="reset"]:hover {
            background: #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                align-items: center;
            }

            .pageheader h1 {
                font-size: 2.2rem;
            }
        }
    </style>
</head>

<body>

    <!-- NAVBAR INCLUDED -->
    <?php require_once '../includes/navbar.php'; ?>

    <section>
        <div class="pageheader" style="background-image: url('../assests/index/ribbonimg.jpg'); 
                height: 200px; 
                background-size: cover; 
                background-position: center; 
                display: flex; 
                flex-direction: column; 
                justify-content: center; 
                align-items: center; 
                color: white; 
                text-align: center;">
            <h1>Contact Us</h1>
            <span>The amazing team making Contacts</span>
        </div>
    </section>

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
        <div class="message <?php echo htmlspecialchars($type); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="container">
        <div class="contactInfo">
            <h2>Get In Touch</h2>
            <div class="box">
                <div class="icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="text">
                    <h3>Address</h3>
                    <p>Flower Road,<br>Baththaramulla, Colombo,<br>701/2</p>
                </div>
            </div>
            <div class="box">
                <div class="icon">
                    <i class="fas fa-phone-alt"></i>
                </div>
                <div class="text">
                    <h3>Phone</h3>
                    <p>+9478 5567890<br>+11 23456783</p>
                </div>
            </div>
            <div class="box">
                <div class="icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="text">
                    <h3>Email</h3>
                    <p>festoraevents52@gmail.com</p>
                </div>
            </div>
        </div>

        <div class="contactForm">
            <h2>Send Message</h2>
            <form method="post" action="">
                <label for="fname">Full Name</label>
                <input type="text" name="fname" id="fname" value="<?php echo htmlspecialchars($old['fname'] ?? ''); ?>"
                    required>

                <label for="teleno">Telephone Number</label>
                <input type="text" name="teleno" id="teleno"
                    value="<?php echo htmlspecialchars($old['teleno'] ?? ''); ?>" required>

                <label for="email">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>"
                    required>

                <label for="message">Your Message</label>
                <textarea name="message" id="message"
                    required><?php echo htmlspecialchars($old['message'] ?? ''); ?></textarea>

                <div class="btn-group">
                    <input type="submit" value="Send Message">
                    <input type="reset" value="Reset">
                </div>
            </form>
        </div>
    </div>

    <!-- FOOTER INCLUDED -->
    <?php require_once '../includes/footer.php'; ?>

</body>

</html>