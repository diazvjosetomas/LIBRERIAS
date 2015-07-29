<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");

//======> Variables
$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["codServicio"]))
	$codServicio 	=$_REQUEST["codServicio"];
if(!empty($_REQUEST["nomServicio"]))
	$nomServicio 	=utf8_decode(str_replace("'","\"",$_REQUEST["nomServicio"]));
if(!empty($_REQUEST["descServicio"]))
	$descServicio 	=utf8_decode(str_replace("'","\"",$_REQUEST["descServicio"]));
if(!empty($_REQUEST["precServicio"]))
	$precServicio 	=formateaMonto($_REQUEST["precServicio"]);
if(!empty($_REQUEST["filCod"]))
	$filCod 		=$_REQUEST["filCod"];
if(!empty($_REQUEST["idServicio"]))
	$idServicio 	=$_REQUEST["idServicio"];

switch ($accion) 
{
	case "guardarServicio":

		//===> Guardando el servicio
		$sql=mysqli_query($enlace,"INSERT INTO servicios()
			  					   VALUES (NULL,'$codServicio','$nomServicio','$descServicio','$precServicio') ") or die ("Error: ".mysqli_error($enlace));
			
			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"¡Registros guardados exitosamente!"));	

	break;

	case 'modificarServicio':
		//===> Modifico producto
		$sql=mysqli_query($enlace,"UPDATE servicios 
			  SET cod_servicio='$codServicio',
			  	  nombre='$nomServicio',
			  	  descripcion='$descServicio',
			  	  precio='$precServicio'
			  WHERE id_servicio=$idServicio") or die ("Error: ".mysqli_error($enlace));

		if($sql)
			echo json_encode(array('result' => true,'mensaje'=>"¡Registros modificados exitosamente!"));			
		else
			echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
	break;

	case 'eliminarServicio':
		//==> Elimino servicio
		$sql=mysqli_query($enlace,"DELETE FROM servicios WHERE id_servicio=$idServicio") or die ("Error: ".mysqli_error($enlace));
		
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
			echo json_encode(array('result' => true,'mensaje'=>"¡Este código ya se encuentra registrado! ".utf8_encode($rs["nombre"])));
		else		
			echo json_encode(array('result' => false));
	break;

	case "buscaSubCategorias":
		?>
		    <select class="form-control" id="id_sub_cat" name="id_sub_cat" data-live-search="true">
		        <option value=""></option>
		        <?php
		        $sql = mysqli_query($enlace,"SELECT * FROM sub_categoria_productos WHERE id_cat_prod=$idCat") or die ("Error: ".mysqli_error($enlace));
		        while($rs=mysqli_fetch_assoc($sql)) 
		        {
		        	if($rs["id_sub_cat_prod"]==$idSubCat_Fil)
		        		$selected="selected";
		        	else
		        		$selected="";
		        ?>
		            <option <?=$selected?> value="<?= $rs["id_sub_cat_prod"] ?>"><?=utf8_encode($rs["sub_categoria"]);?></option>
		        <?
		        }
		        ?>
		    </select>						
		<?
	break;

	case 'verServicios':

	//==> Filtros
	$fil="";

	if(!empty($_REQUEST["fil_codigo"]))
	{
		$fil_codigo 	=$_REQUEST["fil_codigo"];
		if($fil=="")
			$fil.=" cod_servicio LIKE '%$fil_codigo%' ";
		else
			$fil.=" AND cod_servicio LIKE '%$fil_codigo%' ";		
	}
	
	if(!empty($_REQUEST["fil_nombre"]))
	{
		$fil_nombre 	=$_REQUEST["fil_nombre"];
		if($fil=="")
			$fil.=" nombre LIKE '%$fil_nombre%' ";
		else
			$fil.=" AND nombre LIKE '%$fil_nombre%' ";	
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
									   FROM servicios 
									   $fil 
									   ORDER BY nombre ASC") or die ("Error: ".mysqli_error($enlace));
			$cant_registros =mysqli_num_rows($sql);
			$paginado = intval($cant_registros / $cantidad);

			$sql=mysqli_query($enlace,"SELECT *
									   FROM servicios 
									   $fil 
									   ORDER BY nombre ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));
			?>
					<div class="content-panel">
							<table class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>C&oacute;digo</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Servicio</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td><?=$rs["cod_servicio"]?></td>
												<td><p style="cursor:pointer;" data-toggle="tooltip" data-placement="bottom" data-original-title="Ver descripci&oacute;n" onclick="abreDesc('<?=utf8_encode(str_replace("\"","",$rs["nombre"]))?>','<?=utf8_encode(str_replace("\"","",$rs["descripcion"]))?>')"><?=utf8_encode($rs["nombre"])?></p></td>
												<td width="10%" align="center">
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar producto" data-toggle="modal" data-target="#modal-prod<?=$rs["id_servicio"];?>"><i class="fa fa-trash-o fa-lg"></i></button>

												<!--Modal de eliminar servicios-->
													<div id="modal-prod<?=$rs["id_servicio"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
													  <div class="modal-dialog modal-sm">
													    <div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																<h4>Alerta</h4>
															</div> <!--Header-->
															<div class="modal-body">
																<p style="text-align:center;">¿Eliminar servicio: "<?=utf8_encode($rs["nombre"])?>" ?</p>
															</div> <!--Body-->
													    	<div class="modal-footer">
													        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$rs["id_servicio"];?>')" data-dismiss="modal">Eliminar</button>
													        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													      	</div> <!--Footer-->
													    </div> <!--Content-->
													  </div>
													</div>

													<button type="button" class="btn btn-info btn-sm" title="Modificar servicio" onclick="modificar('<?=$rs["id_servicio"]?>','<?=$rs["cod_servicio"]?>','<?=utf8_encode(str_replace("\"","",$rs["nombre"]))?>','<?=utf8_encode(str_replace("\"","",$rs["descripcion"]))?>','<?=number_format($rs["precio"],2,",",".")?>')"><i class="fa fa-edit fa-lg"></i></button>
												</td>
											</tr><?
									}
								}
								if($cant_registros<=0)
								{
									?>
									<tr>
										<td colspan="4"><div style="text-align:center;font-size:16px;"><b>No hay servicios registrados.</b></div></td>
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