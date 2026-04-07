<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

/* =========================
   COUNTS
========================= */

// Users
$total_users = mysqli_num_rows(mysqli_query($con, "SELECT id FROM users"));
$total_recruiters = mysqli_num_rows(mysqli_query($con, "SELECT id FROM users WHERE role='Recruiter'"));
$total_normal_users = mysqli_num_rows(mysqli_query($con, "SELECT id FROM users WHERE role='User'"));

// Jobs
$total_jobs = mysqli_num_rows(mysqli_query($con, "SELECT id FROM jobs"));
$open_jobs = mysqli_num_rows(mysqli_query($con, "SELECT id FROM jobs WHERE selected_user_id IS NULL"));
$completed_jobs = mysqli_num_rows(mysqli_query($con, "SELECT id FROM jobs WHERE selected_user_id IS NOT NULL"));

// Skill Exchange
$total_skills = mysqli_num_rows(mysqli_query($con, "SELECT id FROM skill_exchange"));
$open_skills = mysqli_num_rows(mysqli_query($con, "SELECT id FROM skill_exchange WHERE status='open'"));
$completed_skills = mysqli_num_rows(mysqli_query($con, "SELECT id FROM skill_exchange WHERE status='completed'"));

// Courses
$total_courses = mysqli_num_rows(mysqli_query($con, "SELECT id FROM courses"));

/* =========================
   PIE CHART DATA
========================= */

// Total Recruiters
$total_recruiters = mysqli_num_rows(mysqli_query($con, "SELECT id FROM users WHERE role='Recruiter'"));

// Recruiters who posted jobs
$recruiters_posted = mysqli_num_rows(mysqli_query($con, "
    SELECT DISTINCT user_id FROM jobs
"));

// Recruiters who didn’t post
$recruiters_not_posted = $total_recruiters - $recruiters_posted;


// Total Users
$total_users = mysqli_num_rows(mysqli_query($con, "SELECT id FROM users"));

// Users who posted skill exchange
$users_posted_skill = mysqli_num_rows(mysqli_query($con, "
    SELECT DISTINCT user_id FROM skill_exchange
"));

// Users who didn’t post
$users_not_posted_skill = $total_users - $users_posted_skill;
?>

<main class="admin-content">

    <h3 class="mb-4 color">Dashboard Overview</h3>

    <div class="row g-4">

        <!-- Users -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-people"></i>
                <h4><?php echo $total_users; ?></h4>
                <p>Total Users</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-person-badge"></i>
                <h4><?php echo $total_recruiters; ?></h4>
                <p>Recruiters</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-person"></i>
                <h4><?php echo $total_normal_users; ?></h4>
                <p>Normal Users</p>
            </div>
        </div>

        <!-- Jobs -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-briefcase"></i>
                <h4><?php echo $total_jobs; ?></h4>
                <p>Total Jobs</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-folder2-open"></i>
                <h4><?php echo $open_jobs; ?></h4>
                <p>Open Jobs</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-check2-square"></i>
                <h4><?php echo $completed_jobs; ?>
                </h4>
                <p>Completed Jobs</p>
            </div>
        </div>

        <!-- Skill Exchange -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-lightning"></i>
                <h4><?php echo $total_skills; ?></h4>
                <p>Skill Exchange</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-unlock"></i>
                <h4><?php echo $open_skills; ?></h4>
                <p>Open Skills</p>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-check-circle"></i>
                <h4><?php echo $completed_skills; ?></h4>
                <p>Completed Skills</p>
            </div>
        </div>

        <!-- Courses -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-card">
                <i class="bi bi-book"></i>
                <h4><?php echo $total_courses; ?></h4>
                <p>Courses</p>
            </div>
        </div>

    </div>

    <div class="row g-4 mt-2">

        <!-- Chart 1 -->
        <div class="col-lg-6">
            <div class="chart-card">
                <h5 class="mb-3">Recruiter Job Activity</h5>
                <canvas id="jobChart"></canvas>
            </div>
        </div>

        <!-- Chart 2 -->
        <div class="col-lg-6">
            <div class="chart-card">
                <h5 class="mb-3">Skill Exchange Activity</h5>
                <canvas id="skillChart"></canvas>
            </div>
        </div>

    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

    // ================= Job Chart =================
    const jobChart = new Chart(document.getElementById('jobChart'), {
        type: 'doughnut',
        data: {
            labels: [
                'Posted Jobs',
                'Not Posted'
            ],
            datasets: [{
                data: [
                    <?php echo $recruiters_posted; ?>,
                    <?php echo $recruiters_not_posted; ?>
                ]
            }]
        },
        options: {
            cutout: '60%',
            plugins: {
                legend: {
                    labels: {
                        color: "white"
                    }
                }
            }
        }
    });


    // ================= Skill Chart =================
    const skillChart = new Chart(document.getElementById('skillChart'), {
        type: 'doughnut',
        data: {
            labels: [
                'Posted Skills',
                'Not Posted'
            ],
            datasets: [{
                data: [
                    <?php echo $users_posted_skill; ?>,
                    <?php echo $users_not_posted_skill; ?>
                ]
            }]
        },
        options: {
            cutout: '60%',
            plugins: {
                legend: {
                    labels: {
                        color: "white"
                    }
                }
            }
        }
    });

</script>

<?php include "includes/footer.php"; ?>