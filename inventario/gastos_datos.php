<?

include("../funcionesphp/conex.php");

include("../funcionesphp/funciones.php");



//======> Variables

$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["filtro_cliente"]))

	$filtro_cliente =$_REQUEST["filtro_cliente"];



//===> Datos para guardar un nuevo producto

if(!empty($_REQUEST["cod_producto"]))

	$codProd 		=$_REQUEST["cod_producto"];

if(!empty($_REQUEST["nom_prod"]))

	$nomProd 		=utf8_decode(str_replace("'","\"",$_REQUEST["nom_prod"]));

if(!empty($_REQUEST["desc_prod"]))

	$descProd 		=utf8_decode(str_replace("'","\"",$_REQUEST["desc_prod"]));

if(!empty($_REQUEST["idCat"]))

	$idCat 			=$_REQUEST["idCat"];

if(!empty($_REQUEST["idSubCat"]))

	$idSubCat 		=$_REQUEST["idSubCat"];

if(!empty($_REQUEST["idProdMod"]))

	$idProdMod 		=$_REQUEST["idProdMod"];

if(!empty($_REQUEST["idProdEli"]))

	$idProdEli 		=$_REQUEST["idProdEli"];

if(!empty($_REQUEST["idSubCat_Fil"]))

	$idSubCat_Fil 	=$_REQUEST["idSubCat_Fil"];

if(!empty($_REQUEST["filCod"]))
	$filCod 		=$_REQUEST["filCod"];

if(!empty($_REQUEST["f_rec"]))
	$f_rec 			=ConvFecha($_REQUEST["f_rec"]);

if(!empty($_REQUEST["f_lot"]))
	$f_lot 			=ConvFecha($_REQUEST["f_lot"]);

if(!empty($_REQUEST["idpresentacion"]))
	$idpresentacion 		=$_REQUEST["idpresentacion"];

if(!empty($_REQUEST["presentacion"]))
	$cant_presentacion 		=$_REQUEST["presentacion"];

if(!empty($_REQUEST["cant_minima"]))
	$cant_minima 		=$_REQUEST["cant_minima"];

if(!empty($_REQUEST["nfactura"]))
	$nfactura 		=$_REQUEST["nfactura"];

if(!empty($_REQUEST["ncontrol"]))
	$ncontrol 		=$_REQUEST["ncontrol"];

if(!empty($_REQUEST["idtipopago"]))
	$idtipopago 		=$_REQUEST["idtipopago"];

if(!empty($_REQUEST["idproveedor"]))
	$idproveedor 		=$_REQUEST["idproveedor"];

if(!empty($_REQUEST["fecha_factura"]))
	$fecha_factura 		=$_REQUEST["fecha_factura"];
	$aux = explode("/", $fecha_factura);
	$fecha_factura = $aux[2]."-".$aux[1]."-".$aux[0];

if(!empty($_REQUEST["fecha_vencimiento"]))
	$fecha_vencimiento 		=$_REQUEST["fecha_vencimiento"];
	$aux = explode("/", $fecha_vencimiento);
	$fecha_vencimiento = $aux[2]."-".$aux[1]."-".$aux[0];

if(!empty($_REQUEST["f_rec"]))
	$f_rec 		=$_REQUEST["f_rec"];
	$aux = explode("/", $f_rec);
	$f_rec = $aux[2]."-".$aux[1]."-".$aux[0];

if(!empty($_REQUEST["f_lot"]))
	$f_lot 		=$_REQUEST["f_lot"];
	$aux = explode("/", $f_lot);
	$f_lot = $aux[2]."-".$aux[1]."-".$aux[0];

if(!empty($_REQUEST["idgrupogasto"]))
	$idgrupogasto 		=$_REQUEST["grupo_gastos"];

if(!empty($_REQUEST["observacion"]))
	$observacion 		=$_REQUEST["observacion"];

$idusuario = $_REQUEST["idusuario"];

$estatus = 1;
$tipofactura = 2;

