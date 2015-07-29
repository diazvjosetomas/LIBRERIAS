<?

if(file_exists("../funcionesphp/seguridad.php"))

	include("../funcionesphp/seguridad.php");

else

	include("funcionesphp/seguridad.php");

antiChismoso();
$Id_Condominio = $_SESSION['Id_Condominio'];
$Id_Torre      = $_SESSION['Id_Torre'];
$N_Condominio  = $_SESSION['Nombre_Condominio'];
$N_Torre       = $_SESSION['Nombre_Torre'];




?>

<div class="container" style="padding-top:0px;">		

		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">

			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Registro de Inquilinos <div id="registroTorres"><strong style="color:green;">
				<i class="fa fa-check-square-o"></i></strong>&nbsp;<u><i>.::<?=$N_Condominio ?></i></u> / <u><i> Torre<?=$N_Torre?>::.</i></u></div></div>

			<div class="panel-body">

			<?=input_hidden("idpaciente","")?>

			

			<div class="form-group" style="margin: 1px;">

				<i class="fa fa-check-circle"></i> <em>Datos del Inquilino</em>

				<hr style="margin-top:11px;width:75%;float:right;"></hr>

			</div>



			<div class="row marketing">

				<div class="col-lg-6">

					<?=input_text("<b>C&eacute;dula:</b>","cedula","5","2","","","onkeyup='this.value=ValidaNumero(event,this);'")?>

					<?=input_text("<b>Nombres:</b>","nombres","5","","","mayusculas(this)","")?>

					<?=input_fecha("<b>Fecha de nacimiento:</b>","fec_nac","","00/00/0000","quitaCero(this)","ponCero(this)","onfocus='quitaCero(this)'");?>

					<?=input_textarea("<b>Direcci&oacute;n:</b>","direccion","6","$onclick","$onblur","$attr")?>


					<!-- select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options","$selected","$class","$ancholabel"); -->
					<!--<?=select("<b>Direcci&oacute;n:</b>","torre","8","","torres_condominio","id_condominio = ".$Id_Condominio,$Id_Condominio,"nombre_torre","","","","","","","");?> -->
					


					<? //=input_check("<b>Asegurado:</b>","asegurado","5","0","if(this.checked){ this.value = 1; muestraSeguro(); }else{ this.value = 0; cierraSeguro(); }","$onchange","$onblur","$attr","$class");?>



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

	 				</select>

	            </div>

	          </div>



				</div>

				<div class="col-lg-6">

					<label style="height: 44px;">&nbsp;</label><!-- Salto vacio -->

					<?=input_text("<b>Apellidos:</b>","apellidos","5","","","mayusculas(this)","")?>

					<?=input_text("<b>Tel&eacute;fono:</b>","telefono","5","2","","","onkeyup='mascara(this,\"-\",patron4,true);' maxlength='12' ")?>

					<label style="height: 66px;">&nbsp;</label><!-- Salto vacio para textarea -->

				</div>

			</div>


<? /*


			<div class="form-group" style="margin: 1px;">

				<i class="fa fa-check-circle"></i> <em>Pacientes asociados</em>

				<hr style="margin-top:11px;width:75%;float:right;"></hr>

			</div>



			<div class="form-group" style="margin:1px;text-align:right;">

				<label style="cursor:pointer;" data-toggle="tooltip" data-placement="left" title="Agregar asociado" onclick="$('#modal-registro-asociado').modal('show');"><i class="fa fa-plus" style="font-size:18px;color:green;"></i>&nbsp;<b>Agregar asociado</b></label>

			</div>

					<!-        TABLA ASOCIADOS         ->

					<div id="div_asociados"></div>



<!-- 					    <table id="tabla-asociados" class="table table-striped table-bordered" style="margin-top:10px;width:100%;">

						    <tr>

						    	<td width="30%" align="center" style="background-color:#C7C7C7;"><b>Nombre</b></td>

						    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Fecha nacimiento</b></td>

						    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Edad</b></td>

						    	<td width="8%"  align="center" style="background-color:#C7C7C7;">&nbsp;</td>

						    </tr>

					    </table> -->

		        	<!-                                ->

*/?>

				<div class="form-group" style="text-align:center;margin-top:15px;">

				    <button type="button" class="btn btn-success" onclick="guardar()">Guardar</button>

				    <button type="button" class="btn btn-default" onclick="limpiar_form()">Limpiar</button>

				</div>

			</div>

		</div>

</div>



<!--Modal para registrar al asociado-->

<div id="modal-registro-asociado" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-lg">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

        <h4>Registro de asociados</h4>

      </div>

      <div class="modal-body">

        <form role="form">

          <div class="form-group">

            <label class="col-sm-4 control-label"><b>Nombres:</b></label>

            <div class="col-sm-4">

            	<input type="text" class="form-control" id="aso_nom_paciente" onblur="mayusculas(this)">

            </div>

          </div>

          <div class="form-group">

            <label class="col-sm-4 control-label"><b>Apellidos:</b></label>

            <div class="col-sm-4">

            	<input type="text" class="form-control" id="aso_ape_paciente" onblur="mayusculas(this)">

            </div>

          </div>

          <?=input_fecha("<b>Fecha de nacimiento:</b>","aso_fec_nac","","00/00/0000","quitaCero(this)","ponCero(this)","onfocus='quitaCero(this)'");?>

        </form>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="limpiar_asoc()">Cerrar</button>

        <button type="button" class="btn btn-primary" onclick="agregar_asociado()">Agregar</button>

      </div>

    </div>

  </div>

