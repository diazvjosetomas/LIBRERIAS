<?php
if(!empty($_REQUEST["nombre"]))
	$nombre=$_REQUEST["nombre"];
else
	$nombre="Excel_";
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=".$nombre.date("d_m_Y").".xls");
echo utf8_decode($_REQUEST["data"]);
?>