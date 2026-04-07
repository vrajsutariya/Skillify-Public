<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Get Course ID
$id = $_GET['id'];

// Fetch course data
$course = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM courses WHERE id = '$id'"));

// Update Course
if (isset($_POST['update_course'])) {

    $title = mysqli_real_escape_string($con, $_POST['title']);
    $skills = mysqli_real_escape_string($con, $_POST['skills']);
    $course_link = mysqli_real_escape_string($con, $_POST['course_link']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $duration = mysqli_real_escape_string($con, $_POST['duration']);

    if (!empty($title) && !empty($course_link) && !empty($price)) {

        mysqli_query($con, "
            UPDATE courses SET 
                title='$title',
                skills='$skills',
                course_link='$course_link',
                price='$price',
                duration='$duration'
            WHERE id='$id'
        ");

        echo "<script>window.location='courses.php';</script>";
    }
}
?>

<main class="admin-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="mb-4">
            <h3 class="color">Edit Course</h3>
        </div>

        <!-- Form Card -->
        <div class="p-4 shadow-sm border-0 rounded-3">

            <form method="POST" class="needs-validation" novalidate>

                <div class="row g-3">

                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label">Course Title</label>
                        <input type="text" name="title" class="form-control" value="<?= $course['title'] ?>" required>

                        <div class="invalid-feedback">
                            Please enter course title.
                        </div>
                    </div>

                    <!-- Skills -->
                    <div class="col-md-6">
                        <label class="form-label">Skills</label>
                        <input type="text" name="skills" class="form-control" value="<?= $course['skills'] ?>">

                        <div class="invalid-feedback">
                            Please enter skills.
                        </div>
                    </div>

                    <!-- Course Link -->
                    <div class="col-md-6">
                        <label class="form-label">Course Link</label>
                        <input type="url" name="course_link" class="form-control" value="<?= $course['course_link'] ?>"
                            required>

                        <div class="invalid-feedback">
                            Please enter valid course link.
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="col-md-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" class="form-control" value="<?= $course['price'] ?>" required>

                        <div class="invalid-feedback">
                            Please enter price.
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="col-md-3">
                        <label class="form-label">Duration (Days)</label>
                        <input type="number" name="duration" class="form-control" value="<?= $course['duration'] ?>">

                        <div class="invalid-feedback">
                            Please enter duration.
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex gap-2 mt-3 flex-wrap">
                        <button type="submit" name="update_course" class="btn btn-primary-custom px-4">
                            Update Course
                        </button>

                        <a href="courses.php" class="btn btn-outline-secondary px-4">
                            Cancel
                        </a>
                    </div>

                </div>

            </form>

        </div>

    </div>
</main>

<?php include "includes/footer.php"; ?>