<?

include("../funcionesphp/conex.php");

include("../funcionesphp/funciones.php");



//======> Variables

$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["tipofactura"]))

	$tipofactura 		=$_REQUEST["tipofactura"];



switch ($accion) 

{

	case 'verCompras':



	//==> Filtros

	$fil="";


	if(!empty($tipofactura))

	{

		if($fil=="")

			$fil.=" tipofactura = '$tipofactura' ";

		else

			$fil.=" AND tipofactura = '$tipofactura' ";	

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

			?>

			<div class="container" style="margin-top:30px;">

					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">

						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Reporte de Facturas</div>

						<div class="panel-body" style="padding:0px;">

					<div class="content-panel">

							<table class="table table-bordered table-striped table-condensed table-responsive">

							<tr>

								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>N</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>N Factura</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>N control</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Proveedor</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Usuario</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Fecha</b></td>

								<td align="center" style="background-color:#D3D3D3;"></td>

							</tr>

							<?

								if($sql)

								{
									$n = 1;
									while($rs=mysqli_fetch_array($sql))

									{
											?><tr>

												<td><?=$n?></td>

												<td><?=utf8_encode($rs["nfactura"])?></td>

												<td><?=utf8_encode($rs["ncontrol"])?></td>

												<td><?=utf8_encode($rs["nombre"])?></td>

												<td><?=utf8_encode($rs["nombre_usuario"])?></td>

												<td><?=utf8_encode($rs["fecha_factura"])?></td>

												<td><a onclick="buscaDetalle(<?= $rs["idfactura"] ?>)" style="cursor: pointer;">Detalle</a></td>

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