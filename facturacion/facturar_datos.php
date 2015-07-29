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
//===> Para cerrar la cuenta
if(!empty($_REQUEST["cerrar"]))
	$cerrar				=$_REQUEST["cerrar"];

//===> para ingresar un nuevo paciente
if(!empty($_REQUEST["ced_paciente_reg"]))
	$ced_paciente_reg	=str_replace(".","",$_REQUEST["ced_paciente_reg"]);
if(!empty($_REQUEST["nom_paciente_reg"]))
	$nom_paciente_reg	=limpiaString($_REQUEST["nom_paciente_reg"]);
if(!empty($_REQUEST["ape_paciente_reg"]))
	$ape_paciente_reg	=limpiaString($_REQUEST["ape_paciente_reg"]);
if(!empty($_REQUEST["dir_paciente_reg"]))
	$dir_paciente_reg	=limpiaString($_REQUEST["dir_paciente_reg"]);
if(!empty($_REQUEST["tel_paciente_reg"]))
	$tel_paciente_reg	=$_REQUEST["tel_paciente_reg"];

switch ($accion) 
{
	case "cargarConsumos":

		$mensaje="";
		$productos=array();
		$i=0;

		//===> Guardando los detalles de los consumos
		foreach ($_REQUEST as $var => $value) 
		{
			$precioProd   =explode("precio_", $var);
			if(count($precioProd)>1)
			{
				//===> Ingreso el consumo uno por uno
				$codProd   				=$precioProd[1];
				$cantidadConsumida 		=$_REQUEST['cant_'.$codProd];
				$existenciaOriginal 	=$_REQUEST['exis_ori_'.$codProd];

				//===> Verifico si es servicio, si es vacio es porque lo es
				if($existenciaOriginal=="")
					$codServicio=$codProd;
				else
					$codServicio="";

					//===> Busco si ese producto fue ingresado antes y si tiene cantidad nueva a ingresar
					if($existenciaOriginal!=0 && $existenciaOriginal!="") //==> Si es producto
					{
						$sumaP=0;
						$NP=0;
						$sqlBusq=mysqli_query($enlace,"SELECT * 
													   FROM consumos_paciente 
													   WHERE id_admision='$id_admision'
													   AND id_producto_almacen='$codProd' ") or die("Error:".mysqli_error($enlace));
						$NP=mysqli_num_rows($sqlBusq);

						$rsB=mysqli_fetch_assoc($sqlBusq);
						if($NP>0) //===> Si ya hay... Se le suma la cantidad
						{	
							//echo "Existe";
							//===> Hago la suma
							$sumaP=$cantidadConsumida+$rsB["cantidad_consumida"];
							//echo $cantidadConsumida;
							$actProd=mysqli_query($enlace,"UPDATE
														   consumos_paciente
														   SET
														   cantidad_consumida='$sumaP',
														   fecha_consumo='".date("Y-m-d")."'
														   WHERE 
														   id_consumo='{$rsB["id_consumo"]}' ") or die("Error:".mysqli_error($enlace));
						}
						else //===> No existe ese consumo... Agrego uno nuevo
						{
							$ingresaConsumo=mysqli_query($enlace,"INSERT INTO consumos_paciente()
															  	  VALUES
															  	  (NULL,
															  	   '$id_admision',
															  	   '$codProd',
															  	   '$codServicio',
															  	   '$cantidadConsumida',
															  	   '".date("Y-m-d")."',
															  	   '$iduser') ");
						}
					}
					else //===> Es servicio
					{
						$sumaS=0;
						$NS=0;
						$sqlBusqS=mysqli_query($enlace,"SELECT * 
													   FROM consumos_paciente 
													   WHERE id_admision='$id_admision'
													   AND cod_servicio='$codServicio' ") or die("Error:".mysqli_error($enlace));
						$NS=mysqli_num_rows($sqlBusqS);
						$rsS=mysqli_fetch_assoc($sqlBusqS);

						if($NS>0) //===> Si ya hay... Se le suma la cantidad
						{	
							//===> Hago la suma
							$sumaS=$cantidadConsumida+$rsS["cantidad_consumida"];

							$actServ=mysqli_query($enlace,"UPDATE
														   consumos_paciente
														   SET
														   cantidad_consumida='$sumaS',
														   fecha_consumo='".date("Y-m-d")."'
														   WHERE 
														   id_consumo='{$rsS["id_consumo"]}' ") or die("Error:".mysqli_error($enlace));
						}
						else //===> No existe ese consumo... Agrego uno nuevo
						{
							$ingresaConsumo=mysqli_query($enlace,"INSERT INTO consumos_paciente()
															  	  VALUES
															  	  (NULL,
															  	   '$id_admision',
															  	   '$codProd',
															  	   '$codServicio',
															  	   '$cantidadConsumida',
															  	   '".date("Y-m-d")."',
															  	   '$iduser') ") or die("Error:".mysqli_error($enlace));
						}
					}

				//==> Hago la resta solo si es producto
				if($existenciaOriginal!="" && $existenciaOriginal!=0)
				{
					$resta=($existenciaOriginal)-($cantidadConsumida);

					//===> Resto la cantidad de procducto
					$sqlResta=mysqli_query($enlace,"UPDATE producto_almacen
													SET 
													cantidad='$resta' 
													WHERE id='$codProd'
													AND idalmacen='$idalmacen' ");
				}
				$i++;
			}
			//====> Verifico si cerro la cuenta
			if($cerrar=="c")
			{
				$sqlC=mysqli_query($enlace,"UPDATE admision_paciente SET estatus='2' WHERE id_admision='$id_admision' ");
				$mensaje=" y cuenta cerrada.";
			}
		}
	if( ($ingresaConsumo || $actProd || $actServ || $sqlC) || ($existenciaOriginal!="" && $sqlResta || $sqlC) )
		echo json_encode(array('result' => true,'mensaje'=>"Consumos cargados correctamente".$mensaje));	
	else
		echo json_encode(array('result' => false,'mensaje'=>"Hubo un error en su solicitud"));	
	break;

	case 'buscaPaciente':
		//===> Busca paciente en admision con el estatus de alta y que no este facturado
		$sql=mysqli_query($enlace,"SELECT * 
								   FROM pacientes
								   INNER JOIN admision_paciente ON (pacientes.cedula=admision_paciente.cedula) 
								   WHERE admision_paciente.cedula='$cedula' 
								   AND admision_paciente.estatus=2
								   AND admision_paciente.facturado=1 ") or die ("Error: ".mysqli_error($enlace));
		$noFact=mysqli_num_rows($sql);

		if($noFact>0) //Si encontro...
		{
			$datos=mysqli_fetch_array($sql);

				echo json_encode(array("result" => true,
									   "cedula"=>$datos["cedula"],
									   "nombres"=>utf8_encode($datos["nombres"]),
									   "apellidos"=>utf8_encode($datos["apellidos"]),
									   "obs"=>utf8_encode($datos["observaciones"]),
									   "fecha_ingreso"=>DevuelveFecha($datos["fecha_ingreso"]),
									   "hora_ingreso"=>$datos["hora_ingreso"],
									   "id_admision"=>$datos["id_admision"] ));	
		}	
		else //===> No encontro, ahora busca en pacientes registrados
		{
			$sql1=mysqli_query($enlace,"SELECT * 
										FROM pacientes 
										WHERE pacientes.cedula='$cedula' ");
			$enc=mysqli_num_rows($sql1);

			if($enc>0)
			{
				$datos1=mysqli_fetch_array($sql1);

				echo json_encode(array("result" => true,
									   "cedula"=>$datos1["cedula"],
									   "nombres"=>utf8_encode($datos1["nombres"]),
									   "apellidos"=>utf8_encode($datos1["apellidos"]),
									   "obs"=>'',
									   "fecha_ingreso"=>'',
									   "hora_ingreso"=>'' ));
			}
			else
				echo json_encode(array('result' => false,'mensaje'=>"Paciente no encontrado"));	
		}
			
	break;

	case "guardarPaciente":

		//===> Guardando el paciente
		$sql=mysqli_query($enlace,"INSERT INTO pacientes()
			  					   VALUES (NULL,'$ced_paciente_reg','$nom_paciente_reg','$ape_paciente_reg','$dir_paciente_reg','$tel_paciente_reg','$asegurado','$seguro') ") or die ("Error: ".mysqli_error($enlace));

		$sqlpaciente=mysqli_query($enlace,"SELECT * FROM pacientes WHERE cedula='$ced_paciente_reg' ");
		$paciente=mysqli_fetch_assoc($sqlpaciente);

			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'cedula'=>str_replace(".","",$paciente["cedula"]),'nombres'=>utf8_encode($paciente["nombres"]),'apellidos'=>utf8_encode($paciente["apellidos"])));	

	break;

	case 'buscaDatosProducto':
	if($tipo=="P") //===> Producto
	{
/*echo "SELECT * 
								   FROM productos p 
								   INNER JOIN producto_inventario pi ON (p.id_prod=pi.id_prod)
								   INNER JOIN producto_almacen pa ON (pi.idproductoinventario=pa.id_producto_inventario)
								   LEFT JOIN presentacion_productos pp ON (p.idpresentacion=pp.id_presentacion)
								   WHERE pa.id='$codigo'
								   AND pa.idalmacen='$idalmacen'";*/

		$sql=mysqli_query($enlace,"SELECT * 
								   FROM productos p 
								   INNER JOIN producto_inventario pi ON (p.id_prod=pi.id_prod)
								   INNER JOIN producto_almacen pa ON (pi.idproductoinventario=pa.id_producto_inventario)
								   LEFT JOIN presentacion_productos pp ON (p.idpresentacion=pp.id_presentacion)
								   WHERE pa.id='$codigo'
								   AND pa.idalmacen='$idalmacen' ") or die ("Error: ".mysqli_error($enlace));	
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
								   "cod_prod"=>$rs["id"],
								   "nom_prod"=>utf8_encode($rs["nom_prod"])." ".number_format($rs["cant_presentacion"],0,",",".")." ".$rs["ab_presentacion"],
								   "cantidad_general"=>$rsSum["suma_general"],
								   "cant_prod"=>$rs["cantidad"],
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
		$tabla_prod ="";
		$tabla_serv ="";
		$total_prod =0;
		$cant_prod  =0;
		$total_serv =0;
		$cant_serv  =0;

		$sql=mysqli_query($enlace,"SELECT * 
							       FROM consumos_paciente 
								   WHERE id_admision='$id_admision' 
								   ORDER BY id_producto_almacen DESC");
		$existe=mysqli_num_rows($sql);

		//===> Si hay consumos cargados
		if($existe>0)
		{
			?>
			    <table id="tabla-productos" class="table table-striped table-bordered" style="margin-top:30px;width:100%;">
			    <tr>
			    	<td width="50%" align="center" style="background-color:#C7C7C7;"><b>Descripci&oacute;n</b></td>
<!-- 			    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Existencia en inventario</b></td>
			    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Existencia General</b></td> -->
			    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Prec. Unit.</b></td>
			    	<td width="8%"  align="center" style="background-color:#C7C7C7;"><b>Cant. Consumida</b></td>
<!-- 			    	<td width="12%" align="center" style="background-color:#C7C7C7;"><b>Cant. Consumo</b></td> -->
<!-- 			    	<td width="8%"  align="center" style="background-color:#C7C7C7;">&nbsp;</td> -->
			    </tr>
			<?
			//===> Busco cuales fueron los servicios o productos cargados
			while($rs=mysqli_fetch_assoc($sql))
			{
				//===> Busco Productos primero
				if($rs["id_producto_almacen"]!=0)
				{
					$buscaProducto=mysqli_query($enlace,"SELECT * 
													     FROM producto_almacen pa
													     INNER JOIN producto_inventario pi ON (pa.id_producto_inventario=pi.idproductoinventario) 
													     INNER JOIN productos p ON (pi.id_prod=p.id_prod) 
													     LEFT JOIN presentacion_productos pp ON (p.idpresentacion=pp.id_presentacion)
													     WHERE pa.id='{$rs["id_producto_almacen"]}' ");
						$rsP=mysqli_fetch_assoc($buscaProducto);

							//===> Traigo el total de ese producto en general
							$suma=mysqli_query($enlace,"SELECT SUM(pa.cantidad) as suma_general 
														FROM producto_almacen pa
														INNER JOIN producto_inventario pi ON (pa.id_producto_inventario=pi.idproductoinventario)
														WHERE pi.id_prod='{$rsP["id_prod"]}' ") or die ("Error: ".mysqli_error($enlace));
							$rsSum=mysqli_fetch_assoc($suma);

							$subtotal+=($rsP["prec_pro"]*$rs["cantidad_consumida"]);

							$total_prod+=($rsP["prec_pro"]*$rs["cantidad_consumida"]);
							$cant_prod +=$rs["cantidad_consumida"];
					
					$tabla_prod.="<tr id='".$rsP["id"]."' class='tp prod' style='display:none;'>";
					$tabla_prod.="	<td align='left'>&nbsp;&nbsp;&nbsp;".utf8_encode($rsP["nom_prod"])." ".number_format($rsP["cant_presentacion"],0,"",".")." ".utf8_encode($rsP["ab_presentacion"])."</td>";
					$tabla_prod.="	<td align='right'>".number_format($rsP["prec_pro"],2,",",".")."</td>";
					$tabla_prod.="	<td align='center'>".number_format($rs["cantidad_consumida"],0,"",".")."</td>";
					$tabla_prod.="</tr>";
					
				}

				//===> Si es servicio
				if($rs["cod_servicio"]!="")
				{
					$buscaServ=mysqli_query($enlace,"SELECT * 
											  	     FROM servicios s 
											   		 WHERE s.cod_servicio='{$rs["cod_servicio"]}' ") or die ("Error: ".mysqli_error($enlace));	
					$rsS=mysqli_fetch_assoc($buscaServ);

					$subtotal+=($rsS["precio"]*$rs["cantidad_consumida"]);

					$total_serv+=($rsS["precio"]*$rs["cantidad_consumida"]);
					$cant_serv +=$rs["cantidad_consumida"];
					
					$tabla_serv.="<tr id='".$rsS["cod_servicio"]."' class='tp serv' style='display:none;'>";
					$tabla_serv.="	<td align='left'>&nbsp;&nbsp;&nbsp;".utf8_encode($rsS["nombre"])."</td>";
					$tabla_serv.="	<td align='right'>".number_format($rsS["precio"],2,",",".")."</td>";
					$tabla_serv.="	<td align='center'>".number_format($rs["cantidad_consumida"],0,"",".")."</td>";
					$tabla_serv.="</tr>";
					
				}
			}

				if($tabla_prod!="")
				{
					echo "<tr>";
					echo "	<td><p class='mProd' style='cursor:pointer;width:255px;font-weight:bold;' data-toggle='tooltip' data-placement='right' data-original-title='Mostrar detalles' onclick='muestra_prod()'>MEDICAMENTOS / OTROS INSUMOS</p></td>";
					echo "	<td align='right'><b>".number_format($total_prod,2,",",".")."</b></td>";
					echo "	<td align='center'><b>".number_format($cant_prod,0,"",".")."</b></td>";
					echo "</tr>";
					echo $tabla_prod;
				}

				if($tabla_serv!="")
				{
					echo "<tr>";
					echo "	<td><p class='mServ' style='cursor:pointer;width:130px;font-weight:bold;' data-toggle='tooltip' data-placement='right' data-original-title='Mostrar detalles' onclick='muestra_serv()'>SERVICIOS</p></td>";
					echo "	<td align='right'><b>".number_format($total_serv,2,",",".")."</b></td>";
					echo "	<td align='center'><b>".number_format($cant_serv,0,"",".")."</b></td>";
					echo "</tr>";
					echo $tabla_serv;
				}

				$total=(($subtotal*$iva)+$subtotal);//1.690,80 1.893,70
			?>
				</table>
				<script type="text/javascript">
					$('[data-toggle="tooltip"]').tooltip();
					$("#div_agrega_prod").hide("fast");

					$("#lab_subtot").html(number_format("<?=$subtotal?>",2,",","."));
					$("#subtotal").val("<?=$subtotal?>");
					$("#lab_precio").html(number_format("<?=$total?>",2,",","."))
					$("#total").val("<?=$total?>");
					//console.log("Subtotal: "+"<?=$subtotal?>"+" Total: "+"<?=$total?>");
				</script>
			<?
		}

	break;

	case 'buscaExistProducto':
		$id_prod=$_REQUEST["id_prod"];
		$nombre =$_REQUEST["nombre"];

		$sql=mysqli_query($enlace,"SELECT *,SUM(pa.cantidad) as total
									FROM producto_almacen pa
									INNER JOIN producto_inventario pi ON (pa.id_producto_inventario=pi.idproductoinventario)
									LEFT JOIN almacen a ON (pa.idalmacen=a.idalmacen)
									WHERE pi.id_prod='$id_prod' 
									GROUP BY pa.idalmacen") or die ("Error: ".mysqli_error($enlace));
		?>
			<table class="table table-responsive table-bordered table-striped" width="100%">
				<tr>
					<td colspan="2"><?=$nombre;?></td>
				</tr>
				<tr>
					<td align="center" style="background-color: rgb(202, 202, 202);"><b>Ubicaci&oacute;n</b></td>
					<td align="center" style="background-color: rgb(202, 202, 202);"><b>Existencia</b></td>
				</tr>
		<?
			while($rs=mysqli_fetch_assoc($sql))
			{
				?>
				<tr>
					<td><?=utf8_encode($rs["nombre"])?></td>
					<td><?=number_format($rs["total"],0,"",".")?></td>
				</tr>
				<?
			}
		?>
			</table>
		<?		
	break;

	case 'verServicios':

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
									   FROM servicios 
									   $fil 
									   ORDER BY nombre ASC") or die ("Error: ".mysqli_error($enlace));
			$cant_registros =mysqli_num_rows($sql);
			$paginado = intval($cant_registros / $cantidad);

			$sql=mysqli_query($enlace,"SELECT *
									   FROM servicios 
									   $fil 
									   ORDER BY nombre ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));
			?>
					<div class="content-panel">
							<table class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>C&oacute;digo</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Servicio</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
											?><tr>
												<td><?=$rs["cod_servicio"]?></td>
												<td><p style="cursor:pointer;" data-toggle="tooltip" data-placement="bottom" data-original-title="Ver descripci&oacute;n" onclick="abreDesc('<?=utf8_encode(str_replace("\"","",$rs["nombre"]))?>','<?=utf8_encode(str_replace("\"","",$rs["descripcion"]))?>')"><?=utf8_encode($rs["nombre"])?></p></td>
												<td width="10%" align="center">
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar producto" data-toggle="modal" data-target="#modal-prod<?=$rs["id_servicio"];?>"><i class="fa fa-trash-o fa-lg"></i></button>

												<!--Modal de eliminar servicios-->
													<div id="modal-prod<?=$rs["id_servicio"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
													  <div class="modal-dialog modal-sm">
													    <div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																<h4>Alerta</h4>
															</div> <!--Header-->
															<div class="modal-body">
																<p style="text-align:center;">¿Eliminar servicio: "<?=utf8_encode($rs["nombre"])?>" ?</p>
															</div> <!--Body-->
													    	<div class="modal-footer">
													        	<button type="button" class="btn btn-danger" onclick="eliminar('<?=$rs["id_servicio"];?>')" data-dismiss="modal">Eliminar</button>
													        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													      	</div> <!--Footer-->
													    </div> <!--Content-->
													  </div>
													</div>

													<button type="button" class="btn btn-info btn-sm" title="Modificar servicio" onclick="modificar('<?=$rs["id_servicio"]?>','<?=$rs["cod_servicio"]?>','<?=utf8_encode(str_replace("\"","",$rs["nombre"]))?>','<?=utf8_encode(str_replace("\"","",$rs["descripcion"]))?>','<?=number_format($rs["precio"],2,",",".")?>')"><i class="fa fa-edit fa-lg"></i></button>
												</td>
											</tr><?
									}
								}
								if($cant_registros<=0)
								{
									?>
									<tr>
										<td colspan="4"><div style="text-align:center;font-size:16px;"><b>No hay servicios registrados.</b></div></td>
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