<?php

require_once '../config/config.php';


$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];

    // Trim input
    $email = trim($_POST['email']);
    $payment_method = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
    $payment_date = trim($_POST['pdate']);
    $amount = trim($_POST['amount']);
    $note = trim($_POST['notice']); 

    // Required fields
    if (empty($email))
        $errors[] = "Email is required.";
    if (empty($payment_method))
        $errors[] = "Payment method is required.";
    if (empty($payment_date))
        $errors[] = "Payment date is required.";
    if (empty($amount))
        $errors[] = "Amount is required.";

    // Email format validation
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Amount must be numeric and positive
    if (!empty($amount) && (!is_numeric($amount) || $amount <= 0)) {
        $errors[] = "Amount must be a number greater than 0.";
    }

    // Payment date validation
    $current_date = date("Y-m-d");
    if (!empty($payment_date)) {
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $payment_date)) {
            $errors[] = "Invalid date format. Use YYYY-MM-DD.";
        } elseif ($payment_date < $current_date) {
            $errors[] = "Payment date cannot be in the past.";
        }
    }

    if (empty($errors)) {
        // Escape input for database
        $email = mysqli_real_escape_string($conn, $email);
        $payment_method = mysqli_real_escape_string($conn, $payment_method);
        $payment_date = mysqli_real_escape_string($conn, $payment_date);
        $amount = mysqli_real_escape_string($conn, $amount);
        $note = mysqli_real_escape_string($conn, $note);

        // Insert into database
        $sql = "INSERT INTO payment (email, payment_method, amount, payment_date, note)
                VALUES ('$email', '$payment_method', '$amount', '$payment_date', '$note')";

        if (mysqli_query($conn, $sql)) {
            $success_msg = "Payment submitted successfully!";
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
    <meta name="description" content="Payment page of Event Management site">
    <meta name="keywords" content="Event, Functions, Management">
    <meta name="author" content="Festora">
    <title>Payments</title>
    <link rel="stylesheet" href="../Public/assests/css/payment.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

    <?php include_once '../includes/navbar.php'; ?>

    <!-- ribbon -->
    <div class="pageheader">
        <div class="pageheader-container">
            <h1>OUR PRICING PLANS</h1>
            <p>Transforming your events into unforgettable experiences with Us.</p>
        </div>
    </div>

    <div class="Pricing_plans">
        <div class="plans_cont">
            <div class="plan">
                <div class="plan_value"> <small>Starting From</small> <br> <b>$299</b> </div>
                <div class="value_title">
                    <h2>BASIC</h2>
                    <p class="palan_describe_para">With Basic facilities</p>
                </div>
                <br>
                <hr><br>
                <p>1 Day Event<br><br>Standard Services Consultation<br><br>Breakfast Free for Everyone<br><br>FREE
                    Gifts for Kids</p>
                <br>
                <input class="plan_select_radio_button" name="package" type="radio" value="Basic">
            </div>

            <div class="plan">
                <div class="plan_value"><small>Starting From</small><br><b>$499</b></div>
                <div class="value_title">
                    <h2>STANDARD</h2>
                    <p class="palan_describe_para">With Standard facilities</p>
                </div>
                <br>
                <hr><br>
                <p>2 Days Event<br><br>Full Services Consultation<br><br>Breakfast,Lunch Free for Everyone<br><br>FREE
                    Gifts for Kids</p>
                <br>
                <input class="plan_select_radio_button" name="package" type="radio" value="Standard">
            </div>

            <div class="plan">
                <div class="plan_value"><small>Starting From</small><br><b>$699</b></div>
                <div class="value_title">
                    <h2>PREMIUM</h2>
                    <p class="palan_describe_para">With Premium facilities</p>
                </div>
                <br>
                <hr><br>
                <p>3 Days Event<br><br>Premium Services Consultation<br><br>Breakfast,Lunch & Dinner Free for
                    Everyone<br><br>FREE Gifts for Kids</p>
                <br>
                <input class="plan_select_radio_button" type="radio" name="package" value="Premium">
            </div>
        </div>
        <hr><br>
        <div class="btn">
            <a href="#p_form" class="button" onclick="checkValiedpackage(event)">Go to Payment Form</a>
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

            <form name="Payment_form" id="p_form" action="" method="post">
                <h3>Payment Methods</h3>
                <div class="payment_method">
                    <input type="radio" name="payment_method" value="Debit or Credit Card">
                    <label for="Payment Methods">Debit or Credit Cards</label>
                    <br><br>
                    <input type="radio" name="payment_method" value="Paypal">
                    <label for="Payment Methods">Paypal</label>
                    <br><br>
                    <input type="radio" name="payment_method" value="Bank Transfers">
                    <label for="Payment Methods">Bank Transfers</label>
                    <br><br>
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
                    <button type="submit" value="submit" class="s_button" onclick="return validateForm(event)">Click to
                        submit!</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function checkValiedpackage(event) {
            const paymentMethods = document.getElementsByName('package');
            let selected = false;
            for (let i = 0; i < paymentMethods.length; i++) {
                if (paymentMethods[i].checked) {
                    selected = true;
                    break;
                }
            }
            if (!selected) {
                event.preventDefault();
                alert('Please select a Package to Continue ! Thank You !');
            }
        }

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
                alert('Please select a Package to Continue ! Thank You !');
                return false;
            }

            if (!paymentSelected) {
                event.preventDefault();
                alert('Please select a payment method to Continue ! Thank You !');
                return false;
            }

            return true;
        }
    </script>

    <?php require_once '../includes/footer.php'; ?>
</body>

</html>
