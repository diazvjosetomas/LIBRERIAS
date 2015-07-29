<?

/*	

	CHULETA PARA LOS INPUTS: 



input_hidden("$id","$value");

label("$label","$ancho","$contenido");

input_text("$label","$id","$ancho","$tipo","$onclick","$onblur","$attr","$type");

input_textarea("$label","$id","$ancho","$onclick","$onblur","$attr","$height");

input_numero("$label","$id","$ancho","$onclick","$onblur","$attr");

input_monto("$label","$id","$ancho","$onclick","$onblur","$attr");

input_fecha("$label","$id","$ancho","$fecha","$onclick","$onblur","$attr");

select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options","$selected","$class");

input_check("$label","$id","$ancho","$value","$onclick","$onchange","$onblur","$attr","$class")



*/

?>

<div class="container" style="padding-top:0px;">		

		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">

			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Pago</div>

			<div class="panel-body">

				<div class="form-group" style="margin: 1px;">
					<i class="fa fa-check-circle"></i> <em>Filtros de b&uacute;squeda</em>
					<hr style="margin-top:11px;width:85%;float:right;"></hr>
				</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"><b>N&ordm; Factura: </b></label>
					<div class="col-sm-6">
						<input type="text" class="form-control" id="nfactura" name="nfactura">					
					</div>
			</div>
			<div class="form-group" style="text-align:center;margin-top:15px;">
			    <button type="button" class="btn btn-success" onclick="buscarFactura()">Buscar</button>
			    <button type="button" class="btn btn-success" onclick="verFacturas()">Ver Todas las Facturas</button>
			    <button type="button" class="btn btn-default" onclick="limpiar()">Limpiar</button>
			</div>
			<input type="hidden" id="total">
			<div class="well" id="factura_seleccionada" style="visibility: hidden;"></div>
			<div class="well">
			<fieldset>
			<legend>Datos de Pago</legend>
			<div class="form-group">
				<label class="col-sm-3 control-label"><b>Fecha: </b></label>
				<div class="col-sm-8">
					<div class="input-group">
						<input type="text" class="form-control" id="fecha_registro" style="width: 100px;" readonly name="fecha_registro" value="<?= date("Y-m-d") ?>">
						<input type="text" class="form-control" id="hora_registro" style="width: 100px;" readonly name="hora_registro" value="<?= date("H:i:s") ?>">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><b>Tipo de Pago: </b></label>
				<div class="col-sm-3">
					<select class="form-control" id="tipopago" name="tipopago" onchange="cambiar_documento(this.value)">
						<option></option>
						<option value="1">Efectivo</option>
						<option value="2">Cheques</option>
						<option value="3">Transferencia</option>
						<option value="4">Deposito</option>
					</select>
				</div>
			</div>
				<div class="form-group">
				<label class="col-sm-3 control-label"><b>Banco: </b></label>
					<div class="col-sm-9">
					<div class="input-group">
						<select class="form-control" id="idbanco" name="idbanco" onchange="buscar_saldo(this.value)" style="width: 80%;">
							<option></option>
							<?php
							$sql = "SELECT * FROM banco WHERE estatus = 1";
							$result = mysqli_query($enlace,$sql);
							while ($banco = mysqli_fetch_array($result)) {
								?>
								<option value="<?= $banco["idbanco"] ?>"><?= utf8_encode($banco["nombre"]) ?></option>
							<?php
							}
							?>
						</select><div style="  width: 10%; float: left;">&nbsp;&nbsp;<b>Saldo:</b>&nbsp;<span id="saldo"></span></div>
					</div>
			</div>
			</div>
			<div id="div_documento" class="form-group" style="visibility:hidden;">
				<label class="col-sm-3 control-label"><b>N&ordm; <span id="tipo_documento"></span>: </b></label>
				<div class="col-sm-3">
					<input type="text" class="form-control" id="ndocumento" name="ndocumento">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><b>Importe: </b></label>
				<div class="col-sm-3">
					<div class='input-group'><span class='input-group-addon'><i style='font-weight:bold;'>Bs.F.</i></span><input class='form-control' type='text' id='importe' placeholder='Importe' name='importe' onkeyup='this.value=ValidaNumero(event,this);' ></div>
				</div>
			</div>
			<div class="form-group" style="text-align:center;margin-top:15px;">
			    <button type="button" class="btn btn-success" onclick="guardarPago()">Guardar Pago</button>
			    <button type="button" class="btn btn-default" onclick="limpiar()">Limpiar</button>
			</div>
			</fieldset>
			<fieldset>
				<legend>Conciliaciones</legend>
				<div class="form-group">
					<label class="col-sm-1 control-label"><b>Filtros:</b></label>
					<div class="col-sm-4">
						<select class="form-control" id="fil_estatus" onchange="verCheques()">
							<option></option>
							<option value="1">Debitado</option>
							<option value="0">No Cobrado</option>
						</select>
					</div>
				</div>
				<div id="cheques"></div>
			</fieldset>
		</div>
</div>
</div>
</div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4>TEST</h4>
	      </div>
	      <div class="modal-body" id="detalles">
	      </div>
	      <div class="modal-footer"></div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<div id="divDatos" style="margin:0 auto;max-height:600px;over-flow:auto;"></div>



