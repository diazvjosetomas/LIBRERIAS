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
input_text("$label","$id","$ancho",$tipo,"$onclick","$onblur","$attr","$type","$ancholabel");
input_textarea("$label","$id","$ancho","$onclick","$onblur","$attr","$height");
input_numero("$label","$id","$ancho","$onclick","$onblur","$attr");
input_monto("$label","$id","$ancho","$onclick","$onblur","$attr");
input_fecha("$label","$id","$ancho","$fecha","$onclick","$onblur","$attr");
select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options","$selected","$class","$ancholabel");
input_check("$label","$id","$ancho","$value","$onclick","$onchange","$onblur","$attr","$class")

*/
?>
<div class="container" style="padding-top:0px;">		
		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">			
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">
				<i class="fa fa-building-o"></i> &nbsp;AÑADIR CONDOMINIO</div>
			<div class="panel-body">

				
				<?=input_text("Ingresa Nombre de Condominio","n_condominio","3",$tipo,"","$onblur","$attr","$type","$ancholabel");?>
				<div class="div_add_torre"></div>	
				<div class="functionAddTorreInicial">
				<?=label_class("Añadir Torre","add_1","4","<a href='#' id='add_torre_1' onclick='addTorre(1)'><i class='fa fa-plus-square'></i></a>");?>
				</div>
				<div class="functionAddTorreSecundaria"></div>
				<div class="form-group" style="text-align:center;margin-top:15px;">
					<div class="divButton">
				    <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>
				    <button type="button" class="btn btn-default" onclick="">Limpiar</button>
					</div>
				</div>
			</div>
		</div>
</div>
<div id="divDatos" style="margin:0 auto;"></div>

<script type="text/javascript">
$(document).ready(function(){
	
})

function addTorre(idTorre){

	$('.div_add_torre').append("<div class='form-group'><label class='col-sm-4 control-label'>Añade Nombre de Torre ("+idTorre+")</label><div class='col-sm-3'><input class='form-control' type='texto' id='addTorre_"+idTorre+"' name='addTorre_"+idTorre+"' onclick='' onblur=''></div></div>");
	$('.div_add_torre').fadeIn(2500);	
	$('.functionAddTorreInicial').hide();
	$('.functionAddTorreSecundaria').html("<div class='form-group'><label class='col-sm-4 control-label'>Añadir Torre</label><div class='col-sm-3'><label id='id' class='control-label'><a href='#' id='add_torre_1' onclick='addTorre("+(idTorre + 1)+")'><i class='fa fa-plus-square'></i></a></div></div>");
	$('.divButton').html("");
	$('.divButton').append("<button type='button' class='btn btn-success' onclick='guardar("+idTorre+")'>Guardar</button><button type='button' class='btn btn-default' onclick=''>Limpiar</button>");

}


function guardar(idTorre){

	var i;
	var n_condominio = $("#n_condominio").val();
	var nombresTorres = [];
	if($("#n_condominio").val()==""){
		crear_dialog("Error","Debe llenar el campo nombre.","nombre");

		return false;
	}
	for (i = 1; i <= idTorre; i++) {
		
		if ($('#addTorre_'+i).val()=="") {
			crear_dialog("Error","Debe establecer un nombre para la Torre ("+i+")","");
			return false;
		}

		nombresTorres[i] = $('#addTorre_'+i).val();
	}

	$.get("mantenimiento/registro_condominio.php",{accion:"guardar",idTorre:idTorre,n_condominio:n_condominio,nombresTorres:nombresTorres},function(response){
		json = eval('('+response+')');
		crear_dialog("Info",json.msg,"","");
	});
}


</script>