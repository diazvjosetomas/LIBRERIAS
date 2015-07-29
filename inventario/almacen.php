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
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Registro de Almac&eacute;n</div>
			<div class="panel-body">
			<?=input_hidden("idalmacen","")?>
			<?=input_text("<b>Nombre almac&eacute;n:</b>","nombre","","","","mayusculas(this)","")?>
			<?=input_textarea("<b>Descripci&oacute;n</b>","descripcion","","$onclick","$onblur","$attr")?>
			<?=input_check("<b>Estatus:</b>","estatus","","","","if(this.checked){ this.value = 1; }else{ this.value = 0; }","onkeyup='mascara(this,\"-\",patron4,true);'")?>
			<?=select("<b>Responsable:</b>","responsable","$ancho","$onchange","users","tipo_user=1","id_user","user","$onclick","$onblur","$attr","");?>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar_form()">Limpiar</button>
				</div>
			</div>
		</div>
</div>
<div class="container" style="margin-top:50px;">
	<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
		<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Almacenes Registrados</div>
		<div class="panel-body">
			<div style="margin: 15px;"><button type="button" class="btn btn-success" onclick="mostrar_filtros()"><i class="fa fa-search"></i></button></div>
			<div id="filtros" style="display: none;">
				<div style="float: left; width:50%;"><?=input_text("Nombre","filnombre","5","","","","")?></div>
				<div style="float: left; width:50%;"><?=input_check("Estatus","filestatus","","","","if(this.checked){ this.value = 1; }else{ this.value = 0; }","","")?></div>
				<div style="float: left; width:50%;"><?=select("Responsable:","filresponsable","$ancho","$onchange","users","tipo_user=1","id_user","user","$onclick","$onblur","$attr","");?></div>
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

	$("#divDatos").load("inventario/almacen_data.php",{accion:accion,pg:pg},function()
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
	if($("#accion").val()==""){
		$("#accion").val("guardar");
	}
	$.get("inventario/almacen_data.php",$("#form1").serialize(),function(response){
		json = eval('('+response+')');
		crear_modal("Información",json.msg,"info","","","");
		if(json.result==true){
			limpiar_form();
		}
	});
}

function eliminar(id){
	$.get("inventario/almacen_data.php",{accion:"eliminar",idalmacen:id},function(response){
		json = eval('('+response+')');
		crear_modal("Información",json.msg,"info","","limpiar_form()","");
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
	filestatus = $("#filestatus").val();
	filresponsable = $("#filresponsable").val();
	$.get("inventario/almacen_data.php",{accion:"consultar",filnombre:filnombre,filestatus:filestatus,filresponsable:filresponsable},function(response){
		$("#divDatos").html(response);
	});
}

function modAlmacen(idalmacen,nombre,descripcion,estatus,iduser,nombreresponsable){
	$("#idalmacen").val(idalmacen);
	$("#nombre").val(nombre);
	$("#descripcion").val(descripcion);
	if(estatus==1){
		$("#estatus").prop( "checked", true );
	}else{
		$("#estatus").prop( "checked", false );
	}
	$("#responsable").val(iduser);
	$(".filter-option").html(nombreresponsable);
	$("#accion").val("modificar");

}

function limpiar_form(){
	$("#idalmacen").val("");
	$("#nombre").val("");
	$("#descripcion").val("");
	$("#estatus").prop( "checked", false );
	$(".filter-option").empty();
	$("#accion").val("");
	consultar()
}

function limpiar_filtros(){
	$("#filnombre").val("");
	$("#filresponsable").val("");
	$("#filestatus").prop( "checked", true );
	$(".filter-option").empty();
	$("#filestatus").val(1)
	consultar()
}

consultar();
</script>