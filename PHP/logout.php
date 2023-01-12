<?php
session_start();
session_destroy();
header("Location: ../HTML/INDEX/index.html");
die();
?>