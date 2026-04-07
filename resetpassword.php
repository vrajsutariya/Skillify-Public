<?php
session_start();

/* CHECK IF FORGET EMAIL COOKIE EXISTS */
if (!isset($_COOKIE['FORGET_EMAIL'])) {
    header("Location: login.php");
    exit();
}

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

$email = $_COOKIE['FORGET_EMAIL'];

/* RESET PASSWORD BUTTON CLICK */
if (isset($_POST['reset_password'])) {

    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {

        $alertType = "error";
        $alertTitle = "Password Not Match";
        $alertText = "Both passwords must be the same.";

    } else {

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $update = mysqli_query($con, "UPDATE users SET password='$hashedPassword' WHERE email='$email'");

        if ($update) {

            /* CLEAR FORGET EMAIL COOKIE */
            setcookie("FORGET_EMAIL", "", time() - 3600, "/");

            $alertType = "success";
            $alertTitle = "Password Changed!";
            $alertText = "Your password has been updated successfully.";

        } else {

            $alertType = "error";
            $alertTitle = "Error";
            $alertText = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password | Skillify</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" type="image/png" href="./images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/style.css">
</head>

<body>

    <div class="container min-vh-100 d-flex align-items-center">
        <div class="row w-100 align-items-center">

            <!-- LEFT SIDE -->
            <div class="col-lg-5 d-none d-lg-flex flex-column align-items-center text-center">
                <img src="images/resetpassword.gif" class="img-fluid" style="max-width:350px;">
                <h2 class="fw-bold mb-3">Create New Password</h2>
                <p class="text-secondary">
                    Set a strong new password to secure your Skillify account and continue your learning journey safely.
                </p>
            </div>

            <div class="col-lg-2"></div>

            <!-- RIGHT SIDE FORM -->
            <div class="col-lg-5">
                <div class="card card-custom shadow-lg p-4">

                    <h3 class="text-center mb-4" style="color:#b7ee73;">Reset Password</h3>

                    <form method="POST" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label class="form-label text-white">Password</label>
                            <input type="password" name="password" id="password" class="form-control" minlength="8"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid password.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Confirm Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control"
                                minlength="8" required>
                            <div class="invalid-feedback">
                                Please provide a valid confirm password.
                            </div>
                        </div>

                        <button type="submit" name="reset_password" class="btn btn-primary-custom w-100">
                            Update Password
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <script src="./public/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (!empty($alertType)) { ?>
        <script>
            Swal.fire({
                icon: '<?php echo $alertType; ?>',
                title: '<?php echo $alertTitle; ?>',
                text: '<?php echo $alertText; ?>',
                background: '#1a1a1a',
                color: '#ffffff',
                confirmButtonColor: '#b7ee73'
            }).then((result) => {

                <?php if ($alertType == "success") { ?>
                    window.location = "login.php";
                <?php } ?>

            });
        </script>
    <?php } ?>

</body>

</html>