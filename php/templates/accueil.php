<?php
$title = "Bienvenue";
ob_start() ?>

<h1>Bienvenue</h1>

<?php
$content = ob_get_clean();
include "baselayout.php";
?>