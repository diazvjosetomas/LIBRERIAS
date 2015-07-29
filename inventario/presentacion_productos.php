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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Presentación productos</div>
			<div class="panel-body">
			<?
			input_hidden("idPresentacion","$value");
			input_text("<b>Nombre presentaci&oacute;n:</b>","nomPresentacion","$ancho","$tipo","$onclick","$onblur","$attr","$type");
			input_text("<b>Abreviatura:</b>","abPresentacion","$ancho","$tipo","$onclick","$onblur","$attr","$type");
			?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardarPresentacion()"><i class="fa fa-floppy-o"></i> Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar()"><i class="fa fa-eraser"></i> Limpiar</button>
				</div>
			</div>
		</div>
</div>
			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Presentaciones registradas</div>
						<div class="panel-body" style="padding:0px;">
						<table border="0" class="table" width="100%" cellspacing="4" style="font-size: small !important;">
							<tr>
								<td colspan="2"><button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="right" data-original-title="Filtros de busqueda" onclick="mostrar_filtros()"><i class="fa fa-search"></i></button></td>
							</tr>
							<tr id="fil_1" style="display:none;">
								<td colspan="2">					
									<i class="fa fa-check-circle"></i> <label><em>Filtros de b&uacute;squeda</em></label>
									</td>
							</tr>
							<tr id="fil_2" style="display:none;">
								<td><?input_text("<b>Nombre presentaci&oacute;n:</b>","fil_nombre","6","$tipo","$onclick","$onblur","$attr","$type");?></td>
								<td><?input_text("<b>Abreviatura:</b>","fil_ab","6","$tipo","$onclick","$onblur","$attr","$type");?></td>
							</tr>
							<tr id="fil_3" style="display:none;">
								<td align="center" colspan="2">
									<button type="button" class="btn btn-success" onclick="buscar()"><i class="fa fa-search"></i>Buscar</button>
									<button type="button" class="btn btn-default" onclick="limpiar_fil();"><i class="fa fa-eraser"></i> Limpiar</button>
								</td>
							</tr>
						</table>
						<div id="divDatos" style="margin:0 auto;"></div>
						</div>
					</div>
			</div>

<script type="text/javascript">
setTimeout(function() {
    $('#nomPresentacion').focus();
    verPresentacion();
}, 0);

function verificaCodigo(Cod)
{
	if(Cod!="")
	{
		$.post("servicios/servicios_datos.php",
			{
				"accion":"verificaCodigo",
				"filCod":Cod
			},
			function(resp){
				var json = eval("(" + resp + ")");

				if(json.result==true)
				{
					crear_dialog("Alerta",json.mensaje,"codServicio","");
					return false;
				}
			});
	}
}

function guardarPresentacion()
{
	if($("#nomPresentacion").val()=="")
	{
		crear_dialog("Alerta","Indique el nombre de la presentaci&oacute;n","nomPresentacion");
		return false;
	}
	if($("#abPresentacion").val()=="")
	{
		crear_dialog("Alerta","Indique la abreviatura de la presentaci&oacute;n","abPresentacion");
		return false;
	}

	var accion="";

	if($("#idPresentacion").val()!="")
		accion="modificarPresentacion";
	else
		accion="guardarPresentacion";

	var nomPresentacion	=$("#nomPresentacion").val();
	var abPresentacion	=$("#abPresentacion").val();
	var idPresentacion  =$("#idPresentacion").val();

	$.post("inventario/presentacion_productos_datos.php",
	{
		"accion":accion,
		"nomPresentacion":nomPresentacion,
		"abPresentacion":abPresentacion,
		"idPresentacion":idPresentacion
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verPresentacion(); limpiar(); ");
		else
			crear_dialog("Alerta",json.mensaje,"",""); //reload
	});
}

function modificar(id_presentacion,nom_presentacion,ab_presentacion)
{
	$("#idPresentacion").val(id_presentacion);
	$("#nomPresentacion").val(nom_presentacion);
	$("#abPresentacion").val(ab_presentacion);
}

function eliminar(id_presentacion)
{
	$.post("inventario/presentacion_productos_datos.php",{
		"accion":"eliminarPresentacion",
		"idPresentacion":id_presentacion
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verPresentacion(); limpiar();");
		else
			crear_dialog("Alerta",json.mensaje,"","reload");
	});
}

function pag(num)
{
	var accion="verPresentacion";
	var pg=num;


	$.blockUI({ css: { 
	    border: 'none', 
	    padding: '15px', 
	    backgroundColor: '#000', 
	    '-webkit-border-radius': '10px', 
	    '-moz-border-radius': '10px', 
	    opacity: .5, 
	    color: '#fff' 
	} });

	$("#divDatos").load("inventario/presentacion_productos_datos.php",{accion:accion,pg:pg},function()
	{
		$("#pag"+num).addClass("active");
		setTimeout($.unblockUI); 
	});
}

function verPresentacion()
{
	$("#divDatos").load("inventario/presentacion_productos_datos.php",{
		"accion":"verPresentacion"
	});
}

function mostrar_filtros()
{
	if( $("#fil_1").css("display")=="none" )
	{
		$("#fil_1").show("fast");
		$("#fil_2").show("fast");
		$("#fil_3").show("fast");
	}
	else
	{
		$("#fil_1").hide("fast");
		$("#fil_2").hide("fast");
		$("#fil_3").hide("fast");		
	}
}

function limpiar_fil()
{
	$("#fil_nombre,#fil_ab").val("");
}

function limpiar()
{
	$("form")[0].reset();
	$("#idPresentacion").val("");
}
</script>