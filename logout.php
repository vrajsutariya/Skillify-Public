<?php
setcookie("SEC_LOGIN", "", time() - 3600, "/");
header("Location: login.php");
exit();
?>