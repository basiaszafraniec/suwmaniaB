<?php
$file = fopen("highscore.json", "w") or die("unable to open");
$winner = file_get_contents("php://input");
fwrite($file, $winner);
fclose($file);
?>


