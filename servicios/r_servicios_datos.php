<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");

//======> Variables
$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["codigo"]))
	$codigo 		=limpiaString($_REQUEST["codigo"]);
if(!empty($_REQUEST["nombre"]))
	$nombre 		=limpiaString($_REQUEST["nombre"]);

switch ($accion) 
{
	case 'verServicios':

	//==> Filtros
	$fil="";

	if(!empty($codigo))
	{
		if($fil=="")
			$fil.=" cod_servicio LIKE '%$codigo%' ";
		else
			$fil.=" AND cod_servicio LIKE '%$codigo%' ";	
	}

	if(!empty($nombres))
	{
		if($fil=="")
			$fil.=" nombre LIKE '%$nombre%' ";
		else
			$fil.=" AND nombre LIKE '%$nombre%' ";		
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

/*			$sql=mysqli_query($enlace,"SELECT *
									   FROM servicios 
									   $fil 
									   ORDER BY nombre ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));*/
			?>
			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Servicios</div>
						<div class="panel-body" style="padding:0px;">
					<div class="content-panel">
							<?
								exportar();
							?>
							<div id="Exportar_tabla">
							<table border="1" width="100%" cellpadding="0" cellspacing="0" class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td colspan="3" align="center"><b>Servicios</b></td>
							</tr>
							<tr>
								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>C&oacute;digo</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Descripci&oacute;n</b></td>
								<td width="20%" align="center" style="background-color:#D3D3D3;"><b>Precio</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td><?=utf8_encode($rs["cod_servicio"])?></td>
												<td><?=utf8_encode($rs["nombre"])?></td>
												<td align="right"><?=number_format($rs["precio"],2,",",".")?></td>
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
						</div>
						<!--Footer-->
						<div class="panel-footer"><?
						//======================> MOSTRAR PAGINACION <======================================
							//paginacion($paginado,$n_pag);
						//========================> FIN PAGINACION <==============================================						
						?>
						</div>
				</div>
			<?
	break;
}
?>