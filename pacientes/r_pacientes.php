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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Reporte de pacientes</div>
			<div class="panel-body">
				<div class="form-group" style="margin: 1px;">
					<i class="fa fa-check-circle"></i> <em>Filtros de b&uacute;squeda</em>
					<hr style="margin-top:11px;width:85%;float:right;"></hr>
				</div>
			<?
				input_text("<b>C&eacute;dula:</b>","cedula","","2","","","onkeyup='this.value=ValidaNumero(event,this);'");
				input_text("<b>Nombres:</b>","nombres","","","","","");
				input_text("<b>Apellidos:</b>","apellidos","","","","","");
			?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="buscaPacientes()">Buscar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar()">Limpiar</button>
				</div>
			</div>
		</div>
</div>
<?
	input_hidden("datos_a_enviar","$value");
?>
<div id="divDatos" style="margin:0 auto;max-height:600px;over-flow:auto;"></div>
<form id="lolol" name="lolol" class="form-horizontal" role="form">
<div id="exce"></div>
</form>
<script type="text/javascript">
function buscaPacientes()
{
/*	if($("#cedula").val()=="" && $("#nombres").val()=="" && $("#apellidos").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Seleccione alg&uacute;n filtro de b&uacute;squeda","");
		return false;
	}*/

	var cedula 	  =$("#cedula").val();
	var nombres   =$("#nombres").val();
	var apellidos =$("#apellidos").val();

	$("#divDatos").load("pacientes/r_pacientes_datos.php",{
		"accion":"verPacientes",
		"cedula":cedula,
		"nombres":nombres,
		"apellidos":apellidos
	});
}

function limpiar()
{
	$("form")[0].reset();
}
</script>