<?
if(file_exists("../funcionesphp/seguridad.php"))
	include("../funcionesphp/seguridad.php");
else
	include("funcionesphp/seguridad.php");
antiChismoso();
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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Libro de Compras</div>
			<div class="panel-body">
				<div class="form-group" style="margin: 1px;">
					<i class="fa fa-check-circle"></i> <em>Filtros de b&uacute;squeda</em>
					<hr style="margin-top:11px;width:85%;float:right;"></hr>

				</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><b>Mes: </b></label>
					<div class="col-sm-3">
							<select class='form-control fecha' type='text' id='mes' name='mes'>
								<option value="1">Enero</option>
								<option value="2">Febrero</option>
								<option value="3">Marzo</option>
								<option value="4">Abril</option>
								<option value="5">Mayo</option>
								<option value="6">Junio</option>
								<option value="7">Julio</option>
								<option value="8">Agosto</option>
								<option value="9">Septiembre</option>
								<option value="10">Octubre</option>
								<option value="11">Noviembre</option>
								<option value="12">Diciembre</option>
							</select>		
					</div>
				<label class="col-sm-2 control-label"><b>A&ntilde;o: </b></label>
					<div class="col-sm-4">
							<select  class='form-control fecha' type='text' id='anio' name='anio'>
								<option value="2013">2013</option>
								<option value="2014">2014</option>
								<option value="2015">2015</option>
							</select>			
					</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label"><b>Salida: </b></label>
					<div class="col-sm-3">
						<select id="tiposalida" name="tiposalida" class="form-control">
						<option></option>
						<option value="1">Excel</option>
						<option value="2">PDF</option>
						</select>	
					</div>
			</div>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="buscarFacturas()">Buscar</button>
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
<div id="divDatos" style="width:100%;margin:0 auto;max-height:600px;over-flow:auto;display:none;"></div>

<script type="text/javascript">
$( document ).ready(function() {
   $("#divCont").css("width","100%");
});

function buscarFacturas(){

	var iva   	=$("#iva").val();
	mes=$("#mes").val();
	anio	=$("#anio").val();
	tiposalida  =$("#tiposalida").val();


	$.get("compras/libro_compras_datos.php",{"accion":"verLibro","mes":mes,"anio":anio,"iva":iva,"tiposalida":tiposalida}, function(resp){
		$("#divDatos").show("blind","fast");
		$("#divDatos").html(resp);
		if(tiposalida==1){
			exportarE();
		}
		if(tiposalida==2){
			exportarP('landscape')
		}
	});
}

function limpiar()
{
	$("form")[0].reset();
	$("#divDatos").hide("blind","fast");
}
</script>