<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");

//======> Variables
$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["nombre"]))
	$nombre 		=limpiaString($_REQUEST["nombre"]);
if(!empty($_REQUEST["activo"]))
	$activo			=$_REQUEST["activo"];
if(!empty($_REQUEST["id_seguro"]))
	$id_seguro		=$_REQUEST["id_seguro"];

switch ($accion) 
{
	case "guardarSeguro":

		//===> Ingreso seguro medico
		$sql=mysqli_query($enlace,"INSERT INTO
								   seguros_medicos
								   VALUES(NULL,'$nombre','$activo') ") or die ("Error: ".mysqli_error($enlace));

			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"Nuevo seguro agregado"));	

	break;

	case "modificaSeguro":
		$sql=mysqli_query($enlace,"UPDATE seguros_medicos
								   SET 
								   nombre_seguro='$nombre',
								   estatus='$activo'
								   WHERE id_seguro_m='$id_seguro' ");

			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"Seguro modificado"));
	break;

	case "eliminarSeguro":
		$sql=mysqli_query($enlace,"DELETE FROM seguros_medicos WHERE id_seguro_m='$id_seguro' ");

			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"Seguro eliminado"));
	break;

	case 'verSeguros':

	//==> Filtros
	$fil="";
	
	if(!empty($_REQUEST["fil_nombre"]))
	{
		$fil_nombre 	=$_REQUEST["fil_nombre"];
		if($fil=="")
			$fil.=" nombre_seguro LIKE '%$fil_nombre%' ";
		else
			$fil.=" AND nombre_seguro LIKE '%$fil_nombre%' ";	
	}

	if(!empty($_REQUEST["fil_activo"]))
	{
		$fil_activo 	=$_REQUEST["fil_activo"];
		if($fil=="")
			$fil.=" estatus='$fil_activo' ";
		else
			$fil.=" AND estatus='$fil_activo' ";		
	}

	if($fil!="")
		$fil=" WHERE ".$fil;

	//establecemos los limites de la página actual
	$i=0;
	if ($_POST['pg']=="") 
		$n_pag = 1; 
	else  
		$n_pag=$_POST['pg']; 
	$cantidad=10;
	$inicial = ($n_pag-1) * $cantidad;

			$sql=mysqli_query($enlace,"SELECT *
									   FROM seguros_medicos 
									   $fil 
									   ORDER BY nombre_seguro ASC") or die ("Error: ".mysqli_error($enlace));
			$cant_registros =mysqli_num_rows($sql);
			$paginado = intval($cant_registros / $cantidad);

			$sql=mysqli_query($enlace,"SELECT *
									   FROM seguros_medicos 
									   $fil 
									   ORDER BY nombre_seguro ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));
			?>
					<div class="content-panel">
							<table class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td align="center" style="background-color:#D3D3D3;"><b>Nombre seguro</b></td>
								<td width="10%" align="center" style="background-color:#D3D3D3;"><b>Estatus</b></td>
								<td width="12%" align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td><?=utf8_encode($rs["nombre_seguro"])?></td>
												<?
													if($rs["estatus"]==1)
														$estatus="<p style='color:green;'>Habilitado</p>";
													if($rs["estatus"]==2)
														$estatus="<p style='color:red;'>Deshabilitado</p>";
												?>
												<td align="center"><?=$estatus?></td>
												<td width="10%" align="center">
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar seguro" onclick="conf_eliminar('<?=$rs["id_seguro_m"];?>','<?=utf8_encode($rs["nombre_seguro"])?>')"><i class="fa fa-trash-o fa-lg"></i></button>
													<button type="button" class="btn btn-info btn-sm" title="Modificar seguro" onclick="modificar('<?=$rs["id_seguro_m"]?>','<?=utf8_encode(str_replace("\"","",$rs["nombre_seguro"]))?>','<?=$rs["estatus"]?>')"><i class="fa fa-edit fa-lg"></i></button>
												</td>
											</tr><?
									}
								}
								if($cant_registros<=0)
								{
									?>
									<tr>
										<td colspan="4"><div style="text-align:center;font-size:16px;"><b>No hay seguros m&eacute;dicos registrados.</b></div></td>
									</tr><?
								}
								?>

							</table>
					</div>
						</div>
						<script type="text/javascript">
							$('[data-toggle="tooltip"]').tooltip();
						</script>
						<!--Footer-->
						<div class="panel-footer"><?
						//======================> MOSTRAR PAGINACION <======================================
							paginacion($paginado,$n_pag);
						//========================> FIN PAGINACION <==============================================						
						?>
			<?
	break;
}
?>