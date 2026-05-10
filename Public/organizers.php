<?php
session_start();

// Optional: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
require_once '../config/config.php';

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Collect input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $coordinator = $_POST['coordinator'] ?? '';
    $creative_directors = $_POST['creative'] ?? [];
    $technical_leads = $_POST['technical'] ?? [];

    // Validate
    if (empty($name))
        $errors[] = "Name is required.";
    if (empty($email))
        $errors[] = "Email is required.";
    if (empty($phone))
        $errors[] = "Phone is required.";
    if (empty($coordinator))
        $errors[] = "Please select an Event Coordinator.";
    if (empty($creative_directors))
        $errors[] = "Please select at least one Creative Director.";
    if (empty($technical_leads))
        $errors[] = "Please select at least one Technical Lead.";
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($errors)) {
        $name = mysqli_real_escape_string($conn, $name);
        $email = mysqli_real_escape_string($conn, $email);
        $phone = mysqli_real_escape_string($conn, $phone);
        $coordinator = mysqli_real_escape_string($conn, $coordinator);
        $creative_str = mysqli_real_escape_string($conn, implode(", ", $creative_directors));
        $technical_str = mysqli_real_escape_string($conn, implode(", ", $technical_leads));

        $sql = "INSERT INTO team_selections (name, email, phone, coordinator, creative, technical)
                VALUES ('$name', '$email', '$phone', '$coordinator', '$creative_str', '$technical_str')";

        if (mysqli_query($conn, $sql)) {
            $success_msg = "✅ Thank you, $name! Your team selection has been submitted successfully!";
        } else {
            $error_msg = "Database Error: " . mysqli_error($conn);
        }
    } else {
        $error_msg = implode("<br>", $errors);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Team</title>
    <link rel="icon" type="image/png" href="../assests/LOGO.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* --- GLOBAL --- */
        body {
            font-family: 'Poppins', sans-serif;
            background: #f2f4f8;
            color: #333;
            margin: 0;
        }

        h1,
        h2,
        h3 {
            color: #0d1b2a;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        /* --- PAGE HEADER --- */
        .pageheader {
            height: 200px;
            width: auto;
            background-image: linear-gradient(rgba(0, 0, 0, 1), rgba(0, 0, 0, 0.5)), url(../assests/index/ribbonimg.jpg);
            background-size: cover;
            text-align: center;
        }

        .pageheader h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: white;
        }

        .pageheader span {
            font-size: 1.2rem;
            color: #f1f1f1;
        }

        /* --- ORGANIZER CARDS --- */
        .organizers-section {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .organizer-card {
            background: white;
            border-radius: 15px;
            text-align: center;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .organizer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .organizer-card img {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid orange;
            margin-bottom: 15px;
        }

        .organizer-card h3 {
            margin: 10px 0 5px;
        }

        .organizer-card p {
            color: #555;
        }

        /* --- FORM --- */
        .feedback-form {
            background: white;
            padding: 40px 20px;
            margin: 3rem auto;
            border-radius: 15px;
            max-width: 600px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .feedback-form h3 {
            text-align: center;
            color: #1b263b;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            border: 1.5px solid #ccc;
            border-radius: 8px;
            margin-top: 5px;
        }

        input[type="radio"],
        input[type="checkbox"] {
            margin-right: 8px;
        }

        button.submit-btn {
            margin-top: 25px;
            width: 100%;
            background: orange;
            color: white;
            font-size: 1rem;
            font-weight: 600;
            border: none;
            padding: 12px;
            border-radius: 8px;
            transition: 0.3s;
        }

        button.submit-btn:hover {
            background: orangered;
        }

        /* --- MESSAGES --- */
        .success-msg,
        .error-msg {
            text-align: center;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .success-msg {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .error-msg {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* --- RESPONSIVE --- */
        @media(max-width: 768px) {
            .pageheader h1 {
                font-size: 2rem;
            }

            .feedback-form {
                width: 90%;
            }
        }
    </style>
</head>

<body>

    <?php include_once '../includes/navbar.php'; ?>




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
            <h1>Meet Our Organizers</h1>
            <span>The amazing team making every event unforgettable</span>
        </div>
    </section>




    <!-- Organizer Cards -->
    <section class="organizers-section">
        <div class="organizer-card">
            <img src="../assests/shanuka.JPG" alt="Shanuka">
            <h3>Shanuka Saranga</h3>
            <p><strong>Event Coordinator</strong></p>
            <p>Turning plans into seamless reality.</p>
        </div>

        <div class="organizer-card">
            <img src="../assests/methum.jpg" alt="Methum">
            <h3>Methum Weerasinghe</h3>
            <p><strong>Logistics Manager</strong></p>
            <p>Ensuring every detail runs smoothly.</p>
        </div>



    </section>

    <!-- Team Members -->
    <section class="organizers-section">
        <div class="organizer-card"><img src="../assests/isuru.png">
            <h3>Isuru Shadeep</h3>
            <p><strong>Team Member</strong></p>
        </div>
        <div class="organizer-card"><img src="../assests/tharusha.jpg">
            <h3>Tharusha Sharindi</h3>
            <p><strong>Team Member</strong></p>
        </div>
        <div class="organizer-card"><img src="../assests/samadi.jpg">
            <h3>Samadhi de Silva</h3>
            <p><strong>Team Member</strong></p>
        </div>
        <div class="organizer-card"><img src="../assests/imashi.jpg">
            <h3>Imashi Madushani</h3>
            <p><strong>Team Member</strong></p>
        </div>


    </section>

    <!-- Feedback Form -->
    <section class="feedback-form">
        <?php if (!empty($success_msg)): ?>
            <div class="success-msg"><?= $success_msg ?></div>
        <?php elseif (!empty($error_msg)): ?>
            <div class="error-msg"><?= $error_msg ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <h3>Team Selection Form</h3>

            <label>Name</label>
            <input type="text" name="name" placeholder="Enter your name">

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter your email">

            <label>Phone</label>
            <input type="text" name="phone" placeholder="Enter your phone number">

            <h2>Event Coordinator</h2>
            <label><input type="radio" name="coordinator" value="Shanuka"> Shanuka Saranga</label>
            <label><input type="radio" name="coordinator" value="Dilusha"> Dilusha Radeeshan</label>
            <label><input type="radio" name="coordinator" value="Pubudu"> Pubudu Nuwan</label>

            <h2>Creative Director</h2>
            <label><input type="checkbox" name="creative[]" value="Tharusha"> Tharusha Sharindi</label>
            <label><input type="checkbox" name="creative[]" value="Samadhi"> Samadhi de Silva</label>
            <label><input type="checkbox" name="creative[]" value="Imashi"> Imashi Madushani</label>

            <h2>Technical Lead</h2>
            <label><input type="checkbox" name="technical[]" value="Tharinda"> Tharinda Gimhana</label>
            <label><input type="checkbox" name="technical[]" value="Methum"> Methum Weerasinghe</label>
            <label><input type="checkbox" name="technical[]" value="Isuru"> Isuru Shadeep</label>

            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </section>

    <?php require_once '../includes/footer.php'; ?>
</body>

</html>