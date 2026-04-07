<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Delete Skill Exchange
if (isset($_GET['delete_id'])) {

    $delete_id = (int) $_GET['delete_id'];

    mysqli_query($con, "DELETE FROM skill_exchange WHERE id = '$delete_id'");

    header("Location: skillexchange.php");
}

// Fetch skill exchange with user + skill names
$skills = mysqli_query($con, "
    SELECT se.*, 
           u.firstname, u.lastname, u.image,
           ls.name AS learning_skill,
           ks.name AS known_skill
    FROM skill_exchange se
    JOIN users u ON se.user_id = u.id
    LEFT JOIN skills ls ON se.learning_skill_id = ls.id
    LEFT JOIN skills ks ON se.known_skill_id = ks.id
    ORDER BY se.id DESC
");
?>

<main class="admin-content">
    <div class="container-fluid">

        <h3 class="mb-4 color">Skill Exchange Management</h3>

        <!-- Desktop Table -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Learning</th>
                        <th>Teaching</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $i = 1;
                    while ($row = mysqli_fetch_assoc($skills)) { ?>
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

                            <!-- Skills -->
                            <td><?= $row['learning_skill'] ?? 'N/A' ?></td>
                            <td><?= $row['known_skill'] ?? 'N/A' ?></td>

                            <!-- Status -->
                            <td>
                                <span class="badge <?= $row['status'] == 'open' ? 'bg-success' : 'bg-secondary' ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>

                            <!-- Date -->
                            <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>

                            <!-- Actions -->
                            <td>
                                <a href="editskillexchange.php?id=<?= $row['id'] ?>"
                                    class="btn btn-sm btn-outline-warning me-2">
                                    Edit
                                </a>

                                <a href="skillexchange.php?delete_id=<?= $row['id'] ?>"
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

                    <!-- User Info -->
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="<?= !empty($row['image']) ? "../images/profile/" . $row['image'] : "../images/avtar.gif" ?>"
                            class="user-img">

                        <div>
                            <h6 class="mb-0 text-dark">
                                <?= $row['firstname'] . " " . $row['lastname'] ?>
                            </h6>
                            <small class="text-muted">
                                <?= $row['learning_skill'] ?? 'N/A' ?> → <?= $row['known_skill'] ?? 'N/A' ?>
                            </small>
                        </div>
                    </div>

                    <!-- Info -->
                    <p class="mb-1 text-secondary">
                        <strong>Status:</strong> <?= ucfirst($row['status']) ?>
                    </p>

                    <p class="mb-2 text-secondary">
                        <strong>Date:</strong> <?= date("d M Y", strtotime($row['created_at'])) ?>
                    </p>

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <a href="editskillexchange.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning w-50">
                            Edit
                        </a>

                        <a href="skillexchange.php?delete_id=<?= $row['id'] ?>"
                            class="btn btn-sm btn-outline-danger w-50 delete-btn">
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
                text: "This skill exchange will be permanently deleted!",
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