</div>



<div class="container" style="margin-top:50px;">

	<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">

		<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Inquilinos Registrados</div>

		<div class="panel-body" style="padding:0px;">

			<div style="margin: 15px;"><button type="button" class="btn btn-success" onclick="mostrar_filtros()"><i class="fa fa-search"></i></button></div>

			<div id="filtros" style="display: none;">

				<div style="float: left; width:50%;"><?=input_text("Cedula","filcedula","5","","","","")?></div>

				<div style="float: left; width:50%;"><?=input_text("Nombre","filnombres","5","","","","")?></div>

				<div style="float: left; width:50%;"><?=input_text("Apellidos","filapellidos","5","","","","")?></div>

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

$("#aso_fec_nac").keydown(function(tecla){ 

    if (tecla.keyCode == 13) { 

		if($("#modal-paciente").css("display")=="block")

		{

			setTimeout(function() {

				buscar_paciente();

			}, 200);

		}

    } 

});



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



	$("#divDatos").load("pacientes/pacientes_data.php",{accion:accion,pg:pg},function()

		{

			$("#pag"+num).addClass("active");

			setTimeout($.unblockUI); 

		});

}



function guardar(){

	if($("#cedula").val()==""){

		crear_modal("Alerta","Debe llenar el campo cédula","info","cedula","","");

		return false;

	}

	if($("#nombres").val()==""){

		crear_modal("Alerta","Debe llenar el campo nombre","info","nombres","","");

		return false;

	}

	if($("#apellidos").val()==""){

		crear_modal("Alerta","Debe llenar el campo apellido","info","apellidos","","");

		return false;

	}

	if($("#fec_nac").val()=="00/00/0000")

	{

		crear_modal("Alerta","Indique la fecha de nacimiento","info","fec_nac","","");

		return false;

	}



	capaBloqueo();

	if($("#accion").val()==""){

		$("#accion").val("guardar");

	}

	$.get("pacientes/pacientes_data.php",$("#form1").serialize(),function(response){

		quitarCapa();

		json = eval('('+response+')');

		crear_modal("Información",json.msg,"info","","","");

		if(json.result==true){

			limpiar_form();

		}

	});

}



function conf_eliminar(id,paciente)

