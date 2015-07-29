<?
if(file_exists("../funcionesphp/seguridad.php"))
	include("../funcionesphp/seguridad.php");
else
	include("funcionesphp/seguridad.php");
antiChismoso();
session_start();
?>
<input type="hidden" id="idCliente" name="idCliente" value="">
<input type="hidden" id="subtotal" name="subtotal" value="0">
<input type="hidden" id="total" name="total" value="0">
<?
input_hidden("existe","");
input_hidden("id_cotizacion","");
input_hidden("cerrar","");
?>
<div class="container" style="padding-top:0px;width:100%;">		
		<div id="div_venta" class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;display:;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Precargar cotizaciones</div>
			<div class="panel-body">
			<?
				input_text("<b>Asunto:</b>","asunto","4","$tipo","$onclick","mayusculas(this)","$attr","$type");
			?>
<!--				<div class="form-group" style="margin: 1px;">
					<i class="fa fa-check-circle"></i> <em>Datos del paciente</em>
					<hr style="margin-top:11px;width:85%;float:right;"></hr>
				</div>
 			    <div class="form-group">
		            <label class="col-sm-2 control-label"><b>N&ordm; C&eacute;dula:</b></label>
		            <label class="col-sm-1 control-label" style="text-align:left;font-weight:normal;" id="label_ced"></label>
		        </div>
		        <div class="form-group">
		            <label class="col-sm-2 control-label"><b>Nombres:</b></label>
					<label class="col-sm-2 control-label" style="text-align:left;font-weight:normal;" id="label_nom"></label>

		            <label class="col-sm-2 control-label"><b>Apellidos:</b></label>
					<label class="col-sm-2 control-label" style="text-align:left;font-weight:normal;" id="label_ape"></label>
	          	</div>
	          	<div class="form-group">
		            <label class="col-sm-2 control-label"><b>Fecha de ingreso:</b></label>
					<label class="col-sm-2 control-label" style="text-align:left;font-weight:normal;" id="label_fi"></label>

		            <label class="col-sm-2 control-label"><b>Hora de ingreso:</b></label>
					<label class="col-sm-2 control-label" style="text-align:left;font-weight:normal;" id="label_hi"></label>	          	
	          	</div> -->
					<div class="form-group" style="margin: 1px;">
						<i class="fa fa-check-circle"></i> <em>Consumos</em>
						<hr style="margin-top:11px;width:85%;float:right;"></hr>
					</div>
					<div class="form-group">
					<label class="col-sm-2 control-label"><b>Producto / Servicio: </b></label>
						<div class="col-sm-6" style="width:initial;">
		                        <select class="selectpicker" id="id_prod" name="id_prod" data-live-search="true" onchange="buscaDatosProducto(this.value)">
		                                <option value=""></option>
		                                <?php
		                                include("funcionesphp/conex.php");
		                                //===> Meto productos
		                                $sql = mysqli_query($enlace,"SELECT DISTINCT p.id_prod,p.nom_prod,p.cod_prod,p.cant_presentacion,pp.ab_presentacion
		                                							 FROM productos p
		                                							 LEFT JOIN presentacion_productos pp ON (p.idpresentacion=pp.id_presentacion)
		                                							 INNER JOIN producto_inventario pi ON (p.id_prod=pi.id_prod)
		                                							 ORDER BY p.nom_prod ASC") or die("Error: ".mysqli_error($enlace));
		                                $nProd=mysqli_num_rows($sql);
		                                if($nProd>0)
		                                {
		                                	?>
		                                		<optgroup label="Productos">
		                                	<?
		                                }
		                                while($rs=mysqli_fetch_assoc($sql)) 
		                                {
		                                	?><option tipo="P" value="<?=$rs["id_prod"]?>"><?=utf8_encode($rs["nom_prod"])." ".number_format($rs["cant_presentacion"],0,",",".")." ".$rs["ab_presentacion"]; ?> | <?=$rs["cod_prod"]?></option><?
		                                }
		                                if($nProd>0)
		                                {
		                                	?></optgroup><?
		                                }

		                                //===> Meto servicios
		                                $sqlS = mysqli_query($enlace,"SELECT * 
		                                							 FROM servicios
		                                							 ORDER BY nombre ASC") or die("Error: ".mysqli_error($enlace));
		                                $nServ=mysqli_num_rows($sqlS);
		                                if($nServ>0)
		                                {
		                                	?>
		                                		<optgroup label="Servicios">
		                                	<?
		                                }
		                                while($rs=mysqli_fetch_assoc($sqlS)) 
		                                {
		                                	?><option tipo="S" value="<?= $rs["cod_servicio"] ?>"><?=utf8_encode($rs["nombre"]);?> | <?=$rs["cod_servicio"]?></option><?
		                                }
		                                if($nServ>0)
		                                {
		                                	?></optgroup><?
		                                }
		                                ?>
		                            </select>						
						</div>
						<button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="right" data-original-title="Consultar otros productos" onclick="$('#modal-productos').modal('show')"><i class="fa fa-search"></i></button>
					</div>

					<div class="form-group">
						<label style="width:100%;">
						<label class="col-sm-2 control-label" style="background-color:;margin-right: 15px;text-align: center;width:24%;float:right;font;font-size: 20px;border-radius:6px;"><b>TOTAL BS.F: </b><label id="lab_precio" style=""></label></label>						
						<label class="col-sm-2 control-label" style="background-color:;margin-right: 15px;text-align: center;width:24%;float:right;font;font-size: 20px;border-radius:6px;"><b>SUB-TOTAL: </b><label id="lab_subtot" style=""></label></label>
						<label class="col-sm-2 control-label" style="background-color:;margin-right: 15px;text-align: right;width:24%;float:right;font;font-size: 20px;border-radius:6px;"><b>I.V.A: </b><?=$_SESSION["iva"]?>%</label>
						</label>
					</div>

					<!-        DIV PRODUCTOS         ->
		          	<div id="div_productos" name="div_productos" style="width:100%;">

		        	</div>
		        	<!-                              ->

			</div>
			<!--Footer-->
			<div class="panel-footer" style="text-align:center;">
		        <button type="button" class="btn btn-success" onclick="confirmConsumos()"><i class="fa fa-check"></i> Guardar Precarga</button>
<!-- 		        <button type="button" class="btn btn-success" onclick="cerrarCuenta()"><i class="fa fa-check"></i> Cerrar cuenta y dar de alta</button>
		        <button type="button" class="btn btn-primary" onclick="window.location.reload();"><i class="fa fa-search"></i> Consultar otro paciente</button>								
 -->			</div>
		</div>
</div>

<!--Cotizaciiones cargadas-->
			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Cotizaciones precargadas</div>
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
            <div class="col-sm-8">
            	<select class='selectpicker paciente' id='bus_ced' name='bus_ced' data-live-search='true' onchange=''>
            	<option data-icon="glyphicon glyphicon-user" value="">Buscar...</option>
            	<?
            		$sql=mysqli_query($enlace,"SELECT * FROM admision_paciente ap
            											INNER JOIN pacientes ON (ap.cedula=pacientes.cedula) 
            											WHERE ap.estatus=1
            											ORDER BY ap.id_admision DESC");
            		while($rs=mysqli_fetch_assoc($sql))
            		{
            			echo "<option idadmision='".$rs["id_admision"]."' value='".$rs["cedula"]."'>C.I: ".$rs["cedula"]." | ".utf8_encode($rs["nombres"])." ".utf8_encode($rs["apellidos"])."</option>";
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
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary" onclick="buscar_paciente()">Cargar consumos</button>
      </div>
    </div>
  </div>
</div>

<!--Modal de buscar productos-->
<div id="modal-productos" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4>Buscar productos</h4>
      </div>
      <div class="modal-body">
        <form role="form">
          <div class="form-group">
            <label class="col-sm-4 control-label"><b>Producto:</b></label>
            <div class="col-sm-8">
            	<select class='selectpicker bus_prod' id='bus_prod' name='bus_prod' data-live-search='true' onchange='buscaProd(this.value)'>
            	<option value="">Buscar...</option>
            	<?
            		$sql=mysqli_query($enlace,"SELECT DISTINCT * 
            								   FROM productos 
            								   LEFT JOIN presentacion_productos pp ON (productos.idpresentacion=pp.id_presentacion)
            								   ORDER BY productos.nom_prod ASC");
            		while($rs=mysqli_fetch_assoc($sql))
            		{
            			echo "<option value='".$rs["id_prod"]."'>".utf8_encode($rs["nom_prod"])." ".number_format($rs["cant_presentacion"],0,"",".")." ".utf8_encode($rs["ab_presentacion"])."</option>";
            		}
            	?>
<!--             		<span class="input-group-addon"><i class="fa fa-user"></i></span>
            		<input type="text" class="form-control" id="filtro_paciente" onkeyup='this.value=ValidaNumero(event,this); return busca_paciente_ent(event);' onkeypress='return soloNumeros();'>
 -->
 				</select>
            </div>
          </div>
          <div class="form-group" style="text-align:center;">
	        <button type="button" class="btn btn-default" onblur="limpiaProd()" data-dismiss="modal">Cerrar</button>
          </div>
        </form>
      </div>
      <div id="div_trae_prod" class="modal-footer" style="max-height:350px;overflow:auto;padding:0px;">

      </div>
    </div>
  </div>
</div>

<!--Modal confirmacion de compra-->
<div id="modal-compra" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background-color:red !important;">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="exampleModalLabel" style="font-size:26px;color:white;font-weight:bold;">Confirmar venta</h4>
      </div>
      <div class="modal-body">
      	<div id="divCompra"></div>
      </div>
      <div class="modal-footer">
      	<em>Para confirmar la compra presione el bot&oacute;n "aceptar"</em>&nbsp;&nbsp;&nbsp;
        <button type="button" class="btn btn-success" onclick="vender();">Aceptar</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>
<div id="ventasProd" name="ventasProd">
	
</div>
<script type="text/javascript">
setTimeout(function() {
	carga_tabla();
	verCotizaciones();
	$("#divCont").css("width","100%");
}, 0);


$('#modal-cliente').on('hidden.bs.modal',function (e){
	//Accion al cerrar el primer modal
});

function buscar_paciente()
{
	var cedula 		=$("#bus_ced").val();
	var id_admision =$("#bus_ced option:selected").attr("idadmision");

	if(cedula=="")
	{
		crear_modal("Alerta","Seleccione un paciente","info","bus_ced","","");
		return false;		
	}

	$.post("consumos/carga_consumos_datos.php",
	{
		"accion":"buscaPaciente",
		"cedula":cedula
	}
	,function(resp){
		var json = eval("(" + resp + ")");
		if(json.result==true)
		{
			//$('#modal-cliente').modal('hide');
			$("#div_venta").css("display","");
			$("#label_ced").html(number_format(json.cedula,0,",","."));
			$("#label_nom").html(json.nombres);	
			$("#label_ape").html(json.apellidos);
			$("#label_fi").html(json.fecha_ingreso);
			$("#label_hi").html(json.hora_ingreso);
			$("#id_admision").val(id_admision);
			//$("#idCliente").val(json.idCliente);

			$("#modal-paciente").modal("hide");
			setTimeout(function() {
				buscaDatosAnteriores($("#id_admision").val());
			}, 500);		
		}
		else
		{
			$('#modal-cliente').modal('hide');
			$('#modal-registro-cliente').modal('show');
			$("#ced_cliente_reg").val(filtro_cliente);
			$("#ced_cliente_reg").focus();

		}
	});
}

function buscaDatosAnteriores(id_cotizacion,asunto)
{
	if(id_cotizacion!=undefined)
	{
		capaBloqueo();
		$.post("cotizaciones/carga_cotizaciones_datos.php",{
			"accion":"buscaDatosAnteriores",
			"id_cotizacion":id_cotizacion
		},function(resp)
		{
			quitarCapa();
			//==> Existe el consumo...
			if(resp!="" && resp!=undefined)
			{
				$("#asunto").val(asunto);
				$("#id_cotizacion").val(id_cotizacion);
				$("#div_productos").html("");
				$("#existe").val("1");
				$("#div_productos").html(resp);
			}
		});
	}	
}

function buscaDatosProducto(codigo)
{
	var ban=0;
	$("#form1").find('input:text').each(function(){
        var spl =this.id.split("_");
        if(spl[0]=="cant")
        {
        	//alert(spl[1]+" "+codigo);
         	if(spl[1]===codigo)
         	{
         		crear_modal("Alerta","El producto o servicio ya se ingresó","info","","","");
         		$('.selectpicker').selectpicker('val',0);
         		ban=1;
         	}
        }
    });
	//12060.04
	//100.500.34
	if(ban==0)
	{
		var tipo =$("#id_prod option:selected").attr("tipo");
		$.post("cotizaciones/carga_cotizaciones_datos.php",
		{
			"accion":"buscaDatosProducto",
			"codigo":codigo,
			"tipo":tipo
		},
		function(resp)
		{
			var json = eval("(" + resp + ")");
			if(json.result==true)
			{
				var iva 			=parseFloat($("#iva").val());
				var subTotalProd 	=parseFloat(json.prec_prod);
				var totalProd 		=(parseFloat(json.prec_prod)*(iva)+(parseFloat(json.prec_prod)));

				$("#subtotal").val( (parseFloat($("#subtotal").val())+parseFloat(subTotalProd)) );
				$("#total").val( (parseFloat($("#total").val())+parseFloat(totalProd)) ); // ANTES $("#total").val( (parseFloat($("#total").val())+parseFloat(json.prec_prod) ) );
				
				$("#lab_subtot").html(number_format($("#subtotal").val(),2,",","."));
				$("#lab_precio").html(number_format($("#total").val(),2,",","."));

				if(json.cant_prod==undefined)
					json.cant_prod="";

				if(json.cantidad_general==undefined)
					json.cantidad_general="";

				var tabla="";
					tabla+="<tr id='"+json.cod_prod+"' class='tp'>";
					//tabla 	+="<td align='center'>"+json.cod_prod+"</td>";
					tabla 	+="<td align='left'>"+json.nom_prod+"</td>";
					tabla 	+="<td align='center'>"+(number_format(json.prec_prod,2,",","."))+"</td>";
					tabla 	+="<input type='hidden' id='exis_ori_"+json.cod_prod+"' name='exis_ori_"+json.cod_prod+"' value='"+(json.cant_prod)+"'>";
					tabla 	+="<input type='hidden' id='precio_"+json.cod_prod+"' name='precio_"+json.cod_prod+"' value='"+(json.prec_prod)+"'>";
					tabla 	+="<td align='center'><div class='col-xs-4' style='width: 70%;margin-left: 25px;'><input class='form-control' style='width:60px;' id='cant_"+json.cod_prod+"' name='cant_"+json.cod_prod+"' type='text' value='1' onblur='calcula_total(\""+json.cant_prod+"\",\""+json.prec_prod+"\",this.value,\"cant_"+json.cod_prod+"\",\""+json.tipo+"\",\"\")'></div>";
					//tabla 	+="<div style='margin-top: 5px;font-size: 20px;'><i class='fa fa-plus-square' style='cursor:pointer;' onclick='suma_prod(\"exis_"+json.cod_prod+"\",\"cant_"+json.cod_prod+"\",\"exis_ori_"+json.cod_prod+"\")'></i> <i class='fa fa-minus-square' style='cursor:pointer;' onclick='resta_prod(\"exis_"+json.cod_prod+"\",\"cant_"+json.cod_prod+"\",\"exis_ori_"+json.cod_prod+"\")'></i></div></td>";
					tabla 	+="<td align='center' style='vertical-align: middle;'><i class='fa fa-times' style='color:red;cursor:pointer;font-size: 25px;' onclick='eliminaTrDatos(\""+json.cod_prod+"\")'></i></td>";
					tabla+="</tr>";
				$("#tabla-productos").append(tabla);
				//==> Cargo los productos 
				if(json.tipo=="Producto")
				{
					$("#ventasProd").append("<input type='hidden' id='P_idProd_"+json.cod_prod+"' value='"+json.cod_prod+"|*"+json.nom_prod+"|*"+(number_format(json.prec_prod,2,",","."))+"'>");
					$("#ventasProd").append("<input type='hidden' id='P_cantProd_"+json.cod_prod+"' value='1'> ");
				}
				else if(json.tipo=="Servicio") //==> Cargo los servicios
				{
					$("#ventasProd").append("<input type='hidden' id='S_idProd_"+json.cod_prod+"' value='"+json.cod_prod+"|*"+json.nom_prod+"|*"+(number_format(json.prec_prod,2,",","."))+"'>");
					$("#ventasProd").append("<input type='hidden' id='S_cantProd_"+json.cod_prod+"' value='1'> ");					
				}

				//==> Limpia el select
				$(".selectpicker").selectpicker("val",0);
				//$(".filter-option").empty();
			}
			else
			{

			}
		});
	}
}

function buscaProd(idProd)
{
	if(idProd!="" && idProd!=undefined)
	{
		var nombre=$("#bus_prod option:selected").text();
		$("#div_trae_prod").load("consumos/carga_consumos_datos.php",
		{
			"accion":"buscaExistProducto",
			"id_prod":idProd,
			"nombre":nombre
		},
		function(resp)
		{

		});		
	}
}

function eliminaTrDatos(idTd)
{
		if(idTd!="" && idTd!=undefined)
		{
			$("#"+idTd).remove();
			$("#idProd_"+idTd).remove();
			$("#cantProd_"+idTd).remove();
		}

		//==> Calculo nuevamente el total
       	var total   =0;
       	var subTotal=0;
       	var iva 	=parseFloat($("#iva").val());

       	$("#total").val(0);
       	$("#subtotal").val(0);

        $("#form1").find('input:text').each(function(){
         	var spl =this.id.split("_");
         	if(spl[0]=="cant")
         	{
         		subTotal+= parseFloat($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val());
         		total+=( ( (parseFloat($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val() ))*(iva) )+( parseFloat($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val()) ) );

         	}
        });
        $("#lab_subtot").html(number_format(subTotal,2,",","."));
        $("#lab_precio").html(number_format(total,2,",",".") );

        $("#subtotal").val(subTotal);
        $("#total").val(total);
}

function calcula_total(cant_orig,precio,cant_prod,campo,tipo,act)
{
		if(cant_prod>=1)
		{
			if(parseInt(cant_prod)>parseInt(cant_orig))
			{
				//alert(cant_prod+">"+cant_orig);
				//crear_modal("Alerta","La cantidad de consumo excede la existencia del producto","warning",campo,"","");
				//return false;
			}
				//alert(cant_prod);
		       	$(document).ready(function(){
		       		var total 	=0;
		       		var subTotal=0;
		       		var iva 	=parseFloat($("#iva").val());

		       		$("#total").val(0);
		       		$("#subtotal").val(0);

		        	$("#form1").find('input:text').each(function(){
		         		var spl =this.id.split("_");
		         			if(spl[0]=="cant")
		         			{
			         			//total+=parseInt($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val());
			         			subTotal+= parseFloat($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val());
			         			total+=( ( (parseFloat($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val() ))*(iva) )+( parseFloat($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val()) ) );
			         			if(tipo=="Producto")
			         				$("#P_cantProd_"+spl[1]).val($("#cant_"+spl[1]).val());
			         			else if(tipo=="Servicio")
			         				$("#S_cantProd_"+spl[1]).val($("#cant_"+spl[1]).val());
		         			}
		         			//alert("elemento.id="+ elemento.id + ", elemento.value=" + elemento.value); 	
		        	});
		         	$("#lab_subtot").html(number_format(subTotal,2,",","."));
		        	$("#lab_precio").html(number_format(total,2,",",".") );

		        	$("#subtotal").val(subTotal);
		        	$("#total").val(total);
		        	//alert(total);
		       });
		}
		else
			crear_modal("Alerta","La cantidad mínima de consumo es de 1 producto o servicio","info",campo,"","");
}

function confirmConsumos()
{
	if($("#total").val()!="" && $("#total").val()!=0)
	{
		if($("#asunto").val()=="")
			crear_modal("Información","Ingrese el asunto de la precarga","info","asunto","","");
		else
		{
			$(".sweet-alert").css("box-shadow","inset 0px 0px 14px 2px rgb(248, 197, 134)");
			swal({
			  title: "¿Está seguro de guardar esta Precaga?",
			  text: "",
			  type: "warning",
			  showCancelButton: true,
			  confirmButtonClass: "btn-success",
			  confirmButtonText: "Guardar Precarga",
			  closeOnConfirm: true
			},
			function(){
				setTimeout(function(){
					cargarConsumos("");
				},500);
			});
		}	
	}	
}

function cargarConsumos(cerrar)
{
	if(cerrar=="c")
	   $("#cerrar").val("c");
	   $("#accion").val("cargarConsumos");

	   	capaBloqueo();
    	$.post("cotizaciones/carga_cotizaciones_datos.php",
		$("form").serialize(),
		function(resp){
			var json = eval("(" + resp + ")");
			quitarCapa();
			if(json.result==true)
				crear_modal("Información",json.mensaje,"success","","verCotizaciones(); limpiar();","");
			else
				crear_modal("Información",json.mensaje,"error","","verCotizaciones(); limpiar();","");
		});	
}

function calculaVuelto(monto)
{
	var montoSinPuntos   =monto.replace(".",""); //parseFloat(monto.replace(".",""))
		if( ((monto).search(","))>0 )
			 montoSinPuntos=parseFloat(montoSinPuntos.replace(",","."));

	var totalSinPuntos   =parseFloat($("#total").val());
	var totalVuelto      =number_format(parseFloat(montoSinPuntos-totalSinPuntos),2,",",".");
		var VueltoComp =parseFloat(montoSinPuntos-totalSinPuntos);
	//montoSinPuntos+" - "+totalSinPuntos+" = "+totalVuelto
	if( parseFloat(VueltoComp)>=0 )	
	{	
		$("#vuelto p").css("color","black");
		$("#vuelto").html("<p style='color:green;'>"+totalVuelto+"</p>");
	}
	else
	{
		$("#vuelto p").css("color","black");
		$("#vuelto").html("<p style='color:red;'>"+totalVuelto+"</p>");
	}
	
}

function verCotizaciones()
{
	capaBloqueo();
    $("#divDatos").load("cotizaciones/carga_cotizaciones_datos.php",{
    	"accion":"verCotizaciones"
    },
    function(resp)
    {
    	quitarCapa();
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

function conf_eliminar(id,cotizacion)
{
		$(".sweet-alert").css("box-shadow","inset 0px 0px 14px 2px rgb(248, 197, 134)");
		swal({
		  title: "¿Eliminar cotización de: '"+cotizacion+"'?",
		  text: "",
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

function eliminar(id_cotizacion)
{
	capaBloqueo();
    $.post("cotizaciones/carga_cotizaciones_datos.php",{
    	"accion":"eliminarCotizacion",
    	"id_cotizacion":id_cotizacion
    },
    function(resp)
    {
    	var json = eval("(" + resp + ")");
    	quitarCapa();
		if(json.result==true)
			crear_modal("Información",json.mensaje,"success","","verCotizaciones(); limpiar();","");
		else
			crear_modal("Información",json.mensaje,"error","","verCotizaciones(); limpiar();","");
    });	
}

function limpiar()
{
	$(".tp").remove();
	$("#subtotal").val("0");
	$("#total").val("0");
	$("#accion").val("");
	$("#lab_precio").html("");
	$("#lab_subtot").html("");
	$("#idCliente").val("");
	$(".selectpicker").selectpicker("val",0);
	$("#ventasProd").html("");
	$("#id_cotizacion").val("");
}

function carga_tabla()
{
	var tabla="";
	tabla+='<table id="tabla-productos" class="table table-striped table-bordered" style="margin-top:30px;width:100%;">';
	tabla+="<tr>";
	tabla+=		'<td width="55%" align="center" style="background-color:#C7C7C7;"><b>Descripci&oacute;n</b></td>';
	tabla+=		'<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Prec. Unit.</b></td>';
	tabla+=		'<td width="12%" align="center" style="background-color:#C7C7C7;"><b>Cant. Consumo</b></td>';
	tabla+=		'<td width="2%"  align="center" style="background-color:#C7C7C7;">&nbsp;</td>';
	tabla+=	"</tr>";
	tabla+="</table>";	

	$("#div_productos").html(tabla);
}

function limpiaProd()
{
	$(".bus_prod").selectpicker("val","");
	$("#div_trae_prod").html("");
}
</script>