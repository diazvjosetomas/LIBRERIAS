<?php
$enlace=mysqli_connect("localhost","root","123") or die
 ("Error en conexi&oacute;n: ".mysqli_error($enlace));


	mysqli_select_db($enlace,"sistemas_demoadmin")
	 or die ("Error en conexi&oacute;n a la BD: ".mysqli_error($enlace));

?>