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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Seguros m&eacute;dicos</div>
			<div class="panel-body">
			<?
				input_hidden("id_seguro","");
				input_text("<b>Nombre seguro:</b>","nombre","4","$tipo","$onclick","mayusculas(this)","$attr","$type");
				input_check("<b>Activo:</b>","activo","$ancho","2","if(this.checked){ this.value = 1; }else{ this.value = 2; }","$onchange","$onblur","$attr","$class");
			?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardar()"><i class="fa fa-floppy-o"></i> Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar()"><i class="fa fa-eraser"></i> Limpiar</button>
				</div>
			</div>
		</div>
</div>
<div class="container" style="margin-top:30px;">
		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Seguros m&eacute;dicos registrados</div>
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
					<td><?input_text("<b>Nombre seguro:</b>","fil_nombre","6","$tipo","$onclick","$onblur","$attr","$type");?></td>
					<td><?input_check("<b>Activo:</b>","fil_activo","$ancho","","if(this.checked){ this.value = 1; }else{ this.value = 2; }","$onchange","$onblur","$attr","$class");?></td>
				</tr>
				<tr id="fil_3" style="display:none;">
					<td align="center" colspan="2">
						<button type="button" class="btn btn-success" onclick="buscar()"><i class="fa fa-search"></i> Buscar</button>
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
    verSeguros();
}, 0);

function pag(num)
{
	var accion="verSeguros";
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

	$("#divDatos").load("facturacion/seguros_medicos_datos.php",{accion:accion,pg:pg},function()
		{
			$("#pag"+num).addClass("active");
			setTimeout($.unblockUI); 
		});
}

function guardar()
{
	if($("#nombre").val()=="")
	{
		crear_dialog("Alerta","Indique el nombre del seguro","nombre");
		return false;	
	}

	if($("#id_seguro").val()!="")
		var accion="modificaSeguro";
	else
		var accion="guardarSeguro";	

	var nombre 		=$("#nombre").val();
	var activo 		=$("#activo").val();
	var id_seguro 	=$("#id_seguro").val();

	capaBloqueo();

	$.post("facturacion/seguros_medicos_datos.php",
	{
		"accion":accion,
		"nombre":nombre,
		"activo":activo,
		"id_seguro":id_seguro
	},
	function(resp)
	{
		quitarCapa();

		var json = eval("(" + resp + ")");
		if(json.result==true)
		{
			crear_modal("Información",json.mensaje,"success","","limpiar(); verSeguros();","");
			return false;			
		}	
		else
		{
			crear_modal("Información",json.mensaje,"error","","reload","");
			return false;			
		}
	});
}

function modificar(id_seguro,nombre,estatus)
{
	$("#id_seguro").val(id_seguro);
	$("#nombre").val(nombre);
	if(estatus==1)
		$("#activo").prop("checked",true);	

	$("#activo").val(estatus);
}

function conf_eliminar(id_seguro,seguro)
{
		$(".sweet-alert").css("box-shadow","inset 0px 0px 14px 2px rgb(248, 197, 134)");
		swal({
		  title: "¿Eliminar seguro: '"+seguro+"'?",
		  text: "Esta opción no puede deshacerse",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-danger",
		  confirmButtonText: "Eliminar",
		  closeOnConfirm: true
		},
		function(){
			setTimeout(function(){
				eliminar(id_seguro);
			},500);
		});
}

function eliminar(id_seguro)
{
	$.post("facturacion/seguros_medicos_datos.php",
	{
		"accion":"eliminarSeguro",
		"id_seguro":id_seguro
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_modal("Información",json.mensaje,"success","","verSeguros(); limpiar();","");
		else
			crear_modal("Información",json.mensaje,"error","","reload","");
	});
}

function buscar()
{
	var fil_nombre	=$("#fil_nombre").val();
	var fil_activo 	=$("#fil_activo").val();

	$.blockUI({ css: { 
	    border: 'none', 
	    padding: '15px', 
	    backgroundColor: '#000', 
	    '-webkit-border-radius': '10px', 
	    '-moz-border-radius': '10px', 
	    opacity: .5, 
	    color: '#fff' 
	} });

	$("#divDatos").load("facturacion/seguros_medicos_datos.php",
	{
		"accion":"verSeguros",
		"fil_nombre":fil_nombre,
		"fil_activo":fil_activo	
	},
	function()
	{
		setTimeout($.unblockUI); 
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

function verSeguros()
{
	capaBloqueo();

	$("#divDatos").load("facturacion/seguros_medicos_datos.php",
	{
		"accion":"verSeguros"
	},
	function(resp)
	{
		quitarCapa();
	});
}

function limpiar()
{
	$("form")[0].reset();
	$("#id_seguro").val("");
	$("#activo").val("0");
	$("#activo").prop("checked",false);
}

function limpiar_fil()
{
	$("#fil_nombre").val("");
	$("#fil_activo").val("");
	$("#fil_activo").prop("checked",false);
}
</script>