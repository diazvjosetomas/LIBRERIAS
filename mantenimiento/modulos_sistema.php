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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Nuevo M&oacute;dulo</div>
			<div class="panel-body">
				<?=input_hidden("id_modulo","");?>

				<? input_text("<b>Nombre m&oacute;dulo:</b>","modulo","$ancho","$tipo","$onclick","$onblur","$attr");
				   input_text("<b>&Iacute;cono:</b>","icono","$ancho","$tipo","$onclick","$onblur","$attr");
				   input_numero("<b>Orden:</b>","orden","1","$onclick","$onblur","$attr");
				   input_check("<b>Habilitado:</b>","estatus","$ancho","2","if(this.value==2){this.value=1}else{this.value=2}","$onchange","$onblur","$attr");
				?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar()">Limpiar</button>
				</div>
			</div>
		</div>
</div>
<div id="divDatos" style="margin:0 auto;"></div>

<script type="text/javascript">
setTimeout(function() {
    verModulos();
}, 0);

function guardar()
{
	if($("#modulo").val()=="")
	{
		crear_dialog("Alerta","Introduzca el nombre del m&oacute;dulo","modulo");
		return false;		
	}
	if($("#orden").val()=="")
	{
		crear_dialog("Alerta","Indique el orden del m&oacute;dulo","orden");
		return false;		
	}

	if($("#id_modulo").val()=="")
		var accion="guardarModulo";
	else
		var accion="modificarModulo";

	var modulo 		=$("#modulo").val();
	var icono 		=$("#icono").val();
	var orden  		=$("#orden").val();
	var id_modulo 	=$("#id_modulo").val();
	var estatus 	=$("#estatus").val();

	$.post("mantenimiento/modulos_sistema_datos.php",
	{
		"accion":accion,
		"modulo":modulo,
		"icono":icono,
		"orden":orden,
		"id_modulo":id_modulo,
		"estatus":estatus
	},
	function(resp)
	{
		var json = eval("("+resp+")");
		if(json.result==true)
			crear_modal("Información",json.mensaje,"success","","verModulos(); limpiar();","");
		else
			crear_modal("Información",json.mensaje,"error","","","");
	});
}

function pag(num)
{
	var accion="verModulos";
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

	$("#divDatos").load("mantenimiento/modulos_sistema_datos.php",{accion:accion,pg:pg},function()
		{
			$("#pag"+num).addClass("active");
			setTimeout($.unblockUI); 
		});
}

function verModulos()
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

	$("#divDatos").load("mantenimiento/modulos_sistema_datos.php",
	{
		"accion":"verModulos"
	}
	,
	function()
	{
		setTimeout($.unblockUI);
	});	
}

function modificar(id_modulo,modulo,icono,orden,estatus)
{
	$("#id_modulo").val(id_modulo);
	$("#modulo").val(modulo);
	$("#icono").val(icono);
	$("#orden").val(orden);
	if(estatus==1)
		$("#estatus").prop("checked",true);

	$("#estatus").val(estatus);
}

function eliminar(id_modulo)
{
	$.post("mantenimiento/modulos_sistema_datos.php",
	{
		"accion":"eliminarModulo",
		"id_modulo":id_modulo
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verModulos(); limpiar();");
		else
			crear_dialog("Alerta",json.mensaje,"","reload");
	});
}

function limpiar()
{
	$("form")[0].reset();
	$("#id_modulo").val("");
	$("#estatus").val("2");
	$("#estatus").prop("checked",false);
}
</script>