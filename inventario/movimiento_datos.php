<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");

//var_dump($_REQUEST); die();
//======> Variables
$accion 			=$_REQUEST["accion"];
if(!empty($_REQUEST["filtro_cliente"]))
	$filtro_cliente =$_REQUEST["filtro_cliente"];

//===> Datos para guardar un nuevo producto
if(!empty($_REQUEST["codProd"]))
	$codProd 		=$_REQUEST["codProd"];

if(!empty($_REQUEST["nomProd"]))
	$nomProd 		=utf8_decode(str_replace("'","\"",$_REQUEST["nomProd"]));

if(!empty($_REQUEST["descProd"]))
	$descProd 		=utf8_decode(str_replace("'","\"",$_REQUEST["descProd"]));

if(!empty($_REQUEST["idalmacen1"]))
	$idalmacen1 		=$_REQUEST["idalmacen1"];

if(!empty($_REQUEST["idalmacen2"]))
	$idalmacen2 		=$_REQUEST["idalmacen2"];

$fecha = date("Y-m-d H:m:i");

switch ($accion) 

{

	case "guardarMovimiento":

			foreach ($_REQUEST["productos"] as $key => $idproductoinventario) {
				$sql = "SELECT * FROM producto_almacen WHERE id_producto_inventario=".$idproductoinventario." AND idalmacen=".$idalmacen2;
				//echo $sql;
				$result = mysqli_query($enlace,$sql) or die("Error");
				$num = mysqli_num_rows($result);
				$cantidad = $_REQUEST["cant_".$idproductoinventario];
				if($num>0):
					$consulta = "UPDATE producto_almacen SET cantidad=cantidad+$cantidad WHERE id_producto_inventario=".$idproductoinventario." AND idalmacen=".$idalmacen2;
				else:
					$consulta = "INSERT INTO producto_almacen(id_producto_inventario, cantidad, idalmacen) VALUES('$idproductoinventario','$cantidad','$idalmacen2')";
				endif;
				//echo $consulta; die;
				$sql=mysqli_query($enlace,$consulta) or die ("Error: ".mysqli_error($enlace));	
				$consulta = "UPDATE producto_almacen SET cantidad=cantidad-$cantidad WHERE id_producto_inventario=".$idproductoinventario." AND idalmacen=".$idalmacen1;
				//echo $consulta; die;
				$sql=mysqli_query($enlace,$consulta) or die ("Error: ".mysqli_error($enlace));		
				$consulta = "INSERT INTO movimiento_almacen(idalmacen1, idalmacen2, id_producto_inventario, cantidad, fecha) VALUES ('$idalmacen1','$idalmacen2',$idproductoinventario,'$cantidad','$fecha')";
				mysqli_query($enlace,$consulta) or die ("Error: ".mysqli_error($enlace));
			}

			if(!$sql)

				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	

			else

				echo json_encode(array('result' => true,'mensaje'=>"¡Registros guardados exitosamente!"));	

	break;



	case 'eliminarProd':

		//==> Elimino producto

		$sql=mysqli_query($enlace,"DELETE FROM producto_inventario WHERE idproductoinventario=$idProdEli") or die ("Error: ".mysqli_error($enlace));

		

		if($sql)

			echo json_encode(array('result' => true,'mensaje'=>"¡Registro eliminado exitosamente!"));			

		else

			echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	

	break;



	case 'buscarAlmacen':
	$fil = "WHERE idalmacen='$idalmacen1'";
	if($_REQUEST["fil_producto"]!=""):
		$fil .= " AND productos.nom_prod LIKE '%".$_REQUEST["fil_producto"]."%'";
	endif;
	$i=0;

	if ($_POST['pg']=="") 
		$n_pag = 1; 
	else  

		$n_pag=$_POST['pg']; 
	$cantidad=10;
	$inicial = ($n_pag-1) * $cantidad;

		$query = "SELECT *  FROM producto_almacen
					INNER JOIN producto_inventario ON producto_almacen.id_producto_inventario = producto_inventario.idproductoinventario
					INNER JOIN productos ON productos.id_prod = producto_inventario.id_prod
					INNER JOIN almacen USING(idalmacen) $fil";

		//echo $query;

		$sql=mysqli_query($enlace,$query);

		$nReg=mysqli_num_rows($sql);
		?>
		<div class="well">
		<table style="margin-bottom: 8px;">
			<tr>
				<td><b>Producto:</b></td>
				<td><input type="text" class="form-control" id="fil_producto" name="fil_producto"></td>
				<td>&nbsp;<button type="button" class="btn btn-success" onclick="filtrarAlmacen()"><i class="fa fa-search"></i></button></td>
			</tr>
		</table>
		<table class="table table-bordered" style="background: white;">
		<tr>
			<th>N&ordm;</th>
			<th width="60%">Producto</th>
			<th>Cant. Disponible</th>
			<th>Cantidad</th>
			<th></th>
		</tr>
		<?php
		$n = 1;
		while ($rs=mysqli_fetch_array($sql)) {
			?>
			<tr>
				<td><?= $n ?></td>
				<td><?= $rs["nom_prod"] ?></td>
				<td><input type="text" class="form-control" id="cant_disp_<?= $rs["idproductoinventario"] ?>" readonly name="cant_disp_<?= $rs["idproductoinventario"] ?>" value="<?=$rs["cantidad"]?>"></td>
				<td><input type="text" class="form-control" id="cant_<?= $rs["idproductoinventario"] ?>" name="cant_<?= $rs["idproductoinventario"] ?>" value=""></td>
				<td><input type="checkbox" name="productos[]" value="<?= $rs["idproductoinventario"] ?>"></td>
			</tr>
			<?php
			$n++;
		}
?>
		</table>
		</div>
<?php
		paginacion($paginado,$n_pag);
	break;


	case 'verMovimientos':
	//==> Filtros
	$fil="";

	if(!empty($_REQUEST["fil_codigo"]))
	{
		$fil_codigo 	=$_REQUEST["fil_codigo"];
		if($fil=="")
			$fil.=" cod_prod LIKE '%$fil_codigo%' ";
		else
			$fil.=" AND cod_prod LIKE '%$fil_codigo%' ";		
	}

	if(!empty($_REQUEST["fil_nombre"]))
	{
		$fil_nombre 	=$_REQUEST["fil_nombre"];
		if($fil=="")
			$fil.=" nom_prod LIKE '%$fil_nombre%' ";
		else
			$fil.=" AND nom_prod LIKE '%$fil_nombre%' ";	
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

			$consulta = "SELECT *,(SELECT nombre FROM almacen WHERE idalmacen=movimiento_almacen.idalmacen1) almacen1,(SELECT nombre FROM almacen WHERE idalmacen=movimiento_almacen.idalmacen2) almacen2
									   FROM movimiento_almacen
									   INNER JOIN producto_inventario ON movimiento_almacen.id_producto_inventario = producto_inventario.idproductoinventario
									   INNER JOIN productos ON productos.id_prod = producto_inventario.id_prod
									   $fil 
									   ORDER BY idproductoinventario ASC";
			$sql=mysqli_query($enlace,$consulta) or die ("Error: ".mysqli_error($enlace));
			$cant_registros =mysqli_num_rows($sql);
			$paginado = intval($cant_registros / $cantidad);

			$consulta2 = "SELECT *,(SELECT nombre FROM almacen WHERE idalmacen=movimiento_almacen.idalmacen1) almacen1,(SELECT nombre FROM almacen WHERE idalmacen=movimiento_almacen.idalmacen2) almacen2
									   FROM movimiento_almacen
									   INNER JOIN producto_inventario ON movimiento_almacen.id_producto_inventario = producto_inventario.idproductoinventario
									   INNER JOIN productos ON productos.id_prod = producto_inventario.id_prod
									   $fil 
									   ORDER BY idproductoinventario ASC LIMIT $inicial,$cantidad";
			//echo $consulta2; die();
			$sql=mysqli_query($enlace,$consulta2) or die ("Error: ".mysqli_error($enlace));
			?>
					<div class="content-panel">
							<table class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>N</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Producto</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Almac&eacute;n Origen</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Almac&eacute;n Receptor</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Cantidad</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Fecha</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td><?=$n?></td>
												<td><?=utf8_encode($rs["nom_prod"])?></td>
												<td><?=utf8_encode($rs["almacen1"])?></td>
												<td><?=utf8_encode($rs["almacen2"])?></td>
												<td><?=$rs["cantidad"]?></td>
												<td><?=utf8_encode($rs["fecha"])?></td>
											</tr><?
									}
								}?>
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