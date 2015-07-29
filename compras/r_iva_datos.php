<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");
session_start();


//======> Variables

$accion 			=$_REQUEST["accion"];
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
	$tiposalida = $_REQUEST["tiposalida"];
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
			if($tiposalida==2):
				$fontsize = "font-size: 10px;";
			else:
				$fontsize = "";
			endif;
			?>

			<div class="container" id="Exportar_tabla" style="width:100%;margin-top:30px; <?= $fontsize ?>">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Reporte de IVA</div>
						<div class="panel-body" style="padding:0px;">
					<div class="content-panel">
							<table class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>N</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Fecha</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>N Comprobante</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>N Factura</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>N control Factura</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>N Nota de Debito</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>N Nota de Credito</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Tipo de Transacc.</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>N Factura Afectada</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Total Compras incluyendo el IVA</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Compras sin derecho a Credito IVA</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Base Imponible</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>% Alicuota</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Impuesto IVA</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>IVA Retenido</b></td>
							</tr>

							<?
								if($sql)
								{
									$n = 1;
									$total1=0;
									$totalexento = 0;
									$total2=0;
									$total3=0;
									while($rs=mysqli_fetch_array($sql))
									{
										$iva = $rs["total_factura"] * $_REQUEST["iva"];
										$total1 +=  $rs["total_factura"];
										if($rs["exento"]):
											$totalexento += $rs["base_imponible"];
										else:
											$total2 +=  $rs["base_imponible"];
										endif;
										$total3 +=  $iva;
											?><tr>

												<td><?=$n?></td>
												<td><?=DevuelveFecha($rs["fecha_factura"])?></td>
												<td><?//=utf8_encode($rs["ndocumento"])?></td>
												<td><?=utf8_encode($rs["nfactura"])?></td>
												<td><?=utf8_encode($rs["ncontrol"])?></td>
												<td><?//=//Nota de debito ?></td>
												<td><?//=//Nota de credito ?></td>
												<td><?//=//Tipo de Transaccion ?></td>
												<td><?//=//Numero de Factura Afectada ?></td>
												<td style="text-align: right;"><?= number_format($rs["total_factura"],2,",",".")  ?></td>
												<td style="text-align: right;"><?php if($rs["exento"]): echo number_format($rs["base_imponible"],2,",","."); endif; ?></td>
												<td style="text-align: right;"><?php if(!$rs["exento"]): echo number_format($rs["base_imponible"],2,",","."); endif; ?></td>
												<td><?= $_SESSION["iva"]; ?>%</td>
												<td style="text-align: right;"><?= number_format($iva,2,",",".") ?></td>
												<td style="text-align: right;"><?= number_format($iva,2,",",".") ?></td>
											</tr><?
										$n++;
									}
								?>
								<tr>
									<td colspan="9"></td>
									<td><?= number_format($total1,2,",",".") ?></td>
									<td><?= number_format($totalexento,2,",",".")  ?></td>
									<td><?= number_format($total2,2,",",".")  ?></td>
									<td></td>
									<td><?= number_format($total3,2,",",".")  ?></td>
									<td><?= number_format($total3,2,",",".")  ?></td>
								</tr>
								<?php
								}
								if($cant_registros<=0)
								{
									?>
									<tr>
										<td colspan="20"><div style="text-align:center;font-size:16px;"><b>No se encontraron resultados.</b></div></td>
									</tr><?
								}
								?>
							</table>

					</div>

</div>

<?php
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