<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/src/SMTP.php';
require 'config.php';


/* AUTO LOGIN CHECK */
if (isset($_COOKIE['ADMIN'])) {
    header("Location: admin/dashboard.php");
    exit();
}

if (isset($_COOKIE['SEC_LOGIN'])) {
    header("Location: home.php");
    exit();
}

$con = mysqli_connect("localhost", "root", "", "skillify");

if (!$con) {
    die("Database connection failed");
}

$alertType = "";
$alertTitle = "";
$alertText = "";

$showOtpPopup = false;
$otpError = false;

/* STEP 1: SEND OTP */
if (isset($_POST['send_otp'])) {

    $email = mysqli_real_escape_string($con, $_POST['email']);

    $query = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) == 0) {

        $alertType = "error";
        $alertTitle = "Email Not Found";
        $alertText = "This email is not registered.";

    } else {

        $otp = rand(100000, 999999);

        $_SESSION['forget_otp'] = $otp;
        $_SESSION['forget_email'] = $email;

        /* SEND MAIL */
        $mail = new PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom($_ENV['SMTP_USER'], "Skillify");
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Password Reset OTP - Skillify";
            $mail->Body = '
                <!DOCTYPE html>
                <html>
                <head>
                <meta charset="UTF-8">
                <title>Password Reset - Skillify</title>
                </head>
                <body style="margin:0;padding:0;background:#0f0f0f;font-family:Segoe UI,Arial,sans-serif;">

                <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 15px;">
                <tr>
                <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" 
                style="background:#1a1a1a;border-radius:18px;overflow:hidden;
                box-shadow:0 20px 50px rgba(0,0,0,0.6);">

                <!-- HEADER -->
                <tr>
                <td align="center" style="padding:35px 20px;background:#111111;">

                    <table align="center" cellpadding="0" cellspacing="0" style="margin:0 auto 20px auto;">
                    <tr>
                    <td align="center">

                        <div style="
                            width:80px;
                            height:80px;
                            border-radius:50%;
                            background:#111111;
                            border:3px solid #b7ee73;
                            padding:7px;
                            box-shadow:0 0 25px rgba(183,238,115,0.6);
                            display:inline-block;">

                            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png"
                                width="70"
                                height="70"
                                style="border-radius:50%; display:block; margin-top:7%;"
                                alt="Skillify Logo">
                        </div>

                    </td>
                    </tr>
                    </table>

                    <h2 style="color:#b7ee73;margin:0;font-size:26px;letter-spacing:1px;">
                        Skillify
                    </h2>
                    <p style="color:#bbbbbb;font-size:14px;margin-top:6px;">
                        Password Reset Verification
                    </p>

                </td>
                </tr>

                <!-- BODY -->
                <tr>
                <td style="padding:45px 35px;text-align:center;">

                    <h3 style="color:#ffffff;margin-bottom:15px;font-size:22px;">
                        Reset Your Password 🔐
                    </h3>

                    <p style="color:#cccccc;font-size:15px;line-height:1.6;margin-bottom:35px;">
                        We received a request to reset your password.<br>
                        Use the OTP below to continue.
                    </p>

                    <!-- OTP BOX -->
                    <div style="
                        display:inline-block;
                        background:#111111;
                        border:2px solid #b7ee73;
                        padding:18px 45px;
                        border-radius:12px;
                        font-size:32px;
                        font-weight:700;
                        letter-spacing:8px;
                        color:#b7ee73;
                        box-shadow:0 0 25px rgba(183,238,115,0.6);
                        margin-bottom:35px;">
                        ' . $otp . '
                    </div>

                    <p style="color:#aaaaaa;font-size:14px;margin-bottom:10px;">
                        ⏳ This OTP is valid for <strong>10 minutes</strong>.
                    </p>

                    <p style="color:#777777;font-size:13px;">
                        If you didn’t request a password reset, please ignore this email.
                    </p>

                </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                <td align="center" style="padding:25px;background:#111111;border-top:1px solid #2a2a2a;">

                    <p style="color:#777;font-size:12px;margin:0;">
                        © ' . date("Y") . ' Skillify. All rights reserved.
                    </p>

                    <p style="color:#555;font-size:11px;margin-top:6px;">
                        Empowering Skills. Connecting Careers.
                    </p>

                </td>
                </tr>

                </table>

                </td>
                </tr>
                </table>

                </body>
                </html>
            ';

            $mail->send();

            $showOtpPopup = true;

        } catch (Exception $e) {

            $alertType = "error";
            $alertTitle = "Email Failed";
            $alertText = "Could not send OTP.";
        }
    }
}


