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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Personal</div>
			<div class="panel-body">
			<?=input_hidden("idpersonal","")?>
			<?=input_text("Cedula","cedula","","2","","","onkeyup='this.value=ValidaNumero(event,this);'")?>
			<?=input_text("<b>MSDS:</b>","permisosanitario","","","","","")?>
			<?=input_text("<b>Nombre:</b>","nombre","","","","","")?>
			<?=input_text("Telefono","telefono","","2","","","onkeyup='mascara(this,\"-\",patron4,true);'")?>
			<?=select("<b>Sexo:</b>","sexo","$ancho","$onchange","","","","","$onclick","$onblur","$attr","1;Masculino,2;Femenino");?>
			<?=select("<b>Tipo Personal:</b>","idtipopersonal","$ancho","$onchange","tipo_personal","","idtipopersonal","tipo","$onclick","$onblur","$attr","");?>
			<?=input_text("<b>Especialidad:</b>","especialidad","","","","","")?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar_form()">Limpiar</button>
				</div>
			</div>
		</div>
</div>
<div class="container" style="margin-top:50px;">
	<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
		<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Personal Registrado</div>
		<div class="panel-body">
			<div style="margin: 15px;"><button type="button" class="btn btn-success" onclick="mostrar_filtros()"><i class="fa fa-search"></i></button></div>
			<div id="filtros" style="display: none;">
				<div style="float: left; width:50%;"><?=input_text("Nombre","filnombre","5","","","","")?></div>
				<div class="form-group" style="text-align:center;margin-top:15px;">
			   		<button type="button" class="btn btn-success" onclick="consultar()">Buscar</button>
			   		<button type="button" class="btn btn-default" onclick="limpiar_filtros()">Limpiar</button>
				</div>
			</div>
			<div id="divDatos" style="margin:0 auto;"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
function pag(num)
{
	var accion="consultar";
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

	$("#divDatos").load("personal/personal_data.php",{accion:accion,pg:pg},function()
		{
			$("#pag"+num).addClass("active");
			setTimeout($.unblockUI); 
		});
}

function guardar(){
	if($("#nombre").val()==""){
		crear_dialog("Error","Debe llenar el campo nombre.","nombre");
		return false;
	}
	if($("#cedula").val()==""){
		crear_dialog("Error","Debe llenar el campo cedula.","cedula");
		return false;
	}
	if($("#permisosanitario").val()==""){
		crear_dialog("Error","Debe llenar el campo permiso sanitario.","nombre");
		return false;
	}
	if($("#accion").val()==""){
		$("#accion").val("guardar");
	}
	$.get("personal/personal_data.php",$("#form1").serialize(),function(response){
		json = eval('('+response+')');
		crear_dialog("Info",json.msg)
		if(json.result==true){
			limpiar_form();
		}
	});
}

function eliminar(id){
	$.get("personal/personal_data.php",{accion:"eliminar",idpersonal:id},function(response){
		json = eval('('+response+')');
		crear_dialog("Info",json.msg,"","limpiar_form();");
	});
}

function mostrar_filtros(){
	display = $("#filtros").css("display");
	if(display=="none"){
		$("#filtros").css("display","inline");
	}else{
		$("#filtros").css("display","none");
	}
}

function consultar(){
	filnombre = $("#filnombre").val();
	$.get("personal/personal_data.php",{accion:"consultar",filnombre:filnombre},function(response){
		$("#divDatos").html(response);
	});
}

function modPersonal(idpersonal,nombre,cedula,telefono,sexo,idtipopersonal,especialidad,permisosanitario,tipopersonaltxt){
	$("#idpersonal").val(idpersonal);
	$("#nombre").val(nombre);
	$("#cedula").val(cedula);
	$("#telefono").val(telefono);
	$("#sexo").val(sexo);
	if(sexo == 1){
		sexotxt = "Masculino";
	}else{
		sexotxt = "Femenino";
	}
	$("[data-id='sexo']").children()[0].innerHTML = sexotxt;
	$("[data-id='idtipopersonal']").children()[0].innerHTML = tipopersonaltxt;
	$("#idtipopersonal").val(idtipopersonal);
	$("#especialidad").val(especialidad);
	$("#permisosanitario").val(permisosanitario);
	$("#accion").val("modificar");
}

function limpiar_form(){
	$("#idpersonal").val("");
	$("#nombre").val("");
	$("#cedula").val("");
	$("#permisosanitario").val("");
	$("#telefono").val("");
	$("#sexo").val("");
	$("#idtipopersonal").val("");
	$("#especialidad").val("");
	$(".filter-option").empty();
	$("#accion").val("");
	consultar()
}

function limpiar_filtros(){
	$("#filnombre").val("");
	consultar();
}

consultar();
</script>