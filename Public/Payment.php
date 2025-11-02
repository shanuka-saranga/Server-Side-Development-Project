<?php
session_start();
require_once '../config/config.php';

$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Get form data
    $email = trim($_POST['email']);
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
    $payment_date = trim($_POST['pdate']);
    $amount = floatval($_POST['amount']);
    $note = trim($_POST['notice']);
    $package = isset($_POST['package']) ? trim($_POST['package']) : '';

    $errors = [];

    // Validation
    if (empty($email))
        $errors[] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors[] = "Invalid email format.";

    if (empty($payment_method))
        $errors[] = "Payment method is required.";
    if (empty($payment_date))
        $errors[] = "Payment date is required.";
    if ($amount <= 0)
        $errors[] = "Amount must be greater than 0.";
    if (empty($package))
        $errors[] = "Package selection is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO payment (email, payment_method, amount, payment_date, note, package) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsss", $email, $payment_method, $amount, $payment_date, $note, $package);

        if ($stmt->execute()) {
            $success_msg = "Payment submitted successfully!";
        } else {
            $error_msg = "Database Error: " . $conn->error;
        }

        $stmt->close();
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
    <title>Payments</title>
    <link rel="stylesheet" href="../Public/assests/css/payment.css">
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
            <h1>Payment</h1>
            <span>Simple all ones</span>
        </div>
    </section>



    <form name="Payment_form" id="p_form" action="" method="post">

        <div class="Pricing_plans">
            <div class="plans_cont">
                <!-- Basic -->
                <div class="plan">
                    <div class="plan_value"><small>Starting From</small><br><b>$299</b></div>
                    <h2>BASIC</h2>
                    <p>1 Day Event<br>Standard Services Consultation<br>Breakfast Free for Everyone<br>FREE Gifts for
                        Kids
                    </p>
                    <input class="plan_select_radio_button" name="package" type="radio" value="Basic">
                </div>

                <!-- Standard -->
                <div class="plan">
                    <div class="plan_value"><small>Starting From</small><br><b>$499</b></div>
                    <h2>STANDARD</h2>
                    <p>2 Days Event<br>Full Services Consultation<br>Breakfast,Lunch Free for Everyone<br>FREE Gifts for
                        Kids</p>
                    <input class="plan_select_radio_button" name="package" type="radio" value="Standard">
                </div>

                <!-- Premium -->
                <div class="plan">
                    <div class="plan_value"><small>Starting From</small><br><b>$699</b></div>
                    <h2>PREMIUM</h2>
                    <p>3 Days Event<br>Premium Services Consultation<br>Breakfast,Lunch & Dinner Free for
                        Everyone<br>FREE
                        Gifts for Kids</p>
                    <input class="plan_select_radio_button" type="radio" name="package" value="Premium">
                </div>
            </div>
        </div>

        <div class="container_payment">
            <div class="Payment_form">
                <?php if ($success_msg): ?>
                    <div style="color:green;"><?php echo $success_msg; ?></div>
                <?php endif; ?>
                <?php if ($error_msg): ?>
                    <div style="color:red;"><?php echo $error_msg; ?></div>
                <?php endif; ?>

                <h3>Payment Methods</h3>
                <div class="payment_method">
                    <input type="radio" name="payment_method" value="Debit or Credit Card"> Debit or Credit Card<br>
                    <input type="radio" name="payment_method" value="Paypal"> Paypal<br>
                    <input type="radio" name="payment_method" value="Bank Transfers"> Bank Transfers
                </div>

                <div class="input_details">
                    <div class="i_email">
                        <label for="email">Your Email :</label>
                        <input class="text_inputs" type="text" id="email" name="email" placeholder="Enter Your Email"
                            required>
                    </div>
                </div>

                <div class="input_details input_row">
                    <div class="i_address">
                        <label for="pdate">Payment Date :</label>
                        <input class="text_inputs" type="date" id="pdate" name="pdate" required>
                    </div>
                    <div class="i_phone">
                        <label for="amount">Amount :</label>
                        <input class="text_inputs" type="number" id="amount" name="amount" placeholder="Enter Amount"
                            required>
                    </div>
                </div>

                <div class="i_comments">
                    <label for="notice">Any notice:</label>
                    <textarea name="notice" class="text_inputs"></textarea>
                </div>

                <div class="button_class">
                    <button type="submit" name="submit" value="submit" class="s_button"
                        onclick="return validateForm(event)">Click to submit!</button>
                </div>
            </div>
        </div>

    </form>

    <script>
        function validateForm(event) {
            const paymentMethodRadios = document.getElementsByName('payment_method');
            const packageRadios = document.getElementsByName('package');
            let paymentSelected = false;
            let packageSelected = false;

            for (let i = 0; i < paymentMethodRadios.length; i++) {
                if (paymentMethodRadios[i].checked) {
                    paymentSelected = true;
                    break;
                }
            }

            for (let i = 0; i < packageRadios.length; i++) {
                if (packageRadios[i].checked) {
                    packageSelected = true;
                    break;
                }
            }

            if (!packageSelected) {
                event.preventDefault();
                alert('Please select a Package to Continue!');
                return false;
            }

            if (!paymentSelected) {
                event.preventDefault();
                alert('Please select a payment method to Continue!');
                return false;
            }

            return true;
        }
    </script>

    <?php require_once '../includes/footer.php'; ?>
</body>

</html>