<script type="text/javascript">
function cambiar_documento(tipo){
	if (tipo == 1) {
		$("#div_documento").css("visibility","hidden");
	}
	if (tipo == 2) {
		$("#div_documento").css("visibility","visible");
		$("#tipo_documento").html("Cheque");
	}
	if (tipo == 3) {
		$("#div_documento").css("visibility","visible");
		$("#tipo_documento").html("Transferencia");
	}
	if (tipo == 4) {
		$("#div_documento").css("visibility","visible");
		$("#tipo_documento").html("Deposito");
	}
}

function guardarPago(){
	var idfactura   =$("#idfactura").val();
	var importe 	= $("#importe").val();
	$("#accion").val("Guardar")
	if (idfactura=="") {
		crear_dialog("Alerta","Por favor Seleccione una Factura","");
		return false;
	}
	if ($("#tipo_documento").val()!="" && $("#ndocumento").val()=="") {
		crear_dialog("Alerta","Por favor ingrese el numero de documento.","");
		return false;
	}
	if (importe==""||importe=="0") {
		crear_dialog("Alerta","Por favor ingrese el importe del Pago.","");
		return false;
	}
	importe =  $("#importe").val();
	res = importe.replace(".","");
	res = res.replace(",",".");
	importe = parseFloat(res);	
	$("#importe").val(importe);

	if(importe>parseFloat($("#saldo").val())){
		crear_dialog("Alerta","Disculpe el importe es mayor al saldo disponible. Por favor seleccione otra cuenta.","");
		return false;
	}

	$.get("compras/pagos_datos.php",$("#form1").serialize(),function(result){
		json = eval('('+result+')');
		crear_dialog("Info",json.mensaje);
		if(json.result==true){
			limpiar();
			verPagos();
			verCheques();
		}
	});
}

function verPagos(){
	$.get("compras/pagos_datos.php",{accion:"verPagos"},function(result){
		$("#divDatos").html(result);
	});	
}

function verCheques(){
	estatus = $("#fil_estatus").val()
	$.get("compras/pagos_datos.php",{accion:"verCheques",fil_estatus:estatus},function(result){
		$("#cheques").html(result);
	});	
}

function buscarFactura()

{
	var nfactura   =$("#nfactura").val();
	if (nfactura=="") {
		crear_dialog("Alerta","Indique el N de Factura","nfactura");
		return false;
	}
	$("#factura_seleccionada").css("visibility","visible");
	$("#factura_seleccionada").load("compras/pagos_datos.php",{
		"accion":"buscaFactura",
		"nfactura":nfactura
	});

}

function buscar_saldo(idbanco){
	$.get("compras/pagos_datos.php",{accion:"buscarSaldo",idbanco:idbanco},function(result){
		$("#saldo").html(result);
	});
}

function verFacturas(){
	var nfactura   =$("#nfactura").val();
	$.get("compras/pagos_datos.php",{accion:"verTodasFacturas",nfactura:nfactura},function(result){
		$("#factura_seleccionada").css("visibility","visible");
		$("#factura_seleccionada").html(result);
	});
}

function seleccionar(idfactura,total,resta){
	res = total.replace(".","");
	res = res.replace(",",".");
	total = parseFloat(res);
	if($("#total").val()!=""){
		total = parseFloat($("#total").val())+parseFloat(total);
	}
	$("#total").val(total);
	$("#datos_factura").html("<p><b style='font-size: 22px; font-style: italic;'>Total:</b>&nbsp;&nbsp;<span style='font-size: 22px;'>"+number_format(total,2,".",",")+"</span>&nbsp;<b>Bs.</b></p><p><b style='font-size: 22px; font-style: italic;'>Resta:</b>&nbsp;<span style='font-size: 22px;'>"+resta+"</span>&nbsp;<b>Bs.</b></p>");
}

function quitar(idfactura,importe,resta){
	res = importe.replace(".","");
	res = res.replace(",",".");
	importe = parseFloat(res);
	total = $("#total").val();
	if($("#total").val()!=""){
		total = parseFloat($("#total").val())-parseFloat(total);
	}
	$("#datos_factura").html("<p><b style='font-size: 22px; font-style: italic;'>Total:</b>&nbsp;&nbsp;<span style='font-size: 22px;'>"+total+"</span>&nbsp;<b>Bs.</b></p><p><b style='font-size: 22px; font-style: italic;'>Resta:</b>&nbsp;<span style='font-size: 22px;'>"+resta+"</span>&nbsp;<b>Bs.</b></p>");
}

//function buscaDetalle(idfactura){
//$('#myModal').modal('show');
//$.get("compras/pagos_datos.php",{accion:"buscarDetalle",idfactura:idfactura},function(result){
//	$("#detalles").html(result);
//});
//}



function limpiar()
{
	$("form")[0].reset();
	$("#factura_seleccionada").html("");
	$("#factura_seleccionada").css("visibility","hidden");
	$("#saldo").html("");
	$("#div_documento").css("visibility","hidden");
}

function aprobar(idpago){
	$.get("compras/pagos_datos.php",{accion:"aprobar",idpago:idpago},function(result){
		json = eval('('+result+')');
		crear_dialog("Info",json.msg)
		if(json.result==true){
			limpiar();
			verPagos();
			verCheques();
		}		
	});
}

verPagos();
verCheques();

</script>