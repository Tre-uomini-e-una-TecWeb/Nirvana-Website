<?php
session_start();
$_SESSION["username"] = "";
$_SESSION["privilegi"] = "";
header("Location: HTML/INDEX/index.html");
die();
?>