<?

include("../funcionesphp/conex.php");

include("../funcionesphp/funciones.php");



//======> Variables

$accion 			=$_REQUEST["accion"];

if(!empty($_REQUEST["nfactura"]))

	$nfactura 		=$_REQUEST["nfactura"];

if(!empty($_REQUEST["tipofactura"]))

	$tipofactura 		=$_REQUEST["tipofactura"];

if(!empty($_REQUEST["tipodocumento"]))

	$tipodocumento 		=$_REQUEST["tipodocumento"];

if(!empty($_REQUEST["tipopago"]))

	$tipopago 		=$_REQUEST["tipopago"];

if(!empty($_REQUEST["idbanco"]))

	$idbanco 		=$_REQUEST["idbanco"];

if(!empty($_REQUEST["ndocumento"]))

	$ndocumento 		=$_REQUEST["ndocumento"];

if(!empty($_REQUEST["idfacturas"]))

	$idfacturas 		=$_REQUEST["facturas"];

if(!empty($_REQUEST["idfactura"]))

	$idfactura 		=$_REQUEST["idfactura"];

if(!empty($_REQUEST["importe"]))

	$importe 		=number_format($_REQUEST["importe"],2,".","");

$fecharegistro 		=$_REQUEST["fecha_registro"]." ".$_REQUEST["hora_registro"];

$idusuario = $_REQUEST["idusuario"];

if(!empty($_REQUEST["idpago"]))

	$idpago 		=$_REQUEST["idpago"];

if((!empty($_REQUEST["fil_estatus"])) OR $_REQUEST["fil_estatus"]=="0"){
	$fil_estatus 		=$_REQUEST["fil_estatus"];
}

// 0 = No cobrado
// 1 = Debitado
// 2 = Anulado
$estatus = 0;

switch ($accion) 

