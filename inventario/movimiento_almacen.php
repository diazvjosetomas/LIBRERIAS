<?
if(file_exists("../funcionesphp/seguridad.php"))
	include("../funcionesphp/seguridad.php");
else
	include("funcionesphp/seguridad.php");
antiChismoso();

session_start();
if($_SESSION["tipoUser"]==1) //==> Si es almacenista
{
	$sql=mysqli_query($enlace,"SELECT * FROM almacen WHERE id_user={$_SESSION["idUser"]} ");
	$rs=mysqli_fetch_assoc($sql);
	$idalmacen=$rs["idalmacen"];
	$where=" idalmacen=$idalmacen ";
	$selected=$idalmacen;
}

?>
<div class="container" style="padding-top:0px;">		
		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Movimiento de Almac&eacute;n</div>
			<div class="panel-body">

				<!--Prueba-->
				<div class="row marketing">	
					<div class="col-lg-6" style="width:100%;">
					<div class="form-group">
					<label class="col-sm-4 control-label"><b>Almac&eacute;n Base: </b></label>
						<div class="col-sm-4">
							<select class="form-control" id="idalmacen1" name="idalmacen1" onchange="buscarAlmacen(this.value)">
								<option></option>
								<?php
								$sql = "SELECT * FROM almacen";
								$result=mysqli_query($enlace,$sql);
								while($rs=mysqli_fetch_array($result)){
									?>
										<option value="<?= $rs["idalmacen"] ?>"><?= utf8_encode($rs["nombre"]) ?></option>
									<?php
								}
								?>
							</select>					
						</div>
					</div>
					<div id="productos" style="width:100%;padding-bottom:10px;">
						
					</div>
					<div class="form-group">
					<label class="col-sm-4 control-label"><b>Almac&eacute;n Receptor: </b></label>
						<div class="col-sm-4">
							<select class="form-control" id="idalmacen2" name="idalmacen2">
								<option></option>
								<?php
								$sql = "SELECT * FROM almacen";
								$result=mysqli_query($enlace,$sql);
								while($rs=mysqli_fetch_array($result)){
									?>
									<option value="<?= $rs["idalmacen"] ?>"><?=utf8_encode($rs["nombre"]) ?></option>
									<?php
								}
								?>
							</select>					
						</div>
					</div>		
					</div>
				</div>
				<!--End prueba-->
					<div class="form-group" style="text-align:center;">
					    <button type="button" class="btn btn-success" onclick="guardarMovimiento();"><i id="spin_bt" style="display:none;" class="fa fa-spinner fa-spin"></i> Guardar</button>
						<button type="button" class="btn btn-default" onclick="limpiar();"><i class="fa fa-eraser"></i> Limpiar</button>
					</div>
			</div>
		</div>
</div>

			<div class="container" style="margin-top:30px;">
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Movimientos</div>
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
								<td><?input_text("<b>C&oacute;digo:</b>","fil_codigo","6","$tipo","$onclick","$onblur","$attr","$type");?></td>
								<td><?input_text("<b>Nombre:</b>","fil_nombre","6","$tipo","$onclick","$onblur","$attr","$type");?></td>
							</tr>
							<tr id="fil_3" style="display:none;">
								<td align="center" colspan="2">
									<button type="button" class="btn btn-success" onclick="buscar()"><i class="fa fa-search"></i>Buscar</button>
									<button type="button" class="btn btn-default" onclick="limpiar_fil();"><i class="fa fa-eraser"></i> Limpiar</button>
								</td>
							</tr>
						</table>
						<div id="divDatos" style="margin:0 auto;"></div>
						</div>
					</div>
			</div>
