<?
include("../funcionesphp/funciones.php");

$accion =$_REQUEST["accion"];

if(!empty($_REQUEST["modulo"]))
	$modulo =utf8_decode($_REQUEST["modulo"]);
if(!empty($_REQUEST["icono"]))
	$icono =utf8_decode($_REQUEST["icono"]);
else
	$icono="";
if(!empty($_REQUEST["orden"]))
	$orden=$_REQUEST["orden"];
if(!empty($_REQUEST["id_modulo"]))
	$id_modulo=$_REQUEST["id_modulo"];
if(!empty($_REQUEST["estatus"]))
	$estatus=$_REQUEST["estatus"];

switch ($accion) 
{
	case 'guardarModulo':
		$sql=mysqli_query($enlace,"INSERT INTO modulos_sistema()
								   VALUES(NULL,'$modulo','$icono','$orden','$estatus')");
	
		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Módulo registrado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'modificarModulo':
		$sql=mysqli_query($enlace,"UPDATE modulos_sistema 
								   SET
								   modulo='$modulo',
								   icono='$icono',
								   orden='$orden',
								   estatus='$estatus'
								   WHERE
								   id_modulo='$id_modulo' ");

		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Módulo actualizado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'eliminarModulo':
		$sql=mysqli_query($enlace,"DELETE FROM modulos_sistema WHERE id_modulo=$id_modulo ");

		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Módulo eliminado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'verModulos':
		//establecemos los limites de la página actual
		if ($_POST['pg']=="") 
			$n_pag = 1; 
		else  
			$n_pag=$_POST['pg']; 
		$cantidad=10;
		$inicial = ($n_pag-1) * $cantidad;

		$sql=mysqli_query($enlace,"SELECT * FROM modulos_sistema ORDER BY orden ASC");
		$cant_registros =mysqli_num_rows($sql);
		$paginado = intval($cant_registros / $cantidad);	

		$sql=mysqli_query($enlace,"SELECT * FROM modulos_sistema ORDER BY orden ASC LIMIT $inicial,$cantidad");
			?>
			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">M&oacute;dulos del sistema</div>
						<div class="panel-body" style="padding:0px;">
							<table class="table table-striped table-bordered table-responsive">
							<tr>
								<td width="18%" align="center" style="background-color:#D3D3D3;"><b>M&oacute;dulo</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Orden</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>estatus</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td width="70%"><?=utf8_encode($rs["modulo"])?></td>
												<td align="right"><?=$rs["orden"]?></td>
												<?
													if($rs["estatus"]==1)
														$estatus="<p style='color:green;'>Habilitado</p>";
													if($rs["estatus"]==2)
														$estatus="<p style='color:red;'>Deshabilitado</p>";
												?>
												<td width="10%" align="center"><?=$estatus;?></td>
												<td width="10%" align="center">
												<?
													if($rs["id_modulo"]!=1) //===> Si el es modulo maestro, no le muestra los botones de modificaciones
													{
												?>
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar m&oacute;dulo" data-toggle="modal" data-target="#modal-prod<?=$rs["id_modulo"];?>"><i class="fa fa-trash-o fa-lg"></i></button>

												<!--Modal de eliminar productos-->
													<div id="modal-prod<?=$rs["id_modulo"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
													  <div class="modal-dialog modal-sm">
													    <div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																<h4>Alerta</h4>
															</div> <!--Header-->
															<div class="modal-body">
																<p style="text-align:center;">¿Eliminar m&oacute;dulo: "<?=utf8_encode($rs["modulo"])?>" ?</p>
															</div> <!--Body-->
													    	<div class="modal-footer">
													        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$rs["id_modulo"];?>')" data-dismiss="modal">Eliminar</button>
													        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													      	</div> <!--Footer-->
													    </div> <!--Content-->
													  </div>
													</div>

													<button type="button" class="btn btn-info btn-sm" title="Modificar m&oacute;dulo" onclick="modificar('<?=$rs["id_modulo"]?>','<?=utf8_encode($rs["modulo"])?>','<?=utf8_encode($rs["icono"])?>','<?=$rs["orden"]?>','<?=$rs["estatus"]?>')"><i class="fa fa-edit fa-lg"></i></button>
												</td>
												<?}
												else
												{
													echo "&nbsp;";
												}
												?>
											</tr><?
									}
								}?>
							</table>
						</div>
						<!--Footer-->
						<div class="panel-footer"><?
						//======================> MOSTRAR PAGINACION <======================================
							paginacion($paginado,$n_pag);
						//========================> FIN PAGINACION <==============================================						
						?></div>
					</div>
			</div>
			<?
	break;
}

?>