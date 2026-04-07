<?php
session_start();

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

/* LOGIN BUTTON CLICK */
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];

    if ($email == "admin@skillify.com" && $password == "Admin@123") {
        setcookie("ADMIN", $email, time() + (86400 * 30), "/");
        header("Location: admin/dashboard.php");
    }

    $query = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");

    if (mysqli_num_rows($query) == 0) {

        $alertType = "error";
        $alertTitle = "Email Not Found";
        $alertText = "This email is not registered.";

    } else {

        $user = mysqli_fetch_assoc($query);

        if (!password_verify($password, $user['password'])) {

            $alertType = "error";
            $alertTitle = "Wrong Password";
            $alertText = "Password does not match.";

        } else {

            setcookie("SEC_LOGIN", $email, time() + (86400 * 30), "/");

            header("Location: home.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Log In | Skillify</title>
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
                <img src="images/login.gif" class="img-fluid" style="max-width:350px;">
                <h2 class="fw-bold mb-3">Welcome Back</h2>
                <p class="text-secondary">
                    Log in to Skillify and continue building skills, connecting with recruiters and growing your
                    career.
                </p>
            </div>

            <div class="col-lg-2"></div>

            <!-- RIGHT SIDE FORM -->
            <div class="col-lg-5">
                <div class="card card-custom shadow-lg p-4">

                    <h3 class="text-center mb-4" style="color:#b7ee73;">Sign In</h3>

                    <form method="POST" class="needs-validation" novalidate>

                        <div class="mb-3">
                            <label class="form-label text-white">Email</label>
                            <input type="email" name="email" class="form-control" required>
                            <div class="invalid-feedback">
                                Please provide a valid email.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white">Password</label>
                            <input type="password" name="password" id="password" class="form-control" minlength="8"
                                required>
                            <div class="invalid-feedback">
                                Please provide a valid password.
                            </div>
                        </div>

                        <button type="submit" name="login" class="btn btn-primary-custom w-100">
                            Login Account
                        </button>

                        <div class="text-center mt-4">
                            <span class="text-secondary">Forgot password?</span>
                            <a href="forgetpassword.php" class="login-link text-decoration-underline fw-semibold ms-1"
                                style="color:#b7ee73;">
                                Forgot password
                            </a>
                        </div>

                        <div class="text-center mt-1">
                            <span class="text-secondary">Don't have an account?</span>
                            <a href="register.php" class="login-link text-decoration-underline fw-semibold ms-1"
                                style="color:#b7ee73;">
                                Create an Account
                            </a>
                        </div>

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
            });
        </script>
    <?php } ?>

</body>

</html>