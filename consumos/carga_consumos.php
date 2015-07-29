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
input_hidden("id_admision","");
input_hidden("cerrar","")
?>
<div class="container" style="padding-top:0px;width:100%;">		
		<div id="div_venta" class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;display:none;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Cargar consumos</div>
			<div class="panel-body">
				<div class="form-group" style="margin: 1px;">
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
	          	</div>
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
		                                $sql = mysqli_query($enlace,"SELECT DISTINCT p.id_prod,p.nom_prod,p.cod_prod,p.cant_presentacion,pp.ab_presentacion,pa.id
		                                							 FROM productos p
		                                							 LEFT JOIN presentacion_productos pp ON (p.idpresentacion=pp.id_presentacion)
		                                							 INNER JOIN producto_inventario pi ON (p.id_prod=pi.id_prod)
		                                							 INNER JOIN producto_almacen pa ON (pi.idproductoinventario=pa.id_producto_inventario)
		                                							 WHERE pa.idalmacen='".$_SESSION["idAlmacen"]."'
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
		                                	?><option tipo="P" value="<?=$rs["id"]?>"><?=utf8_encode($rs["nom_prod"])." ".number_format($rs["cant_presentacion"],0,",",".")." ".$rs["ab_presentacion"]; ?> | <?=$rs["cod_prod"]?></option><?
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
		        <button type="button" class="btn btn-info" onclick="confirmConsumos()"><i class="fa fa-plus"></i> Confirmar carga de consumos</button>
		        <button type="button" class="btn btn-success" onclick="cerrarCuenta()"><i class="fa fa-check"></i> Cerrar cuenta y dar de alta</button>
		        <button type="button" class="btn btn-primary" onclick="window.location.reload();"><i class="fa fa-search"></i> Consultar otro paciente</button>								
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
            	<select autofocus class='selectpicker paciente' id='bus_ced' name='bus_ced' data-live-search='true' onchange=''>
            	<option data-icon="glyphicon glyphicon-user" value="">Buscar...</option>
            	<?
            		$sql=mysqli_query($enlace,"SELECT DISTINCT * FROM admision_paciente ap
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

$('#modal-paciente').modal('show');
$("#bus_ced").focus();

setTimeout(function() {
$("#divCont").css("width","100%");
//$("[data-id='bus_ced']").children()[0].attr("placeholder","Nº Cédula");
//$(".paciente").selectpicker("placeholder","Nº Cédula");
//$(".input-block-level").attr("placeholder","Nº Cédula");
$(".bootstrap-select:not([class*='col-']):not([class*='form-control']):not(.input-group-btn)").css("width","320px");

}, 0);


$('#modal-cliente').on('hidden.bs.modal',function (e){
	//Accion al cerrar el primer modal
});

function busca_client_ent(e)
{
	if(e.keyCode==13)
	{
		buscar_paciente();
	}
}

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
			
			//$("#panel-contenido").css("display","");
/*			$("#lab_ced").html(number_format(json.cedula,0,",","."));
			$("#lab_nom").html(json.nombres);
			$("#lab_ape").html(json.apellidos);
			$("#lab_fi").html(json.fecha_ingreso);
			$("#lab_hi").html(json.hora_ingreso);
			$("#lab_obs").html(json.obs);	
			$("#cedula").val(json.cedula);*/		
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

function buscaDatosAnteriores(id_admision)
{
	if(id_admision!=undefined)
	{
		capaBloqueo();
		$.post("consumos/carga_consumos_datos.php",{
			"accion":"buscaDatosAnteriores",
			"id_admision":id_admision
		},function(resp)
		{
			quitarCapa();
			//==> Existe el consumo...
			if(resp!="" && resp!=undefined)
			{
				$("#existe").val("1");
				$("#div_productos").html(resp);
			}
			else
			{
				//===> No existe consumo
				var tabla="";
			    tabla+='<table id="tabla-productos" class="table table-striped table-bordered" style="margin-top:30px;width:100%;">';
			    tabla+="<tr>";
			    tabla+=		'<td width="55%" align="center" style="background-color:#C7C7C7;"><b>Descripci&oacute;n</b></td>';
			    tabla+=		'<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Existencia en inventario</b></td>';
			    tabla+=		'<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Existencia General</b></td>';
			    tabla+=		'<td width="10%" align="center" style="background-color:#C7C7C7;"><b>Prec. Unit.</b></td>';
			    tabla+=		'<td width="12%" align="center" style="background-color:#C7C7C7;"><b>Cant. Consumo</b></td>';
			    tabla+=		'<td width="8%"  align="center" style="background-color:#C7C7C7;">&nbsp;</td>';
			    tabla+=	"</tr>";
			    tabla+="</table>";	

			    $("#div_productos").html(tabla);
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
		$.post("consumos/carga_consumos_datos.php",
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
				var plus="";
				var read="";
					tabla+="<tr id='"+json.cod_prod+"' class='tp'>";
					//tabla 	+="<td align='center'>"+json.cod_prod+"</td>";
					tabla 	+="<td align='left'>"+json.nom_prod+"</td>";
					tabla 	+="<td align='center'>"+(json.cant_prod)+"</td>";
					tabla 	+="<td align='center'>"+(json.cantidad_general)+"</td>";
					tabla 	+="<td align='center'>"+(number_format(json.prec_prod,2,",","."))+"</td>";
					tabla 	+="<input type='hidden' id='exis_ori_"+json.cod_prod+"' name='exis_ori_"+json.cod_prod+"' value='"+(json.cant_prod)+"'>";
					tabla 	+="<input type='hidden' id='precio_"+json.cod_prod+"' name='precio_"+json.cod_prod+"' value='"+(json.prec_prod)+"'>";
					if($("#existe").val()=="1")
					{
						read="readonly='readonly'";
						tabla+='<td align="center"></td>';
						plus+="<i class='fa fa-plus' data-toggle='tooltip' data-placement='left' data-original-title='A&ntilde;adir otra cantidad' style='color:green;cursor:pointer;font-size: 25px;' onclick='habilitaInput(\"cant_"+json.cod_prod+"\",\""+json.prec_prod+"\")'></i>";
					}
					tabla 	+="<td align='center'><div class='col-xs-4' style='width: 70%;margin-left: 25px;'><input class='form-control' style='width:60px;' id='cant_"+json.cod_prod+"' name='cant_"+json.cod_prod+"' "+read+" type='text' value='1' onblur='calcula_total(\""+json.cant_prod+"\",\""+json.prec_prod+"\",this.value,\"cant_"+json.cod_prod+"\",\""+json.tipo+"\",\"\")'></div>"+plus+"";
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
	if($("#existe").val()!="1") //==> Si no existe
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
    else if($("#existe").val()=="1") //==> Existe 
    {
	       	var subTotalAnt =$("#subtotal").val();
	       	var totalAnt 	=$("#total").val();
	       	var iva 		=parseFloat($("#iva").val());
	       	var valorCampo 	=$("#cant_"+idTd).val();
	       	var precio 		=$("#precio_"+idTd).val();

	       //	console.log(idTd);
	       	//===> Si la cantidad no esta vacia
	       	if(valorCampo!="" && valorCampo!=undefined)
	       	{
	       		//===> Reseteo la cantidad del input
	       		//$("#"+id_input).val("");
	        		//console.log(subTotalAnt);
	        		subTotalAnt =(parseFloat(subTotalAnt)-(parseInt(valorCampo)*parseFloat(precio)));
	        		//console.log(subTotalAnt);

	        		//console.log(totalAnt);
	        		totalAnt 	=(parseFloat(totalAnt)-( ((parseInt(valorCampo)*parseFloat(precio))*(iva))+(parseInt(valorCampo)*parseFloat(precio)) ) );
	        		//console.log((parseInt(valorCampo)*parseFloat(precio))*(iva) );

	        		$("#"+idTd).remove();
					$("#idProd_"+idTd).remove();
					$("#cantProd_"+idTd).remove();

	       		//$("#ventasProd").append("<input type='hidden' id='"+id_input+"' name='"+id_input+"' value='"+valorCampo+"'>");

	         	$("#lab_subtot").html(number_format(subTotalAnt,2,",","."));
	        	$("#lab_precio").html(number_format(totalAnt,2,",",".") );

	        	$("#subtotal").val(subTotalAnt);
	        	$("#total").val(totalAnt);
	        }
    }
}

function habilitaInput(id_input,precio)
{
	if(id_input!=undefined)
	{
		$("#"+id_input).removeAttr("readonly");

	       	var subTotalAnt =$("#subtotal").val();
	       	var totalAnt 	=$("#total").val();
	       	var iva 		=parseFloat($("#iva").val());
	       	var valorCampo 	=$("#"+id_input).val();

	       	//===> Si la cantidad no esta vacia
	       	if(valorCampo!="" && valorCampo!=undefined)
	       	{
	       		//===> Reseteo la cantidad del input
	       		$("#"+id_input).val("");

	        		console.log(subTotalAnt);
	        		subTotalAnt =(parseFloat(subTotalAnt)-(parseInt(valorCampo)*parseFloat(precio)));
	        		console.log(subTotalAnt);

	        		console.log(totalAnt);
	        		totalAnt 	=(parseFloat(totalAnt)-( ((parseInt(valorCampo)*parseFloat(precio))*(iva))+(parseInt(valorCampo)*parseFloat(precio)) ) );
	        		console.log((parseInt(valorCampo)*parseFloat(precio))*(iva) );

	       		//$("#ventasProd").append("<input type='hidden' id='"+id_input+"' name='"+id_input+"' value='"+valorCampo+"'>");

	         	$("#lab_subtot").html(number_format(subTotalAnt,2,",","."));
	        	$("#lab_precio").html(number_format(totalAnt,2,",",".") );

	        	$("#subtotal").val(subTotalAnt);
	        	$("#total").val(totalAnt);
	        }
	        else
	        {
	        	//$("#ventasProd").append("<input type='hidden' id='"+id_input+"' name='"+id_input+"' value='"+valorCampo+"'>");
	    	}

		$("#"+id_input).focus();
	}
}

function calcula_total(cant_orig,precio,cant_prod,campo,tipo,act)
{
	if( $("#"+campo).attr("readonly")!="readonly")
	{
		if(cant_prod>=1)
		{
			if(parseInt(cant_prod)>parseInt(cant_orig))
			{
				//alert(cant_prod+">"+cant_orig);
				crear_modal("Alerta","La cantidad de consumo excede la existencia del producto","warning",campo,"","");
				return false;
			}

			if($("#existe").val()!="1" && (act=="" || act==undefined) ) //===> Si no es una actualizacion de consumo
			{
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
			else if($("#existe").val()=="1" && (act!="" || act!=undefined) ) //===> Es actualizacion...
			{
				//console.log("Actualizacion");
		       	var total 		=0;
		       	var subTotal 	=0;
		       	var subTotalAnt =$("#subtotal").val();
		       	var totalAnt 	=$("#total").val();
		       	var iva 		=parseFloat($("#iva").val());
		       	var valorCampo 	=$("#"+campo).val();

		        $("#form1").find('input:text').each(function(){
		        	if(this.id==campo)
		        	{
		         		var spl =this.id.split("_");
		         		//console.log(this.id);
		         		if(spl[0]=="cant")
		         		{
		         			//console.log(spl[0]+" "+spl[1]);
							subTotal+= parseFloat($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val());
							total+=( ( (parseFloat($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val() ))*(iva) )+( parseFloat($("#precio_"+spl[1]).val())*parseInt($("#cant_"+spl[1]).val()) ) );
						}
					}

		        	$("#subtotal").val( (parseFloat(subTotalAnt)+parseFloat(subTotal)) );
		        	$("#total").val( (parseFloat(totalAnt)+parseFloat(total)) );	
		        	//console.log("subtotal: "+$("#subtotal").val()+" Total: "+$("#total").val() ) ;
		         	$("#lab_subtot").html(number_format($("#subtotal").val(),2,",","."));
		        	$("#lab_precio").html(number_format($("#total").val(),2,",",".") );

		        		if( $("#"+campo).attr("nuevo")!=1)
		        			$("#"+campo).attr("readonly","readonly");
		        	});
			}
		}
		else
			crear_modal("Alerta","La cantidad mínima de consumo es de 1 producto o servicio","info",campo,"","");
	}
}

function confirmConsumos()
{
	if($("#total").val()!="" && $("#total").val()!=0)
	{
		$(".sweet-alert").css("box-shadow","inset 0px 0px 14px 2px rgb(248, 197, 134)");
		swal({
		  title: "¿Está seguro de cargar estos consumos?",
		  text: "Esta opción no puede deshacerse",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-danger",
		  confirmButtonText: "Cargar consumo",
		  closeOnConfirm: true
		},
		function(){
			setTimeout(function(){
				cargarConsumos("");
			},500);
		});

	}	
}

function cerrarCuenta()
{
	if($("#total").val()!="" && $("#total").val()!=0)
	{
		$(".sweet-alert").css("box-shadow","inset 0px 0px 14px 2px rgb(248, 197, 134)");
		swal({
		  title: "¿Está seguro de cargar estos consumos y cerrar la cuenta?",
		  text: "Esta opción no puede deshacerse",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-danger",
		  confirmButtonText: "Cerrar cuenta",
		  closeOnConfirm: true
		},
		function(){
			setTimeout(function(){
				cargarConsumos("c");
			},500);
		});

	}	
}

function cargarConsumos(cerrar)
{
	if(cerrar=="c")
	   $("#cerrar").val("c");
	   $("#accion").val("cargarConsumos");

	   	capaBloqueo();
    	$.post("consumos/carga_consumos_datos.php",
		$("form").serialize(),
		function(resp){
			var json = eval("(" + resp + ")");
			quitarCapa();
			if(json.result==true)
				crear_modal("Información",json.mensaje,"success","","reload","");
			else
				crear_modal("Información",json.mensaje,"error","","","");
		});	
}

function confirmCompra()
{
	if($("#total").val()!="" && $("#total").val()!=0)
	{
		var subTotal =number_format($("#subtotal").val(),2,",",".");
		var total 	 =number_format($("#total").val(),2,",",".");
		var iva 	 =($("#iva").val()*100);
		var totalIVA =number_format((parseFloat($("#subtotal").val())*(parseFloat(iva/100))),2,",",".");
		var datos ="";
			datos+="<p style='font-size:28px;text-align:right;'>Sub-total: <b> Bs. "+subTotal+"</b></p>";
			datos+="<p style='font-size:28px;text-align:right;'>IVA ("+iva+"%): <b> Bs. "+totalIVA+"</b></p>";
			datos+="<p style='font-size:28px;text-align:right;'>Total a cancelar: <b> Bs. "+total+"</b></p>";
			datos+="<div class='form-group'>";
			//==> Forma de pago
			datos 	+="<label class='col-sm-2 control-label' style='width:25%;'>Forma de pago: </label>";
			datos 		+="<div class='col-sm-6'>";
			datos 			+="<select class='form-control' id='tipoPago' name='tipoPago' onchange='detallesPago(this.value)'><option value=''></option><option value='1'>Efectivo</option><option value='2'>D&eacute;bito</option><option value='3'>Cr&eacute;dito</option></select>";
			datos 		+="</div>";
			datos+="</div>";
			//==> Efectivo
			datos+="<div id='divEfectivo' name='divEfectivo' class='form-group' style='display:none;'>";
			datos 	+="<label class='col-sm-2 control-label' style='width:25%;'>Total efectivo: </label>";
			datos 		+="<div class='col-xs-3'>";
			datos 			+="<input type='text' class='form-control' id='totalEfectivo' name='totalEfectivo' onkeyup='this.value=ValidaNumero(event,this); calculaVuelto(this.value);' value=''>";
			datos 		+="</div>";
			datos+="<label class='col-sm-2 control-label' style='width:22%;font-size:25px;padding-top:1px;'>Vuelto Bs.F: </label>";
			datos+="<label id='vuelto' name='vuelto' class='col-sm-2 control-label' style='width:16%;font-size:25px;padding-top:1px;'> </label>";
			datos+="</div>";
			//==> Bancos
			datos+="<div id='bancos' name='bancos' class='form-group' style='display:none;'>";
			datos 	+="<label class='col-sm-2 control-label' style='width:25%;'>Banco: </label>";
			datos 		+="<div class='col-sm-6'>";
			datos 			+="<select class='selectpicker' id='banco' name='banco' data-live-search='true'>";
			datos+=					"<option value=''></option>";
			datos+=					"<option value='Venezuela'>Venezuela</option>";
			datos+=					"<option value='Banesco'>Banesco</option>";
			datos+=					"<option value='Provincial'>Provincial</option>";
			datos+=					"<option value='Mercantil'>Mercantil</option>";
			datos+=					"<option value='Bicentenario'>Bicentenario</option>";
			datos+=					"<option value='BOD'>BOD</option>";
			datos+=					"<option value='Bancaribe'>Bancaribe</option>";
			datos+=					"<option value='Exterior'>Exterior</option>";
			datos+=					"<option value='Banco del Tesoro'>Banco del Tesoro</option>";
			datos+=					"<option value='Industrial'>Industrial</option>";
			datos+=					"<option value='BNC'>BNC</option>";
			datos+=					"<option value='Fondo Com&uacute;n'>Fondo Com&uacute;n</option>";
			datos+=					"<option value='Venezolano de Cr&eacute;dito'>Venezolano de Cr&eacute;dito</option>";
			datos+=					"<option value='Caron&iacute;'>Caron&iacute;</option>";
			datos+=					"<option value='Agr&iacute;cola de Venezuela'>Agr&iacute;cola de Venezuela</option>";
			datos+=					"<option value='Sofitasa'>Sofitasa</option>";
			datos+=					"<option value='Plaza'>Plaza</option>";
			datos+=					"<option value='Del Sur'>Del Sur</option>";
			datos+=					"<option value='Citibank'>Citibank</option>";
			datos+=					"<option value='Activo'>Activo</option>";
			datos+=					"<option value='Banplus'>Banplus</option>";
			datos+=					"<option value='100% Banco'>100% Banco</option>";
			datos+=				  "</select>";
			datos 		+="</div>";
			datos+="</div>";

		$('#modal-compra').modal('show');
		$("#divCompra").html(datos);

		//==> Activo el selectpicker para los bancos
		$(".selectpicker").selectpicker();
		
		//crear_dialog("Confirmar venta","<p style='font-size:20px;'>Total a cancelar: <b>"+total+" Bs.F</b></p>","","vender();");
	}
}

function vender()
{
	if($("#tipoPago").val()!="")
	{
		//==> Efectivo
		if( $("#tipoPago").val()==1 )
		{
			if( $("#totalEfectivo").val()=="" || $("#totalEfectivo").val()==0 )
			{
				crear_dialog("Alerta","Indique la cantidad de efectivo","totalEfectivo");
				return false;
			}	
			else if( $("#totalEfectivo").val()!="" && $("#totalEfectivo").val()!=0 )		
			{
				//===> Aqui valido que el monto en efectivo sea el necesario
				var montoSinPuntos   =$("#totalEfectivo").val().replace(".",""); 
					if( (($("#totalEfectivo").val()).search(","))>0 )
						 montoSinPuntos=parseFloat(montoSinPuntos.replace(",","."));

				var totalSinPuntos   =parseFloat($("#total").val());		

				if(parseFloat(montoSinPuntos)<parseFloat(totalSinPuntos))
				{
					crear_dialog("Alerta","La cantidad de efectivo no es suficiente","totalEfectivo");
					return false;		
				}		
			}	
		}
			//==>Debito
			if( $("#tipoPago").val()==2 )
			{
				if( $("#banco").val()=="" )
				{
					crear_dialog("Alerta","Seleccione la entidad bancaria","banco");
					return false;					
				}
			}
				
				//==> Credito
				if( $("#tipoPago").val()==3 )
				{
					if( $("#banco").val()=="" )
					{
						crear_dialog("Alerta","Seleccione la entidad bancaria","banco");
						return false;					
					}
				}

		//===> Cierro el modal
		$('#modal-compra').modal('hide');
	    	
	    	$("#accion").val("guardaVenta");

    		$.post("ventas/ventas_datos.php",
			$("form").serialize(),
			function(resp){
				var json = eval("(" + resp + ")");

				if(json.result==true)
					crear_dialog("Informaci&oacute;n",json.mensaje,"","reload");
				else
					crear_dialog("Informaci&oacute;n",json.mensaje,"","");
			});
	}
	else
	{
		crear_dialog("Alerta","Seleccione la forma de pago","tipoPago");
		return false;		
	}
}

function detallesPago(valor)
{
	if(valor!="")
	{
		if(valor==1)
		{
			$("#divEfectivo").css("display","");
			//==> Oculto bancos
			$("#bancos").css("display","none");
			$(".filter-option").empty();
		}
		else
		{
			$("#divEfectivo").css("display","none");
			$("#vuelto").html("");
			//==> Muestro bancos
			$("#bancos").css("display","");
		}
	}
	else
	{
		$("#divEfectivo").css("display","none");
		$("#vuelto").html("");
		$("#bancos").css("display","none");
		$(".filter-option").empty();
	}
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
}

function limpiaProd()
{
	$(".bus_prod").selectpicker("val","");
	$("#div_trae_prod").html("");
}
</script>