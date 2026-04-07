<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Delete Job
if (isset($_GET['delete_id'])) {

    $delete_id = (int) $_GET['delete_id'];

    mysqli_query($con, "DELETE FROM jobs WHERE id = '$delete_id'");

    header("Location: jobs.php");
}

// Fetch jobs with user info
$jobs = mysqli_query($con, "
    SELECT jobs.*, users.firstname, users.lastname, users.image 
    FROM jobs 
    JOIN users ON jobs.user_id = users.id 
    ORDER BY jobs.id DESC
");
?>

<main class="admin-content">
    <div class="container-fluid">

        <h3 class="mb-4 color">Jobs Management</h3>

        <!-- Desktop Table -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Posted By</th>
                        <th>Position</th>
                        <th>Type</th>
                        <th>Experience</th>
                        <th>Location</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i = 1;
                    while ($row = mysqli_fetch_assoc($jobs)) { ?>
                        <tr>
                            <td><?= $i++ ?></td>

                            <!-- User -->
                            <td class="d-flex align-items-center gap-3">
                                <img src="<?= !empty($row['image']) ? "../images/profile/" . $row['image'] : "../images/avtar.gif" ?>"
                                    class="user-img">
                                <div class="fw-semibold">
                                    <?= $row['firstname'] . " " . $row['lastname'] ?>
                                </div>
                            </td>

                            <!-- Job Info -->
                            <td><?= $row['position'] ?></td>

                            <td>
                                <span class="badge bg-success">
                                    <?= $row['job_type'] ?>
                                </span>
                            </td>

                            <td><?= $row['experience'] ?> yrs</td>

                            <td><?= $row['location'] ?></td>

                            <!-- Actions -->
                            <td>
                                <a href="editjob.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning me-2">
                                    Edit
                                </a>
                                <a href="jobs.php?delete_id=<?= $row['id'] ?>"
                                    class="btn btn-sm btn-outline-danger delete-btn">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="d-md-none">
            <?php mysqli_data_seek($jobs, 0);
            while ($row = mysqli_fetch_assoc($jobs)) { ?>

                <div class="user-card mb-3">

                    <!-- User Info -->
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="<?= !empty($row['image']) ? "../images/profile/" . $row['image'] : "../images/avtar.gif" ?>"
                            class="user-img">

                        <div>
                            <h6 class="mb-0 text-dark">
                                <?= $row['firstname'] . " " . $row['lastname'] ?>
                            </h6>
                            <small class="text-muted"><?= $row['position'] ?></small>
                        </div>
                    </div>

                    <!-- Job Info -->
                    <p class="mb-1 text-secondary">
                        <strong>Type:</strong> <?= $row['job_type'] ?>
                    </p>

                    <p class="mb-1 text-secondary">
                        <strong>Experience:</strong> <?= $row['experience'] ?> yrs
                    </p>

                    <p class="mb-2 text-secondary">
                        <strong>Location:</strong> <?= $row['location'] ?>
                    </p>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <a href="editjob.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning w-50">
                            Edit
                        </a>
                        <a href="jobs.php?delete_id=<?= $row['id'] ?>"
                            class="btn btn-sm btn-outline-danger delete-btn w-50">
                            Delete
                        </a>
                    </div>

                </div>

            <?php } ?>
        </div>

    </div>
</main>

<!-- SweetAlert Delete -->
<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            let link = this.getAttribute('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "This job will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = link;
                }
            });
        });
    });
</script>

<?php include "includes/footer.php"; ?>