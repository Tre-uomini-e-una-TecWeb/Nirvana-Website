<?php
header("HTTP/1.0 403 Forbidden");
$pagina_HTML=file_get_contents("../HTML/AMMINISTRAZIONE/403.html");
echo $pagina_HTML;
?>