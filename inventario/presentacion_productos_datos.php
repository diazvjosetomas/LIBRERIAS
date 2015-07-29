<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");

//======> Variables
$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["nomPresentacion"]))
	$nomPresentacion =utf8_decode(str_replace("'","\"",$_REQUEST["nomPresentacion"]));
if(!empty($_REQUEST["abPresentacion"]))
	$abPresentacion  =utf8_decode(str_replace("'","\"",$_REQUEST["abPresentacion"]));
if(!empty($_REQUEST["idPresentacion"]))
	$idPresentacion  =$_REQUEST["idPresentacion"];

switch ($accion) 
{
	case "guardarPresentacion":

		//===> Guardando el servicio
		$sql=mysqli_query($enlace,"INSERT INTO presentacion_productos()
			  					   VALUES (NULL,'$nomPresentacion','$abPresentacion') ") or die ("Error: ".mysqli_error($enlace));
			
			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"¡Registros guardados exitosamente!"));	

	break;

	case 'modificarPresentacion':
		//===> Modifico presentacion
		$sql=mysqli_query($enlace,"UPDATE presentacion_productos 
			  SET nom_presentacion='$nomPresentacion',
			  	  ab_presentacion='$abPresentacion'
			  WHERE id_presentacion=$idPresentacion") or die ("Error: ".mysqli_error($enlace));

		if($sql)
			echo json_encode(array('result' => true,'mensaje'=>"¡Registros modificados exitosamente!"));			
		else
			echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
	break;

	case 'eliminarPresentacion':
		//==> Elimino servicio
		$sql=mysqli_query($enlace,"DELETE FROM presentacion_productos WHERE id_presentacion=$idPresentacion") or die ("Error: ".mysqli_error($enlace));
		
		if($sql)
			echo json_encode(array('result' => true,'mensaje'=>"¡Registro eliminado exitosamente!"));			
		else
			echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
	break;

	case 'verificaCodigo':
		$sql=mysqli_query($enlace,"SELECT * FROM servicios WHERE cod_servicio='$filCod'");
		$rs=mysqli_fetch_array($sql);
		$nReg=mysqli_num_rows($sql);

		if($nReg>0)
			echo json_encode(array('result' => true,'mensaje'=>"¡Este c&oacute;digo ya se encuentra registrado! <br> <em>".utf8_encode($rs["nombre"])."</em>"));
		else		
			echo json_encode(array('result' => false));
	break;

	case 'verPresentacion':

	//==> Filtros
	$fil="";

	if(!empty($_REQUEST["fil_nombre"]))
	{
		$fil_nombre 	=$_REQUEST["fil_nombre"];
		if($fil=="")
			$fil.=" nom_presentacion LIKE '%$fil_nombre%' ";
		else
			$fil.=" AND nom_presentacion LIKE '%$fil_nombre%' ";		
	}
	
	if(!empty($_REQUEST["fil_ab"]))
	{
		$fil_ab 		=$_REQUEST["fil_ab"];
		if($fil=="")
			$fil.=" ab_presentacion LIKE '%$fil_ab%' ";
		else
			$fil.=" AND ab_presentacion LIKE '%$fil_ab%' ";	
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
									   FROM presentacion_productos 
									   $fil 
									   ORDER BY nom_presentacion ASC") or die ("Error: ".mysqli_error($enlace));
			$cant_registros =mysqli_num_rows($sql);
			$paginado = intval($cant_registros / $cantidad);

			$sql=mysqli_query($enlace,"SELECT *
									   FROM presentacion_productos 
									   $fil 
									   ORDER BY nom_presentacion ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));
			?>
					<div class="content-panel">
							<table class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td width="60%" align="center" style="background-color:#D3D3D3;"><b>Presentaci&oacute;n</b></td>
								<td width="20%" align="center" style="background-color:#D3D3D3;"><b>Abreviatura</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td><?=utf8_encode($rs["nom_presentacion"])?></td>
												<td><?=utf8_encode($rs["ab_presentacion"])?></td>
												<td width="10%" align="center">
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar producto" data-toggle="modal" data-target="#modal-prod<?=$rs["id_presentacion"];?>"><i class="fa fa-trash-o fa-lg"></i></button>

												<!--Modal de eliminar presentacion-->
													<div id="modal-prod<?=$rs["id_presentacion"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
													  <div class="modal-dialog modal-sm">
													    <div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																<h4>Alerta</h4>
															</div> <!--Header-->
															<div class="modal-body">
																<p style="text-align:center;">¿Eliminar presentaci&oacute;n: "<?=utf8_encode($rs["nom_presentacion"])?>" ?</p>
															</div> <!--Body-->
													    	<div class="modal-footer">
													        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$rs["id_presentacion"];?>')" data-dismiss="modal">Eliminar</button>
													        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													      	</div> <!--Footer-->
													    </div> <!--Content-->
													  </div>
													</div>

													<button type="button" class="btn btn-info btn-sm" title="Modificar presentaci&oacute;n" onclick="modificar('<?=$rs["id_presentacion"]?>','<?=utf8_encode(str_replace("\"","",$rs["nom_presentacion"]))?>','<?=utf8_encode(str_replace("\"","",$rs["ab_presentacion"]))?>')"><i class="fa fa-edit fa-lg"></i></button>
												</td>
											</tr><?
									}
								}
								if($cant_registros<=0)
								{
									?>
									<tr>
										<td colspan="4"><div style="text-align:center;font-size:16px;"><b>No hay presentaciones registradas.</b></div></td>
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