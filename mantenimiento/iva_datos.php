<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");

//======> Variables
$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["fecha"]))
	$fecha 			=ConvFecha($_REQUEST["fecha"]);

//===> Para la busqueda del producto o servicio
if(!empty($_REQUEST["valor"]))
	$valor			=str_replace(".","",$_REQUEST["valor"]);

switch ($accion) 
{
	case "ingresarIVA":

		//===> Ingreso el paciente a admision
		$sql=mysqli_query($enlace,"INSERT INTO 
								   iva()
			  					   VALUES( 
			  					   	NULL,
			  					   '$fecha',
			  					   '$valor') ") or die ("Error: ".mysqli_error($enlace));

			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"I.V.A. Actualizado"));	

	break;
}
?>