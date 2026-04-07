<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Delete User
if (isset($_GET['delete_id'])) {

    $delete_id = (int) $_GET['delete_id'];

    mysqli_query($con, "DELETE FROM users WHERE id='$delete_id'");

    header("Location: users.php");
}

// Fetch users
$users = mysqli_query($con, "SELECT * FROM users ORDER BY id DESC");
?>

<main class="admin-content">
    <div class="container-fluid">

        <h3 class="mb-4 color">Users Management</h3>

        <!-- Desktop Table -->
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle custom-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    while ($row = mysqli_fetch_assoc($users)) { ?>
                        <tr>
                            <td><?= $i++ ?></td>

                            <td class="d-flex align-items-center gap-3">
                                <img src="<?= !empty($row['image']) ? "../images/profile/" . $row['image'] : "../images/avtar.gif" ?>"
                                    class="user-img">
                                <div>
                                    <div class="fw-semibold">
                                        <?= $row['firstname'] . " " . $row['lastname'] ?>
                                    </div>
                                </div>
                            </td>

                            <td><?= $row['email'] ?></td>
                            <td><?= $row['mobile'] ?></td>

                            <td>
                                <span class="badge bg-success">
                                    <?= ucfirst($row['role']) ?>
                                </span>
                            </td>

                            <td>
                                <a href="edituser.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning me-2">
                                    Edit
                                </a>
                                <a href="users.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger delete-btn">
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
            <?php mysqli_data_seek($users, 0);
            while ($row = mysqli_fetch_assoc($users)) { ?>
                <div class="user-card mb-3">

                    <div class="d-flex align-items-center gap-3 mb-2">
                        <img src="<?= !empty($row['image']) ? "../images/profile/" . $row['image'] : '../images/avtar.gif' ?>"
                            class="user-img">
                        <div>
                            <h6 class="mb-0 text-dark">
                                <?= $row['firstname'] . " " . $row['lastname'] ?>
                            </h6>
                            <small class="text-muted"><?= $row['email'] ?></small>
                        </div>
                    </div>

                    <p class="mb-1 text-secondary"><strong>Mobile:</strong> <?= $row['mobile'] ?></p>
                    <p class="mb-2">
                        <strong class="text-secondary">Role:</strong>
                        <span class="badge bg-success">
                            <?= ucfirst($row['role']) ?>
                        </span>
                    </p>

                    <div class="d-flex gap-2">
                        <a href="edituser.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-outline-warning w-50">
                            Edit
                        </a>
                        <a href="users.php?delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger w-50 delete-btn">
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
                text: "This user will be permanently deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
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