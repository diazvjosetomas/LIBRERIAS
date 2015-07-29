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
input_textarea("$label","$id","$ancho","$onclick","$onblur","$attr");
input_numero("$label","$id","$ancho","$onclick","$onblur","$attr");
input_monto("$label","$id","$ancho","$onclick","$onblur","$attr");
input_fecha("$label","$id","$ancho",$fecha,"$onclick","$onblur","$attr");
select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options","$selected","$class");
input_check("$label","$id","$ancho","$value","$onclick","$onchange","$onblur","$attr","$class")

*/
?>
<div class="container" style="padding-top:0px;">		
		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Usuarios</div>
			<div class="panel-body">
			<?
				input_hidden("iduser","$value");
				input_text("<b>Nombre:</b>","nusuario","$ancho","$tipo","$onclick","$onblur","$attr","$type");
				select("<b>Tipo de usuario:</b>","tipo_user","$ancho","$onchange","tipo_usuarios","tipo_user<>0","tipo_user","nombre","$onclick","$onblur","$attr","$options","$selected","$class");
				input_text("<b>Usuario:</b>","usuario","$ancho","$tipo","$onclick","$onblur","$attr","$type");
				input_text("<b>Contrase&ntilde;a:</b>","pass","$ancho","$tipo","$onclick","$onblur","$attr","password");
				input_text("<b>Repita contrase&ntilde;a:</b>","pass_rep","$ancho","$tipo","$onclick","$onblur","$attr","password");
				input_check("<b>Requiere almac&eacute;n:</b>","inv","$ancho","","if(this.checked){ $(\"#div_almacen\").show(\"blind\",\"fast\"); $(\".almacen\").selectpicker(\"val\",0); }else{ $(\"#div_almacen\").hide(\"blind\",\"fast\"); $(\".almacen\").selectpicker(\"val\",0); }","$onchange","$onblur","$attr","$class")
			?>
			<div id="div_almacen" class="form-group" style="display:none;">
			<label class="col-sm-4 control-label"><b>Almac&eacute;n: </b></label>
				<div class="col-sm-8">
		            <select class="selectpicker almacen" id="id_almacen" name="id_almacen" data-live-search="true">
		                  <option value=""></option>
		                  <?php
		                  include("funcionesphp/conex.php");
		                  $sql = mysqli_query($enlace,"SELECT * FROM almacen WHERE estatus=1") or die("Error: ".mysqli_error($enlace));
		                  while($rs=mysqli_fetch_assoc($sql)) 
		                  {
		                  	?><option value="<?= $rs["idalmacen"] ?>"><?=utf8_encode($rs["nombre"]);?></option><?
		                  }
		                  ?>
		            </select>						
				</div>
			</div>
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
    verUsuarios();
}, 0);

function guardar()
{
	if($("#nusuario").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Introduzca el nombre del usuario.","nusuario");
		return false;
	}
	if($("#tipo_user").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Seleccione el tipo de usuario.","tipo_user");
		return false;
	}
	if($("#usuario").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Introduzca el usuario.","usuario");
		return false;
	}
	if($("#pass").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Introduzca la contrase&ntilde;a.","pass");
		return false;
	}
	if($("#pass_rep").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Repita la contrase&ntilde;a.","pass_rep");
		return false;
	}

	if($("#pass").val()!=$("#pass_rep").val())
	{
		crear_dialog("Informaci&oacute;n","Las contrase&ntilde;as no coinciden.","");
		return false;		
	}

	if($("#iduser").val()!="")
		var accion="modificarusuario";
	else
		var accion="guardarUsuario";

		var nusuario 	=$("#nusuario").val();
		var tipo_user 	=$("#tipo_user").val();
		var usuario 	=$("#usuario").val();
		var pass 		=$("#pass").val();
		var iduser 		=$("#iduser").val();
		var id_almacen  =$("#id_almacen").val();

		$.blockUI({ css: { 
		    border: 'none', 
		    padding: '15px', 
		    backgroundColor: '#000', 
		    '-webkit-border-radius': '10px', 
		    '-moz-border-radius': '10px', 
		    opacity: .5, 
		    color: '#fff' 
		} });

		$.post("mantenimiento/usuarios_datos.php",
		{
			"accion":accion,
			"nusuario":nusuario,
			"tipo_user":tipo_user,
			"usuario":usuario,
			"pass":pass,
			"iduser":iduser,
			"id_almacen":id_almacen
		},
		function(resp)
		{
			setTimeout($.unblockUI);
			var json = eval("("+resp+")");
			if(json.result==true)
				crear_dialog("Informaci&oacute;n",json.mensaje,"","verUsuarios(); limpiar();");
			else
				crear_dialog("Informaci&oacute;n",json.mensaje,"","");		
		});
}

function modificar(id_user,nombre_usuario,user,tipo_user,tipo_user_text,id_almacen)
{
	$("#iduser").val(id_user);
	$("#nusuario").val(nombre_usuario);
	$("#usuario").val(user);
	$("#tipo_user").val(tipo_user);
	$(".filter-option").html(tipo_user_text);
	$("#pass").val("********");
	$("#pass_rep").val("********");
	if(id_almacen!="" && id_almacen!=undefined)
	{
		$("#inv").prop("checked",true);
		$("#div_almacen").show("blind","fast");
		$(".almacen").selectpicker("val",id_almacen);
	}
}

function eliminar(id_user)
{
	$.post("mantenimiento/usuarios_datos.php",
	{
		"accion":"eliminarUsuario",
		"iduser":id_user
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verUsuarios(); limpiar();");
		else
			crear_dialog("Alerta",json.mensaje,"","reload");
	});
}

function verUsuarios()
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

	$("#divDatos").load("mantenimiento/usuarios_datos.php",
	{
		"accion":"verUsuarios"
	}
	,
	function()
	{
		setTimeout($.unblockUI);
	});	
}

function limpiar()
{
	$("form")[0].reset();
	$("#iduser").val("");
	$("#tipo_user").val("");
	$(".filter-option").empty();
	$("#inv").prop("checked",false);
	$("#div_almacen").hide("blind","fast");
	$(".almacen").selectpicker("val",0);
}
</script>