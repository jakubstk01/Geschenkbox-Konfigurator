<?php
session_start();
unset($_SESSION['config']);
header("Location: /configurator/step1.php");
exit;