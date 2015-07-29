<?php

include("../funcionesphp/conex.php");

include("../funcionesphp/funciones.php");
session_start();


//======> Variables

$accion =$_REQUEST["accion"];
if(!empty($_REQUEST["tipofactura"]))
	$tipofactura 		=$_REQUEST["tipofactura"];
if(!empty($_REQUEST["mes"]))
	$mes 		= $_REQUEST["mes"];
if(!empty($_REQUEST["anio"]))
	$anio 		= $_REQUEST["anio"];


switch ($mes) {
	case '1':
		$mestxt = "Enero";
	break;
	case '2':
		$mestxt = "Febrero";
	break;
	case '3':
		$mestxt = "Marzo";
	break;	
	case '4':
		$mestxt = "Abril";
	break;
	case '5':
		$mestxt = "Mayo";
	break;
	case '6':
		$mestxt = "Junio";
	break;
	case '7':
		$mestxt = "Julio";
	break;
	case '8':
		$mestxt = "Agosto";
	break;
	case '9':
		$mestxt = "Septiembre";
	break;
	case '10':
		$mestxt = "Octubre";
	break;
	case '11':
		$mestxt = "Noviembre";
	break;
	case '12':
		$mestxt = "Diciembre";
	break;
}

	//==> Filtros
	$tiposalida = $_REQUEST["tiposalida"];
	$fil="";

	if(!empty($mes))
	{
		if($fil=="")
			$fil.=" MONTH(fecha_factura) = $mes ";
		else
			$fil.=" AND MONTH(fecha_factura) = $mes ";	
	}

	if(!empty($anio))
	{
		if($fil=="")
			$fil.=" YEAR(fecha_factura) = $anio ";
		else
			$fil.=" AND YEAR(fecha_factura) = $anio ";	
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
									   INNER JOIN users USING(id_user)									  
									   INNER JOIN proveedores USING(idproveedor)
									   $fil 
									   ORDER BY nfactura ASC";
									   //echo $consulta; die;
			$sql=mysqli_query($enlace,$consulta) or die ("Error: ".mysqli_error($enlace));
			//echo $sql; die();
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
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Libro de Compras</div>
						<div class="panel-body" style="padding:0px;">
					<div class="content-panel">
						<table style="width: 90%;margin: 10px;" border="0">
							<tr>
								<td><b>Raz&oacute;n Social:</b></td>
								<td>Policl&iacute;nica Andr&eacute;s Bello C.A.</td>
							</tr>
							<tr>
								<td><b>N&uacute;mero de RIF:</b></td>
								<td>J-31334670-5</td>
							</tr>
							<tr>
								<td><b>Domicilio Fiscal:</b></td>
								<td>Av. Andr&eacute;s Bello No. 57, La Cooperativa Maracay, Edo. Aragua - Venezuela</td>
							</tr>
							<tr>
								<td style="width: 13%;"><b>Correspondiente al mes:</b></td>
								<td><?= $mestxt ?> del a&ntilde;o <?= $anio ?></td>
							</tr>
							<tr>
								<td colspan="4">&nbsp;</td>
							</tr>
						</table>
							<table class="table table-bordered table-striped table-condensed table-responsive">
							<tr>
								<td colspan="4">&nbsp;</td>
								<td colspan="2">Documentos</td>
								<td colspan="4">&nbsp;</td>
								<td colspan="2">Compras Internas No Gravadas</td>
								<td colspan="3">Compras de importacion</td>
								<td colspan="6" style="text-align: center;">Compras Nacionales</td>
							</tr>
							<tr>
								<td width="15%" align="center" style="background-color:#D3D3D3;"><b>N</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Fecha Emisí&oacute;n</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Nombre y Apellido &oacute; Raz&oacute;n Social del Proveedor &oacute;</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Nº de C&eacute;dula</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Tipo</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Numero</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Numero Control</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Tipo de Transacc.</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>N Factura Afectada</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Total Compras incluyendo el IVA</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Tipo</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Monto</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Base Imponible</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Alic. %</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Impuesto IVA</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Base Imponible</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Alic. %</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Impuesto IVA</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Base Imponible</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Alic. %</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Impuesto IVA</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>IVA Retenido (por el comprador)</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Comprobante de Rentenci&oacute;n</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Porcentaje de Rentenci&oacute;n</b></td>
							</tr>
							<?php
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
												<td><?= utf8_encode($rs["nombre"]) ?></td>
												<td><?=utf8_encode($rs["rif"])?></td>
												<td><!-- Tipo Documento --></td>
												<td><!-- N Documento --></td>
												<td><?=utf8_encode($rs["ncontrol"])?></td>
												<td><!-- Tipo de Transaccion --></td>
												<td><!-- Numero de Factura Afectada --></td>
												<td style="text-align: right;"><?= number_format($rs["total_factura"],2,",",".")  ?></td>
												<td style="text-align: right;"><!-- Tipo --></td>
												<td style="text-align: right;"><?php if($rs["exento"]): echo number_format($rs["base_imponible"],2,",","."); endif; ?></td>
												<td style="text-align: right;"><!-- Importacion --></td>
												<td>0%</td>
												<td style="text-align: right;"><!-- Importacion --></td>
												<td style="text-align: right;"><?php if(!$rs["exento"]): echo number_format($rs["base_imponible"],2,",","."); endif; ?></td>
												<td><?= $_SESSION["iva"] ?>%</td>
												<td style="text-align: right;"><?php if(!$rs["exento"]): echo number_format($iva,2,",","."); endif; ?></td>
												<td>&nbsp;</td>
												<td>8%</td>
												<td>&nbsp;</td>
												<td><!-- IVA Retenido (por el comprador) --></td>
												<td><!-- Comprobante de Rentencion --></td>
												<td><!-- Porcentaje de Rentencion --></td>
											</tr><?
										$n++;
									}
								}
								if($cant_registros<=0)
								{
									?>
									<tr>
										<td colspan="24"><div style="text-align:center;font-size:16px;"><b>No se encontraron resultados.</b></div></td>
									</tr><?
								}else{
								?>
								<tr>
									<td colspan="9"></td>
									<td><?= number_format($total1,2,",",".") ?></td>
									<td>&nbsp;</td>
									<td><?= number_format($totalexento,2,",",".")  ?></td>
									<td colspan="3"></td>
									<td><?= number_format($total2,2,",",".")  ?></td>
									<td colspan="3"></td>
									<td><!-- IVA 8% --></td>
								</tr>
								<?php
								}
								?>
							</table>
					</div>
</div>
</div>
</div>