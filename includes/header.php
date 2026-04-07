<?php
if (isset($_COOKIE['ADMIN'])) {
    header("Location: admin/dashboard.php");
    exit();
}

if (!isset($_COOKIE['SEC_LOGIN'])) {
    header("Location: login.php");
    exit();
}
$con = mysqli_connect("localhost", "root", "", "skillify");
$email = $_COOKIE['SEC_LOGIN'];
$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$email'"));
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skillify</title>

    <link rel="icon" type="image/png" href="./images/logo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./public/style.css">

    <script>
        function logoutUser() {
            document.getElementById("pageLoader").style.display = "flex";

            setTimeout(function () {
                window.location.href = "logout.php";
            }, 4000);
        }
    </script>
</head>

<body>
    <div id="pageLoader">
        <img src="./images/loader.gif" alt="Loading">
    </div>

    <div id="mailLoader">
        <img src="./images/mail.gif" alt="Loading">
    </div>

    <!-- <div id="errorLoader">
        <img src="./images/error.gif" alt="Loading">
    </div> -->

    <header>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top skillify-navbar">
            <div class="container-fluid">

                <!-- Left : Logo -->
                <a class="navbar-brand d-flex align-items-center" href="home.php">
                    <img src="./images/logo.png" alt="Skillify Logo" class="logo me-2">
                    <span class="brand-text">Skillify</span>
                </a>

                <!-- Mobile Toggle Button -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#skillifyNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Center + Right Content -->
                <div class="collapse navbar-collapse justify-content-between" id="skillifyNavbar">

                    <!-- Center Menu -->
                    <ul class="navbar-nav mx-auto text-center">
                        <li class="nav-item">
                            <a class="nav-link" href="home.php">Home</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href=<?php echo $user['role'] == 'User' ? "jobs.php" : "hire.php"; ?>><?php echo $user['role'] == 'User' ? 'Jobs' : 'Hire'; ?></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="skillexchange.php">Skill Exchange</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                        </li>
                    </ul>

                    <!-- Right : Avatar Dropdown -->
                    <div class="dropdown text-center">
                        <img src="./images/avtar.gif" class="avatar dropdown-toggle" data-bs-toggle="dropdown">

                        <ul class="dropdown-menu dropdown-menu-end skillify-dropdown">
                            <li>
                                <a class="dropdown-item" href="profile.php">Profile</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="course.php">Course</a>
                            </li>
                            <li>
                                <a class="dropdown-item text-danger" href="#" onclick="logoutUser()">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>