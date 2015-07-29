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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Registro de Proveedor</div>
			<div class="panel-body">
			<?=input_hidden("idproveedor","")?>
			<?=input_text("Nombre","nombre","","","","","")?>
			<?=input_textarea("Descripci&oacute;n","descripcion","","$onclick","$onblur","$attr")?>
			<?=input_text("Nombre Contacto","contacto","","","","","")?>
			<?=input_textarea("Direcci&oacute;n","direccion","","$onclick","$onblur","$attr")?>
			<?=input_text("R.I.F","rif","","","","","")?>
			<?=select("Tipo Persona","tipopersona","$ancho","$onchange","","","","","$onclick","$onblur","$attr","1;Persona Natural Residente,2;Persona Juridica Domiciliada,3;Persona natural no residente,4;Persona Juridica no domiciliada");?>
			<?=input_check("Estatus","estatus","","","","if(this.checked){ this.value = 1; }else{ this.value = 0; }","")?>
			<?=select("Tipo","tipo","$ancho","$onchange","","","","","$onclick","$onblur","$attr","1;Nacional,2;Internacional");?>
			<?=input_text("Telefono","telefono","","2","","","onkeyup='mascara(this,\"-\",patron4,true);'")?>
			<?=input_text("Telefono2","telefono2","","2","","","onkeyup='mascara(this,\"-\",patron4,true);'")?>
			<?=input_text("Email","email","","","","","")?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar_form()">Limpiar</button>
				</div>
			</div>
		</div>
</div>
<div class="container" style="margin-top:50px;">
	<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
		<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Proveedores Registrados</div>
			<div class="panel-body">
				<div style="margin: 15px;"><button type="button" class="btn btn-success" onclick="mostrar_filtros()"><i class="fa fa-search"></i></button></div>
				<div id="filtros" style="display: none;">
					<div style="float: left; width:50%;"><?=input_text("Nombre","filnombre","5","","","","")?></div>
					<div style="float: left; width:50%;"><?=input_check("Estatus","filestatus","","","","if(this.checked){ this.value = 1; }else{ this.value = 0; }","","checked")?></div>
					<div style="float: left; width:50%;"><?=select("Tipo Persona","filtipopersona","$ancho","$onchange","","","","","$onclick","$onblur","$attr","1;Persona Natural Residente,2;Persona Juridica Domiciliada,3;Persona natural no residente,4;Persona Juridica no domiciliada");?></div>
					<div class="form-group" style="text-align:center;margin-top:15px;">
				   		<button type="button" class="btn btn-success" onclick="consultar()">Buscar</button>
				   		<button type="button" class="btn btn-default" onclick="limpiar_filtros()">Limpiar</button>
					</div>
				</div>
				<div id="divDatos" style="margin:0 auto;"></div>
			</div>
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

	$("#divDatos").load("mantenimiento/proveedor_data.php",{accion:accion,pg:pg},function()
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
	if($("#rif").val()==""){
		crear_dialog("Error","Debe llenar el campo rif.","rif");
		return false;
	}
	if($("#telefono").val()==""){
		crear_dialog("Error","Debe llenar el campo telefono.","telefono");
		return false;
	}
	if($("#direccion").val()==""){
		crear_dialog("Error","Debe llenar el campo direccion.","direccion");
		return false;
	}
	if($("#accion").val()==""){
		$("#accion").val("guardar");
	}
	$.get("mantenimiento/proveedor_data.php",$("#form1").serialize(),function(response){
		json = eval('('+response+')');
		crear_dialog("Info",json.msg)
		if(json.result==true){
			limpiar_form();
		}
	});
}

function eliminar(id){
	if(confirm("Esta seguro que desea eliminar?")){
		$.get("mantenimiento/proveedor_data.php",{accion:"eliminar",idproveedor:id},function(response){
			json = eval('('+response+')');
			crear_dialog("Info",json.msg)
			limpiar_form();
		});
	}
}

function consultar(){
	filnombre = $("#filnombre").val();
	filestatus = $("#filestatus").val();
	filtipopersona = $("#filtipopersona").val();
	$.get("mantenimiento/proveedor_data.php",{accion:"consultar",filnombre:filnombre,filestatus:filestatus,filtipopersona:filtipopersona},function(response){
		$("#divDatos").html(response);
	});
}

function modProveedor(idproveedor,nombre,descripcion,contacto,direccion,rif,tipopersona,estatus,tipo,telefono,telefono2,email){
	$("#idproveedor").val(idproveedor);
	$("#nombre").val(nombre);
	$("#descripcion").val(descripcion);
	if(estatus==1){
		$("#estatus").prop( "checked", true );
	}else{
		$("#estatus").prop( "checked", false );
	}
	$("#contacto").val(contacto);
	$("#direccion").val(direccion);
	$("#rif").val(rif);
	$("#telefono").val(telefono);
	$("#telefono2").val(telefono2);
	$("#email").val(email);
	$("#tipo").val(tipo);
	$("#tipopersona").val(tipopersona);
	switch(tipopersona){
		case "1":
			tipopersonatxt = "Persona Natural Residente";
		break;
		case "2":
			tipopersonatxt = "Persona Juridica Domiciliada";
		break;
		case "3":
			tipopersonatxt = "Persona Natural no Residente";
		break;
		case "4":
			tipopersonatxt = "Persona Natural no Domiciliada";
		break;
	}
	$("[data-id='tipopersona']").children()[0].innerHTML = tipopersonatxt;
	switch(tipo){
		case "1":
			tipotxt = "Nacional";
		break;
		case "2":
			tipotxt = "Internacional";
		break;
	}
	$("[data-id='tipo']").children()[0].innerHTML = tipotxt;
	$("#accion").val("modificar");
}

function limpiar_form(){
	$("#idproveedor").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#contacto").val("");
	$("#direccion").val("");
	$("#rif").val("");
	$("#telefono").val("");
	$("#telefono2").val("");
	$("#email").val("");
	$("#estatus").prop( "checked", false );
	$(".filter-option").empty();
	$("#accion").val("");
	consultar()
}

function limpiar_filtros(){
	$("#filnombre").val("");
	$("#filtipopersona").val("");
	$("#filestatus").prop( "checked", true );
	consultar()
}

consultar();
</script>