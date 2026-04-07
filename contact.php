<?php include "./includes/header.php"; ?>

<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer/src/Exception.php';
require 'PHPMailer/PHPMailer/src/PHPMailer.php';
require 'PHPMailer/PHPMailer/src/SMTP.php';
require 'config.php';

$alertType = "";
$alertTitle = "";
$alertText = "";

if (isset($_POST['send_message'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom($_ENV['SMTP_USER'], "Skillify Contact");
        $mail->addAddress($_ENV['SMTP_TO']);

        $mail->isHTML(true);
        $mail->Subject = "New Contact Message - Skillify";

        $mail->Body = '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
            </head>
            <body style="margin:0;background:#0f0f0f;font-family:Segoe UI,Arial,sans-serif;">
                <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 15px;">
                    <tr>
                        <td align="center">
                        <table width="600" cellpadding="0" cellspacing="0"
                            style="background:#1a1a1a;border-radius:18px;overflow:hidden;
                            box-shadow:0 20px 50px rgba(0,0,0,0.6);">
                            <tr>
                                <td align="center" style="padding:35px;background:#111111;">
                                    <h2 style="color:#b7ee73;margin:0;font-size:26px;">
                                    Skillify Contact Message
                                    </h2>
                                    <p style="color:#bbbbbb;font-size:14px;margin-top:6px;">
                                    You received a new message from your website
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td style="padding:40px;">
                                    <table width="100%" style="color:#cccccc;font-size:15px;line-height:1.6;">
                                    <tr>
                                        <td style="padding:10px 0;"><strong style="color:#b7ee73;">Name:</strong></td>
                                        <td>' . $name . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 0;"><strong style="color:#b7ee73;">Email:</strong></td>
                                        <td>' . $email . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 0;"><strong style="color:#b7ee73;">Subject:</strong></td>
                                        <td>' . $subject . '</td>
                                    </tr>
                                    <tr>
                                        <td style="padding:10px 0;"><strong style="color:#b7ee73;">Message:</strong></td>
                                        <td>' . $message . '</td>
                                    </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" style="padding:25px;background:#111111;border-top:1px solid #2a2a2a;">
                                    <p style="color:#777;font-size:12px;margin:0;">
                                    © ' . date("Y") . ' Skillify
                                    </p>
                                    <p style="color:#555;font-size:11px;margin-top:6px;">
                                    Website Contact Notification
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

        $alertType = "success";
        $alertTitle = "Message Sent!";
        $alertText = "Thank you for contacting Skillify.";

    } catch (Exception $e) {

        $alertType = "error";
        $alertTitle = "Email Failed";
        $alertText = "Message could not be sent.";

    }

}

?>

<main>

    <!-- Hero -->

    <section class="contact-hero">
        <div class="container">

            <h1>Contact Skillify</h1>

            <p>
                Have questions about Skillify, need support, or want to share feedback?
                Our team is here to help you improve your learning journey.
            </p>

        </div>
    </section>


    <!-- Contact Section -->

    <section class="contact-section">
        <div class="container">

            <div class="row g-4">

                <!-- Contact Info -->

                <div class="col-lg-5">

                    <div class="contact-card">

                        <h2 class="contact-title">Get In Touch</h2>

                        <p class="mb-4">
                            If you need help with your account, courses, or platform features,
                            feel free to reach out to us anytime.
                        </p>

                        <div class="contact-info">

                            <p>
                                <i class="bi bi-envelope"></i>
                                <a href="mailto:support@skillify.com">support@skillify.com</a>
                            </p>

                            <p>
                                <i class="bi bi-geo-alt"></i>
                                Surat, Gujarat, India
                            </p>

                            <p>
                                <i class="bi bi-clock"></i>
                                Mon – Sat | 9:00 AM – 7:00 PM
                            </p>

                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Follow Skillify</h5>

                        <div class="contact-social">

                            <a href="#"><i class="bi bi-facebook"></i></a>

                            <a href="#"><i class="bi bi-instagram"></i></a>

                            <a href="#"><i class="bi bi-linkedin"></i></a>

                            <a href="#"><i class="bi bi-twitter-x"></i></a>

                        </div>

                    </div>

                </div>


                <!-- Contact Form -->

                <div class="col-lg-7">

                    <div class="contact-card">

                        <h2 class="contact-title">Send Us a Message</h2>

                        <form method="POST" class="needs-validation" id="contactform" novalidate>

                            <div class="row g-3">

                                <div class="col-md-6">
                                    <input type="text" name="name" class="form-control" placeholder="Your Name"
                                        required>
                                    <div class="invalid-feedback">
                                        Please provide a valid name.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <input type="email" name="email" class="form-control" placeholder="Your Email"
                                        required>
                                    <div class="invalid-feedback">
                                        Please provide a valid email.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <input type="text" name="subject" class="form-control" placeholder="Subject"
                                        required>
                                    <div class="invalid-feedback">
                                        Please provide a valid subject.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <textarea name="message" class="form-control" rows="6" placeholder="Your Message"
                                        required></textarea>
                                    <div class="invalid-feedback">
                                        Please provide a valid message.
                                    </div>
                                </div>

                                <div class="col-12">
                                    <button type="submit" name="send_message" class="btn btn-primary-custom w-100">
                                        Send Message
                                    </button>
                                </div>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
    </section>


    <!-- FAQ Section -->

    <section class="faq-section">

        <div class="container">

            <h2 class="faq-title">Frequently Asked Questions</h2>


            <div class="faq-item">
                <details>
                    <summary>How can I contact Skillify support?</summary>
                    <p>You can contact our support team by sending an email to support@skillify.com or by filling out
                        the contact form above.</p>
                </details>
            </div>


            <div class="faq-item">
                <details>
                    <summary>How long does it take to receive a response?</summary>
                    <p>Our team usually replies within 24 hours during working days.</p>
                </details>
            </div>


            <div class="faq-item">
                <details>
                    <summary>Can I suggest new features for Skillify?</summary>
                    <p>Yes! We welcome suggestions and feedback. Send your ideas using the contact form.</p>
                </details>
            </div>


            <div class="faq-item">
                <details>
                    <summary>Is Skillify free to use?</summary>
                    <p>Skillify offers free features as well as premium tools for advanced learning.</p>
                </details>
            </div>


            <div class="faq-item">
                <details>
                    <summary>What type of learning resources are available on Skillify?</summary>
                    <p>Skillify provides curated learning resources, practice material, and skill-based content to help
                        students grow.</p>
                </details>
            </div>


            <div class="faq-item">
                <details>
                    <summary>Can I report bugs or technical issues?</summary>
                    <p>Yes. If you find any issue on the platform, please report it through the contact form or email
                        support@skillify.com.</p>
                </details>
            </div>


            <div class="faq-item">
                <details>
                    <summary>Do I need an account to use Skillify?</summary>
                    <p>Some features can be accessed without an account, but creating an account unlocks full
                        functionality.</p>
                </details>
            </div>


        </div>

    </section>

</main>

<script>
    (function () {
        'use strict'
        const form = document.getElementById('contactform');
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            else {
                document.getElementById("mailLoader").style.display = "flex";
            }

            form.classList.add('was-validated');
        }, false)

    })();
</script>

<?php if ($alertType != "") { ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        Swal.fire({
            icon: "<?php echo $alertType; ?>",
            title: "<?php echo $alertTitle; ?>",
            text: "<?php echo $alertText; ?>",
            confirmButtonColor: "#b7ee73",
            background: "#1a1a1a",
            color: "#ffffff"
        })

    </script>

<?php } ?>

<?php include "./includes/footer.php"; ?>