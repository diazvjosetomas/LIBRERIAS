<?
include("funcionesphp/funciones.php");
//===> Variables

$accion=$_REQUEST["accion"];

switch ($accion)
{
	case "iniciosesion":
	$user=antinyeccion(utf8_decode($_REQUEST["user"]));
	$pass=md5(antinyeccion(utf8_decode($_REQUEST["pass"])));

		$sql="SELECT * FROM users 
			  WHERE user='$user' AND pass='$pass'";

		$consulta=mysqli_query($enlace,$sql);
		$nfilas=mysqli_num_rows($consulta);
		$datos=mysqli_fetch_array($consulta);

		if($nfilas>0)
		{
			//==> Busco valor del iva
			$sqli=mysqli_query($enlace,"SELECT * FROM iva WHERE fecha_vigencia<='".date("Y-m-d")."' ");
			$rs=mysqli_fetch_assoc($sqli);

			//==> Busco valor del islr
			$sqlislr=mysqli_query($enlace,"SELECT * FROM islr WHERE fecha_vigencia<='".date("Y-m-d")."' ");
			$rsi=mysqli_fetch_assoc($sqlislr);

			//==> Busco margen de ganancia
			$sqlm=mysqli_query($enlace,"SELECT * FROM margen_ganancia WHERE fecha_vigencia<='".date("Y-m-d")."' ");
			$rsM=mysqli_fetch_assoc($sqlm);

			session_start();
			$_SESSION["aut"] 		="SI";
			$_SESSION["nombre"]		=utf8_encode($datos["nombre_usuario"]);
			$_SESSION["idUser"]		=$datos["id_user"];
			$_SESSION["tipoUser"] 	=$datos["tipo_user"];
			$_SESSION["iva"] 		=$rs["valor"];
			$_SESSION["islr"] 		=$rsi["valor"];
			$_SESSION["margenG"] 	=$rsM["valor"];
			$_SESSION["idAlmacen"]  =$datos["id_almacen"];

			echo json_encode(array('result' => true,'mensaje' => "¡Correcto! <i class='fa fa-check fa-fw' style='color:green;'></i>"));
		}
		else
		{
			echo json_encode(array('result' => false,'mensaje' => "Error: Usuario o Clave incorrecta"));
		}
	break;
}

?>