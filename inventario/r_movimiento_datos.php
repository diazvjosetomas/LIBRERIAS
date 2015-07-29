<?

include("../funcionesphp/conex.php");

include("../funcionesphp/funciones.php");



//======> Variables

$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["idalmacen"]))

	$idalmacen 		=$_REQUEST["idalmacen"];



switch ($accion) 

{

	case 'verInventario':



	//==> Filtros

	$fil="";


	if(!empty($idalmacen))

	{

		if($fil=="")

			$fil.=" idalmacen = '$idalmacen' ";

		else

			$fil.=" AND idalmacen = '$idalmacen' ";	

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



			$sql=mysqli_query($enlace,"SELECT *,(SELECT nombre FROM almacen WHERE idalmacen=movimiento_almacen.idalmacen1) almacen1,(SELECT nombre FROM almacen WHERE idalmacen=movimiento_almacen.idalmacen2) almacen2
									   FROM movimiento_almacen
									   INNER JOIN users USING(id_user)
									   INNER JOIN producto_inventario ON movimiento_almacen.id_producto_inventario = producto_inventario.idproductoinventario
									   INNER JOIN productos ON productos.id_prod = producto_inventario.id_prod
									   $fil 
									   ORDER BY idproductoinventario ASC") or die ("Error: ".mysqli_error($enlace));

			$cant_registros =mysqli_num_rows($sql);

			$paginado = intval($cant_registros / $cantidad);



/*			$sql=mysqli_query($enlace,"SELECT *

									   FROM servicios 

									   $fil 

									   ORDER BY nombre ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));*/

			?>

			<div class="container" style="margin-top:30px;">

					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">

						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Movimientos de Almacen</div>

						<div class="panel-body" style="padding:0px;">

					<div class="content-panel">

							<table class="table table-bordered table-striped table-condensed table-responsive">

							<tr>

								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>N</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Usuario</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Producto</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Almacen Origen</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Almacen Receptor</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Cantidad</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Fecha</b></td>

							</tr>

							<?

								if($sql)

								{
									$n = 1;
									while($rs=mysqli_fetch_array($sql))

									{
											?><tr>

												<td><?=$n?></td>

												<td><?=utf8_encode($rs["nombre_usuario"])?></td>

												<td><?=utf8_encode($rs["nom_prod"])?></td>

												<td><?=utf8_encode($rs["almacen1"])?></td>

												<td><?=utf8_encode($rs["almacen2"])?></td>

												<td><?=$rs["cantidad"]?></td>

												<td><?=utf8_encode($rs["fecha"])?></td>

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

}

?>