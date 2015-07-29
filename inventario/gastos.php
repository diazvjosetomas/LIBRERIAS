<?

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
<style type="text/css">
	.tabla_producto tr,td{
		border: 0px;
		padding: 4px;
	}
</style>
<input type="hidden" id="idproducto" name="idProdMod" value="">

<div class="container" style="padding-top:0px;">		

		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">

			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Ingreso de Gastos</div>

			<div class="panel-body">

				<!--Prueba-->

				<div class="row marketing">	

					<div class="col-lg-5" style="box-shadow: 2px 2px 7px rgb(102, 153, 210) !important; border-color: #9ECCFF; margin-left: 20px; padding: 6px;">
					<fieldset>
						<legend>Datos Factura</legend>
					<div class="form-group">

					<label class="col-sm-4 control-label"><b>N&ordm; Factura: </b></label>

						<div class="col-sm-6">

							<input class="form-control" type="text" id="nfactura" name="nfactura">						

						</div>

					</div>

					<div class="form-group">

					<label class="col-sm-4 control-label"><b>N&ordm; Control: </b></label>

						<div class="col-sm-6">

							<input class="form-control" type="text" id="ncontrol" name="ncontrol">						

						</div>

					</div>
					<div class="form-group">

					<label class="col-sm-4 control-label"><b>Tipo de Pago: </b></label>
						<div class="col-sm-6">
							<select id="idtipopago" name="idtipopago" class="form-control">
								<option value="1">Contado</option>
								<option value="2">Credito</option>
							</select>				
						</div>

					</div>
					<div class="form-group">
					<label class="col-sm-4 control-label"><b>Proveedor: </b></label>
						<div class="col-sm-6">
							<select id="idproveedor" name="idproveedor" class="form-control">
							<option></option>
							<?php
							$sql    = "SELECT * FROM proveedores";
							$result1 = mysqli_query($enlace,$sql);
							while ($rs = mysqli_fetch_assoc($result1)) {
								?>
								<option value="<?= $rs["idproveedor"] ?>"><?= $rs["nombre"] ?></option>
								<?php
							}
							?>
							</select>				
						</div>
						<i style="font-size: 20px; cursor: pointer;" title="Nuevo Proveedor" class="fa fa-briefcase" data-toggle="modal" data-target="#proveedor-modal"></i>
					</div>
						<?= input_fecha("<b>Fecha Factura:</b>","fecha_factura","$ancho",$fecha,"$onclick","$onblur","$attr"); ?>
				</fieldset>
<!-- NUEVO PRODUCTO  -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Nuevo Grupo</h4>
	      </div>
	      <div class="modal-body">
				<div class="form-group">
					<label class="col-sm-4 control-label"><b>C&oacute;digo producto: </b></label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="cod_producto" name="cod_producto">						
						</div>
				</div>	
				<div class="form-group">
					<label class="col-sm-4 control-label"><b>Nombre: </b></label>
						<div class="col-sm-5">
							<input class="form-control" type="text" id="nom_prod" name="nom_prod">						
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><b>Descripci&oacute;n: </b></label>
					<div class="col-sm-8">
						<textarea class="form-control" type="text" id="desc_prod" name="desc_prod" style="height:120px;"></textarea>
					</div>
				</div>
			</div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        <button type="button" class="btn btn-primary" onclick="guardarProducto()">Guardar Producto</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<!-- NUEVO PROVEEDOR  -->
	<div class="modal fade" id="proveedor-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Nuevo Proveedor</h4>
	      </div>
	      <div class="modal-body">
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
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        <button type="button" class="btn btn-primary" onclick="guardarProveedor()">Guardar Proveedor</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<div class="col-lg-6">
	<div class="panel panel-default">
		<div class="panel-body">					
	<?
	input_textarea("<b>Observaciones:</b>","observacion","$ancho","","$onclick");
	?>
		</div>
	</div>
