<?
include("../funcionesphp/funciones.php");

$accion =$_REQUEST["accion"];

if(!empty($_REQUEST["programa"]))
	$programa =utf8_decode($_REQUEST["programa"]);
if(!empty($_REQUEST["ruta"]))
	$ruta =utf8_decode($_REQUEST["ruta"]);
if(!empty($_REQUEST["idmodulo"]))
	$idmodulo =utf8_decode($_REQUEST["idmodulo"]);
if(!empty($_REQUEST["orden"]))
	$orden=$_REQUEST["orden"];
if(!empty($_REQUEST["id_programa"]))
	$id_programa=$_REQUEST["id_programa"];
if(!empty($_REQUEST["estatus"]))
	$estatus=$_REQUEST["estatus"];

switch ($accion) 
{
	case 'guardarPrograma':
		$sql=mysqli_query($enlace,"INSERT INTO programas_sistema()
								   VALUES(NULL,'$idmodulo','$programa','$ruta','$orden','0','$estatus')");

		$id=mysqli_insert_id($enlace);
		
		//===> Le agrego el opt
		$actOpt=mysqli_query($enlace,"UPDATE programas_sistema
									  SET 
									  opt=$id
									  WHERE
									  id_programa=$id ");
	
		if($sql && $actOpt)
			echo json_encode(array("result"=>true,"mensaje"=>"Programa registrado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'modificarPrograma':
		$sql=mysqli_query($enlace,"UPDATE programas_sistema 
								   SET
								   id_modulo='$idmodulo',
								   nombre_programa='$programa',
								   ruta='$ruta',
								   orden='$orden',
								   estatus='$estatus'
								   WHERE
								   id_programa='$id_programa' ");

		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Programa actualizado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'eliminarPrograma':
		$sql=mysqli_query($enlace,"DELETE FROM programas_sistema WHERE id_programa=$id_programa ");

		if($sql)
			echo json_encode(array("result"=>true,"mensaje"=>"Programa eliminado exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'verProgramas':

	//==> Filtros
	$fil="";

	if(!empty($_REQUEST["fil_nombre"]))
	{
		$fil_nombre 	=$_REQUEST["fil_nombre"];
		if($fil=="")
			$fil.=" nombre_programa LIKE '%$fil_nombre%' ";
		else
			$fil.=" AND nombre_programa LIKE '%$fil_nombre%' ";		
	}
	
	if(!empty($_REQUEST["fil_modulo"]))
	{
		$fil_modulo 	=$_REQUEST["fil_modulo"];
		if($fil=="")
			$fil.=" ps.id_modulo=$fil_modulo ";
		else
			$fil.=" AND ps.id_modulo=$fil_modulo ";	
	}

	if($fil!="")
		$fil=" WHERE ".$fil;

		//establecemos los limites de la página actual
		if ($_POST['pg']=="") 
			$n_pag = 1; 
		else  
			$n_pag=$_POST['pg']; 
		$cantidad=10;
		$inicial = ($n_pag-1) * $cantidad;

		$sql=mysqli_query($enlace,"SELECT ps.*,ms.modulo
								   FROM programas_sistema ps 
								   INNER JOIN modulos_sistema ms ON (ps.id_modulo=ms.id_modulo) 
								   $fil
								   ORDER BY ms.modulo,ps.orden ASC") or die ("Error: ".mysqli_error($enlace));
		$cant_registros =mysqli_num_rows($sql);
		$paginado = intval($cant_registros / $cantidad);	

		$sql=mysqli_query($enlace,"SELECT ps.*,ms.modulo
								   FROM programas_sistema ps 
								   INNER JOIN modulos_sistema ms ON (ps.id_modulo=ms.id_modulo) 
								   $fil
								   ORDER BY ms.modulo,ps.orden ASC 
								   LIMIT $inicial,$cantidad");
			?>
						<table class="table table-striped table-bordered table-responsive">
							<tr>
								<td width="18%" align="center" style="background-color:#D3D3D3;"><b>Programa</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>M&oacute;dulo</b></td>
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
												<td width="50%"><?=utf8_encode($rs["nombre_programa"])?></td>
												<td width="30%"><?=utf8_encode($rs["modulo"])?></td>
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
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar programa" onclick="conf_eliminar('<?=$rs["id_programa"];?>','<?=utf8_encode($rs["nombre_programa"])?>')"><i class="fa fa-trash-o fa-lg"></i></button>
													<button type="button" class="btn btn-info btn-sm" title="Modificar programa" onclick="modificar('<?=$rs["id_programa"]?>','<?=$rs["id_modulo"]?>','<?=utf8_encode($rs["modulo"])?>','<?=utf8_encode($rs["nombre_programa"])?>','<?=utf8_encode($rs["ruta"])?>','<?=$rs["orden"]?>','<?=$rs["estatus"]?>')"><i class="fa fa-edit fa-lg"></i></button>
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
						<!--Footer-->
						<div class="panel-footer"><?
						//======================> MOSTRAR PAGINACION <======================================
							paginacion($paginado,$n_pag);
						//========================> FIN PAGINACION <==============================================						
						?></div>
			<?
	break;
}

?>