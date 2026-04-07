<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Delete Course
if (isset($_GET['delete_id'])) {

    $delete_id = (int) $_GET['delete_id'];

    mysqli_query($con, "DELETE FROM courses WHERE id = '$delete_id'");

    header("Location: courses.php");
}

// Fetch courses
$courses = mysqli_query($con, "SELECT * FROM courses ORDER BY id DESC");
?>

<main class="admin-content">
    <div class="container-fluid">

        <!-- Header + Add Button -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h3 class="color mb-0">Course Management</h3>

            <a href="addcourse.php" class="btn btn-primary">
                + Add Course
            </a>
        </div>

        <!-- Desktop Table -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Skills</th>
                        <th>Price</th>
                        <th>Duration</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i = 1;
                    while ($row = mysqli_fetch_assoc($courses)) { ?>
                        <tr>
                            <td><?= $i++ ?></td>

                            <!-- Title -->
                            <td class="fw-semibold">
                                <?= $row['title'] ?>
                            </td>

                            <!-- Skills -->
                            <td>
                                <?= !empty($row['skills']) ? $row['skills'] : 'N/A' ?>
                            </td>

                            <!-- Price -->
                            <td>
                                ₹<?= number_format($row['price']) ?>
                            </td>

                            <!-- Duration -->
                            <td>
                                <?= $row['duration'] ? $row['duration'] . " Days" : 'N/A' ?>
                            </td>

                            <!-- Date -->
                            <td>
                                <?= date("d M Y", strtotime($row['created_at'])) ?>
                            </td>

                            <!-- Actions -->
                            <td>
                                <a href="editcourse.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning me-2">
                                    Edit
                                </a>

                                <a href="courses.php?delete_id=<?= $row['id'] ?>"
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
            <?php mysqli_data_seek($courses, 0);
            while ($row = mysqli_fetch_assoc($courses)) { ?>

                <div class="user-card mb-3">

                    <!-- Title -->
                    <h6 class="mb-1 text-dark fw-semibold">
                        <?= $row['title'] ?>
                    </h6>

                    <!-- Skills -->
                    <p class="mb-1 text-secondary">
                        <strong>Skills:</strong>
                        <?= !empty($row['skills']) ? $row['skills'] : 'N/A' ?>
                    </p>

                    <!-- Price -->
                    <p class="mb-1 text-secondary">
                        <strong>Price:</strong>
                        ₹<?= number_format($row['price']) ?>
                    </p>

                    <!-- Duration -->
                    <p class="mb-1 text-secondary">
                        <strong>Duration:</strong>
                        <?= $row['duration'] ? $row['duration'] . " Days" : 'N/A' ?>
                    </p>

                    <!-- Date -->
                    <p class="mb-2 text-secondary">
                        <strong>Date:</strong>
                        <?= date("d M Y", strtotime($row['created_at'])) ?>
                    </p>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <a href="editcourse.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning w-50">
                            Edit
                        </a>

                        <a href="courses.php?delete_id=<?= $row['id'] ?>"
                            class="btn btn-sm btn-outline-danger delete-btn w-50">
                            Delete
                        </a>
                    </div>

                </div>

            <?php } ?>
        </div>

    </div>
</main>

<script>
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();

            let link = this.getAttribute('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "This course will be permanently deleted!",
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