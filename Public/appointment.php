<?php
require_once "../config/config.php";

// Initialize message variable
$message = "";

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get form data and escape special characters
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $reason = mysqli_real_escape_string($conn, $_POST['reason']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $branch = mysqli_real_escape_string($conn, $_POST['branch']);

    // Prepare SQL query
    $sql = "INSERT INTO appointment (fname, reason, date, contact, branch)
            VALUES ('$name', '$reason', '$date', '$contact', '$branch')";

    // Execute query
    if (mysqli_query($conn, $sql)) {
        $message = "<script>alert('Appointment submitted successfully');</script>";
    } else {
        $message = "<h2 style='color:red; text-align:center; margin-top:20px;'>Error: " . mysqli_error($conn) . "</h2>";
    }
}

// Close connection
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

  /* Form container */
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

  /* Image container */
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

  /* Responsive */
  @media (max-width: 768px) {
    .appointment-section {
      flex-direction: column;
      padding: 1rem;
    }

    .image-container img {
      max-width: 100%;
    }
  }

  .message {
    text-align: center;
    margin-bottom: 20px;
  }

</style>
</head>
<body>

<header>
  <h1>Festora Events</h1>
  <p>Schedule Your Appointment</p>
</header>

<div class="message">
  <?php if(!empty($message)) echo $message; ?>
</div>

<section class="appointment-section">
  <div class="form-container">
    <h2>Schedule Your Visit</h2>
    <form action="" method="POST">
      
      <label for="name">Full Name</label>
      <input type="text" id="name" name="name" placeholder="Enter your full name" required>

      <label for="reason">Reason / Event Type</label>
      <select id="reason" name="reason" required>
        <option value="">-- Select Event Type --</option>
        <option value="Social Meeting">Social Meeting</option>
        <option value="Birthday Party">Birthday Party</option>
        <option value="Sports Event">Sports Event</option>
        <option value="Wedding Event">Wedding Event</option>
        <option value="Architecture Event">Architecture Event</option>
        <option value="Exhibition">Exhibition</option>
      </select>

      <label for="date">Preferred Date to Visit</label>
      <input type="date" id="date" name="date" required>

      <label for="contact">Contact Number</label>
      <input type="text" id="contact1" name="contact" placeholder="e.g. 0771234567" required>

      <label for="branch">Preferred Branch</label>
      <select id="branch" name="branch" required>
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
    <img src="https://cdn.prod.website-files.com/5f55ff47b6d23a11cb496a69/68235a933c9fbf612456d36d_Purpose%20of%20one%20on%20one%20meetings.jpg" alt="Festora Event Meeting">
  </div>
</section>

</body>
</html>
