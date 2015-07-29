<?
if(file_exists("../funcionesphp/seguridad.php"))
	include("../funcionesphp/seguridad.php");
else
	include("funcionesphp/seguridad.php");
antiChismoso();
/*	
	CHULETA PARA LOS INPUTS: 

input_hidden("$id","$value");
input_text("$label","$id","$ancho","$tipo","$onclick","$onblur","$attr","$type");
input_textarea("$label","$id","$ancho","$onclick","$onblur","$attr","$height");
input_numero("$label","$id","$ancho","$onclick","$onblur","$attr");
input_monto("$label","$id","$ancho","$onclick","$onblur","$attr");
input_fecha("$label","$id","$ancho",$fecha,"$onclick","$onblur","$attr");
select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options","$selected","$class");
input_check("$label","$id","$ancho","$value","$onclick","$onchange","$onblur","$attr","$class")

*/
?>
<div class="container" style="padding-top:0px;">		
		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Cambio de contrase&ntilde;a</div>
			<div class="panel-body">
			<?
			input_text("<b>Nueva contrase&ntilde;a:</b>","pass","$ancho","$tipo","$onclick","$onblur","$attr","password");
			input_text("<b>Repita la contrase&ntilde;a:</b>","pass_rep","$ancho","$tipo","$onclick","$onblur","$attr","password");
			?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar()"><i class="fa fa-eraser"></i> Limpiar</button>
				</div>
			</div>
		</div>
</div>
<div id="divDatos" style="margin:0 auto;"></div>

<script type="text/javascript">
function guardar()
{
	if($("#pass").val()=="")
	{
		crear_dialog("Alerta","Introduzca la nueva contrase&ntilde;a","pass");
		return false;		
	}
	if($("#pass_rep").val()=="")
	{
		crear_dialog("Alerta","Repita la nueva contrase&ntilde;a","pass_rep");
		return false;		
	}
	if($("#pass").val()!=$("#pass_rep").val())
	{
		crear_dialog("Alerta","Las contrase&ntilde;as no coinciden","");
		return false;		
	}	

	var pass 	=$("#pass").val();

	$.post("mantenimiento/cambio_pass_datos.php",
	{
		"accion":"cambioPass",
		"pass":pass
	},
	function(resp)
	{
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","limpiar(); ");
		else
			crear_dialog("Alerta",json.mensaje,"",""); //reload		
	});
}

function limpiar()
{
	$("form")[0].reset();
}
</script>