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

input_hidden("asegurado","$value");
?>
<div class="container" style="padding-top:0px;">		
		<div id="panel-contenido" class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;display:none;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Admisi&oacute;n</div>
			<div class="panel-body">

			<div class="form-group">
				<label class='col-xs-2 control-label'><i class="fa fa-check-circle"></i><em>Datos de la admisi&oacute;n</em></label>
				<div>
					<hr style="width:70%;float:left;">
				</div>
			</div>
			<?
				input_hidden("cedula","$value");
				label("<b>N&ordm; C&eacute;dula:</b>","lab_ced","$ancho","$contenido");
				label("<b>Nombres:</b>","lab_nom","$ancho","$contenido");
				label("<b>Apellidos:</b>","lab_ape","$ancho","$contenido");
				input_fecha("<b>Fecha de ingreso:</b>","fecha_ingreso","$ancho","$fecha","$onclick","$onblur","disabled");
				label("<b>Hora ingreso:</b>","hora_ingreso","$ancho","".date('H:i a')."");
				input_textarea("<b>Observaciones:</b>","obs","5","$onclick","$onblur","$attr","80px");
			?>
			<div class="form-group">
				<label class='col-xs-2 control-label'><i class="fa fa-check-circle"></i><em>Datos del seguro</em></label>
				<div>
					<hr style="width:70%;float:left;">
				</div>
			</div>
			<?
				label("<b>Seguro:</b>","seguro","$ancho","");
				input_text("<b>Clave seguro:</b>","clave","$ancho","$tipo","$onclick","$onblur","$attr","$type");
				input_monto("<b>Monto cubierto:</b>","monto_cubierto","$ancho","$onclick","$onblur","$attr");
			?>

				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="ingresarPaciente()">Ingresar paciente</button>
				    <button type="button" class="btn btn-danger" onclick="cerrar()">Cancelar</button>
				</div>
			</div>
		</div>
</div>
<div id="divDatos" style="margin:0 auto;"></div>

<!--Modal de buscar paciente-->
<div id="modal-paciente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4>Paciente</h4>
      </div>
      <div class="modal-body">
        <form role="form">
          <div class="form-group">
            <label class="col-sm-4 control-label"><b>N&ordm; C&eacute;dula:</b></label>
            <div class="col-sm-5">
            	<div class="input-group">
            		<span class="input-group-addon"><i class="fa fa-user"></i></span>
            		<input type="text" class="form-control" id="filtro_paciente" onkeyup='this.value=ValidaNumero(event,this); return busca_paciente_ent(event);' onkeypress='return soloNumeros();'>
            	</div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="buscar_paciente()">Buscar</button>
      </div>
    </div>
  </div>
</div>

