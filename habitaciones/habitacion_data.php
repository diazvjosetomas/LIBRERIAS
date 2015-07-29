<?php
include("../funcionesphp/funciones.php");
//var_dump($_REQUEST); die();
$idhabitacion 	= $_REQUEST["idhabitacion"];
$nombre 		= utf8_decode($_REQUEST["nombre"]);
$estatus   		= $_REQUEST["estatus"];
$accion   		= $_REQUEST["accion"];

//Filtros

$filnombre 		= $_REQUEST["filnombre"];
if($filnombre!=""){
	$fil = "habitacion LIKE '%$filnombre%'";
}
$filestatus 	= $_REQUEST["filestatus"];
if($filestatus!=""){
	if($fil!=""){ $fil .= " AND "; }
	$fil = $fil."estatus = '$filestatus'";
}

if($fil!=""){ $fil = "WHERE ".$fil; }

switch ($accion) {
	case 'guardar':
		$sql = "INSERT INTO habitacion(habitacion, estatus) VALUES ('$nombre','$estatus')";
		$result = mysqli_query($enlace,$sql);
		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El habitacion se ha registrado satisfactoriamente."));
		}
		break;

	case 'modificar':
		$sql = "UPDATE habitacion SET habitacion='$nombre',estatus=$estatus WHERE idhabitacion=$idhabitacion";
		//echo $sql;
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El habitacion se ha modificado satisfactoriamente."));
		}
		break;

	case 'eliminar':
		$sql = "DELETE FROM habitacion WHERE idhabitacion='$idhabitacion'";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El habitacion se ha eliminado satisfactoriamente."));
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

		$sql=mysqli_query($enlace,"SELECT * FROM habitacion $fil") or die ("Error: ".mysqli_error($enlace));
		$cant_registros =mysqli_num_rows($sql);
		$paginado = intval($cant_registros / $cantidad);

		$sql = "SELECT * FROM habitacion $fil";
		//echo $sql; die();
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			?>
			<table class='table table-bordered table-striped'>
				<thead style='background-color: rgba(208, 213, 216, 0.69);'>
					<tr>
						<th>N</th>
						<th>Nombre</th>
						<th width="14%" align="center">Estatus</th>
						<th width="12%" align="center"></th>
					</tr>
				</thead>
			<?php
			$i = 1;
			//($result);
			if($cant_registros>0){
				while ($habitacion = mysqli_fetch_assoc($result)) {
				?>
					<tr>
						<td><?= $i ?></td>
						<td><?=utf8_encode($habitacion["habitacion"])?></td>
						<td align="center"><?php if($habitacion["estatus"]==1): echo "<b style='color: darkseagreen;'>Activo</b>"; else: echo "<b style='color: orange;'>Inactivo</b>"; endif; ?></td>
						<td align="center">
							<button type="button" class="btn btn-danger btn-sm" title="Eliminar habitacion" data-toggle="modal" data-target="#modal-habitacion<?=$habitacion["idhabitacion"];?>"><i class="fa fa-trash-o fa-lg"></i></button>
							<button type="button" class="btn btn-info btn-sm" title="Modificar habitacion" onclick="modHabitacion(<?= "'".$habitacion["idhabitacion"]."','".utf8_encode($habitacion["habitacion"])."',".$habitacion["estatus"] ?>)"><i class="fa fa-edit fa-lg"></i></button>
						</td>
					</tr>
				<!--Modal de eliminar almacen-->
				<div id="modal-habitacion<?=$habitacion["idhabitacion"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-sm">
				    <div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title">Alerta</h4>
						</div> <!--Header-->
						<div class="modal-body">
							<p style="text-align:center;">¿Eliminar habitacion: "<?=utf8_encode($habitacion["habitacion"])?>" ?</p>
						</div> <!--Body-->
				    	<div class="modal-footer">
				        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$habitacion["idhabitacion"];?>')" data-dismiss="modal">Eliminar</button>
				        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
				      	</div> <!--Footer-->
				    </div> <!--Content-->
				  </div>
				</div>
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
			<div><?= paginacion($paginado,$n_pag) ?></div>
			<?php
		}
		break;
}


?>