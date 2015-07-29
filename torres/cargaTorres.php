<? include("../funcionesphp/funciones.php");
extract($_GET);

	

	$sql = "SELECT * FROM torres_condominio WHERE id_condominio = '$id_condominio'";
		$ejectConsulta = mysqli_query($enlace, $sql);






		$sql2 = "SELECT * FROM condominio WHERE id_condominio = '$id_condominio'";
					$ejecutaSql2 = mysqli_query($enlace, $sql2);

					if ($ejecutaSql2) {
						$arrayDatos = mysqli_fetch_array($ejecutaSql2);
						$nombreCondominio = $arrayDatos['nombre'];
					}else{
						$nombreCondominio = "Error";
					}	
		

	if ($ejectConsulta) {

		$numTorres = mysqli_num_rows($ejectConsulta);


		

		?>
	<style type="text/css" src="../css/bootstrap.css">

	  td{
	  	padding: 12px;
	  }

	 .zonaTorres{

	 	width: 100%;
	 	height: 100%;
	 	/*COLOR DE FONDO*/
	 	background: rgba(109,116,122,1);
	 	background: -moz-linear-gradient(top, rgba(109,116,122,1) 0%, rgba(32,124,229,0) 100%);
	 	background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(109,116,122,1)), color-stop(100%, rgba(32,124,229,0)));
	 	background: -webkit-linear-gradient(top, rgba(109,116,122,1) 0%, rgba(32,124,229,0) 100%);
	 	background: -o-linear-gradient(top, rgba(109,116,122,1) 0%, rgba(32,124,229,0) 100%);
	 	background: -ms-linear-gradient(top, rgba(109,116,122,1) 0%, rgba(32,124,229,0) 100%);
	 	background: linear-gradient(to bottom, rgba(109,116,122,1) 0%, rgba(32,124,229,0) 100%);
	 	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#6d747a', endColorstr='#207ce5', GradientType=0 );

	 	text-align: center;
	 	/*vertical-align: middle;*/
	 	padding: 25px;


	 	color:white;

	 	/*BORDES*/
	 	border-radius: 10px 10px 0px 0px;
	 	-moz-border-radius: 10px 10px 0px 0px;
	 	-webkit-border-radius: 10px 10px 0px 0px;
	 	border: 0px solid #000000;

	 	/*IZQUIERDO*/


	 }

	 .torre{
	 	/*BORDES*/
	 	border-radius: 10px 10px 10px 10px;
	 	-moz-border-radius: 10px 10px 10px 10px;
	 	-webkit-border-radius: 10px 10px 10px 10px;
	 	border: 0px solid #000000;

	 	/*GRADIENTES*/
	 	background: rgba(226,226,226,1);
	 	background: -moz-linear-gradient(top, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 26%, rgba(209,209,209,1) 51%, rgba(254,254,254,1) 100%);
	 	background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(226,226,226,1)), color-stop(26%, rgba(219,219,219,1)), color-stop(51%, rgba(209,209,209,1)), color-stop(100%, rgba(254,254,254,1)));
	 	background: -webkit-linear-gradient(top, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 26%, rgba(209,209,209,1) 51%, rgba(254,254,254,1) 100%);
	 	background: -o-linear-gradient(top, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 26%, rgba(209,209,209,1) 51%, rgba(254,254,254,1) 100%);
	 	background: -ms-linear-gradient(top, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 26%, rgba(209,209,209,1) 51%, rgba(254,254,254,1) 100%);
	 	background: linear-gradient(to bottom, rgba(226,226,226,1) 0%, rgba(219,219,219,1) 26%, rgba(209,209,209,1) 51%, rgba(254,254,254,1) 100%);
	 	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#e2e2e2', endColorstr='#fefefe', GradientType=0 );

	 	/*SOMBRA*/
	 	-webkit-box-shadow: -5px 6px 18px 0px rgba(0,0,0,0.75);
	 	-moz-box-shadow: -5px 6px 18px 0px rgba(0,0,0,0.75);
	 	box-shadow: -5px 6px 18px 0px rgba(0,0,0,0.75);

	 	/*ALTO & ANCHO*/
	 	width: 95px;
	 	height: 95px;

	 	/*COLOR DE LETRA*/
	 	color:white;
	 	


	 }
	 .torre:hover{
	 	/*SOMBRA*/
	 	-webkit-box-shadow: -5px 6px 18px 0px rgba(80,142,209,1);
	 	-moz-box-shadow: -5px 6px 18px 0px rgba(80,142,209,1);
	 	box-shadow: -5px 6px 18px 0px rgba(80,142,209,1);

	 }

	 em{
	 	font-size: 12px;
	 	font-weight: normal;
	 }
	</style>

	


	<div class="zonaTorres" align="center">
		
		<? echo "Condominio: <u><i>".$nombreCondominio."</i></u><br><br>"; ?>
		
		<table class="" style="">
			<tr>
		<?php while ($row = mysqli_fetch_array($ejectConsulta)) {

			if (strlen($row['nombre_torre']) > 8 ) {
				$cadenaNombre = substr($row['nombre_torre'],0,7)."...";
			}else{
				$cadenaNombre = $row['nombre_torre'];
			}



			echo "<td><div class='torre ' style='text-align: center;font-size: 55px;padding: 20px;'>
			          <a onclick='cargaSesion(".$id_condominio.",".$row['id_torre_condominio'].")' title='".$row['nombre_torre']."' href='#'>			          
			          <i class='fa fa-building-o'><br>
			          <em>".$cadenaNombre."</em>
			          </div></td>";
		} ?>
			</tr>
			
		</table>

	</div>





	<?}else{?>

	<div class="alert alert-danger"> Hubo un error al cargar las torres del condominio </div>

	<?}




	?>
