<?php
ob_start();

echo 1;
ob_flush();
flush();

sleep(221);
echo 2;
ob_flush();
flush();
?>
