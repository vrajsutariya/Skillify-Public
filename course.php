<?php include "./includes/header.php"; ?>

<?php

require 'PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/src/SMTP.php';
require 'config.php';

$con = mysqli_connect("localhost", "root", "", "skillify");

$email = $_COOKIE['SEC_LOGIN'];

$user = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM users WHERE email='$email'"));

$user_id = $user['id'];

/* ================= ENROLLED COURSES ================= */

$enrolledCourses = [];

if ($user['enrolled_courses']) {
    $enrolledCourses = json_decode($user['enrolled_courses'], true);
}

/* ================= PAYMENT SUCCESS ================= */

if (isset($_POST['course_id'])) {

    $course_id = (int) $_POST['course_id'];

    if (!in_array($course_id, $enrolledCourses)) {

        $enrolledCourses[] = $course_id;

        $json = json_encode($enrolledCourses);

        mysqli_query($con, "UPDATE users SET enrolled_courses='$json' WHERE id=$user_id");

        /* COURSE DETAILS */

        $course = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM courses WHERE id=$course_id"));

        $mail = new PHPMailer\PHPMailer\PHPMailer(true);

        try {

            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['SMTP_USER'];
            $mail->Password = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom($_ENV['SMTP_USER'], "Skillify");
            $mail->addAddress($email);

            $mail->isHTML(true);

            $mail->Subject = "Course Subscription - Skillify";

            $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
            <meta charset="UTF-8">
            </head>

            <body style="margin:0;padding:0;background:#0f0f0f;font-family:Segoe UI,Arial,sans-serif;">

            <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 10px;">
            <tr>
            <td align="center">

            <table width="600" cellpadding="0" cellspacing="0"
            style="background:#1a1a1a;border-radius:16px;overflow:hidden;
            box-shadow:0 15px 40px rgba(0,0,0,0.6);">

            <!-- HEADER -->
            <tr>
            <td align="center" style="padding:30px;background:#111111;border-bottom:1px solid #2a2a2a;">

            <h2 style="margin:0;color:#b7ee73;font-size:26px;">
            Skillify
            </h2>

            <p style="margin-top:6px;color:#aaaaaa;font-size:14px;">
            Course Enrollment Confirmation
            </p>

            </td>
            </tr>

            <!-- CONTENT -->
            <tr>
            <td style="padding:35px;">

            <h3 style="color:#ffffff;margin-top:0;">
            Congratulations! 🎉
            </h3>

            <p style="color:#bbbbbb;font-size:15px;line-height:1.6;">
            You have successfully enrolled in the following course.
            Start learning now and upgrade your skills!
            </p>

            <!-- COURSE CARD -->
            <table width="100%" cellpadding="0" cellspacing="0"
            style="margin-top:20px;background:#222;border-radius:12px;padding:20px;">

            <tr>
            <td style="padding:10px;color:#ccc;font-size:15px;">
            <strong style="color:#b7ee73;">Course Name:</strong>
            </td>
            <td style="color:#ffffff;">
            ' . $course['title'] . '
            </td>
            </tr>

            <tr>
            <td style="padding:10px;color:#ccc;font-size:15px;">
            <strong style="color:#b7ee73;">Duration:</strong>
            </td>
            <td style="color:#ffffff;">
            ' . $course['duration'] . ' Days
            </td>
            </tr>

            <tr>
            <td style="padding:10px;color:#ccc;font-size:15px;">
            <strong style="color:#b7ee73;">Price:</strong>
            </td>
            <td style="color:#ffffff;">
            ₹' . $course['price'] . '
            </td>
            </tr>

            </table>

            <!-- BUTTON -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top:30px;">
            <tr>
            <td align="center">

            <a href="' . $course['course_link'] . '"
            style="
            background:#b7ee73;
            color:#000;
            padding:14px 28px;
            text-decoration:none;
            font-weight:600;
            border-radius:8px;
            font-size:15px;
            display:inline-block;
            box-shadow:0 6px 20px rgba(183,238,115,0.3);
            ">

            Start Learning

            </a>

            </td>
            </tr>
            </table>

            </td>
            </tr>

            <!-- FOOTER -->
            <tr>
            <td align="center" style="padding:25px;background:#111111;border-top:1px solid #2a2a2a;">

            <p style="color:#888;font-size:13px;margin:0;">
            © ' . date("Y") . ' Skillify
            </p>

            <p style="color:#666;font-size:12px;margin-top:6px;">
            Learn • Grow • Build Skills
            </p>

            </td>
            </tr>

            </table>

            </td>
            </tr>
            </table>

            </body>
            </html>
            ';

            $mail->send();

        } catch (Exception $e) {
        }

    }

    exit();
}

/* ================= FILTER ================= */

$titleFilter = "";
$skillFilter = "";

if (isset($_GET['title'])) {
    $titleFilter = $_GET['title'];
}

if (isset($_GET['skill'])) {
    $skillFilter = $_GET['skill'];
}