</div>
<div class="col-lg-6">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-2 control-label"><b>Subtotal: </b></label>
				<div class="col-sm-4">
					<input class="form-control" type="text" id="sub_total" name="sub_total" onkeyup="this.value=ValidaNumero(event,this)" readonly="readonly" onkeypress='return soloNumeros();'>
				</div>
				<label class="col-sm-2 control-label"><b>Retenci&oacute;n ISLR: </b></label>
				<div class="col-sm-4">
					<input class="form-control" type="text" id="retencion_islr" name="retencion_islr" onkeyup="this.value=ValidaNumero(event,this)" readonly="readonly" onkeypress='return soloNumeros();'>
				</div>
			</div>
				<div class="form-group">
				<label class="col-sm-2 control-label"><b>Total Impuesto: </b></label>
					<div class="col-sm-4">
						<input class="form-control" type="text" id="total_impuesto" name="total_impuesto" onkeyup="this.value=ValidaNumero(event,this)" readonly="readonly" onkeypress='return soloNumeros();'>
					</div>
					<label class="col-sm-2 control-label"><b>Retenci&oacute;n IVA: </b></label>
					<div class="col-sm-4">
						<input class="form-control" type="text" id="retencion_iva" name="retencion_iva" onkeyup="this.value=ValidaNumero(event,this)" readonly="readonly" onkeypress='return soloNumeros();'>
					</div>
				</div>
				<div class="form-group">
				<label class="col-sm-2 control-label"><b>Total General: </b></label>
					<div class="col-sm-4">
						<input class="form-control" type="text" id="total_general" name="total_general" onkeyup="this.value=ValidaNumero(event,this)" readonly="readonly" onkeypress='return soloNumeros();'>
					</div>
				</div>
			</div>
		</div>
</div>
</div>
<!--<div class="col-lg-12">
	<div class="form-group" style="margin-top: 20px;">
		<label class="col-sm-2 control-label"><b>C&oacute;digo producto: </b></label>
			<div class="col-sm-3">
				<input class="form-control" type="text" id="cod_prod" name="cod_prod">						
			</div>
			<a class="btn btn-success" onclick="verificaCodigo(this.value)"><i class="fa fa-search"></i></a>
	</div>	
</div>-->
<div id="producto_buscado" style="padding: 10px; float: left;">
<table class='tabla_producto'>
	<tr>
		<td>
			<select class="form-control" id="idgrupogasto" name="idgrupogasto">
				<option></option>
				<?php
				$sql = "SELECT * FROM grupo_gastos WHERE estatus=1";
				$result2 = mysqli_query($enlace,$sql);
				while ($Gastos = mysqli_fetch_array($result2)) {
					?>
					<option value="<?= $Gastos["idgrupogasto"] ?>"><?= $Gastos["nombre_grupo"] ?></option>
					<?php
				}
				?>
			</select>
		</td>
		<td><input type='text' class='form-control' id='nomb_prod' value='' /></td>
		<td><input class='form-control' type='text' id='cant_prod' name='cant_prod' placeholder='Cantidad' onkeyup='this.value=ValidaNumero(event,this)' onkeypress='return soloNumeros();'></td>
		<td><div class='input-group'><span class='input-group-addon'><i style='font-weight:bold;'>Bs.F.</i></span><input class='form-control' type='text' id='prec_prod' placeholder='Precio' name='prec_prod' onkeyup='this.value=ValidaNumero(event,this);' ></div></td>
		<td><a class='btn btn-success' title='Agregar Producto' onclick='agregar()'><i class='fa fa-plus'></i></a></td>
	</tr>
</table>
</div>
<table class="table table-bordered table-striped table-condensed table-responsive">
	<thead>
		<th>Descripcion</th>
		<th>Cantidad</th>
		<th>Precio Unitario</th>
		<th>IVA Total</th>
		<th>Total</th>
		<th></th>
	</thead>
	<tbody id="productos">
		<tr><td colspan="6" style="text-align: center;"><b>No hay productos cargados</b></td></tr>
	</tbody>
</table>

