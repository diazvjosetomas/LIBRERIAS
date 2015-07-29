<?
include("../funcionesphp/funciones.php");

$accion =$_REQUEST["accion"];

if(!empty($_REQUEST["nusuario"]))
	$nusuario=utf8_decode($_REQUEST["nusuario"]);
if(!empty($_REQUEST["tipo_user"]))
	$tipo_user=$_REQUEST["tipo_user"];
if(!empty($_REQUEST["usuario"]))
	$usuario=utf8_decode($_REQUEST["usuario"]);
if(!empty($_REQUEST["pass"]))
	$pass=md5($_REQUEST["pass"]);
if(!empty($_REQUEST["iduser"]))
	$iduser=$_REQUEST["iduser"];
if(!empty($_REQUEST["id_almacen"]))
	$id_almacen=$_REQUEST["id_almacen"];

switch ($accion) 
{
	case 'guardarUsuario':
		$sql=mysqli_query($enlace,"INSERT INTO users()
								   VALUES(NULL,'$nusuario','$usuario','$pass','$tipo_user','$id_almacen')");
	
		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Usuario registrado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'modificarusuario':
	
	if($pass!=md5("********")) //===> Cambio la contrasenia
		$fil="pass='$pass', ";
	else
		$fil="";

		$sql=mysqli_query($enlace,"UPDATE users 
								   SET
								   nombre_usuario='$nusuario',
								   user='$usuario',
								   $fil
								   tipo_user='$tipo_user',
								   id_almacen='$id_almacen'
								   WHERE
								   id_user='$iduser' ");

		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Usuario actualizado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'eliminarUsuario':
		$sql=mysqli_query($enlace,"DELETE FROM users WHERE id_user=$iduser ");

		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Usuario eliminado exitosamente"));
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
								   FROM users 
								   INNER JOIN tipo_usuarios ON (tipo_usuarios.tipo_user=users.tipo_user) 
								   ORDER BY nombre_usuario,users.tipo_user ASC");
		$cant_registros =mysqli_num_rows($sql);
		$paginado = intval($cant_registros / $cantidad);	

		$sql=mysqli_query($enlace,"SELECT *
								   FROM users 
								   INNER JOIN tipo_usuarios ON (tipo_usuarios.tipo_user=users.tipo_user) 
								   ORDER BY nombre_usuario,users.tipo_user ASC
								   LIMIT $inicial,$cantidad");
			?>
			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Usuarios registrados</div>
						<div class="panel-body" style="padding:0px;">
							<table class="table table-striped table-bordered table-responsive">
							<tr>
								<td width="18%" align="center" style="background-color:#D3D3D3;"><b>Nombre usuario</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Tipo usuario</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td width="50%"><?=utf8_encode($rs["nombre_usuario"])?></td>
												<td width="30%"><?=utf8_encode($rs["nombre"])?></td>
												<?
/*													if($rs["estatus"]==1)
														$estatus="<p style='color:green;'>Habilitado</p>";
													if($rs["estatus"]==2)
														$estatus="<p style='color:red;'>Deshabilitado</p>";*/
												?>
												<!-- <td width="10%" align="center"><?=$estatus;?></td> -->
												<td width="10%" align="center">
												<?
													if($rs["id_user"]!=1) //===> Si el es El Administrador principal, no le muestra los botones de modificaciones
													{
												?>
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar usuario" data-toggle="modal" data-target="#modal-prod<?=$rs["id_user"];?>"><i class="fa fa-trash-o fa-lg"></i></button>

												<!--Modal de eliminar productos-->
													<div id="modal-prod<?=$rs["id_user"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
													  <div class="modal-dialog modal-sm">
													    <div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																<h4>Alerta</h4>
															</div> <!--Header-->
															<div class="modal-body">
																<p style="text-align:center;">¿Eliminar usuario: "<?=utf8_encode($rs["nombre_usuario"])?>" ?</p>
															</div> <!--Body-->
													    	<div class="modal-footer">
													        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$rs["id_user"];?>')" data-dismiss="modal">Eliminar</button>
													        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													      	</div> <!--Footer-->
													    </div> <!--Content-->
													  </div>
													</div>

													<button type="button" class="btn btn-info btn-sm" title="Modificar usuario" onclick="modificar('<?=$rs["id_user"]?>','<?=utf8_encode($rs["nombre_usuario"])?>','<?=utf8_encode($rs["user"])?>','<?=$rs["tipo_user"]?>','<?=utf8_encode($rs["nombre"])?>','<?=$rs["id_almacen"]?>')"><i class="fa fa-edit fa-lg"></i></button>
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