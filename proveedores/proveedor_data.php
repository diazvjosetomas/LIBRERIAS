<?php
include("../funcionesphp/funciones.php");
//var_dump($_REQUEST); die();
$idproveedor 	= $_REQUEST["idproveedor"];
$nombre 		= $_REQUEST["nombre"];
$descripcion  	= $_REQUEST["descripcion"];
$contacto 		= $_REQUEST["contacto"];
$direccion  	= $_REQUEST["direccion"];
$rif		  	= $_REQUEST["rif"];
$tipopersona  	= $_REQUEST["tipopersona"];
$retencion_iva 	= $_REQUEST["retencion_iva"];
$estatus   		= $_REQUEST["estatus"];
$tipo 		   	= $_REQUEST["tipo"];
$telefono 		= str_replace("-", "", $_REQUEST["telefono"]);
$telefono2 		= str_replace("-", "", $_REQUEST["telefono2"]);
$email 			= $_REQUEST["email"];
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
$filtipopersona = $_REQUEST["filtipopersona"];
if($filtipopersona!=""){
	if($fil!=""){ $fil .= " AND "; }
	$fil = $fil."tipo_persona = '$filtipopersona'";
}

if($fil!=""){ $fil = "WHERE ".$fil; }

switch ($accion) {
	case 'guardar':
		$sql = "INSERT INTO proveedores(nombre, descripcion, contacto, direccion, rif, tipo_persona, estatus, tipo, telefono, telefono2, email,retencion_iva) VALUES ('$nombre','$descripcion','$contacto','$direccion','$rif','$tipopersona','$estatus','$tipo','$telefono','$telefono2','$email','$retencion_iva')";
		$result = mysqli_query($enlace,$sql);
		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El proveedor se ha registrado satisfactoriamente."));
		}
		break;

	case 'modificar':
		$sql = "UPDATE proveedores SET nombre='$nombre',descripcion='$descripcion',contacto='$contacto',direccion='$direccion',rif='$rif',tipo_persona='$tipopersona',estatus='$estatus',tipo='$tipo',telefono='$telefono',telefono2='$telefono2',email='$email',retencion_iva='$retencion_iva' WHERE idproveedor='$idproveedor'";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El proveedor se ha modificado satisfactoriamente."));
		}
		break;

	case 'eliminar':
		$sql = "DELETE FROM proveedores WHERE idproveedor=$idproveedor";
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			echo json_encode(array("result"=>true,"msg"=>"El proveedor se ha eliminado satisfactoriamente."));
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

		$sql=mysqli_query($enlace,"SELECT * FROM proveedores $fil") or die ("Error: ".mysqli_error($enlace));
		$cant_registros =mysqli_num_rows($sql);
		$paginado = intval($cant_registros / $cantidad);

		$sql = "SELECT * FROM proveedores $fil";
		//echo $sql; die();
		$result = mysqli_query($enlace,$sql);

		if(!$result){
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error."));
		}else{
			?>
			<table class='table table-bordered table-striped'>
				<thead style='background-color: rgba(208, 213, 216, 0.69);'>
					<tr>
						<th style="width:5%;">N&ordm;</th>
						<th style="text-align:center;">Nombre proveedor</th>
						<th style="text-align:center;width:12%;">Estatus</th>
						<th style="text-align:center;width:10%;">Opci&oacute;n</th>
					</tr>
				</thead>
			<?php
			$i = 1;
			//($result);
			if($cant_registros>0){
				while ($proveedor = mysqli_fetch_assoc($result)) {
				?>
				<tr>
					<td><?= $i ?></td>
					<td><?= $proveedor["nombre"] ?></td>
					<td><?php if($proveedor["estatus"]==1): echo "<b style='color: darkseagreen;'>Activo</b>"; else: echo "<b style='color: orange;'>Inactivo</b>"; endif; ?></td>
					<td>
						<button type="button" class="btn btn-danger btn-sm" title="Eliminar almacen" data-toggle="modal" data-target="#modal-proveedor<?= $proveedor["idproveedor"] ?>"><i class="fa fa-trash-o fa-lg"></i></button>
						<button type="button" class="btn btn-info btn-sm" title="Modificar almacen" onclick="modProveedor(<?= "'".$proveedor["idproveedor"]."','".$proveedor["nombre"]."','".$proveedor["descripcion"]."','".$proveedor["contacto"]."','".$proveedor["direccion"]."','".$proveedor["rif"]."','".$proveedor["tipo_persona"]."',".$proveedor["estatus"].",'".$proveedor["tipo"]."','".$proveedor["telefono"]."','".$proveedor["telefono2"]."','".$proveedor["email"]."'" ?>)"><i class="fa fa-edit fa-lg"></i></button>
					</td>
				</tr>
				<!--Modal de eliminar proveedor-->
				<div id="modal-proveedor<?=$proveedor["idproveedor"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-sm">
				    <div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4>Alerta</h4>
						</div> <!--Header-->
						<div class="modal-body">
							<p style="text-align:center;">¿Eliminar proveedor: "<?=utf8_encode($proveedor["nombre"])?>" ?</p>
						</div> <!--Body-->
				    	<div class="modal-footer">
				        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$proveedor["idproveedor"];?>')" data-dismiss="modal">Eliminar</button>
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