</div>

				<!--End prueba-->

					<div class="form-group" style="text-align:center;">

					    <button type="button" class="btn btn-success" onclick="guardarFactura();"><i id="spin_bt" style="display:none;" class="fa fa-spinner fa-spin"></i> Guardar</button>

						<button type="button" class="btn btn-default" onclick="limpiar();"><i class="fa fa-eraser"></i> Limpiar</button>

					</div>

			</div>

		</div>

</div>



			<div class="container" style="margin-top:30px;">

					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">

						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Gastos</div>

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

//===> Al parecer la pag carga muy rapido y no toma esta funcion si no la pones a que espere un momento...

setTimeout(function() {

    $('#cod_prod').focus();

    verFacturas();

}, 0);


function guardarFactura()
{
	if($("[name='grupo_gastos[]']").val()=="" || $("[name='grupo_gastos[]']").val()==undefined)

	{
		crear_dialog("Alerta","Agregue uno o mas servicios a la factura","nomb_prod");
		return false;
	}
	if($("#nfactura").val()=="")
	{
		crear_dialog("Alerta","Indique el numero de la factura","nfactura");
		return false;
	}

	if($("#idtipopago").val()=="")
	{
		crear_dialog("Alerta","Indique el tipo de pago","idtipopago");
		return false;
	}

	if($("#idproveedor").val()=="")
	{
		crear_dialog("Alerta","Indique el Proveedor","idproveedor");
		return false;
	}

	if($("#fecha_factura").val()=="")
	{
		crear_dialog("Alerta","Indique la fecha de la factura","fecha_factura");

		return false;

	}

	var accion="";

	$("#accion").val("guardarFactura");

	$.post("inventario/gastos_datos.php",$("#form1").serialize(),
	function(resp){
		var json = eval("(" + resp + ")");
		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verFacturas(); limpiar(); ");
		else
			crear_dialog("Alerta",json.mensaje,"",""); //reload
	});

}


function guardarProducto()
{
	if($("#cod_producto").val()=="")

	{
		crear_dialog("Alerta","Indique el c&oacute;digo del producto","cod_producto");
		return false;
	}
	if($("#nom_prod").val()=="")
	{
		crear_dialog("Alerta","Indique el nombre del producto","nom_prod");
		return false;
	}

	if($("#desc_prod").val()=="")
	{

		crear_dialog("Alerta","Indique la decripci&oacute;n del producto","desc_prod");

		return false;

	}

	if($("#id_cat_prod").val()=="")
	{
		crear_dialog("Alerta","Seleccione la categor&iacute;a del producto","id_prod");
		return false;
	}

	if($("#id_sub_cat").val()=="")
	{
		crear_dialog("Alerta","Seleccione la sub-categor&iacute;a del producto","id_prod");
		return false;
	}	


	var accion="";

	$("#accion").val("guardarProducto");

	$.post("inventario/gastos_datos.php",$("#form1").serialize(),

	function(resp){
		var json = eval("(" + resp + ")");
		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","limpiar(); ");
		else
			crear_dialog("Alerta",json.mensaje,"",""); //reload
	});
}

function guardarProveedor()
{
	if($("#nombre").val()=="")

	{
		crear_dialog("Alerta","Indique el nombre del Proveedor","nombre");
		return false;
	}
	if($("#direccion").val()=="")
	{
		crear_dialog("Alerta","Indique la direccion del Proveedor","direccion");
		return false;
	}

	if($("#rif").val()=="")
	{
		crear_dialog("Alerta","Indique el RIF del Proveedor","rif");
		return false;
	}

	if($("#telefono").val()=="")

	{
		crear_dialog("Alerta","Indique el telefono del Proveedor","telefono");
		return false;
	}


	if($("#email").val()=="")
	{
		crear_dialog("Alerta","Indique el email del Proveedor","email");
		return false;
	}


	var accion="";

	$("#accion").val("guardarProveedor");

	$.post("inventario/gastos_datos.php",$("#form1").serialize(),
	function(resp){
		var json = eval("(" + resp + ")");
		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","limpiar(); ");
		else
			crear_dialog("Alerta",json.mensaje,"",""); //reload
	});
}



function eliProd(idProd)

