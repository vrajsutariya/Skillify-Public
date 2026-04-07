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

$error = "";
$success = "";
$alertTitle = "";
$alertText = "";

/* DATABASE CONNECTION */
$con = mysqli_connect("localhost", "root", "", "skillify");

if (!$con) {
    die("Database connection failed");
}

$alertType = "";
$showOtpPopup = false;

/* STEP 1: REGISTER CLICK */
if (isset($_POST['register'])) {

    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $alertType = "error";
        $alertTitle = "Passwords do not match";
        $alertText = "Please check again";
    } else {

        $check = mysqli_query($con, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {

            $alertType = "warning";
            $alertTitle = "Email already exists";
            $alertText = "Use another email";

        } else {

            $otp = rand(100000, 999999);

            $_SESSION['otp'] = $otp;
            $_SESSION['email'] = $email;
            $_SESSION['password'] = password_hash($password, PASSWORD_DEFAULT);
            $_SESSION['role'] = $role;

            /* SEND EMAIL */
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
                $mail->Subject = "Verify Your Email - Skillify";
                $mail->Body = '
                    <!DOCTYPE html>
                    <html>
                    <head>
                    <meta charset="UTF-8">
                    <title>Verify Your Email - Skillify</title>
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
                            Secure Email Verification
                        </p>

                    </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                    <td style="padding:45px 35px;text-align:center;">

                        <h3 style="color:#ffffff;margin-bottom:15px;font-size:22px;">
                            Almost There! 🚀
                        </h3>

                        <p style="color:#cccccc;font-size:15px;line-height:1.6;margin-bottom:35px;">
                            Thank you for joining <strong style="color:#b7ee73;">Skillify</strong>.<br>
                            Enter the verification code below to activate your account.
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
                            If you didn’t request this registration, please ignore this email.
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
                $alertText = "Could not send OTP";
            }
        }
    }
}

/* STEP 2: VERIFY OTP */
if (isset($_POST['verify_otp'])) {

    if ($_POST['entered_otp'] == $_SESSION['otp']) {

        $email = $_SESSION['email'];
        $password = $_SESSION['password'];
        $role = $_SESSION['role'];

        mysqli_query($con, "INSERT INTO users (email,password,role) VALUES ('$email','$password','$role')");

        setcookie("SEC_LOGIN", $email, time() + (86400 * 30), "/");

        session_destroy();

        header("Location: home.php");
        exit();

    } else {
        $_SESSION['otp_error'] = "Invalid OTP. Try again!";
        $showOtpPopup = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register | Skillify</title>
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
                <img src="images/register.gif" class="img-fluid" style="max-width:350px;">
                <h2 class="fw-bold mb-3">Build Skills.<br>Grow Careers.</h2>
                <p class="text-secondary">
                    Join Skillify and connect learners with recruiters to exchange skills and grow professionally.
                </p>
            </div>

            <div class="col-lg-2"></div>

            <!-- RIGHT SIDE FORM -->
            <div class="col-lg-5">
                <div class="card card-custom shadow-lg p-4">

                    <h3 class="text-center mb-4" style="color:#b7ee73;">Sign Up</h3>

                    <form method="POST" class="needs-validation" id="registerForm" novalidate>

                        <div class="mb-3">
                            <label class="form-label  text-white">Role</label>
                            <select name="role" class="form-select" required>
                                <option value="User" <?php if (($_POST['role'] ?? '') == 'User')
                                    echo 'selected'; ?>>User
                                </option>
                                <option value="Recruiter" <?php if (($_POST['role'] ?? '') == 'Recruiter')
                                    echo 'selected'; ?>>Recruiter</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="<?php echo $_POST['email'] ?? ""; ?>" required>
                            <div class="invalid-feedback">
                                Please provide a valid email.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Password</label>
                            <input type="password" name="password" id="password" class="form-control" minlength="8"
                                value="<?php echo $_POST['password'] ?? ""; ?>" required>
                            <div class="invalid-feedback">
                                Please provide a valid password.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                minlength="8" value="<?php echo $_POST['confirm_password'] ?? ""; ?>" required>
                            <div class="invalid-feedback">
                                Please provide a valid confirm password.
                            </div>
                        </div>

                        <button type="submit" name="register" class="btn btn-primary-custom w-100">
                            Register Account
                        </button>

                        <div class="text-center mt-4">
                            <span class="text-secondary">Already have an account?</span>
                            <a href="login.php" class="login-link text-decoration-underline fw-semibold ms-1"
                                style="color:#b7ee73;">
                                Sign In
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <script src="./public/script.js"></script>

    <script>
        (function () {
            'use strict'
            const form = document.getElementById('registerForm');
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

    <!-- Password Match Validation -->
    <script>
        function validatePassword() {
            const pass = document.getElementById("password");
            const repass = document.getElementById("confirm_password");

            if (pass.value !== repass.value) {
                repass.classList.add("is-invalid");
                return false;
            } else {
                repass.classList.remove("is-invalid");
                return true;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if ($showOtpPopup) { ?>
        <script>
            function showOtpPopup(errorMsg = "") {
                Swal.fire({
                    title: 'Enter OTP',
                    input: 'text',
                    inputPlaceholder: 'Enter 6 digit OTP',
                    confirmButtonText: "Verify",
                    background: '#1a1a1a',
                    color: '#ffffff',
                    confirmButtonColor: '#b7ee73',
                    allowOutsideClick: false,
                    showCancelButton: true,
                    inputAttributes: {
                        maxlength: 6
                    },
                    footer: errorMsg ? `<span style="color:#ff6b6b;">${errorMsg}</span>` : '',
                    preConfirm: (otp) => {
                        if (!otp) {
                            Swal.showValidationMessage('OTP is required');
                            return false;
                        }

                        return otp;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        const form = document.createElement('form');
                        form.method = 'POST';

                        form.innerHTML = `
                <input type="hidden" name="entered_otp" value="${result.value}">
                <input type="hidden" name="verify_otp">
            `;

                        document.body.appendChild(form);
                        document.getElementById("mailLoader").style.display = "flex";
                        form.submit();
                    }
                });
            }

            // Call popup
            showOtpPopup("<?php echo $_SESSION['otp_error'] ?? ''; ?>");
        </script>
        <?php unset($_SESSION['otp_error']); ?>
    <?php } ?>

    <?php if (!empty($alertType) && !$showOtpPopup) { ?>
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

    <script>

        function showLoader() {
            document.getElementById("mailLoader").style.display = "flex";
        }

    </script>

</body>

</html>