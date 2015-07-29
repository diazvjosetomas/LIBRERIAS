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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Configuraci&oacute;n margen de ganancia</div>
			<div class="panel-body">
			<?
				input_fecha("<b>Fecha vigencia:</b>","fecha","$ancho","$fecha","$onclick","$onblur","$attr");
				input_numero("<b>Margen de ganancia:</b>","valor","1","$onclick","$onblur","$attr");
			?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardar()"><i class="fa fa-floppy-o"></i> Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar()"><i class="fa fa-eraser"></i> Limpiar</button>
				</div>
			</div>
		</div>
</div>
<div id="divDatos" style="margin:0 auto;"></div>

<script type="text/javascript">
function guardar()
{
	if($("#fecha").val()=="")
	{
		crear_dialog("Alerta","Seleccione la fecha de vigencia","fecha");
		return false;	
	}	
	if($("#valor").val()=="")
	{
		crear_dialog("Alerta","Indique el margen de ganancia","valor");
		return false;	
	}

	var fecha =$("#fecha").val();
	var valor =$("#valor").val();

	capaBloqueo();

	$.post("mantenimiento/margen_ganancia_datos.php",
	{
		"accion":"ingresarMargen",
		"fecha":fecha,
		"valor":valor
	},
	function(resp)
	{
		quitarCapa();

		var json = eval("(" + resp + ")");
		if(json.result==true)
		{
			crear_dialog("Informaci&oacute;n",json.mensaje,"","reload");
			return false;			
		}	
		else
		{
			crear_dialog("Informaci&oacute;n",json.mensaje,"","reload");
			return false;			
		}
	});
}
</script>