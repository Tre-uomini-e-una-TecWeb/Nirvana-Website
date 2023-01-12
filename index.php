<?php
session_start();
session_set_cookie_params(0);
$_SESSION["username"] = "";
$_SESSION["privilegi"] = "";
header("Location: HTML/INDEX/index.html");
die();
?>