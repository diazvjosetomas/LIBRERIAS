<?
if(file_exists("../funcionesphp/seguridad.php"))
	include("../funcionesphp/seguridad.php");
else
	include("funcionesphp/seguridad.php");
antiChismoso();
/*	
	CHULETA PARA LOS INPUTS: 

input_hidden("$id","$value");
input_text("$label","$id","$ancho","$tipo","$onclick","$onblur","$attr");
input_textarea("$label","$id","$ancho","$onclick","$onblur","$attr");
input_numero("$label","$id","$ancho","$onclick","$onblur","$attr");
input_monto("$label","$id","$ancho","$onclick","$onblur","$attr");
input_fecha("$label","$id","$ancho",$fecha,"$onclick","$onblur","$attr");
select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options");
input_check("$label","$id","$ancho","$value","$onclick","$onchange","$onblur","$attr")

*/
?>
<div class="container" style="padding-top:0px;">		
		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Permisos para usuarios</div>
			<div class="panel-body" style="padding:0px;">
			<br>
			<?select("<b>Tipo de usuario:</b>","tipo_user","$ancho","buscaPermisos(this.value)","tipo_usuarios","$where","tipo_user","nombre","$onclick","$onblur","$attr","$options");?>
			
			<div id="divDatos" style="height:500px;width:100%;max-height:500px;overflow:auto;overflow-x: hidden;">

			</div>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="verCheck()">Guardar</button>
				</div>
			</div>
		</div>
</div>
<div id="prueba" style="with:100%;"></div>

<script type="text/javascript">
function buscaPermisos(tipo_user)
{
	if(tipo_user!="")
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

		$("#divDatos").load("mantenimiento/permisos_datos.php",
		{
			"accion":"verPermisos",
			"tipo_user":tipo_user
		},
		function(resp)
		{
			setTimeout($.unblockUI);		
		});	
	}
	else
	{
		$("#divDatos").html("");
	}
}

function verCheck()
{
	var tipo_user= 	$("#tipo_user").val();
/*	var res="";
	$(".checkbox").each(function(){
		if( $(this).is(":checked") )
		{
			res+=$(this).val()+"<br>";
		}
	});
	$("#prueba").html(res);*/

	if(tipo_user!="")
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
		$("#accion").val("guardarPermisos");

		$.post("mantenimiento/permisos_datos.php",
			$("form").serialize()
		,
		function(resp)
		{
			setTimeout($.unblockUI);

			var json = eval("("+resp+")");
			if(json.result==true)
				crear_modal("Información",json.mensaje,"success","","reload","")
				//crear_dialog("Informaci&oacute;n",json.mensaje,"","reload");
			else
				crear_modal("Información",json.mensaje,"error","","","")
				//crear_dialog("Informaci&oacute;n",json.mensaje,"","");	
		});	
	}
	else
	{
		$("#divDatos").html("");
	}
}

function marcaTodosCheck(id_modulo)
{
	$("."+id_modulo).each(function(){
		if( $(this).is(":checked") ) //==> Si todos estan en check, los desmarco
		{
			$(this).prop("checked",false);
		}
		else 						//===> Si no estan en check, los marco a todos
		{
			$(this).prop("checked",true);
		}
	});
}
</script>