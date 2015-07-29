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

			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Reporte de Pagos</div>

			<div class="panel-body">

				<div class="form-group" style="margin: 1px;">

					<i class="fa fa-check-circle"></i> <em>Filtros de b&uacute;squeda</em>

					<hr style="margin-top:11px;width:85%;float:right;"></hr>

				</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"><b>Tipo de Pagos: </b></label>
					<div class="col-sm-6">
						<select class="form-control" id="tipopago" name="tipopago">
							<option></option>
							<option value="1">Efectivo</option>
							<option value="2">Cheques</option>
							<option value="3">Transferencia</option>
							<option value="4">Deposito</option>
						</select>					
					</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"><b>Estatus: </b></label>
					<div class="col-sm-6">
						<select class="form-control" id="estatus" name="estatus">
							<option></option>
							<option value="0">Pendiente</option>
							<option value="1">Aprobado</option>
							<option value="2">Anulado</option>
						</select>					
					</div>
			</div>

				<div class="form-group" style="text-align:center;margin-top:15px;">

				    <button type="button" class="btn btn-success" onclick="buscarPagos()">Buscar</button>

				    <button type="button" class="btn btn-default" onclick="limpiar()">Limpiar</button>

				</div>

			</div>

		</div>

</div>
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4>Detalles de la Factura</h4>
	      </div>
	      <div class="modal-body" id="detalles">
	      </div>
	      <div class="modal-footer"></div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<div id="divDatos" style="margin:0 auto;max-height:600px;over-flow:auto;"></div>



<script type="text/javascript">

function buscarPagos()

{

	var tipopago  =$("#tipopago").val();
	var estatus   =$("#estatus").val();

	$("#divDatos").load("compras/r_pagos_datos.php",{

		"accion":"verPagos",
		"estatus":estatus,
		"tipopago":tipopago

	});

}

function buscaDetalle(idfactura){
$('#myModal').modal('show');
$.get("compras/r_pagos_datos.php",{accion:"buscarDetalle",idfactura:idfactura},function(result){
	$("#detalles").html(result);
});
}



function limpiar()

{

	$("form")[0].reset();

}

</script>