<?php
$title = "404 Not Found";
ob_start() ?>

<h1><?php echo $message ?></h1>

<?php
$content = ob_get_clean();
include "baselayout.php";
?>