{

	$.post("inventario/gastos_datos.php",{

		"accion":"eliminarProd",

		"idProdEli":idProd

	},

	function(resp){

		var json = eval("(" + resp + ")");



		if(json.result==true)

			crear_dialog("Alerta",json.mensaje,"","verFacturas(); limpiar();");

		else

			crear_dialog("Alerta",json.mensaje,"","reload");

	});	

}
/*
function verificaCodigo()

{
	Cod = $("#cod_prod").val()
	if(Cod!="")
	{
		$.post("inventario/gastos_datos.php",
			{
				"accion":"verificaCodigo",
				"filCod":Cod
			},
			function(resp){
				var json = eval("(" + resp + ")");
				if(json.result==true)
				{
				$("#producto_buscado").css("display","inline")
				$("#producto_buscado").html("");
				return false;

				}else{
					$("#producto_buscado").css("display","inline")
					$("#producto_buscado").html("Producto no encontrado. <a data-toggle='modal' data-target='#myModal' style='cursor: pointer;'>Registrar Producto</a>");
				}

			});

	}

}
*/
function agregar(){
	if($("#cant_prod").val()==""){
		crear_dialog("Alerta","Indique la cantidad del producto","cant_prod_");
		return false;
	}
	if($("#prec_prod").val()==""){
		crear_dialog("Alerta","Indique el precio del producto","prec_prod");
		return false;
	}
	if($("#idgrupogasto").val()==""){
		crear_dialog("Alerta","Indique el Grupo Gasto","idgrupogasto");
		return false;
	}	
	grupo_gastos = $("#idgrupogasto").val();
	prec = $("#prec_prod").val();
	res = prec.replace(".","");
	res = res.replace(",",".");
	precio = parseFloat(res);
	cantidad = parseFloat($("#cant_prod").val());
	iva_producto = precio*0.12;
	iva = (precio*cantidad)*0.12;
	islr = (precio*cantidad)*0.03;
	total_producto = (cantidad*precio)+iva;
	if($("[name='grupo_gastos[]']").val()=="" || $("[name='grupo_gastos[]']").val()==undefined){
		$("#productos").html("<tr id='fila_1'><td><input type='hidden' id='' name='grupo_gastos[]' value='"+grupo_gastos+"'><input type='hidden' id='' name='concepto[]' value='"+$("#nomb_prod").val()+"'>"+$("#nomb_prod").val()+"</td><td><input type='hidden' id='cantidad_prod_1' name='cantidad_prod[]' value='"+$("#cant_prod").val()+"'>"+$("#cant_prod").val()+"</td><td><input type='hidden' id='precio_prod_1' name='precio_prod[]' value='"+precio+"'>"+$("#prec_prod").val()+"</td><td>"+number_format(iva,2,".",",")+"</td><td>"+number_format(total_producto,2,".",",")+"</td><td><i onclick='eliminar(1)' class='fa fa-times' style='cursor: pointer; color: red; font-size: 25px;'></i></td></tr>");
	}else{
		var n = $("#productos tr").length+1;
		$("#productos").append("<tr id='fila_"+n+"'><td><input type='hidden' id='' name='grupo_gastos[]' value='"+grupo_gastos+"'><input type='hidden' id='' name='concepto[]' value='"+$("#nomb_prod").val()+"'>"+$("#nomb_prod").val()+"</td><td><input type='hidden' id='cantidad_prod"+n+"' name='cantidad_prod[]' value='"+$("#cant_prod").val()+"'>"+$("#cant_prod").val()+"</td><td><input type='hidden' id='precio_prod_"+n+"' name='precio_prod[]' value='"+precio+"'>"+$("#prec_prod").val()+"</td><td>"+number_format(iva,2,".",",")+"</td><td>"+number_format(total_producto,2,".",",")+"</td><td><i class='fa fa-times' onclick='eliminar("+n+")' style='cursor: pointer; color: red; font-size: 25px;'></i></td></tr>");		
	}
	if($("#sub_total").val()=="")
		sub_total = 0;
	sub_total = parseFloat(sub_total)+(cantidad*precio);
	if($("#total_impuesto").val()=="")
		total_iva = 0;
	total_iva = parseFloat(total_iva) + iva;
	if($("#total_impuesto").val()=="")
		total_islr = 0;
	total_islr = parseFloat(total_islr) + islr;
	$("#sub_total").val(number_format(sub_total,2,",","."));
	$("#total_impuesto").val(number_format(total_iva,2,",","."));
	$("#retencion_islr").val(number_format(total_islr,2,",","."));
	$("#retencion_iva").val(number_format(total_iva,2,",","."));
	$("#total_general").val(number_format((sub_total+total_iva),2,",","."));
	$("#nomb_prod").val("");
	$("#cant_prod").val("");
	$("#prec_prod").val("");
	//$("#cod_prod").val("");
}

