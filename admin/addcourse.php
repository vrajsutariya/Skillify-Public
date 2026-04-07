<?php
include "includes/header.php";

$con = mysqli_connect("localhost", "root", "", "skillify");

// Insert Course
if (isset($_POST['add_course'])) {

    $title = mysqli_real_escape_string($con, $_POST['title']);
    $skills = mysqli_real_escape_string($con, $_POST['skills']);
    $course_link = mysqli_real_escape_string($con, $_POST['course_link']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $duration = mysqli_real_escape_string($con, $_POST['duration']);

    if (!empty($title) && !empty($course_link) && !empty($price)) {

        mysqli_query($con, "
            INSERT INTO courses (title, skills, course_link, price, duration) 
            VALUES ('$title', '$skills', '$course_link', '$price', '$duration')
        ");

        echo "<script>window.location='courses.php';</script>";
    }
}
?>

<main class="admin-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="mb-4">
            <h3 class="color">Add New Course</h3>
        </div>

        <!-- Form Card -->
        <div class="p-4 shadow-sm border-0 rounded-3">

            <form method="POST" class="needs-validation" novalidate>

                <div class="row g-3">

                    <!-- Title -->
                    <div class="col-md-6">
                        <label class="form-label">Course Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter course title" required>

                        <div class="invalid-feedback">
                            Please enter course title.
                        </div>
                    </div>

                    <!-- Skills -->
                    <div class="col-md-6">
                        <label class="form-label">Skills (comma separated)</label>
                        <input type="text" name="skills" class="form-control" placeholder="e.g. HTML, CSS, JS" required>

                        <div class="invalid-feedback">
                            Please enter skills.
                        </div>
                    </div>

                    <!-- Course Link -->
                    <div class="col-md-6">
                        <label class="form-label">Course Link</label>
                        <input type="url" name="course_link" class="form-control" placeholder="Enter course URL"
                            required>

                        <div class="invalid-feedback">
                            Please enter valid course link.
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="col-md-3">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" class="form-control" placeholder="Enter price" required>

                        <div class="invalid-feedback">
                            Please enter price.
                        </div>
                    </div>

                    <!-- Duration -->
                    <div class="col-md-3">
                        <label class="form-label">Duration (Days)</label>
                        <input type="number" name="duration" class="form-control" placeholder="Enter duration" required>

                        <div class="invalid-feedback">
                            Please enter duration.
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex gap-2 mt-3 flex-wrap">
                        <button type="submit" name="add_course" class="btn btn-primary-custom px-4">
                            Add Course
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