{
	case 'Guardar':
		$sql = "SELECT saldo FROM cuenta_banco WHERE idbanco = $idbanco";
		$result = mysqli_query($enlace,$sql);
		$saldo = mysqli_fetch_assoc($result);
		if($saldo["saldo"]<$importe){
			echo json_encode(array("result"=>false,"mensaje"=>"El saldo de esta cuenta no es suficiente."));
		}
		$sql = "UPDATE cuenta_banco SET saldo=saldo-".$importe." WHERE idbanco=$idbanco";
		//echo $sql; die();
		mysqli_query($enlace,$sql);
		$sql = "UPDATE facturas SET resta=resta-".$importe." WHERE idfactura=$idfactura";
		mysqli_query($enlace,$sql);

		if(empty($idfactura)){
			foreach ($idfacturas as $key => $value) {
				$sql = "INSERT INTO pagos(tipopago, idbanco, ndocumento, idfactura, importe, idusuario, fecharegistro,estatus) VALUES ('$tipopago','$idbanco','$ndocumento','$value','$importe','$idusuario','$fecharegistro','$estatus')";
				$result = mysqli_query($enlace,$sql);
				$idpago = mysqli_insert_id($enlace);
				$sql = "INSERT INTO pagos_detalles(idpago, importe) VALUES ('$idpago','$importe')";
				//echo $sql; die();
				$result = mysqli_query($enlace,$sql);
			}
		}else{
				$sql = "INSERT INTO pagos(tipopago, idbanco, ndocumento, idfactura, importe, idusuario, fecharegistro,estatus) VALUES ('$tipopago','$idbanco','$ndocumento','$idfactura','$importe','$idusuario','$fecharegistro','$estatus')";
				//echo $sql; die();
				$result = mysqli_query($enlace,$sql);
				$idpago = mysqli_insert_id($enlace);
				$sql = "INSERT INTO pagos_detalles(idpago, importe) VALUES ('$idpago','$importe')";
		}


		if(!$result):
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error en la consulta."));
		else:
			echo json_encode(array("result"=>true,"mensaje"=>"El pago se ha registrado con exito!"));
		endif;
	break;
	case 'buscaFactura':
		//==> Filtros
		$fil="facturas.estatus=1";
	
		if(!empty($nfactura))
		{
			if($fil=="")
				$fil.=" nfactura = '$nfactura' ";
			else
				$fil.=" AND nfactura = '$nfactura' ";	
		}
	
		if($fil!="")
		$fil=" WHERE ".$fil;
		$sql = "SELECT * FROM facturas
					INNER JOIN users USING(id_user)									  
					INNER JOIN proveedores USING(idproveedor)
					$fil";
		//echo $sql;
		$result = mysqli_query($enlace,$sql);
		$Factura = mysqli_fetch_assoc($result);
		if(!empty($Factura["nfactura"])):
		?>
		<input type="hidden" id="idfactura" name="idfactura" value="<?= $Factura["idfactura"] ?>">
		<table>
			<tr>
				<td><b>N&ordm; Factura:</b></td>
				<td><?= $Factura["nfactura"] ?></td>
				<td style="width: 12%;"><b>N&ordm; Control:</b></td>
				<td><?= $Factura["ncontrol"] ?></td>
			</tr>
			<tr>
				<td style="width: 16%;"><b>Proveedor:</b></td>
				<td style="width: 60%;"><?= $Factura["nombre"] ?></td>
				<td><b>Usuario:</b></td>
				<td><?= $Factura["nombre_usuario"] ?></td>
			</tr>
			<tr>
				<td style="font-size: 22px; font-style: italic;"><b>Total:</b></td>
				<td><?= number_format($Factura["total_factura"],2,",",".") ?></td>
				<td style="font-size: 22px; font-style: italic;"><b>Resta:</b></td>
				<td><?= number_format($Factura["resta"],2,",",".") ?></td>
			</tr>
		</table>
		<?php
		else:
			echo "No se han encontrado Resultados.";
		endif;
	break;
	case 'verTodasFacturas':
	//==> Filtros
	$fil="facturas.estatus=1";

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
						<table class="table table-bordered table-striped table-condensed table-responsive">
						<tr>
							<td align="center" style="background-color:#D3D3D3;"></td>
							<td align="center" style="background-color:#D3D3D3;"><b>N Factura</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>N control</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Proveedor</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Usuario</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Fecha</b></td>
						</tr>
						<?
							if($sql)
							{
								$n = 1;
								while($rs=mysqli_fetch_array($sql))
								{
										?><tr>
											<td><input type="checkbox" name="facturas[]" onchange="if(this.checked){ seleccionar(<?= $rs["idfactura"] ?>,'<?= number_format($rs["total_factura"],2,",",".") ?>','<?= number_format($rs["resta"],2,",",".") ?>'); }else{ quitar(<?= $rs["idfactura"] ?>,'<?= number_format($rs["total_factura"],2,",",".") ?>','<?= number_format($rs["resta"],2,",",".") ?>'); }" style="cursor: pointer;"></td>
											<td><?=utf8_encode($rs["nfactura"])?></td>
											<td><?=utf8_encode($rs["ncontrol"])?></td>
											<td><?=utf8_encode($rs["nombre"])?></td>
											<td><?=utf8_encode($rs["nombre_usuario"])?></td>
											<td><?=DevuelveFecha($rs["fecha_factura"])?></td>
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
						<div id="datos_factura"></div>
			<?

	break;
	case 'verPagos':
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

			$sql=mysqli_query($enlace,"SELECT *,pagos.fecharegistro pfecharegistro
									   FROM pagos
									   INNER JOIN users ON users.id_user = pagos.idusuario
									   INNER JOIN facturas USING(idfactura)
									   $fil 
									   ORDER BY idfactura ASC") or die ("Error: ".mysqli_error($enlace));
			$cant_registros =mysqli_num_rows($sql);
			$paginado = intval($cant_registros / $cantidad);
/*			$sql=mysqli_query($enlace,"SELECT *

									   FROM servicios 

									   $fil 

									   ORDER BY nombre ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));*/

			?>
		<div class="container" style="margin-top:50px;">
			<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
				<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Pagos</div>
					<div class="panel-body">
						<table class="table table-bordered table-striped table-condensed table-responsive">
						<tr>
							<td width="15%" align="center" style="background-color:#D3D3D3;"><b>N</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>N Factura</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Tipo de Pago</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>N Documento</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Importe</b></td>
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
											<td><?=utf8_encode($rs["ndocumento"])?></td>
											<td><?=number_format($rs["importe"],2,",",".")?></td>
											<td><?=utf8_encode($rs["nombre_usuario"])?></td>
											<td><?=DevuelveFechaTimeStamp($rs["pfecharegistro"])?></td>
											<td>
												<button type="button" class="btn btn-danger btn-sm" title="Eliminar Pago" data-toggle="modal" data-target="#modal-Pago<?=$Pago["idpago"];?>"><i class="fa fa-trash-o fa-lg"></i></button>
												<button type="button" class="btn btn-info btn-sm" title="Modificar Pago" onclick="modGrupo(<?= "'".$Grupo["idgrupogasto"]."','".utf8_encode($Grupo["nombre_grupo"])."',".$Grupo["estatus"] ?>)"><i class="fa fa-edit fa-lg"></i></button>
											</td>
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
				</div>
			</div>
			<?

	break;
	case 'aprobar':
		$sql = "UPDATE pagos SET estatus = 1 WHERE idordenpago=$idpago";
		//echo $sql; die();
		$result = mysqli_query($enlace,$sql);
		if($result):
			echo json_encode(array("result"=>true,"msg"=>"Su pago ha sido aprobado."));
		else:
			echo json_encode(array("result"=>false,"msg"=>"Hubo un error en la consulta."));
		endif;
		break;
	case 'verCheques':
	//==> Filtros
	$fil="pagos.tipopago=2";

	if((!empty($fil_estatus)) OR $fil_estatus==0)
	{
		if($fil=="")
			$fil.=" pagos.estatus = '$fil_estatus' ";
		else
			$fil.=" AND pagos.estatus = '$fil_estatus' ";	
	}

	if($fil!="")
		$fil=" WHERE ".$fil;

	//establecemos los limites de la página actual
	$i=0;
	if ($_POST['pg']=="") 
		$n_pag = 1; 
	else  
		$n_pag=$_POST['pg']; 
	$cantidad=3;
	$inicial = ($n_pag-1) * $cantidad;
			$consulta = "SELECT *, pagos.estatus pestatus, pagos.fecharegistro pfecharegistro
									   FROM pagos
									   INNER JOIN users ON users.id_user = pagos.idusuario
									   INNER JOIN facturas USING(idfactura)
									   INNER JOIN banco USING(idbanco)
									   $fil 
									   ORDER BY idfactura ASC";
			//echo $consulta; die;
			$sql=mysqli_query($enlace,$consulta) or die ("Error: ".mysqli_error($enlace));
			$cant_registros =mysqli_num_rows($sql);
			$paginado = intval($cant_registros / $cantidad);
/*			$sql=mysqli_query($enlace,"SELECT *

									   FROM servicios 

									   $fil 

									   ORDER BY nombre ASC LIMIT $inicial,$cantidad") or die ("Error: ".mysqli_error($enlace));*/

			?>
						<table class="table table-bordered table-striped table-condensed table-responsive">
						<tr>
							<td align="center" style="background-color:#D3D3D3;"><b>N Factura</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Banco</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>N Cheque</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Importe</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Usuario</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Estatus</b></td>
							<td align="center" style="background-color:#D3D3D3;"><b>Fecha</b></td>
							<td align="center" style="background-color:#D3D3D3;"></td>
						</tr>
						<?
							if($sql)
							{
								while($rs=mysqli_fetch_array($sql))
								{
									if($rs["pestatus"]==0): $txtestatus = "<b style='color: orange;'>No Cobrado</b>"; elseif($rs["pestatus"]==1): $txtestatus = "<b style='color: green;'>Debitado</b>"; elseif($rs["pestatus"]==2): $txtestatus = "<b style='color: red;'>Anulado</b>"; endif;
										?><tr>
											<td><?=utf8_encode($rs["nfactura"])?></td>
											<td><?=utf8_encode($rs["nombre"])?></td>
											<td><?=utf8_encode($rs["ndocumento"])?></td>
											<td><?=number_format($rs["importe"],2,",",".")?></td>
											<td><?=utf8_encode($rs["nombre_usuario"])?></td>
											<td><?= $txtestatus ?></td>
											<td><?=DevuelveFechaTimeStamp($rs["pfecharegistro"])?></td>
											<td>
												<? if($rs["pestatus"] == 0): ?><button type="button" class="btn btn-success btn-sm" title="Aprobar Pago" data-toggle="modal" data-target="#modal-prod<?=$rs["idordenpago"];?>">Aprobar</button><?php endif; ?>	
											</td>
										</tr>
							<!--Modal -->
								<div id="modal-prod<?=$rs["idordenpago"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
								  <div class="modal-dialog modal-sm">
								    <div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
											<h4>Alerta</h4>
										</div> <!--Header-->
										<div class="modal-body">
											<p style="text-align:center;">¿Esta seguro que desea aprobar el pago?</p>
										</div> <!--Body-->
								    	<div class="modal-footer">
								        	<button type="button" class="btn btn-success" onclick="aprobar(<?= $rs["idordenpago"] ?>)" data-dismiss="modal">Aprobar</button>
								        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
								      	</div> <!--Footer-->
								    </div> <!--Content-->
								  </div>
								</div>
								<?
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
						<div><?= paginacion($paginado,$n_pag) ?></div>
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
		case 'buscarSaldo':
			$sql = "SELECT saldo FROM cuenta_banco WHERE idbanco=$idbanco";
			$result = mysqli_query($enlace,$sql);
			$saldo = mysqli_fetch_assoc($result);
			if(!empty($saldo)){
				echo number_format($saldo["saldo"],2,",",".")."&nbsp;<b>Bs.</b>";
				echo "<input type='hidden' id='saldo' value='".$saldo["saldo"]."' />";
			}
			else
			{
				echo "No hay disponible para esta cuenta.";
			}
			break;
}

?>