/* STEP 2: VERIFY OTP */
if (isset($_POST['verify_otp'])) {

    if ($_POST['entered_otp'] == $_SESSION['forget_otp']) {

        $email = $_SESSION['forget_email'];

        setcookie("FORGET_EMAIL", $email, time() + 600, "/"); // 10 min

        unset($_SESSION['forget_otp']);

        echo "<script>
            window.location='resetpassword.php';
        </script>";
        exit();

    } else {
        $otpError = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password | Skillify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="./images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/style.css">
</head>

<body>

    <div id="mailLoader">
        <img src="./images/mail.gif" alt="Loading">
    </div>

    <div class="container min-vh-100 d-flex align-items-center">
        <div class="row w-100 align-items-center">

            <!-- LEFT SIDE -->
            <div class="col-lg-5 d-none d-lg-flex flex-column align-items-center text-center">
                <img src="images/forgetpassword.gif" class="img-fluid mb-3" style="max-width:350px;">
                <h2 class="fw-bold mb-3">Forgot Password</h2>
                <p class="text-secondary">
                    Enter the email address associated with your account and we will send you OTP to reset your
                    password.
                </p>
            </div>

            <div class="col-lg-2"></div>

            <!-- RIGHT SIDE FORM -->
            <div class="col-lg-5">
                <div class="card card-custom shadow-lg p-4">

                    <h3 class="text-center mb-4" style="color:#b7ee73;">Forgot Password</h3>

                    <form method="POST" id="forgetpasswordform" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label class="form-label text-white">Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback">
                                Please provide a valid email.
                            </div>
                        </div>

                        <button type="submit" name="send_otp" class="btn btn-primary-custom w-100">
                            Send OTP
                        </button>

                        <div class="text-center mt-4">
                            <span class="text-secondary">Not registered?</span>
                            <a href="register.php" class="login-link text-decoration-none fw-semibold ms-1"
                                style="color:#b7ee73;">
                                Register here
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <script src="./public/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        (function () {
            'use strict'
            const form = document.getElementById('forgetpasswordform');
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                else {
                    document.getElementById("mailLoader").style.display = "flex";
                }

                form.classList.add('was-validated');
            }, false)

        })();
    </script>

    <?php if (!empty($alertType)) { ?>
        <script>
            Swal.fire({
                icon: '<?php echo $alertType; ?>',
                title: '<?php echo $alertTitle; ?>',
                text: '<?php echo $alertText; ?>',
                background: '#1a1a1a',
                color: '#ffffff',
                confirmButtonColor: '#b7ee73'
            });
        </script>
    <?php } ?>

    <?php if ($otpError) { ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid OTP',
                text: 'Please enter correct OTP.',
                background: '#1a1a1a',
                color: '#ffffff',
                confirmButtonColor: '#b7ee73'
            }).then(() => {
                openOtpPopup();
            });
        </script>
    <?php } ?>

    <?php if ($showOtpPopup || $otpError) { ?>
        <script>

            function openOtpPopup() {
                Swal.fire({
                    title: 'Enter OTP',
                    input: 'text',
                    inputPlaceholder: 'Enter 6 digit OTP',
                    confirmButtonText: "Verify",
                    showCancelButton: true,
                    cancelButtonText: "Cancel",
                    background: '#1a1a1a',
                    color: '#ffffff',
                    confirmButtonColor: '#b7ee73',
                    cancelButtonColor: '#555',
                    allowOutsideClick: false,
                    inputAttributes: {
                        maxlength: 6
                    },
                    preConfirm: (otp) => {
                        if (!otp) {
                            Swal.showValidationMessage('OTP is required')
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        const form = document.createElement('form');
                        form.method = 'POST';

                        const otpInput = document.createElement('input');
                        otpInput.type = 'hidden';
                        otpInput.name = 'entered_otp';
                        otpInput.value = result.value;

                        const verifyBtn = document.createElement('input');
                        verifyBtn.type = 'hidden';
                        verifyBtn.name = 'verify_otp';

                        form.appendChild(otpInput);
                        form.appendChild(verifyBtn);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            <?php if ($showOtpPopup) { ?>
                openOtpPopup();
            <?php } ?>

        </script>
    <?php } ?>

</body>

</html>