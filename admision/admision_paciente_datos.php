<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");

//======> Variables
$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["filtro_paciente"]))
	$filtro_paciente 	=str_replace(".","",$_REQUEST["filtro_paciente"]);
if(!empty($_REQUEST["ced_paciente_reg"]))
	$ced_paciente_reg	=str_replace(".","",$_REQUEST["ced_paciente_reg"]);
if(!empty($_REQUEST["nom_paciente_reg"]))
	$nom_paciente_reg	=limpiaString($_REQUEST["nom_paciente_reg"]);
if(!empty($_REQUEST["ape_paciente_reg"]))
	$ape_paciente_reg	=limpiaString($_REQUEST["ape_paciente_reg"]);
if(!empty($_REQUEST["dir_paciente_reg"]))
	$dir_paciente_reg	=limpiaString($_REQUEST["dir_paciente_reg"]);
if(!empty($_REQUEST["tel_paciente_reg"]))
	$tel_paciente_reg	=$_REQUEST["tel_paciente_reg"];
//===> Para el ingreso
if(!empty($_REQUEST["cedula"]))
	$cedula				=$_REQUEST["cedula"];
if(!empty($_REQUEST["fecha_ingreso"]))
	$fecha_ingreso		=ConvFecha($_REQUEST["fecha_ingreso"]);
if(!empty($_REQUEST["hora_ingreso"]))
	$hora_ingreso   	=$_REQUEST["hora_ingreso"];
if(!empty($_REQUEST["obs"]))
	$obs				=limpiaString($_REQUEST["obs"]);
if(!empty($_REQUEST["asegurado"]) || $_REQUEST["asegurado"]==0)
	$asegurado			=$_REQUEST["asegurado"];
if(!empty($_REQUEST["seguro"]))
	$seguro				=$_REQUEST["seguro"];
//===> Datos del seguro
if(!empty($_REQUEST["clave"]))
	$clave				=$_REQUEST["clave"];
if(!empty($_REQUEST["monto_cubierto"]))
	$monto_cubierto		=formateaMonto($_REQUEST["monto_cubierto"]);



switch ($accion) 
{
	case "ingresarPaciente":
		//===> Actualizo los valores de clave y monto cubierto
		$act=mysqli_query($enlace,"UPDATE pacientes 
								   SET 
								   monto_cubierto='$monto_cubierto',
								   clave_seguro='$clave'
								   WHERE cedula='$cedula' ");

		//===> Ingreso el paciente a admision
		$sql=mysqli_query($enlace,"INSERT INTO admision_paciente()
			  					   VALUES (NULL,'$cedula','$fecha_ingreso','$hora_ingreso','$obs','1','1') ") or die ("Error: ".mysqli_error($enlace));

			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"Paciente ingresado"));	

	break;
	
	case "guardarPaciente":

		//===> Guardando el paciente
		$sql=mysqli_query($enlace,"INSERT INTO pacientes()
			  					   VALUES (NULL,'$ced_paciente_reg','$nom_paciente_reg','$ape_paciente_reg','$dir_paciente_reg','$tel_paciente_reg','$asegurado','$seguro') ") or die ("Error: ".mysqli_error($enlace));

		$sqlpaciente=mysqli_query($enlace,"SELECT * FROM pacientes WHERE cedula='$ced_paciente_reg' ");
		$paciente=mysqli_fetch_assoc($sqlpaciente);

			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'cedula'=>str_replace(".","",$paciente["cedula"]),'nombres'=>utf8_encode($paciente["nombres"]),'apellidos'=>utf8_encode($paciente["apellidos"])));	

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

	case 'busca_paciente':
		$sql=mysqli_query($enlace,"SELECT * 
								   FROM pacientes
								   LEFT JOIN seguros_medicos sm USING (id_seguro_m)
								   WHERE cedula=$filtro_paciente ") or die ("Error: ".mysqli_error($enlace));
		$nfilas=mysqli_num_rows($sql);
		$datos=mysqli_fetch_array($sql);

		if($nfilas>0)
			echo json_encode(array("result"=> true,
								   "cedula"=>$datos["cedula"],
								   "nombres"=>utf8_encode($datos["nombres"]),
								   "apellidos"=>utf8_encode($datos["apellidos"]),
								   "asegurado"=>$datos["asegurado"],
								   "seguro"=>utf8_encode($datos["nombre_seguro"])));			
		else
			echo json_encode(array('result' => false,'mensaje'=>"Paciente no encontrado"));	
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