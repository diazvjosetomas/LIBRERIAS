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
input_textarea("$label","$id","$ancho","$onclick","$onblur","$attr");
input_numero("$label","$id","$ancho","$onclick","$onblur","$attr");
input_monto("$label","$id","$ancho","$onclick","$onblur","$attr");
input_fecha("$label","$id","$ancho",$fecha,"$onclick","$onblur","$attr");
select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options");
input_check("$label","$id","$ancho","$value","$onclick","$onchange","$onblur","$attr","$class")

*/
?>
<div class="container" style="padding-top:0px;">		
		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Nuevo tipo de usuario</div>
			<div class="panel-body">
			<?
				input_hidden("tipoUser","$value");
				input_text("<b>Tipo de usuario:</b>","tusuario","$ancho","$tipo","$onclick","$onblur","$attr","$type");
			?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>
				    <button type="button" class="btn btn-default" onclick="">Limpiar</button>
				</div>
			</div>
		</div>
</div>
<div id="divDatos" style="margin:0 auto;"></div>

<script type="text/javascript">
setTimeout(function() {
    verUsuarios();
}, 0);

function guardar()
{
	if($("#tusuario").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Introduzca el tipo usuario.","tusuario");
		return false;
	}

	if($("#tipoUser").val()!="")
		var accion="modificarusuario";
	else
		var accion="guardarUsuario";

	var tusuario 	=$("#tusuario").val();
	var tipo_user 	=$("#tipoUser").val();

		$.blockUI({ css: { 
		    border: 'none', 
		    padding: '15px', 
		    backgroundColor: '#000', 
		    '-webkit-border-radius': '10px', 
		    '-moz-border-radius': '10px', 
		    opacity: .5, 
		    color: '#fff' 
		} });

		$.post("mantenimiento/tipo_usuarios_datos.php",
		{
			"accion":accion,
			"tusuario":tusuario,
			"tipo_user":tipo_user
		},
		function(resp)
		{
			setTimeout($.unblockUI);	
			var json = eval("("+resp+")");
			if(json.result==true)
				crear_dialog("Informaci&oacute;n",json.mensaje,"","verUsuarios(); limpiar();");
			else
				crear_dialog("Informaci&oacute;n",json.mensaje,"","");	
		});	
}

function modificar(tipo_user,nombre)
{
	$("#tipoUser").val(tipo_user);
	$("#tusuario").val(nombre);
}

function eliminar(tipo_user)
{
	$.post("mantenimiento/tipo_usuarios_datos.php",
	{
		"accion":"eliminarUsuario",
		"tipo_user":tipo_user
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verUsuarios(); limpiar();");
		else
			crear_dialog("Alerta",json.mensaje,"","reload");
	});
}

function verUsuarios()
{
	$.blockUI({ css: { 
	    border: 'none', 
	    padding: '15px', 
	    backgroundColor: '#000', 
	    '-webkit-border-radius': '10px', 
	    '-moz-border-radius': '10px', 
	    opacity: .5, 
	    color: '#fff' 
	} });

	$("#divDatos").load("mantenimiento/tipo_usuarios_datos.php",
	{
		"accion":"verUsuarios"
	}
	,
	function()
	{
		setTimeout($.unblockUI);
	});	
}

function limpiar()
{
	$("form")[0].reset();
	$("#tipoUser").val("");
}
</script>