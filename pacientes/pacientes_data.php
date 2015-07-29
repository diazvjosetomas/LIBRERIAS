<?php
include("../funcionesphp/funciones.php");

$idpaciente = $_REQUEST["idpaciente"];
$cedula 	= str_replace(".", "", $_REQUEST["cedula"]);
$nombres 	= limpiaString(trim($_REQUEST["nombres"]));
$apellidos 	= limpiaString(trim($_REQUEST["apellidos"]));
$direccion  = limpiaString(trim($_REQUEST["direccion"]));
$telefono   = $_REQUEST["telefono"];
$accion   	= $_REQUEST["accion"];

if(!empty($_REQUEST["asegurado"]) || $_REQUEST["asegurado"]==0)
	$asegurado			=$_REQUEST["asegurado"];
if(!empty($_REQUEST["bus_seg"]))
	$seguro				=$_REQUEST["bus_seg"];
if(!empty($_REQUEST["fec_nac"]))
	$fec_nac			=ConvFecha($_REQUEST["fec_nac"]);
//echo $cedula; die();

//Filtros
$filcedula 		= str_replace(".", "", $_REQUEST["filcedula"]);
if($filcedula!=""){
	$fil = $fil."cedula = '$filcedula'";
}
$filnombres 	= $_REQUEST["filnombres"];
if($filnombres!=""){
	if($fil!=""){ $fil .= " AND "; }
	$fil = $fil."nombres LIKE '%$filnombres%'";
}
$filapellidos 	= $_REQUEST["filapellidos"];
if($filapellidos!=""){
	if($fil!=""){ $fil .= " AND "; }
	$fil = $fil."apellidos LIKE '%$filapellidos%'";
}

if($fil!=""){ $fil = "WHERE ".$fil; }

