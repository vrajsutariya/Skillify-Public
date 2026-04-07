<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);

    mysqli_query($con, "DELETE FROM skills WHERE id='$id'");

    header("Location: skills.php");
    exit();
}

// Fetch skills
$skills = mysqli_query($con, "SELECT * FROM skills ORDER BY id DESC");
?>

<main class="admin-content">
    <div class="container-fluid">

        <!-- Header + Add Button -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h3 class="color mb-0">Skill Management</h3>

            <a href="addskill.php" class="btn btn-primary">
                + Add Skill
            </a>
        </div>

        <!-- Desktop Table -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Skill Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i = 1;
                    while ($row = mysqli_fetch_assoc($skills)) { ?>
                        <tr>
                            <td><?= $i++ ?></td>

                            <!-- Skill Name -->
                            <td class="fw-semibold">
                                <?= $row['name'] ?>
                            </td>

                            <!-- Actions -->
                            <td>
                                <a href="editskill.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning me-2">
                                    Edit
                                </a>

                                <a href="skills.php?delete_id=<?= $row['id'] ?>"
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
            <?php mysqli_data_seek($skills, 0);
            while ($row = mysqli_fetch_assoc($skills)) { ?>

                <div class="user-card mb-3">

                    <!-- Skill Name -->
                    <h6 class="mb-2 text-dark fw-semibold">
                        <?= $row['name'] ?>
                    </h6>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <a href="editskill.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning w-50">
                            Edit
                        </a>

                        <a href="skills.php?delete_id=<?= $row['id'] ?>"
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

            const link = this.getAttribute('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "This skill will be permanently deleted!",
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