<!--Modal para registrar al paciente-->
<div id="modal-registro-paciente" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4>Registro de nuevo paciente</h4>
      </div>
      <div class="modal-body">
        <form role="form">
          <div class="form-group">
            <label class="col-sm-4 control-label"><b>N&ordm; C&eacute;dula:</b></label>
            <div class="col-sm-4">
            	<input type="text" class="form-control" id="ced_paciente_reg" readonly="readonly" onkeypress="return soloNumeros();">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label"><b>Nombres:</b></label>
            <div class="col-sm-6">
            	<input type="text" class="form-control" id="nom_paciente_reg" onblur="mayusculas(nom_paciente_reg)">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label"><b>Apellidos:</b></label>
            <div class="col-sm-6">
            	<input type="text" class="form-control" id="ape_paciente_reg" onblur="mayusculas(ape_paciente_reg)">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label"><b>Direcci&oacute;n:</b></label>
            <div class="col-sm-6">
            	<textarea class="form-control" id="dir_paciente_reg"></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label"><b>Tel&eacute;fono:</b></label>
            <div class="col-sm-4">
            	<input type="text" maxlength="12" class="form-control" id="tel_paciente_reg" onkeyup='mascara(this,"-",patron4,true);' onkeypress="return soloNumeros();">
            </div>
          </div>
          <?
          	input_check("<b>Asegurado:</b>","asegurado","$ancho","0","if(this.checked){ this.value = 1; muestraSeguro(); }else{ this.value = 0; cierraSeguro(); }","$onchange","$onblur","$attr","$class");
          ?>
          <div id="div_seguro" class="form-group" style="display:none;">
            <label class="col-sm-4 control-label"><b>Seguro:</b></label>
            <div class="col-sm-8">
            	<select class='selectpicker bus_seg' id='bus_seg' name='bus_seg' data-live-search='true' onchange=''>
            	<option value="">Buscar...</option>
            	<?
            		$sql=mysqli_query($enlace,"SELECT DISTINCT * 
            								   FROM seguros_medicos sm
            								   WHERE sm.estatus=1
            								   ORDER BY sm.nombre_seguro ASC");
            		while($rs=mysqli_fetch_assoc($sql))
            		{
            			echo "<option value='".$rs["id_seguro_m"]."'>".utf8_encode($rs["nombre_seguro"])."</option>";
            		}
            	?>
<!--             		<span class="input-group-addon"><i class="fa fa-user"></i></span>
            		<input type="text" class="form-control" id="filtro_paciente" onkeyup='this.value=ValidaNumero(event,this); return busca_paciente_ent(event);' onkeypress='return soloNumeros();'>
 -->
 				</select>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="cerrar()">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="guardar_paciente()">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
relojillo("hora_ingreso");

$('#modal-paciente').modal('show');
$("#filtro_paciente").focus();

function busca_paciente_ent(e)
{
	if(e.keyCode==13)
	{
		buscar_paciente();
	}
}

function buscar_paciente()
{
	if($("#filtro_paciente").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Ingrese c&eacute;dula del paciente","filtro_paciente");
		return false;
	}

	var filtro_paciente	=$("#filtro_paciente").val();
	var accion 			="busca_paciente";

	$.post("admision/admision_paciente_datos.php",
	{
		"filtro_paciente":filtro_paciente,
		"accion":accion
	},
	function(resp)
	{
		var json = eval("(" + resp + ")");
		if(json.result==true)
		{
			$('#modal-paciente').modal('hide');
			$("#panel-contenido").css("display","");
			$("#lab_ced").html(number_format(json.cedula,0,",","."));
			$("#lab_nom").html(json.nombres);
			$("#lab_ape").html(json.apellidos);	
			$("#cedula").val(json.cedula);
			
			if(json.asegurado!=undefined && json.asegurado==1)
			{
				$("#asegurado").val("1");
				$("#seguro").html(json.seguro);
			}
		}
		else
		{
			$('#modal-paciente').modal('hide');
			$('#modal-registro-paciente').modal('show');
			$("#ced_paciente_reg").val(filtro_paciente);
			$('#ced_paciente_reg').focus();
		}
	});
}

function guardar_paciente()
{
	if($("#ced_paciente_reg").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Ingrese c&eacute;dula del paciente","ced_paciente_reg");
		return false;
	}
	if($("#nom_paciente_reg").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Ingrese los nombres del paciente","nom_paciente_reg");
		return false;
	}
	if($("#ape_paciente_reg").val()=="")
	{
		crear_dialog("Informaci&oacute;n","Ingrese los apellidos del paciente","ape_paciente_reg");
		return false;
	}

	var accion 			 ="guardarPaciente";
	var ced_paciente_reg =$("#ced_paciente_reg").val();
	var nom_paciente_reg =$("#nom_paciente_reg").val();
	var ape_paciente_reg =$("#ape_paciente_reg").val();
	var dir_paciente_reg =$("#dir_paciente_reg").val();
	var tel_paciente_reg =$("#tel_paciente_reg").val();
	var seguro 			 =$("#bus_seg").val();
	var asegurado 		 =$("#asegurado").val();

	$.post("admision/admision_paciente_datos.php",{
		"accion":accion,
		"ced_paciente_reg":ced_paciente_reg,
		"nom_paciente_reg":nom_paciente_reg,
		"ape_paciente_reg":ape_paciente_reg,
		"dir_paciente_reg":dir_paciente_reg,
		"tel_paciente_reg":tel_paciente_reg,
		"asegurado":asegurado,
		"seguro":seguro
		},
		function(resp)
		{
		var json = eval("(" + resp + ")");

		if(json.result==true)
		{
			$("#ced_paciente_reg").val("");
			$('#modal-registro-paciente').modal('hide');
			$("#panel-contenido").css("display","");
			$("#lab_ced").html(number_format(json.cedula,0,",","."));
			$("#lab_nom").html(json.nombres);
			$("#lab_ape").html(json.apellidos);
			$("#cedula").val(json.cedula);
		}
		else
		{
			crear_modal("Información",json.mensaje,"error","","","");
		}
	});
}

function muestraSeguro()
{
	$("#div_seguro").show("blind","fast");
}

function cierraSeguro()
{
	$("#div_seguro").hide("blind","fast");
	$(".bus_seg").selectpicker("val","");
}

function ingresarPaciente()
{
	if($("#asegurado").val()==1)
	{
		if($("#clave").val()=="")
		{
			crear_modal("Información","Ingrese la clave del seguro","warning","clave","","");
			return false;
		}

		if($("#monto_cubierto").val()=="")
		{
			crear_modal("Información","Ingrese el monto cubierto por el seguro","warning","monto_cubierto","","");
			return false;
		}
	}

	var cedula 		  =$("#cedula").val();
	var fecha_ingreso =$("#fecha_ingreso").val();
	var hora_ingreso  =$("#hora_ingreso").html();
	var obs 		  =$("#obs").val();
	var clave 		  =$("#clave").val();
	var monto_cubierto=$("#monto_cubierto").val();

	capaBloqueo();
	$.post("admision/admision_paciente_datos.php",
	{
		"accion":"ingresarPaciente",
		"cedula":cedula,
		"fecha_ingreso":fecha_ingreso,
		"hora_ingreso":hora_ingreso,
		"obs":obs,
		"clave":clave,
		"monto_cubierto":monto_cubierto
	},
	function(resp)
	{
		var json = eval("(" + resp + ")");
		quitarCapa();

		if(json.result==true)
		{
			crear_modal("Información",json.mensaje,"success","","$('#cedula').val(''); window.location.reload();","");
		}	
		else
		{
			crear_modal("Información",json.mensaje,"error","","","");
			return false;
		}		
	});

}

function cerrar()
{
	crear_modal("Información","Los cambios no guardados se perderán","warning","","","window.location.reload()");
	return false;	
}

window.onbeforeunload = function(event){
	if($("#cedula").val()!="" || $("#ced_paciente_reg").val()!="")
	{
    	event.returnValue = "Los cambios no guardados se perder\u00e1n.";
    }
};
</script>