/* ================= FETCH COURSES ================= */

$query = "SELECT * FROM courses WHERE 1=1";

if ($titleFilter) {
    $query .= " AND title LIKE '%$titleFilter%'";
}

if ($skillFilter) {
    $query .= " AND skills LIKE '%$skillFilter%'";
}

$query .= " ORDER BY id DESC";

$result = mysqli_query($con, $query);

?>

<main>

    <section class="profile-section">

        <div class="container">

            <h2 class="profile-title mb-4">Courses</h2>


            <!-- ================= ENROLLED COURSES ================= -->

            <?php if (count($enrolledCourses) > 0) { ?>

                <h4 class="mb-3">Your Enrolled Courses</h4>

                <div class="row g-4 mb-5">

                    <?php

                    $ids = implode(",", $enrolledCourses);

                    $enrolledQuery = mysqli_query($con, "SELECT * FROM courses WHERE id IN ($ids)");

                    while ($course = mysqli_fetch_assoc($enrolledQuery)) {

                        ?>

                        <div class="col-lg-4 col-md-6">

                            <div class="skill-card">

                                <div class="skill-card-body">

                                    <h3><?php echo $course['title']; ?></h3>

                                    <p><?php echo $course['duration']; ?> Days</p>

                                    <a href="<?php echo $course['course_link']; ?>" target="_blank"
                                        class="btn btn-primary-custom btn-sm">
                                        Start Course
                                    </a>

                                </div>
                            </div>

                        </div>

                    <?php } ?>

                </div>

            <?php } ?>


            <!-- ================= SEARCH ================= -->

            <form method="GET" class="row g-3 mb-4 needs-validation" novalidate>

                <div class="col-md-4">

                    <input type="text" name="title" class="form-control" placeholder="Search Course"
                        value="<?php echo $titleFilter; ?>" required>
                    <div class="invalid-feedback">
                        Please provide a valid title.
                    </div>

                </div>

                <div class="col-md-4">

                    <input type="text" name="skill" class="form-control" placeholder="Search Skill"
                        value="<?php echo $skillFilter; ?>" required>
                    <div class="invalid-feedback">
                        Please provide a valid skill.
                    </div>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary-custom w-100">
                        Search
                    </button>

                </div>

                <div class="col-md-2">

                    <a href="course.php" class="btn btn-outline-secondary w-100">
                        Reset
                    </a>

                </div>

            </form>


            <!-- ================= COURSES ================= -->

            <div class="row g-4">

                <?php

                $count = 0;

                while ($row = mysqli_fetch_assoc($result)) {

                    $count++;

                    ?>

                    <div class="col-lg-4 col-md-6">

                        <div class="skill-card">

                            <div class="skill-card-body">

                                <h3><?php echo $row['title']; ?></h3>

                                <p><?php echo $row['skills']; ?></p>

                                <p><?php echo $row['duration']; ?> Days</p>

                                <p><strong>₹<?php echo $row['price']; ?></strong></p>

                                <?php if (in_array($row['id'], $enrolledCourses)) { ?>

                                    <button class="btn btn-primary-custom btn-sm">
                                        Enrolled
                                    </button>

                                <?php } else { ?>

                                    <button class="btn btn-primary-custom btn-sm subscribeBtn"
                                        data-id="<?php echo $row['id']; ?>" data-price="<?php echo $row['price']; ?>">
                                        Subscribe
                                    </button>

                                <?php } ?>

                            </div>
                        </div>

                    </div>

                <?php } ?>


                <?php if ($count == 0) { ?>

                    <div class="col-12 text-center">

                        <img src="images/nodata.gif" style="width:20rem;margin-bottom:1rem;">

                        <h4 style="color:#999;">No Courses Found</h4>

                    </div>

                <?php } ?>

            </div>

        </div>

    </section>

</main>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

    let loader = document.getElementById("mailLoader")

    document.querySelectorAll(".subscribeBtn").forEach(btn => {

        btn.addEventListener("click", function () {

            let courseId = this.dataset.id
            let price = this.dataset.price

            var options = {

                key: "<?php echo $_ENV['RZP_KEY'] ?>",

                amount: price * 100,

                currency: "INR",

                name: "Skillify",

                description: "Course Subscription",

                theme: {
                    color: "#b7ee73"
                },

                handler: function () {

                    /* SHOW MAIL LOADER */

                    loader.style.display = "flex"

                    /* DISABLE PAGE SCROLL */

                    document.body.style.overflow = "hidden"

                    /* SEND DATA TO PHP */

                    fetch("course.php", {

                        method: "POST",

                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },

                        body: "course_id=" + courseId

                    })

                        .then(res => res.text())

                        .then(() => {

                            /* WAIT SMALL TIME FOR MAIL */

                            setTimeout(() => {

                                location.reload()

                            }, 2000)

                        })

                }

            };

            var rzp = new Razorpay(options)

            rzp.open()

        })

    })

</script>

<?php include "./includes/footer.php"; ?>