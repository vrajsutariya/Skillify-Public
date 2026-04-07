<?php
setcookie("ADMIN", "", time() - 3600, "/");
header("Location: ../login.php");
exit();
?>