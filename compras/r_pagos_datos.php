<?

include("../funcionesphp/conex.php");

include("../funcionesphp/funciones.php");



//======> Variables

$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["tipopago"]))
	$tipopago 		=$_REQUEST["tipopago"];

if(!empty($_REQUEST["estatus"]))
	$estatus 		=$_REQUEST["estatus"];



switch ($accion) 

{

	case 'verPagos':
	//==> Filtros
	$fil="";

	if(!empty($tipopago))
	{
		if($fil=="")
			$fil.=" pagos.tipopago = '$tipopago' ";
		else
			$fil.=" AND pagos.tipopago = '$tipopago' ";	
	}

	if(!empty($estatus))
	{
		if($fil=="")
			$fil.=" pagos.estatus = '$estatus' ";
		else
			$fil.=" AND pagos.estatus = '$estatus' ";	
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
									   FROM pagos
									   INNER JOIN users ON users.id_user = pagos.idusuario
									   INNER JOIN facturas USING(idfactura)
									   INNER JOIN proveedores USING(idproveedor)
									   $fil 
									   ORDER BY pagos.fecharegistro ASC") or die ("Error: ".mysqli_error($enlace));

			$cant_registros =mysqli_num_rows($sql);

			$paginado = intval($cant_registros / $cantidad);



/*			$sql=mysqli_query($enlace,"SELECT *

									   FROM servicios 

									   $fil 

									   ORDER BY nombre ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));*/

			?>

			<div class="container" style="margin-top:30px;">

					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">

						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Reporte de Pagos</div>

						<div class="panel-body" style="padding:0px;">

					<div class="content-panel">

							<table class="table table-bordered table-striped table-condensed table-responsive">

							<tr>

								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>N</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>N Factura</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>N Documento</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Importe</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Resta</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Usuario</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Estatus</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Fecha</b></td>

								<td align="center" style="background-color:#D3D3D3;"></td>

							</tr>

							<?

								if($sql)

								{
									$n = 1;
									while($rs=mysqli_fetch_array($sql))

									{
										if($rs["estatus"]==0): $txtestatus = "<b style='color: orange;'>Pendiente</b>"; elseif($rs["estatus"]==1): $txtestatus = "<b style='color: green;'>Aprobado</b>"; elseif($rs["estatus"]==2): $txtestatus = "<b style='color: red;'>Anulado</b>"; endif;
											?><tr>
												<td><?=$n?></td>
												<td><?=utf8_encode($rs["nfactura"])?></td>
												<td><?=utf8_encode($rs["ndocumento"])?></td>
												<td><?=number_format($rs["importe"],2,",",".")?></td>
												<td><?=number_format($rs["resta"],2,",",".")?></td>
												<td><?= utf8_encode($rs["nombre_usuario"]) ?></td>
												<td><?= $txtestatus ?></td>
												<td><?=utf8_encode($rs["fecharegistro"])?></td>
												<td><a onclick="buscaDetalle(<?= $rs["idfactura"] ?>)" style="cursor: pointer;">Detalle</a></td>
											</tr><?
										$n++;
									}

								}

								if($cant_registros<=0)

								{

									?>

									<tr>

										<td colspan="8"><div style="text-align:center;font-size:16px;"><b>No se encontraron resultados.</b></div></td>

									</tr><?

								}

								?>



							</table>

					</div>

						</div>

						<!--Footer-->

						<div class="panel-footer"><?

						//======================> MOSTRAR PAGINACION <======================================

							paginacion($paginado,$n_pag);

						//========================> FIN PAGINACION <==============================================						

						?>

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