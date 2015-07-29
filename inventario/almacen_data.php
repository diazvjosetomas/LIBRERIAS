<?php
include("../funcionesphp/funciones.php");
//var_dump($_REQUEST); die();
$idalmacen 		= $_REQUEST["idalmacen"];
$nombre 		= utf8_decode($_REQUEST["nombre"]);
$descripcion  	= utf8_decode($_REQUEST["descripcion"]);
$estatus   		= $_REQUEST["estatus"];
$responsable   	= $_REQUEST["responsable"];
$accion   		= $_REQUEST["accion"];

//Filtros

$filnombre 		= $_REQUEST["filnombre"];
if($filnombre!=""){
	$fil = "nombre LIKE '%$filnombre%'";
}
$filestatus 	= $_REQUEST["filestatus"];
if($filestatus!=""){
	if($fil!=""){ $fil .= " AND "; }
	$fil = $fil."estatus = '$filestatus'";
}
$filresponsalble = $_REQUEST["filresponsalble"];
if($filresponsalble!=""){
	if($fil!=""){ $fil .= " AND "; }
	$fil = $fil."id_user = '$filresponsalble'";
}

if($fil!=""){ $fil = "WHERE ".$fil; }

switch ($accion) {
	case 'guardar':
		$sql = "INSERT INTO almacen(nombre, descripcion, estatus, id_user) VALUES ('$nombre','$descripcion','$estatus','$responsable')";
		$result = mysqli_query($enlace,$sql);
		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El almacen se ha registrado satisfactoriamente."));
		}
		break;

	case 'modificar':
		$sql = "UPDATE almacen SET nombre='$nombre',descripcion='$descripcion',estatus='$estatus',id_user='$responsable' WHERE idalmacen='$idalmacen'";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El almacen se ha modificado satisfactoriamente."));
		}
		break;

	case 'eliminar':
		$sql = "DELETE FROM almacen WHERE idalmacen=$idalmacen";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El almacen se ha eliminado satisfactoriamente."));
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

		$sql=mysqli_query($enlace,"SELECT * FROM almacen $fil") or die ("Error: ".mysqli_error($enlace));
		$cant_registros =mysqli_num_rows($sql);
		$paginado = intval($cant_registros / $cantidad);

		$sql = "SELECT * FROM almacen LEFT JOIN users USING(id_user) $fil";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			?>
			<table class='table table-bordered table-striped'>
				<thead style='background-color: rgba(208, 213, 216, 0.69);'>
					<tr>
						<th>N&ordm;</th>
						<th>Nombre</th>
						<th>Estatus</th>
						<th>Opci&oacute;n</th>
					</tr>
				</thead>
			<?php
			$i = 1;
			//($result);
			if($cant_registros>0){
				while ($almacen = mysqli_fetch_assoc($result)) {
				?>
				<tr>
					<td><?= $i ?></td>
					<td><?= utf8_encode($almacen["nombre"]) ?></td>
					<td width="15%"><?php if($almacen["estatus"]==1): echo "<b style='color: darkseagreen;'>Activo</b>"; else: echo "<b style='color: orange;'>Inactivo</b>"; endif; ?></td>
					<td width="12%" align="center">
						<button type="button" class="btn btn-danger btn-sm" title="Eliminar almacen" data-toggle="modal" data-target="#modal-almacen<?=$almacen["idalmacen"];?>"><i class="fa fa-trash-o fa-lg"></i></button>
						<button type="button" class="btn btn-info btn-sm" title="Modificar almacen" onclick="modAlmacen(<?= "'".$almacen["idalmacen"]."','".utf8_encode($almacen["nombre"])."','".utf8_encode($almacen["descripcion"])."',".$almacen["estatus"].",'".$almacen["id_user"]."','".utf8_encode($almacen["user"])."'" ?>)"><i class="fa fa-edit fa-lg"></i></button>
					</td>
				</tr>
				<!--Modal de eliminar almacen-->
				<div id="modal-almacen<?=$almacen["idalmacen"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-sm">
				    <div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title">Alerta</h4>
						</div> <!--Header-->
						<div class="modal-body">
							<p style="text-align:center;">¿Eliminar almac&eacute;n: "<?=utf8_encode($almacen["nombre"])?>" ?</p>
						</div> <!--Body-->
				    	<div class="modal-footer">
				        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$almacen["idalmacen"];?>')" data-dismiss="modal">Eliminar</button>
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
			</div>
			</div>
			</div>
			<?php
		}
		break;
}


?>