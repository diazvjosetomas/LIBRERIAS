<?php
include("../funcionesphp/funciones.php");
//var_dump($_REQUEST); die();
$idtipopersonal = $_REQUEST["idtipopersonal"];
$nombre 		= utf8_decode($_REQUEST["nombre"]);
$accion   		= $_REQUEST["accion"];

//Filtros

$filnombre 		= $_REQUEST["filnombre"];
if($filnombre!=""){
	$fil = "nombre LIKE '%$filnombre%'";
}

if($fil!=""){ $fil = "WHERE ".$fil; }

switch ($accion) {
	case 'guardar':
		$sql = "INSERT INTO tipo_personal(tipo) VALUES ('$nombre')";
		//echo $sql; die;
		$result = mysqli_query($enlace,$sql);
		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El tipo se ha registrado satisfactoriamente."));
		}
		break;

	case 'modificar':
		$sql = "UPDATE tipo_personal SET tipo='$nombre' WHERE idtipopersonal='$idtipopersonal'";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El tipo se ha modificado satisfactoriamente."));
		}
		break;

	case 'eliminar':
		$sql = "DELETE FROM tipo_personal WHERE idtipopersonal='$idtipopersonal'";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El tipo se ha eliminado satisfactoriamente."));
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

		$sql=mysqli_query($enlace,"SELECT * FROM tipo_personal $fil") or die ("Error: ".mysqli_error($enlace));
		$cant_registros =mysqli_num_rows($sql);
		$paginado = intval($cant_registros / $cantidad);

		$sql = "SELECT * FROM tipo_personal $fil";
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
						<th width="12%" align="center"></th>
					</tr>
				</thead>
			<?php
			$i = 1;
			//($result);
			if($cant_registros>0){
				while ($tipo = mysqli_fetch_assoc($result)) {
				?>
					<tr>
						<td><?= $i ?></td>
						<td><?=utf8_encode($tipo["tipo"])?></td>
						<td align="center">
							<button type="button" class="btn btn-danger btn-sm" title="Eliminar Tipo" data-toggle="modal" data-target="#modal-tipo<?=$tipo["idtipopersonal"];?>"><i class="fa fa-trash-o fa-lg"></i></button>
							<button type="button" class="btn btn-info btn-sm" title="Modificar Tipo" onclick="modTipo(<?= "'".$tipo["idtipopersonal"]."','".utf8_encode($tipo["tipo"])."'" ?>)"><i class="fa fa-edit fa-lg"></i></button>
						</td>
					</tr>
				<!--Modal de eliminar almacen-->
				<div id="modal-tipo<?=$tipo["idtipopersonal"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-sm">
				    <div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title">Alerta</h4>
						</div> <!--Header-->
						<div class="modal-body">
							<p style="text-align:center;">¿Eliminar tipo: "<?=utf8_encode($tipo["tipo"])?>" ?</p>
						</div> <!--Body-->
				    	<div class="modal-footer">
				        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$tipo["idtipopersonal"];?>')" data-dismiss="modal">Eliminar</button>
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