switch ($accion) 

{
	case 'guardarFactura':
		$sql = "INSERT INTO facturas(nfactura, ncontrol, tipopago, fecha_factura, idproveedor,id_user,estatus,tipofactura,observacion) VALUES ('$nfactura','$ncontrol','$idtipopago','$fecha_factura','$idproveedor','$idusuario','$estatus','$tipofactura','$observacion')";
		$result = mysqli_query($enlace,$sql) or die("Error: ".mysqli_error($enlace));
		$idfactura = mysqli_insert_id($enlace);
		$i = 0;
		foreach ($idgrupogasto as $key => $idgrupo) {
			$cantidad 	= $_REQUEST["cantidad_prod"];
			$precio 	= $_REQUEST["precio_prod"];
			$concepto 	= $_REQUEST["concepto"];
			$sql_inventario = "INSERT INTO detalle_gastos(idfactura, idgrupogasto, cantidad, precio, concepto) VALUES ('$idfactura','".$idgrupo."','".$cantidad[$i]."','".$precio[$i]."','".$concepto[$i]."')";
			$result = mysqli_query($enlace,$sql_inventario) or die("Error: ".mysqli_error());
			$i++;
		}

		if(!$result)
			echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
		else
			echo json_encode(array('result' => true,'mensaje'=>"¡Registros guardados exitosamente!"));	

	break;

	case "guardarProducto":

			//===> Guardando el producto
			$consulta = "INSERT INTO productos VALUES (NULL,'$codProd','$nomProd','$descProd','$idCat','$idSubCat',$idpresentacion,'$cant_presentacion','$cant_minima')";
			//echo $consulta; die;
			$sql=mysqli_query($enlace,$consulta) or die ("Error: ".mysqli_error($enlace));		

			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"¡Registros guardados exitosamente!"));	

	break;

	case 'guardarProveedor':
		$nombre			= $_REQUEST["nombre"];
		$descripcion	= $_REQUEST["descripcion"];
		$contacto		= $_REQUEST["contacto"];
		$direccion		= $_REQUEST["direccion"];
		$rif			= $_REQUEST["rif"];
		$tipo_persona	= $_REQUEST["tipo_persona"];
		$estatus		= $_REQUEST["estatus"];
		$tipo			= $_REQUEST["tipo"];
		$telefono		= $_REQUEST["telefono"];
		$telefono2		= $_REQUEST["telefono2"];
		$email			= $_REQUEST["email"];
		$sql = "INSERT INTO proveedores(nombre, descripcion, contacto, direccion, rif, tipo_persona, estatus, tipo, telefono, telefono2, email) VALUES ('$nombre','$descripcion','$contacto','$direccion','$rif','$tipo_persona','$estatus','$tipo','$telefono','$telefono2','$email')";
		$result=mysqli_query($enlace,$sql) or die ("Error: ".mysqli_error($enlace));

		if(!$result)
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



	case 'verificaCodigo':

		$query = "SELECT * FROM productos INNER JOIN presentacion_productos ON presentacion_productos.id_presentacion = productos.idpresentacion  WHERE cod_prod='$filCod'";

		//echo $query;

		$sql=mysqli_query($enlace,$query);

		$rs=mysqli_fetch_array($sql);

		$nReg=mysqli_num_rows($sql);



		if($nReg>0){

			echo json_encode(array('result' => true,'id_prod' => $rs["id_prod"],'nom_prod' => $rs["nom_prod"],'desc_prod' => utf8_encode($rs["desc_prod"]),'id_cat_prod'=> $rs["id_cat_prod"],'id_sub_cat_prod'=>$rs["id_sub_cat_prod"],'cant_presentacion'=>$rs["cant_presentacion"],'idpresentacion'=>$rs["idpresentacion"],'presentacion'=>$rs["ab_presentacion"]));

		}

		else{

			echo json_encode(array('result' => false));

		}

	break;



	case "buscaSubCategorias":

		?>

		    <select class="form-control" id="id_sub_cat" name="id_sub_cat" data-live-search="true">

		        <option value=""></option>

		        <?php

		        $sql = mysqli_query($enlace,"SELECT * FROM sub_categoria_productos WHERE id_cat_prod=$idCat") or die ("Error: ".mysqli_error($enlace));

		        while($rs=mysqli_fetch_assoc($sql)) 

		        {

		        	if($rs["id_sub_cat_prod"]==$idSubCat_Fil)

		        		$selected="selected";

		        	else

		        		$selected="";

		        ?>

		            <option <?=$selected?> value="<?= $rs["id_sub_cat_prod"] ?>"><?=utf8_encode($rs["sub_categoria"]);?></option>

		        <?

		        }

		        ?>

		    </select>						

		<?

	break;



	case 'verFacturas':



	//==> Filtros

	$fil="tipofactura = 2";



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


			$consulta = "SELECT *
							FROM facturas
							$fil 
							ORDER BY nfactura ASC";
			$sql=mysqli_query($enlace,$consulta) or die ("Error: ".mysqli_error($enlace));

			$cant_registros =mysqli_num_rows($sql);

			$paginado = intval($cant_registros / $cantidad);


			$consulta2 = "SELECT *
							FROM facturas
							$fil 
							ORDER BY nfactura ASC LIMIT $inicial,$cantidad";
			//echo $consulta2; die();
			$sql=mysqli_query($enlace,$consulta2) or die ("Error: ".mysqli_error($enlace));

			?>

					<div class="content-panel">

							<table class="table table-bordered table-striped table-condensed table-responsive">

							<tr>
								<td align="center" style="background-color:#D3D3D3;"><b>N Factura</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>N Control</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Fecha Factura</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Total</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>

							<?

								if($sql)

								{

									while($rs=mysqli_fetch_array($sql))

									{

											?><tr>
												<td><?=$rs["nfactura"]?></td>
												<td><?=$rs["ncontrol"]?></td>
												<td align="right" width="10%"><?=DevuelveFecha($rs["fecha_factura"],0,",",".")?></td>
												<td align="center" width="10%"><?=$rs["Total"]?></td>
												<td width="10%">
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar producto" data-toggle="modal" data-target="#modal-prod<?=$rs["id_prod"];?>"><i class="fa fa-trash-o fa-lg"></i></button>
												<!--Modal de eliminar productos-->
													<div id="modal-prod<?=$rs["id_prod"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
													  <div class="modal-dialog modal-sm">
													    <div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																<h4>Alerta</h4>
															</div> <!--Header-->
															<div class="modal-body">
																<p style="text-align:center;">¿Eliminar producto: "<?=utf8_encode($rs["nom_prod"])?>" ?</p>
															</div> <!--Body-->
													    	<div class="modal-footer">
													        	<button type="button" class="btn btn-danger" onclick="eliProd('<?=$rs["idproductoinventario"];?>')" data-dismiss="modal">Eliminar</button>
													        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													      	</div> <!--Footer-->
													    </div> <!--Content-->
													  </div>
													</div>
													<!--<button type="button" class="btn btn-info btn-sm" title="Modificar categor&iacute;a" onclick="modProd('<?=$rs["idproductoinventario"]?>','<?=$rs["id_prod"]?>',<?=$rs["cod_prod"]?>','<?=utf8_encode(str_replace("\"","",$rs["nom_prod"]))?>','<?=utf8_encode(str_replace("\"","",$rs["desc_prod"]))?>','<?=$rs["prec_prod"]?>','<?=number_format($rs["cant_prod"],0,",",".")?>','<?=$rs["id_cat_prod"]?>','<?=$rs["categoria"]?>','<?=$rs["id_sub_cat_prod"]?>','<?=DevuelveFecha($rs["fecha_recepcion"])?>','<?=DevuelveFecha($rs["fecha_lote"])?>','<?=$rs["idalmacen"]?>')"><i class="fa fa-edit fa-lg"></i></button>-->

												</td>

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