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
select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options","$selected");
input_check("$label","$id","$ancho","$value","$onclick","$onchange","$onblur","$attr")

*/
?>
<div class="container" style="padding-top:0px;">		
		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Nuevo Programa</div>
			<div class="panel-body">
				<?=input_hidden("id_programa","");?>
				<?=input_text("<b>Nombre programa:</b>","programa","$ancho","$tipo","$onclick","$onblur","$attr");?>
				<?=input_text("<b>Ruta programa:</b>","ruta","4","$tipo","$onclick","$onblur","$attr");?>
				<?=select("<b>M&oacute;dulo:</b>","idmodulo","$ancho","$onchange","modulos_sistema","estatus=1","id_modulo","modulo","$onclick","$onblur","$attr","$options");?>
				<?=input_numero("<b>Orden:</b>","orden","1","$onclick","$onblur","$attr");?>
				<?=input_check("<b>Habilitado:</b>","estatus","$ancho","2","if(this.value==2){this.value=1}else{this.value=2}","$onchange","$onblur","$attr")?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar()">Limpiar</button>
				</div>
			</div>
		</div>
</div>

			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Programas del sistema</div>
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
								<td><?input_text("<b>Nombre programa:</b>","fil_nombre","6","$tipo","$onclick","$onblur","$attr","$type");?></td>
								<td><?select("<b>M&oacute;dulo:</b>","fil_modulo","$ancho","$onchange","modulos_sistema","estatus=1","id_modulo","modulo","$onclick","$onblur","$attr","$options");?></td>
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
<!-- <div id="divDatos" style="margin:0 auto;"></div> -->

<script type="text/javascript">
setTimeout(function() {
    verProgramas();
}, 0);

function guardar()
{
	if($("#programa").val()=="")
	{
		crear_dialog("Alerta","Introduzca el nombre del programa","programa");
		return false;		
	}
	if($("#ruta").val()=="")
	{
		crear_dialog("Alerta","Introduzca la ruta del programa","ruta");
		return false;		
	}
	if($("#idmodulo").val()=="")
	{
		crear_dialog("Alerta","Seleccione el m&oacute;dulo al que pertenece el programa","idmodulo");
		return false;		
	}
	if($("#orden").val()=="")
	{
		crear_dialog("Alerta","Indique el orden del m&oacute;dulo","orden");
		return false;		
	}

	if($("#id_programa").val()=="")
		var accion="guardarPrograma";
	else
		var accion="modificarPrograma";

	var programa 	=$("#programa").val();
	var ruta 		=$("#ruta").val();
	var orden  		=$("#orden").val();
	var idmodulo    =$("#idmodulo").val();
	var id_programa =$("#id_programa").val();
	var estatus 	=$("#estatus").val();

	$.post("mantenimiento/programas_sistema_datos.php",
	{
		"accion":accion,
		"programa":programa,
		"ruta":ruta,
		"orden":orden,
		"idmodulo":idmodulo,
		"id_programa":id_programa,
		"estatus":estatus
	},
	function(resp)
	{
		var json = eval("("+resp+")");
		if(json.result==true)
			crear_modal("Información",json.mensaje,"success","","verProgramas(); limpiar();","");
		else
			crear_modal("Información",json.mensaje,"error","","","");
	});
}

function verProgramas()
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

	$("#divDatos").load("mantenimiento/programas_sistema_datos.php",
	{
		"accion":"verProgramas"
	}
	,
	function()
	{
		setTimeout($.unblockUI);
	});	
}

function modificar(id_programa,id_modulo,modulo,nombre_programa,ruta,orden,estatus)
{
	$("#id_programa").val(id_programa);
	$("#programa").val(nombre_programa);
	$("#ruta").val(ruta);
	$("#idmodulo").val(id_modulo);
	$(".filter-option").html(modulo);
	$("#orden").val(orden);
	if(estatus==1)
		$("#estatus").prop("checked",true);

	$("#estatus").val(estatus);
}

function conf_eliminar(id,programa)
{
		$(".sweet-alert").css("box-shadow","inset 0px 0px 14px 2px rgb(248, 197, 134)");
		swal({
		  title: "¿Eliminar programa: '"+programa+"'?",
		  text: "Esta opción no puede deshacerse",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-danger",
		  confirmButtonText: "Eliminar",
		  closeOnConfirm: true
		},
		function(){
			setTimeout(function(){
				eliminar(id);
			},500);
		});
}

function eliminar(id_programa)
{
	$.post("mantenimiento/programas_sistema_datos.php",
	{
		"accion":"eliminarPrograma",
		"id_programa":id_programa
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_modal("Información",json.mensaje,"success","","verProgramas(); limpiar();","");
		else
			crear_modal("Información",json.mensaje,"error","","reload","");
	});
}

function pag(num)
{
	var accion="verProgramas";
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

	$("#divDatos").load("mantenimiento/programas_sistema_datos.php",{accion:accion,pg:pg},function()
		{
			$("#pag"+num).addClass("active");
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

function buscar()
{
	var fil_nombre	=$("#fil_nombre").val();
	var fil_modulo 	=$("#fil_modulo").val();

	$.blockUI({ css: { 
	    border: 'none', 
	    padding: '15px', 
	    backgroundColor: '#000', 
	    '-webkit-border-radius': '10px', 
	    '-moz-border-radius': '10px', 
	    opacity: .5, 
	    color: '#fff' 
	} });

	$("#divDatos").load("mantenimiento/programas_sistema_datos.php",
	{
		"accion":"verProgramas",
		"fil_nombre":fil_nombre,
		"fil_modulo":fil_modulo	
	},
	function()
	{
		setTimeout($.unblockUI); 
	});		
}

function limpiar_fil()
{
	$("#fil_modulo,#fil_nombre").val("");
	$(".filter-option").empty();
}

function limpiar()
{
	$("form")[0].reset();
	$("#id_programa").val("");
	$("#estatus").val("2");
	$("#estatus").prop("checked",false);
	$(".filter-option").empty();
}
</script>