<script type="text/javascript">
function guardarMovimiento()
{
	flag_error = false;
	if($("#cant_prod").val()=="")
	{
		crear_dialog("Alerta","Indique la cantidad del producto","cant_prod");
		return false;
	}

	if($("#cant_prod").val()=="0")
	{
		crear_dialog("Alerta","La cantidad debe ser mayor a '0'","cant_prod");
		return false;
	}

	if($("#id_prod").val()=="")
	{
		crear_dialog("Alerta","Seleccione la categor&iacute;a del producto","id_prod");
		return false;
	}


	if($("#idalmacen1").val()=="")
	{
		crear_dialog("Alerta","Seleccione el almac&eacute;n al que pertenece el producto","idalmacen");
		return false;
	}

	if($("#idalmacen1").val()==$("#idalmacen2").val())
	{
		crear_dialog("Alerta","El almacen 1 no puede ser igual al almacen receptor.","idalmacen");
		return false;
	}

	$.each($("[name='productos[]']:checked"),function(k,v){
		if($("#cant_"+v.attributes.value.value).val() > $("#cant_disp_"+v.attributes.value.value).val()){
			crear_dialog("Alerta","La cantidad a enviar no puede ser mayor a la cantidad disponible.","#cant_"+v.attributes.value.value);
			flag_error = true;
			return false;
		}
	});
	if(flag_error){
		return false;
	}

	$("#accion").val("guardarMovimiento");

	$.post("inventario/movimiento_datos.php",$("#form1").serialize(),
	function(resp){
		var json = eval("(" + resp + ")");
		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verMovimientos(); limpiar(); ");
		else
			crear_dialog("Alerta",json.mensaje,"",""); //reload
	});
}

function eliProd(idProd)
{
	$.post("inventario/productos_datos.php",{
		"accion":"eliminarProd",
		"idProdEli":idProd
	},

	function(resp){
		var json = eval("(" + resp + ")");
		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verMovimientos(); limpiar();");
		else
			crear_dialog("Alerta",json.mensaje,"","reload");
	});	
}

$("#cod_prod").keypress(function(e) {
    if (e.keyCode == 13) {
    	verificaCodigo($("#cod_prod").val())
    }
});

function buscarAlmacen(idalmacen)

{
	if(idalmacen!="")
	{
		$.post("inventario/movimiento_datos.php",
			{
				"accion":"buscarAlmacen",
				"idalmacen1":idalmacen
			},
			function(resp){
				$("#productos").html(resp);
			});
	}
}

function filtrarAlmacen()
{
	fil_producto = $("#fil_producto").val()	
	idalmacen 	 = $("#idalmacen1").val()
	if(idalmacen!="")
	{
		$.post("inventario/movimiento_datos.php",
			{
				"accion":"buscarAlmacen",
				"idalmacen1":idalmacen,
				"fil_producto":fil_producto
			},
			function(resp){
				$("#productos").html(resp);
			});
	}
}

function verMovimientos()
{
	$("#divDatos").load("inventario/movimiento_datos.php",{
		"accion":"verMovimientos"
	});
}

function limpiar()
{
	$("form")[0].reset();
	$("#idProdMod").val("");
	$(".selectpicker").selectpicker("val",0);
	$("#divSubCat").html("");
	$("#productos").html("");
	setTimeout(function() {
	    $('#cod_prod').focus();
	    verMovimientos();
	}, 0);
}

function limpiar_fil()
{
	$("#fil_codigo,#fil_nombre").val("");
}

function pag(num)
{
	var accion="verMovimientos";
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

	$("#divDatos").load("inventario/movimiento_datos.php",{accion:accion,pg:pg},function()
	{
		$("#pag"+num).addClass("active");
		setTimeout($.unblockUI); 
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

function buscar()
{
	var fil_codigo	=$("#fil_codigo").val();
	var fil_nombre 	=$("#fil_nombre").val();

	$.blockUI({ css: { 
	    border: 'none', 
	    padding: '15px', 
	    backgroundColor: '#000', 
	    '-webkit-border-radius': '10px', 
	    '-moz-border-radius': '10px', 
	    opacity: .5, 
	    color: '#fff' 
	} });

	$("#divDatos").load("inventario/productos_datos.php",
	{
		"accion":"verMovimientos",
		"fil_codigo":fil_codigo,
		"fil_nombre":fil_nombre	
	},
	function()
	{
		setTimeout($.unblockUI); 
	});		
}

function abreDesc(nom_prod,descripcion) 
{
	crear_dialog("Descripci&oacute;n: <em>"+nom_prod+"</em>","<p style='text-align:justify;'>"+descripcion+"</p>","","");
	return false;	
}
verMovimientos();
</script>