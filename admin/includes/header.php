<?php
if (!isset($_COOKIE['ADMIN'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Skillify</title>

    <link rel="icon" href="../images/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="./public/style.css">
</head>

<body>

    <div class="admin-wrapper">

        <!-- Sidebar -->
        <aside class="admin-sidebar">

            <!-- Logo -->
            <div class="sidebar-header">
                <img src="../images/logo.png" class="logo">
                <span>Skillify Admin</span>
            </div>

            <!-- Menu -->
            <ul class="sidebar-menu">

                <li><a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a></li>

                <li><a href="users.php"><i class="bi bi-people"></i> Users</a></li>

                <li><a href="jobs.php"><i class="bi bi-briefcase"></i> Jobs</a></li>

                <li><a href="skillexchange.php"><i class="bi bi-arrow-left-right"></i> Skill Exchange</a></li>

                <li><a href="courses.php"><i class="bi bi-book"></i> Courses</a></li>

                <li><a href="skills.php"><i class="bi bi-lightning"></i> Skills</a></li>

                <li class="logout">
                    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>

            </ul>
        </aside>


        <!-- Main Content -->
        <div class="admin-main">

            <!-- Top Navbar -->
            <header class="admin-topbar d-flex justify-content-between align-items-center">

                <button class="menu-toggle d-md-none" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>

                <h5 class="mb-0">Admin Dashboard</h5>

                <div class="admin-profile">
                    <img src="../images/avtar.gif">
                </div>

            </header>