switch ($accion) 
{
	case 'guardar':

		foreach ($_REQUEST as $key => $value) 
		{
			$ex=explode("as_",$key);
				if(count($ex)>1)
				{
					//echo count($ex);
					//echo $key." ".$value."<br>";

					$ex_val=explode("|*",$value);
						$nom_aso=trim($ex_val[0]);
						$ape_aso=trim($ex_val[1]);
						$fec_aso=ConvFecha($ex_val[2]);

						$ing_aso=mysqli_query($enlace,"INSERT INTO pacientes_asociado()
													   VALUES(NULL,$cedula,'$nom_aso','$ape_aso','$fec_aso','','',0,0,'','') ") or die ("Error: ".mysqli_error($enlace));
				}
		}
		//exit();

		$sql = "SELECT idpaciente FROM pacientes WHERE cedula = '$cedula'";
		$count = mysqli_num_rows(mysqli_query($enlace,$sql));
		if($count<=0)
		{
			$sql = "INSERT INTO pacientes() 
					VALUES (NULL,'$cedula','$nombres','$apellidos','$fec_nac','$direccion','$telefono','$asegurado','$seguro','','')";
			$result = mysqli_query($enlace,$sql) or die("Error: ".mysqli_error($enlace));

			if(!$result || !$ing_aso){
				echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
			}else{
				echo json_encode(array("result"=>true,"msg"=>"El paciente se ha registrado satisfactoriamente."));
			}
		}
		else{
			echo json_encode(array("result"=>false,"msg"=>"La cedula que ingreso ya esta registrada en la base de datos."));
		}
		break;

	case 'modificar':
	//exit();
		$sql = "UPDATE pacientes 
				SET 
					nombres='$nombres',
					apellidos='$apellidos',
					fecha_nacimiento='$fec_nac',
					direccion='$direccion',
					telefono='$telefono', 
					asegurado='$asegurado', 
					id_seguro_m='$seguro' 
				WHERE idpaciente=$idpaciente";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			//===> Elimino los asociados del paciente...
			$el_aso=mysqli_query($enlace,"DELETE FROM pacientes_asociado WHERE cedula='$cedula' ");

			foreach ($_REQUEST as $key => $value) 
			{
				$ex=explode("as_",$key);
					if(count($ex)>1)
					{
						$ex_val=explode("|*",$value);
							$nom_aso=trim($ex_val[0]);
							$ape_aso=trim($ex_val[1]);
							$fec_aso=ConvFecha($ex_val[2]);

							$ing_aso=mysqli_query($enlace,"INSERT INTO pacientes_asociado()
														   VALUES(NULL,$cedula,'$nom_aso','$ape_aso','$fec_aso','','',0,0,'','') ") or die ("Error: ".mysqli_error($enlace));
					}
			}

			echo json_encode(array("result"=>true,"msg"=>"El paciente se ha modificado satisfactoriamente."));
		}
		break;

	case 'buscaAsociados':
		$sql=mysqli_query($enlace,"SELECT * FROM pacientes_asociado WHERE cedula='$cedula' ");
		$nr=mysqli_num_rows($sql);
		
		if($nr>0)
		{
			?>
			<table id="tabla-asociados" class="table table-striped table-bordered" style="margin-top:10px;width:100%;">
			    <tr>
			    	<td width="30%" align="center" style="background-color:#C7C7C7;"><b>Nombre</b></td>
			    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Fecha nacimiento</b></td>
			    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Edad</b></td>
			    	<td width="8%"  align="center" style="background-color:#C7C7C7;">&nbsp;</td>
			    </tr>
			<?	
			while($rs=mysqli_fetch_assoc($sql))
			{
				?>
			    <tr id="<?=substr(utf8_encode($rs['nombres']),0,2).substr(utf8_encode($rs['apellidos']),0,2).substr(DevuelveFecha($rs['fecha_nacimiento']),0,2);?>">
			    	<td align="center"><?=utf8_encode($rs["nombres"]." ".utf8_encode($rs["apellidos"]))?></td>
			    	<td align="center"><?=DevuelveFecha($rs["fecha_nacimiento"])?></td>
			    	<td align="center"><?=calcular_edad("/",DevuelveFecha($rs["fecha_nacimiento"]))?></td>
					<td align='center' style='vertical-align: middle;'><i class='fa fa-times' style='color:red;cursor:pointer;font-size: 25px;' data-toggle='tooltip' data-placement='top' title='Eliminar asociado' onclick='eliminaTrDatos("<?=substr(utf8_encode($rs['nombres']),0,2).substr(utf8_encode($rs['apellidos']),0,2).substr(DevuelveFecha($rs['fecha_nacimiento']),0,2);?>")'></i></td>
			    </tr>
			    <script type="text/javascript">
					$("#div_asociados").append("<input type='hidden' id='as_<?=substr(utf8_encode($rs['nombres']),0,2).substr(utf8_encode($rs['apellidos']),0,2).substr(DevuelveFecha($rs['fecha_nacimiento']),0,2);?>' name='as_<?=substr(utf8_encode($rs['nombres']),0,2).substr(utf8_encode($rs['apellidos']),0,2).substr(DevuelveFecha($rs['fecha_nacimiento']),0,2);?>' value='<?=utf8_encode($rs['nombres'])?>|*<?=utf8_encode($rs['apellidos'])?>|*<?=DevuelveFecha($rs['fecha_nacimiento'])?>'>");
			    </script>
				<?				
			}
			?>
			</table>
			<script type="text/javascript">
				 $('[data-toggle="tooltip"]').tooltip();
			</script>
			<?
		}
	
	break;

	case 'eliminar':
		$sql = "DELETE FROM pacientes WHERE idpaciente=$idpaciente";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El paciente se ha elminado satisfactoriamente."));
		}
		break;
	
	case 'consultar':

	//establecemos los limites de la página actual
	if ($_POST['pg']=="") 
		$n_pag = 1; 
	else  
		$n_pag=$_POST['pg']; 
	$cantidad=10;
	$inicial = ($n_pag-1) * $cantidad;

		$sql=mysqli_query($enlace,"SELECT * FROM pacientes $fil ORDER BY cedula ASC") or die ("Error: ".mysqli_error($enlace));
		$cant_registros =mysqli_num_rows($sql);
		$paginado = intval($cant_registros / $cantidad);

		$sql = "SELECT * FROM pacientes $fil ORDER BY cedula ASC LIMIT $inicial,$cantidad";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			?>
			<table class='table table-bordered table-striped'>
				<thead style='background-color: rgba(208, 213, 216, 0.69);'>
					<tr>
						<th>N&ordm;</th>
						<th>C&eacute;dula</th>
						<th>Nombres</th>
						<th></th>
					</tr>
				</thead>
			<?php
			$i = 1;
			//($result);
			if($cant_registros>0){
				while ($paciente = mysqli_fetch_assoc($result)) {
				?>
					<tr>
						<td width="5%"><?= $i ?></td>
						<td width="12%"><?= number_format($paciente["cedula"],0,",",".") ?></td>
						<td><?=utf8_encode($paciente["nombres"])." ".utf8_encode($paciente["apellidos"])?></td>
						<td width="10%" align="center">
							<button type="button" class="btn btn-danger btn-sm" title="Eliminar Paciente" onclick="conf_eliminar('<?=$paciente["idpaciente"];?>','<?=utf8_encode($paciente["nombres"])?>')"><i class="fa fa-trash-o fa-lg"></i></button>
							<button type="button" class="btn btn-info btn-sm" title="Modificar Paciente" onclick="modPaciente(<?= "'".$paciente["idpaciente"]."','".$paciente["cedula"]."','".utf8_encode($paciente["nombres"])."','".utf8_encode($paciente["apellidos"])."','".utf8_encode($paciente["direccion"])."','".$paciente["telefono"]."','".$paciente["asegurado"]."','".$paciente["id_seguro_m"]."','".DevuelveFecha($paciente["fecha_nacimiento"])."' " ?>)"><i class="fa fa-edit fa-lg"></i></button>
						</td>
					</tr>
				<?php
					$i++;
				}
			}
			else{
				?>
				<tr><td colspan="4">No se han encontrado resultados.</td></tr>
				<?php
			}
			?>
			</table>
			</div>
			<div class="panel-footer" id="paginator" style="">
				<script type="text/javascript" src="js/funciones.js"></script>
				<script type="text/javascript">
					$("#paginator").html(paginar('<?=$n_pag?>','<?=$cant_registros?>','10','10'));
					$('[data-toggle="tooltip"]').tooltip();
				</script>
			</div>
			<?php
		}
		break;
}


?>