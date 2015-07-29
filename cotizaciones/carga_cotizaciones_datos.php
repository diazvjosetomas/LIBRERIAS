<?
include("../funcionesphp/conex.php");
include("../funcionesphp/funciones.php");
session_start();
//======> Variables
$accion 			=$_REQUEST["accion"];
$iduser 			=$_SESSION["idUser"];
$idalmacen 			=$_SESSION["idAlmacen"];

if(!empty($_REQUEST["cedula"]))
	$cedula 	=str_replace(".","",$_REQUEST["cedula"]);

//===> Para la busqueda del producto o servicio
if(!empty($_REQUEST["tipo"]))
	$tipo				=$_REQUEST["tipo"];
if(!empty($_REQUEST["codigo"]))
	$codigo				=$_REQUEST["codigo"];
if(!empty($_REQUEST["id_admision"]))
	$id_admision		=$_REQUEST["id_admision"];
//===> Para modificar la cotizacion
if(!empty($_REQUEST["id_cotizacion"]))
	$id_cotizacion		=$_REQUEST["id_cotizacion"];
//===> Nueva cotizacion
if(!empty($_REQUEST["asunto"]))
	$asunto				=limpiaString($_REQUEST["asunto"]);

switch ($accion) 
{
	case "cargarConsumos":

		$mensaje="";
		$productos=array();
		$i=0;

		//===> Elimino todo lo anterior (Solo si va a modificar)
		if($id_cotizacion!="")
		{
			$elsql=mysqli_query($enlace,"DELETE FROM cotizaciones_contenido WHERE id_cotizacion='$id_cotizacion' ");
			//===> Actualizo asunto de la cotizacion...
			$acAs =mysqli_query($enlace,"UPDATE cotizaciones SET asunto='$asunto' WHERE id_cotizacion='$id_cotizacion' ");
		}
		else //====> Es una nueva cotizacion
		{
			$inssql=mysqli_query($enlace,"INSERT INTO cotizaciones()
										  VALUES(NULL,'$asunto') ");
			$id_cotizacion=mysqli_insert_id($enlace);
		}


		//===> Guardando los detalles de la cotizacion
		foreach ($_REQUEST as $var => $value) 
		{
			$precioProd   =explode("precio_", $var);
			if(count($precioProd)>1)
			{
				//===> Ingreso el detalle uno por uno
				$codProd   				=$precioProd[1];
				$cantidadConsumida 		=$_REQUEST['cant_'.$codProd];
				$existenciaOriginal 	=$_REQUEST['exis_ori_'.$codProd];

				//===> Verifico si es servicio, si es vacio es porque lo es
				if($existenciaOriginal=="")
					$codServicio=$codProd;
				else
					$codServicio="";

					if($existenciaOriginal!=0 && $existenciaOriginal!="") //==> Si es producto
					{
						$sumaP=0;
						$NP=0;
							$ingresaConsumo=mysqli_query($enlace,"INSERT INTO cotizaciones_contenido()
															  	  VALUES
															  	  (NULL,
															  	   '$id_cotizacion',
															  	   '$codProd',
															  	   '$codServicio',
															  	   '$cantidadConsumida') ");
					}
					else //===> Es servicio
					{
						$sumaS=0;
						$NS=0;
							$ingresaConsumo=mysqli_query($enlace,"INSERT INTO cotizaciones_contenido()
															  	  VALUES
															  	  (NULL,
															  	   '$id_cotizacion',
															  	   '$codProd',
															  	   '$codServicio',
															  	   '$cantidadConsumida') ") or die("Error:".mysqli_error($enlace));
					}
				$i++;
			}
		}
	if($ingresaConsumo) 
		echo json_encode(array('result' => true,'mensaje'=>"Cotización cargada correctamente"));	
	else
		echo json_encode(array('result' => false,'mensaje'=>"Hubo un error en su solicitud"));	

	break;

	case "eliminarCotizacion":
		$elcsql=mysqli_query($enlace,"DELETE FROM cotizaciones WHERE id_cotizacion='$id_cotizacion' ");
		$elsql =mysqli_query($enlace,"DELETE FROM cotizaciones_contenido WHERE id_cotizacion='$id_cotizacion' ");

		if($elcsql && $elsql) 
			echo json_encode(array('result' => true,'mensaje'=>"Cotización eliminada correctamente"));	
		else
			echo json_encode(array('result' => false,'mensaje'=>"Hubo un error en su solicitud"));	
	break;

	case 'buscaDatosProducto':
	if($tipo=="P") //===> Producto
	{
		$sql=mysqli_query($enlace,"SELECT * 
								   FROM productos p 
								   INNER JOIN producto_inventario pi ON (p.id_prod=pi.id_prod)
								   LEFT JOIN producto_almacen pa ON (pi.idproductoinventario=pa.id_producto_inventario)
								   LEFT JOIN presentacion_productos pp ON (p.idpresentacion=pp.id_presentacion)
								   WHERE p.id_prod='$codigo' ") or die ("Error: ".mysqli_error($enlace));	
		$rs=mysqli_fetch_assoc($sql);

		//===> Traigo el total de ese producto en general
		$suma=mysqli_query($enlace,"SELECT SUM(pa.cantidad) as suma_general 
									FROM producto_almacen pa
									INNER JOIN producto_inventario pi ON (pa.id_producto_inventario=pi.idproductoinventario)
									WHERE pi.id_prod='{$rs["id_prod"]}' ") or die ("Error: ".mysqli_error($enlace));
		$rsSum=mysqli_fetch_assoc($suma);

		if(!$sql)
			echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
		else
			echo json_encode(array("result"=> true,
								   "tipo"=>"Producto",
								   "cod_prod"=>$rs["id_prod"],
								   "nom_prod"=>utf8_encode($rs["nom_prod"])." ".number_format($rs["cant_presentacion"],0,",",".")." ".$rs["ab_presentacion"],
								   "cantidad_general"=>$rsSum["suma_general"],
								   "cant_prod"=>$rsSum["suma_general"],
								   "prec_prod"=>$rs["prec_pro"]));
	}
	else if($tipo=="S") //===> Servicio
	{
		$sql=mysqli_query($enlace,"SELECT * 
								   FROM servicios s 
								   WHERE s.cod_servicio='$codigo' ") or die ("Error: ".mysqli_error($enlace));
		$rs=mysqli_fetch_assoc($sql);

		if(!$sql)
			echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
		else
			echo json_encode(array("result"=> true,
								   "tipo"=>"Servicio",
								   "cod_prod"=>$rs["cod_servicio"],
								   "nom_prod"=>utf8_encode($rs["nombre"]),
								   "prec_prod"=>$rs["precio"] ));
	}
	break;

	case "buscaDatosAnteriores":
		$subtotal	=0;
		$total 		=0;
		$iva 		=($_SESSION["iva"]/100); 

		$sql=mysqli_query($enlace,"SELECT * FROM cotizaciones_contenido WHERE id_cotizacion='$id_cotizacion' ORDER BY id_prod DESC");
		$existe=mysqli_num_rows($sql);

		//===> Si hay consumos cargados
		if($existe>0)
		{
			?>
			    <table id="tabla-productos" class="table table-striped table-bordered" style="margin-top:30px;width:100%;">
			    <tr>
			    	<td width="50%" align="center" style="background-color:#C7C7C7;"><b>Descripci&oacute;n</b></td>
			    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Prec. Unit.</b></td>
			    	<td width="12%" align="center" style="background-color:#C7C7C7;"><b>Cant. Consumo</b></td>
			    	<td width="4%"  align="center" style="background-color:#C7C7C7;">&nbsp;</td>
			    </tr>
			<?
			//===> Busco cuales fueron los servicios o productos cargados
			while($rs=mysqli_fetch_assoc($sql))
			{
				//===> Busco Productos primero
				if($rs["id_prod"]!=0)
				{
					$buscaProducto=mysqli_query($enlace,"SELECT * 
													     FROM productos p
													     INNER JOIN producto_inventario pi ON (p.id_prod=pi.id_prod) 
													     LEFT JOIN presentacion_productos pp ON (p.idpresentacion=pp.id_presentacion)
													     WHERE p.id_prod='{$rs["id_prod"]}' ");
						$rsP=mysqli_fetch_assoc($buscaProducto);

							//===> Traigo el total de ese producto en general
							$suma=mysqli_query($enlace,"SELECT SUM(pa.cantidad) as suma_general 
														FROM producto_almacen pa
														INNER JOIN producto_inventario pi ON (pa.id_producto_inventario=pi.idproductoinventario)
														WHERE pi.id_prod='{$rsP["id_prod"]}' ") or die ("Error: ".mysqli_error($enlace));
							$rsSum=mysqli_fetch_assoc($suma);

							$subtotal+=($rsP["prec_pro"]*$rs["cantidad"]);
					?>
						<tr id="<?=$rsP["id_prod"];?>" class="tp">
							<td align='left'><?=utf8_encode($rsP["nom_prod"])." ".number_format($rsP["cant_presentacion"],0,"",".")." ".utf8_encode($rsP["ab_presentacion"])?></td>
							<td align='center'><?=number_format($rsP["prec_pro"],2,",",".");?></td>
							<input type="hidden" id="exis_ori_<?=$rsP["id_prod"];?>" name="exis_ori_<?=$rsP["id_prod"];?>" value="<?=$rsSum["suma_general"]?>">
							<input type="hidden" id="precio_<?=$rsP["id_prod"];?>" name="precio_<?=$rsP["id_prod"];?>" value="<?=$rsP["prec_pro"]?>">
							<td align='center' style="vertical-align: middle;">
								<div class='col-xs-4' style='width: 70%;margin-left: 25px;'>
									<input class='form-control' style='width:60px;' id='cant_<?=$rsP["id_prod"];?>' name='cant_<?=$rsP["id_prod"];?>' type='text' value='<?=$rs["cantidad"]?>' onblur="calcula_total('<?=$rsP["id_prod"];?>','<?=$rsP["prec_pro"]?>',this.value,'cant_<?=$rsP["id_prod"]?>','Producto','1')">
								</div>
							</td>
							<td align='center' style='vertical-align: middle;'><i class='fa fa-times' style='color:red;cursor:pointer;font-size: 25px;' onclick='eliminaTrDatos("<?=$rsP["id_prod"];?>")'></i></td>
						</tr>
					<?
				}

				//===> Si es servicio
				if($rs["cod_servicio"]!="")
				{
					$buscaServ=mysqli_query($enlace,"SELECT * 
											  	     FROM servicios s 
											   		 WHERE s.cod_servicio='{$rs["cod_servicio"]}' ") or die ("Error: ".mysqli_error($enlace));	
					$rsS=mysqli_fetch_assoc($buscaServ);

					$subtotal+=($rsS["precio"]*$rs["cantidad"]);
					?>
						<tr id="<?=$rsS["cod_servicio"];?>" class="tp">
							<td align='left'><?=utf8_encode($rsS["nombre"])?></td>
							<td align='center'><?=number_format($rsS["precio"],2,",",".");?></td>
							<input type="hidden" id="exis_ori_<?=$rsS["cod_servicio"];?>" name="exis_ori_<?=$rsS["cod_servicio"];?>" value="">
							<input type="hidden" id="precio_<?=$rsS["cod_servicio"];?>" name="precio_<?=$rsS["cod_servicio"];?>" value="<?=$rsS["precio"]?>">
							<td align='center'>
								<div class='col-xs-4' style='width: 70%;margin-left: 25px;'>
									<input class='form-control' style='width:60px;' id='cant_<?=$rsS["cod_servicio"];?>' name='cant_<?=$rsS["cod_servicio"];?>' type='text' value='<?=$rs["cantidad"]?>' onblur="calcula_total('<?=$rsS["cod_servicio"];?>','<?=$rsS["precio"]?>',this.value,'cant_<?=$rsS["cod_servicio"]?>','Servicio','1')">
								</div>
							<td align='center' style='vertical-align: middle;'>
								<i class='fa fa-times' style='color:red;cursor:pointer;font-size: 25px;' onclick='eliminaTrDatos("<?=$rsS["cod_servicio"];?>")'></i>
							</td>
						</tr>
					<?
				}
			}
				$total=(($subtotal*$iva)+$subtotal);//1.690,80 1.893,70
			?>
				</table>
				<script type="text/javascript">
					$('[data-toggle="tooltip"]').tooltip();

					$("#lab_subtot").html(number_format("<?=$subtotal?>",2,",","."));
					$("#subtotal").val("<?=$subtotal?>");
					$("#lab_precio").html(number_format("<?=$total?>",2,",","."))
					$("#total").val("<?=$total?>");
					//console.log("Subtotal: "+"<?=$subtotal?>"+" Total: "+"<?=$total?>");
				</script>
			<?
		}

	break;

	case 'verCotizaciones':

	//==> Filtros
	$fil="";

	if(!empty($_REQUEST["fil_codigo"]))
	{
		$fil_codigo 	=$_REQUEST["fil_codigo"];
		if($fil=="")
			$fil.=" cod_servicio LIKE '%$fil_codigo%' ";
		else
			$fil.=" AND cod_servicio LIKE '%$fil_codigo%' ";		
	}
	
	if(!empty($_REQUEST["fil_nombre"]))
	{
		$fil_nombre 	=$_REQUEST["fil_nombre"];
		if($fil=="")
			$fil.=" nombre LIKE '%$fil_nombre%' ";
		else
			$fil.=" AND nombre LIKE '%$fil_nombre%' ";	
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
									   FROM cotizaciones 
									   $fil 
									   ORDER BY asunto ASC") or die ("Error: ".mysqli_error($enlace));
			$cant_registros =mysqli_num_rows($sql);
			$paginado = intval($cant_registros / $cantidad);

			$sql=mysqli_query($enlace,"SELECT *
									   FROM cotizaciones 
									   $fil 
									   ORDER BY asunto ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));
			?>
					<div class="content-panel">
							<table class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td width="" align="center" style="background-color:#D3D3D3;"><b>Descripci&oacute;n</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td><?=utf8_encode($rs["asunto"])?></td>
												<td width="10%" align="center">
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar cotizaci&oacute;n" onclick="conf_eliminar('<?=$rs["id_cotizacion"]?>','<?=utf8_encode($rs["asunto"])?>')"><i class="fa fa-trash-o fa-lg"></i></button>
													<button type="button" class="btn btn-info btn-sm" title="Modificar cotizaci&oacute;n" onclick="buscaDatosAnteriores('<?=$rs["id_cotizacion"]?>','<?=utf8_encode($rs["asunto"])?>')"><i class="fa fa-edit fa-lg"></i></button>
												</td>
											</tr><?
									}
								}
								if($cant_registros<=0)
								{
									?>
									<tr>
										<td colspan="4"><div style="text-align:center;font-size:16px;"><b>No hay cotizaciones precargadas.</b></div></td>
									</tr><?
								}
								?>

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