function eliminar(n){
	if(n>=1){
		prec = $("#precio_prod_"+n).val();
		res = prec.replace(".","");
		res = res.replace(",",".");
		precio = parseFloat(res);
		cantidad = parseFloat($("#cantidad_prod_"+n).val());
		iva = (precio*cantidad)*0.12;
		islr = (precio*cantidad)*0.03;
		if($("#sub_total").val()=="")
			sub_total = 0;
		sub_total = parseFloat(sub_total)-(parseFloat($("#cantidad_prod_"+n).val())*parseFloat($("#precio_prod_"+n).val()));
		if($("#total_impuesto").val()=="")
			total_iva = 0;
		total_iva = parseFloat(total_iva) - iva;
		if($("#total_impuesto").val()=="")
			total_islr = 0;
		total_islr = parseFloat(total_islr) - islr;
		$("#sub_total").val(sub_total);
		$("#total_impuesto").val(total_iva);
		$("#retencion_islr").val(total_islr);
		$("#total_general").val(sub_total+total_iva);
		$("#fila_"+n).remove();
		if($("#productos tr").length == 0)
			$("#productos").html("<tr><td colspan='6' style='text-align: center;'><b>No hay productos cargados</b></td></tr>");
	}
	else{
		$("#fila_"+n).remove();
		$("#sub_total").val(0);
		$("#total_impuesto").val(0);
		$("#retencion_islr").val(0);
		$("#retencion_iva").val(0);
		$("#total_general").val(0);
	}
}

/*
function modProd(idproducto,idProd,codProd,nomProd,descProd,precProd,cantProd,idCat,txtCategoria,idSubCat,f_rec,f_lot,idalmacen)

{

	$("#idproducto").val(idproducto);

	$("#idProdMod").val(idProd);

	$("#cod_prod").val(codProd);

	$("#nom_prod").val(nomProd);

	$("#desc_prod").val(descProd);

	$("#presentacion").val(cant_presentacion);

	$("#idpresentacion").val(idpresentacion);

	$("#prec_prod").val(number_format(precProd,2,",","."));

	$("#cant_prod").val(cantProd);

	$(".categoria").selectpicker("val",idCat);

	buscaSubCategoria(idCat,idSubCat);

	$("#f_rec").val(f_rec);

	$("#f_lot").val(f_lot);

	$('.almacen').selectpicker("val",idalmacen);

}

*/

function buscaSubCategoria(idCategoria,idSubCat)

{

	if(idCategoria!="")

	{

		$("#divSubCat").load("inventario/gastos_datos.php",

			{

				"accion":"buscaSubCategorias",

				"idCat":idCategoria,

				"idSubCat_Fil":idSubCat

			});

	}

}



function verFacturas()

{

	$("#divDatos").load("inventario/gastos_datos.php",{

		"accion":"verFacturas"

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

	    verFacturas();

	}, 0);

}



function limpiar_fil()

{

	$("#fil_codigo,#fil_nombre").val("");

}



function pag(num)

{

	var accion="verFacturas";

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



	$("#divDatos").load("inventario/gastos_datos.php",{accion:accion,pg:pg},function()

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



	$("#divDatos").load("inventario/gastos_datos.php",

	{

		"accion":"verFacturas",

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

</script>