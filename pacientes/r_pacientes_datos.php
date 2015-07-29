<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");

//======> Variables
$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["cedula"]))
	$cedula 		=str_replace(".","",$_REQUEST["cedula"]);
if(!empty($_REQUEST["nombres"]))
	$nombres 		=limpiaString($_REQUEST["nombres"]);
if(!empty($_REQUEST["apellidos"]))
	$apellidos 		=limpiaString($_REQUEST["apellidos"]);

switch ($accion) 
{
	case 'verPacientes':

	//==> Filtros
	$fil="";

	if(!empty($cedula))
	{
		if($fil=="")
			$fil.=" cedula='$cedula' ";
		else
			$fil.=" AND cedula='$cedula' ";		
	}

	if(!empty($nombres))
	{
		if($fil=="")
			$fil.=" nombres LIKE '%$nombres%' ";
		else
			$fil.=" AND nombres LIKE '%$nombres%' ";		
	}
	
	if(!empty($apellidos))
	{
		if($fil=="")
			$fil.=" apellidos LIKE '%$apellidos%' ";
		else
			$fil.=" AND apellidos LIKE '%$apellidos%' ";	
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
									   FROM pacientes 
									   $fil 
									   ORDER BY nombres ASC") or die ("Error: ".mysqli_error($enlace));
			$cant_registros =mysqli_num_rows($sql);
			$paginado = intval($cant_registros / $cantidad);

/*			$sql=mysqli_query($enlace,"SELECT *
									   FROM servicios 
									   $fil 
									   ORDER BY nombre ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));*/
			?>
			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Pacientes</div>
						<div class="panel-body" style="padding:0px;">
					<div class="content-panel">
							<?
								exportar();
							?>
							<div id="Exportar_tabla">
							<table border="1" width="100%" cellpadding="0" cellspacing="0" class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td colspan="4" align="center"><b>Pacientes</b></td>
							</tr>
							<tr>
								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>N&ordm; C&eacute;dula</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Nombres</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Apellidos</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Tel&eacute;fono</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td><?=number_format($rs["cedula"],0,",",".")?></td>
												<td><?=utf8_encode($rs["nombres"])?></td>
												<td><?=utf8_encode($rs["apellidos"])?></td>
												<td align="right"><?=$rs["telefono"]?></td>
											</tr><?
									}
								}
								if($cant_registros<=0)
								{
									?>
									<tr>
										<td colspan="4"><div style="text-align:center;font-size:16px;"><b>No hay pacientes registrados.</b></div></td>
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