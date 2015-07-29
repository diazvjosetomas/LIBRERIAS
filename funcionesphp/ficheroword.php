<?php
header("Content-type: application/vnd.ms-word"); 
header("Content-Disposition: attachment; filename=Word_".date("d_m_Y").".doc");
echo utf8_decode($_REQUEST["data"]);
?>