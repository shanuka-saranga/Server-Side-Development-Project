<?php
session_start(); // start session
require_once '../config/config.php';
require_once '../includes/navbar.php';

// Restrict access for non-logged users
if (!isset($_SESSION['user_id'])) {
    echo "
    <div style='
        text-align:center;
        margin:50px auto;
        padding:20px;
        max-width:500px;
        border:1px solid #f5c6cb;
        background-color:#f8d7da;
        color:#721c24;
        font-family:Poppins, sans-serif;
        border-radius:8px;
        box-shadow:0 4px 8px rgba(0,0,0,0.1);
    '>
        <h2>Access Denied</h2>
        <p>To add an appointment, please login first.</p>
        <a href='../Public/login.php' 
           style='display:inline-block;margin-top:10px;
                  color:#721c24;
                  font-weight:bold;
                  text-decoration:underline;'>
            Login Here
        </a>
    </div>
    ";
    exit();
}

$message = "";
$messageClass = "";

if (isset($_POST['submit'])) {
    $name = $_POST['name'] ?? '';
    $reason = $_POST['reason'] ?? '';
    $date = $_POST['date'] ?? '';
    $contact = $_POST['contact'] ?? '';
    $branch = $_POST['branch'] ?? '';

    $isValid = true;
    $errorMsg = "";

    // Validate Name
    if (empty($name)) {
        $isValid = false;
        $errorMsg = "Please enter your name";
    } elseif (!preg_match("/^[a-zA-Z\s]+$/", $name)) {
        $isValid = false;
        $errorMsg = "Please enter a valid name (letters and spaces only)";
    }

    // Validate Reason
    if ($isValid && empty($reason)) {
        $isValid = false;
        $errorMsg = "Please select an event type";
    }

    // Validate Date
    if ($isValid && empty($date)) {
        $isValid = false;
        $errorMsg = "Please enter a valid date";
    } elseif ($isValid && !empty($date)) {
        $selectedDate = strtotime($date);
        $today = strtotime(date('Y-m-d'));
        if ($selectedDate < $today) {
            $isValid = false;
            $errorMsg = "Please enter a valid date";
        }
    }

    // Validate Contact
    if ($isValid && empty($contact)) {
        $isValid = false;
        $errorMsg = "Enter a phone number";
    } elseif ($isValid && !preg_match("/^[0-9]{10}$/", $contact)) {
        $isValid = false;
        $errorMsg = "Contact number can only have 10 digits";
    }

    // Validate Branch
    if ($isValid && empty($branch)) {
        $isValid = false;
        $errorMsg = "Select the branch that you want to visit";
    }

    // Insert to database if valid
    if ($isValid) {
        $name = mysqli_real_escape_string($conn, $name);
        $reason = mysqli_real_escape_string($conn, $reason);
        $date = mysqli_real_escape_string($conn, $date);
        $contact = mysqli_real_escape_string($conn, $contact);
        $branch = mysqli_real_escape_string($conn, $branch);

        $sql = "INSERT INTO appointment (fname, reason, date, contact, branch)
                VALUES ('$name', '$reason', '$date', '$contact', '$branch')";

        if (mysqli_query($conn, $sql)) {
            $message = "Appointment submitted successfully!";
            $messageClass = "success";
        } else {
            $message = "Error: " . mysqli_error($conn);
            $messageClass = "error";
        }
    } else {
        $message = $errorMsg;
        $messageClass = "error";
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Festora | Appointment</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      background-color: white;
      color: rgb(70, 70, 70);
    }

    header {
      background-color: #ff7900;
      color: white;
      text-align: center;
      padding: 2rem 1rem;
    }

    header h1 {
      margin: 0;
      font-size: 2rem;
    }

    header p {
      margin-top: 0.5rem;
    }

    .appointment-section {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: stretch;
      padding: 2rem;
      gap: 2rem;
    }

    .form-container {
      background-color: #f9f9f9;
      padding: 2rem;
      border-radius: 10px;
      flex: 1 1 400px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .form-container h2 {
      color: #ff7900;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    label {
      font-weight: bold;
      display: block;
      margin-top: 1rem;
    }

    input, select {
      width: 100%;
      padding: 0.7rem;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-top: 0.3rem;
      font-size: 1rem;
    }

    input:focus, select:focus {
      border-color: #ff7900;
      outline: none;
      box-shadow: 0 0 6px rgba(255,121,0,0.5);
    }

    .btn {
      width: 100%;
      background-color: #ff7900;
      color: white;
      border: none;
      padding: 0.9rem;
      font-size: 1rem;
      border-radius: 8px;
      margin-top: 1.5rem;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn:hover {
      background-color: rgb(70, 70, 70);
    }

    .image-container {
      flex: 1 1 400px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .image-container img {
      width: 100%;
      max-width: 450px;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    footer {
      background-color: rgb(70,70,70);
      color: white;
      text-align: center;
      padding: 1rem;
      margin-top: 2rem;
    }

    @media (max-width: 768px) {
      .appointment-section {
        flex-direction: column;
        padding: 1rem;
      }
      .image-container img {
        max-width: 100%;
      }
    }

    /* Message styles */
    .message {
      text-align: center;
      margin: 20px auto;
      padding: 10px;
      width: 80%;
      border-radius: 5px;
      font-weight: 500;
    }
    .success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    .error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>
<body>

<header>
  <h1>Festora Events</h1>
  <p>Schedule Your Appointment</p>
</header>

<?php if (!empty($message)): ?>
  <div class="message <?php echo $messageClass; ?>">
    <?php echo htmlspecialchars($message); ?>
  </div>
<?php endif; ?>

<section class="appointment-section">
  <div class="form-container">
    <h2>Schedule Your Visit</h2>
    <form action="" method="POST">
      
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your full name">

      <label for="reason">Reason / Event Type</label>
      <select id="reason" name="reason">
        <option value="">-- Select Event Type --</option>
        <option value="Social Meeting">Social Meeting</option>
        <option value="Birthday Party">Birthday Party</option>
        <option value="Sports Event">Sports Event</option>
        <option value="Wedding Event">Wedding Event</option>
        <option value="Architecture Event">Architecture Event</option>
        <option value="Exhibition">Exhibition</option>
      </select>

      <label for="date">Preferred Date to Visit</label>
      <input type="date" id="date" name="date">

      <label for="contact">Contact Number</label>
      <input type="text" id="contact" name="contact" placeholder="e.g. 0771234567">

      <label for="branch">Preferred Branch</label>
      <select id="branch" name="branch">
        <option value="">-- Select Branch --</option>
        <option value="Colombo">Colombo</option>
        <option value="Gampaha">Gampaha</option>
        <option value="Kalutara">Kalutara</option>
        <option value="Matara">Matara</option>
      </select>

      <input type="submit" value="Confirm Appointment" class="btn" name="submit">
    </form>
  </div>

  <div class="image-container">
    <img src="../assests/appointment.jpg" alt="Festora Event Meeting">
  </div>
</section>

</body>
</html>

<?php require_once "../includes/footer.php"; ?>
