<? session_start();
if(file_exists("../funcionesphp/seguridad.php"))
	include("../funcionesphp/seguridad.php");
else
	include("funcionesphp/seguridad.php");

include '../funcionesphp/funciones.php';
antiChismoso();
extract($_GET);

$sql = "SELECT c.nombre, t.nombre_torre 
			FROM condominio c, torres_condominio t
			WHERE c.id_condominio = '$id_condominio'
			AND   t.id_torre_condominio = '$id_torre'
			AND   c.id_condominio = t.id_condominio
			GROUP BY c.nombre";


$ejecutaSql = mysqli_query($enlace, $sql);
if (!$ejecutaSql) {
	echo "<div class='alert alert-danger'>Error!</div>";
}else{

	$arrayDatos = mysqli_fetch_array($ejecutaSql);
	$_SESSION['Id_Condominio'] = $id_condominio;
	$_SESSION['Id_Torre'] = $id_torre;
	$_SESSION['Nombre_Condominio'] = $arrayDatos[0];
	$_SESSION['Nombre_Torre'] = $arrayDatos[1];
	echo "<strong style='color:green;''><i class='fa fa-check-square-o'></i></strong>&nbsp;Condominio:<u><i>".$arrayDatos[0]."</i></u> /Torre: <u><i>".$arrayDatos[1]."</i></u>";
}

            





?>