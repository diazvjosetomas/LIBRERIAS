<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");

session_start();
//======> Variables
$accion 	=$_REQUEST["accion"];
$iduser 	=$_SESSION["idUser"];

if(!empty($_REQUEST["pass"]))
	$pass 			=md5(utf8_decode($_REQUEST["pass"]));

switch ($accion) 
{
	case "cambioPass":

		//===> Cambiando la constrasenia
		$sql=mysqli_query($enlace,"UPDATE 
								   users 
								   SET
								   pass='$pass'
								   WHERE
								   id_user=$iduser ") or die ("Error: ".mysqli_error($enlace));
			
			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"Contrase&ntilde;a actualizada exitosamente!"));	

	break;
}
?>