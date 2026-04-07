<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Get User ID
$id = $_GET['id'];

// Fetch user data
$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE id='$id'"));

// Update User
if (isset($_POST['update_user'])) {

    $firstname = mysqli_real_escape_string($con, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($con, $_POST['lastname']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $address = mysqli_real_escape_string($con, $_POST['address']);
    $role = mysqli_real_escape_string($con, $_POST['role']);

    if (!empty($firstname) && !empty($email) && !empty($mobile)) {

        mysqli_query($con, "
            UPDATE users SET
                firstname='$firstname',
                lastname='$lastname',
                email='$email',
                mobile='$mobile',
                address='$address',
                role='$role'
            WHERE id='$id'
        ");

        echo "<script>window.location='users.php';</script>";
    }
}
?>

<main class="admin-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="mb-4">
            <h3 class="color fw-bold">Edit User</h3>
        </div>

        <!-- Form Card -->
        <div class="p-4 shadow-sm border-0 rounded-3">

            <form method="POST" class="needs-validation" novalidate>

                <div class="row g-3">

                    <!-- First Name -->
                    <div class="col-md-6">
                        <label class="form-label">First Name</label>
                        <input type="text" name="firstname" class="form-control" value="<?= $user['firstname'] ?>"
                            required>

                        <div class="invalid-feedback">
                            Please enter first name.
                        </div>
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="lastname" class="form-control" value="<?= $user['lastname'] ?>">
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $user['email'] ?>" required>

                        <div class="invalid-feedback">
                            Please enter valid email.
                        </div>
                    </div>

                    <!-- Mobile -->
                    <div class="col-md-6">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control" value="<?= $user['mobile'] ?>" required>

                        <div class="invalid-feedback">
                            Please enter mobile number.
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="col-md-12">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3"><?= $user['address'] ?></textarea>
                    </div>

                    <!-- Role -->
                    <div class="col-md-6">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="User" <?= $user['role'] == 'User' ? 'selected' : '' ?>>User</option>
                            <option value="Recruiter" <?= $user['role'] == 'Recruiter' ? 'selected' : '' ?>>Recruiter
                            </option>
                        </select>

                        <div class="invalid-feedback">
                            Please select role.
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex gap-2 mt-3 flex-wrap">

                        <button type="submit" name="update_user" class="btn btn-primary-custom px-4">
                            Update User
                        </button>

                        <a href="users.php" class="btn btn-outline-secondary px-4">
                            Cancel
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>
</main>

<?php include "includes/footer.php"; ?>