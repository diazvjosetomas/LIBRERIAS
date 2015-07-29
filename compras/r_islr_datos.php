<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");
session_start();

//======> Variables
$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["idproveedor"]))
	$idproveedor 		=$_REQUEST["idproveedor"];

if(!empty($_REQUEST["tipofactura"]))
	$tipofactura 		=$_REQUEST["tipofactura"];

if(!empty($_REQUEST["fecha_inicio"]))
	$fecha_inicio 		=ConvFecha($_REQUEST["fecha_inicio"]);

if(!empty($_REQUEST["fecha_fin"]))
	$fecha_fin 		=ConvFecha($_REQUEST["fecha_fin"]);

switch ($accion) 
{
	case 'verFacturas':
	//==> Filtros

	$fil="";

	if(!empty($fecha_inicio))
	{
		if($fil=="")
			$fil.=" fecharegistro >= '$fecha_inicio' ";
		else
			$fil.=" AND fecharegistro >= '$fecha_inicio' ";	
	}

	if(!empty($fecha_fin))
	{
		if($fil=="")
			$fil.=" fecharegistro <= '$fecha_fin' ";
		else
			$fil.=" AND fecharegistro <= '$fecha_fin' ";	
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

	$sql = "SELECT * FROM proveedores WHERE idproveedor = $idproveedor";
	//echo $sql; die;
	$result = mysqli_query($enlace,$sql);
	$Proveedor = mysqli_fetch_array($result);

	$sql=mysqli_query($enlace,"SELECT *
							   FROM facturas
							   INNER JOIN users USING(id_user)									  
							   INNER JOIN proveedores USING(idproveedor)
							   $fil 
							   ORDER BY nfactura ASC") or die ("Error: ".mysqli_error($enlace));
	$cant_registros =mysqli_num_rows($sql);
	$paginado = intval($cant_registros / $cantidad);

/*			$sql=mysqli_query($enlace,"SELECT *
									   FROM servicios 
									   $fil 
									   ORDER BY nombre ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));*/
			?>
			<div class="container" style="width:100%;margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Reporte de ISLR</div>
						<div class="panel-body" style="padding:0px;" id="Exportar_tabla">
					<div class="content-panel">
						<table style="  width: 100%;margin: 10px;">
						<tr>
							<td colspan="2" style="text-align: center;"><b>Datos del Agente de Retenci&oacute;n</b></td>
							<td colspan="2" style="text-align: center;"><b>Datos del Contribuyente</b></td>
						</tr>
						<tr>
							<td style="width: 10%;"><b>Razon Social:</b></td>
							<td>Policlinica Andres Bello C.A.</td>
							<td style="width: 10%;"><b>Razon Social:</b></td>
							<td><?= utf8_encode($Proveedor["nombre"]) ?></td>

						</tr>
						<tr>
							<td><b>Numero de RIF:</b></td>
							<td>J-31334670-5</td>
							<td><b>Numero de RIF:</b></td>
							<td style="width: 25%;"><?= $Proveedor["rif"] ?></td>
						</tr>
						<tr>
							<td><b>Numero de NIT:</b></td>
							<td style="text-align:left;">0417830286</td>
							<td><b>Numero de NIT:</b></td>
							<td>???</td>
						</tr>
						<tr>
							<td><b>Domicilio Fiscal:</b></td>
							<td>Av. Andres Bello No. 57, La Cooperativa Maracay, Edo. Aragua - Venezuela</td>
							<td><b>Domicilio Fiscal:</b></td>
							<td><?= utf8_encode($Proveedor["direccion"]); ?></td>
						</tr>
						<tr>
							<td colspan="4">&nbsp;</td>
						</tr>
						</table>
							</table>
							<table class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td align="center" style="background-color:#D3D3D3;"><b>Fecha de Pago y/o Abono en cuenta</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Numero de Factura</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Monto Pagado y/o abonado en cuenta</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Monto Objeto de Retencion</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>%Alicuota</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Impuesto Retenido</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Concepto del Pago</b></td>
							</tr>

							<?

								if($sql)

								{
									$n = 1;
									while($rs=mysqli_fetch_array($sql))

									{
										$islr = $rs["total_factura"] * $_REQUEST["islr"];
											?><tr>
												<td><?=DevuelveFechaTimeStamp($rs["fecha_factura"])?></td>
												<td><?=utf8_encode($rs["nfactura"])?></td>
												<td><?//Monto Pagado y/o abonado en cuenta ?></td>
												<td style="text-align: right;"><?= $rs["base_imponible"] ?></td>
												<td><?= $_SESSION["islr"]; ?>%</td>
												<td style="text-align: right;"><?= number_format($islr,2,",",".") ?></td>
												<td><?//Concepto del Pago ?></td>
											</tr><?
										$n++;
									}

								}

								if($cant_registros<=0)

								{

									?>

									<tr>

										<td colspan="7"><div style="text-align:center;font-size:16px;"><b>No se encontraron resultados.</b></div></td>

									</tr><?

								}

								?>



							</table>

					</div>

						</div>
			<?

	break;

	case 'buscarDetalle':
	?>
	<table class="table table-bordered table-striped table-condensed table-responsive">
	<thead>
		<tr>
			<th>N&ordm;</th>
			<th>Nombre</th>
			<th>Cantidad</th>
			<th>Precio</th>
			<th>Total</th>
		</tr>
	</thead>
	<?php
		$idfactura = $_REQUEST["idfactura"];
		$sql = "SELECT * FROM producto_inventario INNER JOIN productos USING(id_prod) INNER JOIN producto_almacen ON producto_almacen.id_producto_inventario = producto_inventario.idproductoinventario  WHERE idfactura=$idfactura";
		//echo $sql; die();
		$result = mysqli_query($enlace,$sql);
		$n = 0;
		while ($rs = mysqli_fetch_array($result)) {
			$n++;
			?>
			<tr>
				<td><?= $n ?></td>
				<td><?= $rs["nom_prod"] ?></td>
				<td><?= $rs["prec_pro"] ?></td>
				<td><?= $rs["cantidad"] ?></td><!-- Deberia ser la cantidad que se ingreso en el momento de registrar la factura -->
				<td><?= $total ?></td>
			</tr>
			<?php
		}
		?>
	</table>
		<?php
		break;

}

?>