{

		$(".sweet-alert").css("box-shadow","inset 0px 0px 14px 2px rgb(248, 197, 134)");

		swal({

		  title: "¿Eliminar paciente: '"+paciente+"'?",

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



function eliminar(id){

	capaBloqueo();

	$.get("pacientes/pacientes_data.php",{accion:"eliminar",idpaciente:id},function(response){

		quitarCapa();

		json = eval('('+response+')');

		crear_modal("Información",json.msg,"success","","limpiar_form();","");

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



function muestraSeguro()

{

	$("#div_seguro").show("blind","fast");

}



function cierraSeguro()

{

	$("#div_seguro").hide("blind","fast");

	$(".bus_seg").selectpicker("val","");

}



function consultar(){

	filcedula 		= $("#filcedula").val();

	filnombres 		= $("#filnombres").val()

	filapellidos 	= $("#filapellidos").val()

	$.get("pacientes/pacientes_data.php",{accion:"consultar",filcedula:filcedula,filnombres:filnombres,filapellidos:filapellidos},function(response){

		$("#divDatos").html(response);

	});

}



function modPaciente(idpaciente,cedula,nombres,apellidos,direccion,telefono,asegurado,seguro,fnac){

	$("#idpaciente").val(idpaciente);

	$("#cedula").val(cedula);

	$("#cedula").attr("readonly","readonly");

	$("#nombres").val(nombres);

	$("#apellidos").val(apellidos);

	$("#direccion").val(direccion);

	$("#telefono").val(telefono);

	$("#fec_nac").val(fnac);



	if(asegurado==1)

		$("#asegurado").prop("checked",true);

	else

		$("#asegurado").prop("checked",false);

	if(seguro!=0)

	{

		$("#div_seguro").show("blind","fast");

		$(".bus_seg").selectpicker("val",seguro);

	}

	else

	{

		$("#div_seguro").hide("blind","fast");

		$(".bus_seg").selectpicker("val","");		

	}

	$("#accion").val("modificar");



	buscaAsociados(cedula);



}



function agregar_asociado()

{

	if($("#aso_nom_paciente").val()=="")

	{

		crear_modal("Alerta","Indique el nombre del asociado","info","aso_nom_paciente","","");

		return false;

	}

	if($("#aso_ape_paciente").val()=="")

	{	

		crear_modal("Alerta","Indique el apellido del asociado","info","aso_ape_paciente","","");

		return false;		

	}

	if($("#aso_fec_nac").val()=="00/00/0000")

	{

		crear_modal("Alerta","Indique la fecha de nacimiento del asociado","info","aso_fec_nac","","");

		return false;

	}



	var nom= $("#aso_nom_paciente").val();

	var ape= $("#aso_ape_paciente").val();

	var fna= $("#aso_fec_nac").val();



		var tabla="";

			tabla+="<tr id='"+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"'>";

			tabla 	+="<td align='center'><label id='lab_"+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"' name='lab_"+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"' style='cursor:pointer;' data-toggle='tooltip' data-placement='top' title='Editar' onclick='editar_na(\""+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"\")'>"+$.trim(nom)+" "+$.trim(ape)+"</label><div id='div_"+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"' class='form-inline' style='display:none;'><input type='text' id='editn_"+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"' name='editn_"+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"' class='form-control' style='width:40%;' onblur='mayusculas(this)' value='"+$.trim(nom)+"'><input type='text' id='edita_"+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"' name='edita_"+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"' class='form-control' style='width:40%;' onblur='mayusculas(this)' value='"+$.trim(ape)+"'><i data-toggle='tooltip' data-placement='top' title='Aceptar' class='fa fa-check-square' style='color:green;cursor:pointer;font-size: 22px;padding-left: 5px;' onclick='acepta_edit(\""+nom.substring(0,2)+ape.substring(0,2)+fna.substring(0,2)+"\",\""+fna+"\")'></i></div></td>";

			tabla 	+="<td align='center'>"+fna+"</td>";

			tabla 	+="<td align='center'>"+calcular_edad(fna)+"</td>";

			tabla 	+="<td align='center' style='vertical-align: middle;'><i class='fa fa-times' style='color:red;cursor:pointer;font-size: 25px;' data-toggle='tooltip' data-placement='top' title='Eliminar asociado' onclick='eliminaTrDatos(\""+$.trim(nom.substring(0,2))+$.trim(ape.substring(0,2))+fna.substring(0,2)+"\")'></i></td>";

			tabla+="<tr>";

		$("#tabla-asociados").append(tabla);

		$('[data-toggle="tooltip"]').tooltip();



	$("#div_asociados").append("<input type='hidden' id='as_"+$.trim(nom).substring(0,2)+$.trim(ape).substring(0,2)+fna.substring(0,2)+"' name='as_"+$.trim(nom).substring(0,2)+$.trim(ape).substring(0,2)+fna.substring(0,2)+"' value='"+$.trim(nom)+"|*"+$.trim(ape)+"|*"+fna+"'>");



	$("#modal-registro-asociado").modal("hide");

	limpiar_asoc();

}



function buscaAsociados(cedula)

{

	if(cedula!=undefined)

	{

		capaBloqueo();

		$.post("pacientes/pacientes_data.php",{

			"accion":"buscaAsociados",

			"cedula":cedula

		},function(resp)

		{

			quitarCapa();

			//==> Existen asociados...

			if(resp!="" && resp!=undefined)

			{

				$("#div_asociados").html(resp);

			}

		});

	}	

}



function eliminaTrDatos(id_tr)

{

	$("#"+id_tr).remove();

	$("#as_"+id_tr).remove();

}



function limpiar_asoc()

{

	$("#aso_nom_paciente").val("");

	$("#aso_ape_paciente").val("");

	$("#aso_fec_nac").val("00/00/0000");

}



function limpiar_form(){

	$("#idpaciente").val("");

	$("#cedula").val("");

	$("#nombres").val("");

	$("#fec_nac").val("00/00/0000");

	$("#apellidos").val("");

	$("#direccion").val("");

	$("#telefono").val("");

	$("#accion").val("");

	$("#asegurado").prop("checked",false);

	$("#cedula").removeAttr("readonly");

	$("#div_seguro").hide("blind","fast");

	$(".bus_seg").selectpicker("val","");

	consultar();

	cargaTabla();

}



function editar_na(id)

{

	$("#lab_"+id).css("display","none");

	$("#div_"+id).css("display","");

}



function acepta_edit(id,fna)

{

	if(id!="" && id!=undefined)

	{

		console.log(id);

		var nom_n=$("#editn_"+id).val();

		var ape_n=$("#edita_"+id).val();



		$("#as_"+id).val($.trim(nom_n)+"|*"+$.trim(ape_n)+"|*"+fna);



		$("#lab_"+id).html($.trim(nom_n)+" "+$.trim(ape_n));



		$("#lab_"+id).css("display","");

		$("#div_"+id).css("display","none");

	}

}



function cargaTabla()

{

	var table="";

	table+='<table id="tabla-asociados" class="table table-striped table-bordered" style="margin-top:10px;width:100%;">';

	table+='    <tr>';

	table+='    	<td width="30%" align="center" style="background-color:#C7C7C7;"><b>Nombre</b></td>';

	table+='    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Fecha nacimiento</b></td>';

	table+='    	<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Edad</b></td>';

	table+='    	<td width="8%"  align="center" style="background-color:#C7C7C7;">&nbsp;</td>';

	table+='    </tr>';

	table+='</table>';



	$("#div_asociados").html(table);

}



consultar();

cargaTabla();

</script>