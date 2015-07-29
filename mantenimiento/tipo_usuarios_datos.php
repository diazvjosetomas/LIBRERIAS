<?
include("../funcionesphp/funciones.php");

$accion =$_REQUEST["accion"];

if(!empty($_REQUEST["tusuario"]))
	$tusuario =utf8_decode($_REQUEST["tusuario"]);
if(!empty($_REQUEST["tipo_user"]))
	$tipo_user =$_REQUEST["tipo_user"];

switch ($accion) 
{
	case 'guardarUsuario':

		//===> Busca el ultimo usuario
		$buscaId=mysqli_query($enlace,"SELECT * FROM tipo_usuarios ORDER BY tipo_user DESC LIMIT 1");
		$rs=mysqli_fetch_assoc($buscaId);

		$tipoUser=$rs["tipo_user"]+1; //==> Sumo un numero al id


		$sql=mysqli_query($enlace,"INSERT INTO tipo_usuarios()
								   VALUES('$tipoUser','$tusuario')");
	
		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Tipo de usuario registrado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'modificarusuario':
		$sql=mysqli_query($enlace,"UPDATE tipo_usuarios 
								   SET
								   nombre='$tusuario'
								   WHERE
								   tipo_user='$tipo_user' ");

		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Tipo de usuario actualizado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'eliminarUsuario':
		$sql=mysqli_query($enlace,"DELETE FROM tipo_usuarios WHERE tipo_user=$tipo_user");

		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Tipo de usuario eliminado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'verUsuarios':
		//establecemos los limites de la página actual
		if ($_POST['pg']=="") 
			$n_pag = 1; 
		else  
			$n_pag=$_POST['pg']; 
		$cantidad=10;
		$inicial = ($n_pag-1) * $cantidad;

		$sql=mysqli_query($enlace,"SELECT *
								   FROM tipo_usuarios 
								   ORDER BY nombre ASC");
		$cant_registros =mysqli_num_rows($sql);
		$paginado = intval($cant_registros / $cantidad);	

		$sql=mysqli_query($enlace,"SELECT *
								   FROM tipo_usuarios  
								   ORDER BY nombre 
								   LIMIT $inicial,$cantidad");
			?>
			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Tipos de usuarios</div>
						<div class="panel-body" style="padding:0px;">
							<table class="table table-striped table-bordered table-responsive">
							<tr>
								<td width="18%" align="center" style="background-color:#D3D3D3;"><b>Tipo de usuario</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td width="80%"><?=utf8_encode($rs["nombre"])?></td>
												<?
/*													if($rs["estatus"]==1)
														$estatus="<p style='color:green;'>Habilitado</p>";
													if($rs["estatus"]==2)
														$estatus="<p style='color:red;'>Deshabilitado</p>";*/
												?>
												<!-- <td width="10%" align="center"><?=$estatus;?></td> -->
												<td width="" align="center">
												<?
													if($rs["tipo_user"]!=0) //===> Si el Administrador, no le muestra los botones de modificaciones
													{
												?>
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar tipo de usuario" data-toggle="modal" data-target="#modal-prod<?=$rs["tipo_user"];?>"><i class="fa fa-trash-o fa-lg"></i></button>

												<!--Modal de eliminar productos-->
													<div id="modal-prod<?=$rs["tipo_user"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
													  <div class="modal-dialog modal-sm">
													    <div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																<h4>Alerta</h4>
															</div> <!--Header-->
															<div class="modal-body">
																<p style="text-align:center;">¿Eliminar tipo de usuario: "<?=utf8_encode($rs["nombre"])?>" ?</p>
															</div> <!--Body-->
													    	<div class="modal-footer">
													        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$rs["tipo_user"];?>')" data-dismiss="modal">Eliminar</button>
													        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													      	</div> <!--Footer-->
													    </div> <!--Content-->
													  </div>
													</div>

													<button type="button" class="btn btn-info btn-sm" title="Modificar tipo de usuario" onclick="modificar('<?=$rs["tipo_user"]?>','<?=utf8_encode($rs["nombre"])?>')"><i class="fa fa-edit fa-lg"></i></button>
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