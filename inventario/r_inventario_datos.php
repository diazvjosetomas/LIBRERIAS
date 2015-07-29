<?

include("../funcionesphp/conex.php");

include("../funcionesphp/funciones.php");



//======> Variables

$accion 			=$_REQUEST["accion"];


if(!empty($_REQUEST["nombre_producto"]))

	$nombre_producto 		=limpiaString($_REQUEST["nombre_producto"]);

if(!empty($_REQUEST["idalmacen"]))

	$idalmacen 		=$_REQUEST["idalmacen"];



switch ($accion) 

{

	case 'verInventario':



	//==> Filtros

	$fil="";


	if(!empty($nombre_producto))

	{

		if($fil=="")

			$fil.=" productos.nom_prod LIKE '%$nombre_producto%' ";

		else

			$fil.=" AND productos.nom_prod LIKE '%$nombre_producto%' ";		

	}

	

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



			$sql=mysqli_query($enlace,"SELECT *
									   FROM producto_almacen
									   INNER JOIN producto_inventario ON producto_almacen.id_producto_inventario = producto_inventario.idproductoinventario
									   INNER JOIN productos ON productos.id_prod = producto_inventario.id_prod
									   INNER JOIN almacen USING(idalmacen)

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

						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Inventario General</div>

						<div class="panel-body" style="padding:0px;">

					<div class="content-panel">

							<table class="table table-bordered table-striped table-condensed table-responsive">

							<tr>

								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>N</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Producto</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Ubicaci&oacute;n</b></td>

								<td align="center" style="background-color:#D3D3D3;"><b>Cantidad</b></td>

							</tr>

							<?

								if($sql)

								{
									$n = 1;
									while($rs=mysqli_fetch_array($sql))

									{
											?><tr>

												<td><?=$n?></td>

												<td><?=utf8_encode($rs["nom_prod"])?></td>

												<td><?=utf8_encode($rs["nombre"])?></td>

												<td><?=$rs["cantidad"]?></td>

											</tr><?
										$n++;
									}

								}

								if($cant_registros<=0)

								{

									?>

									<tr>

										<td colspan="4"><div style="text-align:center;font-size:16px;"><b>No se encontraron resultados.</b></div></td>

									</tr><?

								}

								?>



							</table>

					</div>

						</div>

						<!--Footer-->

						<div class="panel-footer"><?

						//======================> MOSTRAR PAGINACION <======================================

							//paginacion($paginado,$n_pag);

						//========================> FIN PAGINACION <==============================================						

						?>

						</div>

			<?

	break;

}

?>