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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Servicios</div>
			<div class="panel-body">
			<?
			input_hidden("idServicio","$value");
			input_text("<b>C&oacute;digo:</b>","codServicio","$ancho","$tipo","$onclick","verificaCodigo()","$attr","$type");
			input_text("<b>Nombre del servicio:</b>","nomServicio","$ancho","$tipo","$onclick","mayusculas(this)","$attr","$type");
			input_textarea("<b>Descripci&oacute;n:</b>","descServicio","$ancho","$onclick","$onblur","$attr","$height");
			input_monto("<b>Precio:</b>","precServicio","$ancho","$onclick","$onblur","$attr");
			?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardarServicio()"><i class="fa fa-floppy-o"></i> Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar()"><i class="fa fa-eraser"></i> Limpiar</button>
				</div>
			</div>
		</div>
</div>

			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Servicios registrados</div>
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
								<td><?input_text("<b>C&oacute;digo:</b>","fil_codigo","6","$tipo","$onclick","$onblur","$attr","$type");?></td>
								<td><?input_text("<b>Nombre:</b>","fil_nombre","6","$tipo","$onclick","$onblur","$attr","$type");?></td>
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
    $('#codServicio').focus();
    verServicios();
}, 0);

function verificaCodigo()
{
	Cod=$("#codServicio").val();
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
					crear_modal("Alerta",json.mensaje,"warning","codServicio","","");
					return false;
				}
			});
	}
}

function guardarServicio()
{
	if($("#codServicio").val()=="")
	{
		crear_dialog("Alerta","Indique el c&oacute;digo del servicio","codServicio");
		return false;
	}
	if($("#nomServicio").val()=="")
	{
		crear_dialog("Alerta","Indique el nombre del servicio","nomServicio");
		return false;
	}
	if($("#descServicio").val()=="")
	{
		crear_dialog("Alerta","Indique la decripci&oacute;n del servicio","descServicio");
		return false;
	}
	if($("#precServicio").val()=="")
	{
		crear_dialog("Alerta","Indique el precio del servicio","precServicio");
		return false;
	}

	var accion="";

	if($("#idServicio").val()!="")
		accion="modificarServicio";
	else
		accion="guardarServicio";

	var codServicio	=$("#codServicio").val();
	var nomServicio	=$("#nomServicio").val();
	var descServicio=$("#descServicio").val();
	var precServicio=$("#precServicio").val();
	var idServicio	=$("#idServicio").val();

	$.post("servicios/servicios_datos.php",
	{
		"accion":accion,
		"codServicio":codServicio,
		"nomServicio":nomServicio,
		"descServicio":descServicio,
		"precServicio":precServicio,
		"idServicio":idServicio
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_modal("Información",json.mensaje,"success","","verServicios(); limpiar();","");
		else
			crear_modal("Alerta",json.mensaje,"error","","","");
	});
}

function modificar(id_servicio,cod_servicio,nombre,descripcion,precio)
{
	$("#idServicio").val(id_servicio);
	$("#codServicio").val(cod_servicio);
	$("#nomServicio").val(nombre);
	$("#descServicio").val(descripcion);
	$("#precServicio").val(precio);
}

function eliminar(id_servicio)
{
	$.post("servicios/servicios_datos.php",{
		"accion":"eliminarServicio",
		"idServicio":id_servicio
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verServicios(); limpiar();");
		else
			crear_dialog("Alerta",json.mensaje,"","reload");
	});
}

function pag(num)
{
	var accion="verServicios";
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

	$("#divDatos").load("servicios/servicios_datos.php",{accion:accion,pg:pg},function()
	{
		$("#pag"+num).addClass("active");
		setTimeout($.unblockUI); 
	});
}

function abreDesc(nom_prod,descripcion) 
{
	crear_dialog("Descripci&oacute;n: <em>"+nom_prod+"</em>","<p style='text-align:justify;'>"+descripcion+"</p>","","");
	return false;	
}

function verServicios()
{
	$("#divDatos").load("servicios/servicios_datos.php",{
		"accion":"verServicios"
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
	$("#fil_codigo,#fil_nombre").val("");
}

function limpiar()
{
	$("form")[0].reset();
	$("#idServicio").val("");
}
</script>