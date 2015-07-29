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
<div class="container" style="padding-top:0px;">		

		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Subir Factura</div>
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
						<?= input_fecha("<b>Fecha Vencimiento: </b>","fecha_vencimiento","$ancho",$fecha,"$onclick","$onblur","$attr"); ?>
					<div class="form-group">
						<label class="col-sm-4 control-label"><b>Observaciones: </b></label>
						<div class="col-sm-6">
							<textarea class="form-control" id="observacion" name="observacion"></textarea>
						</div>
					</div>
				</fieldset>
<!-- NUEVO PRODUCTO  -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4>Nuevo Producto</h4>
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
							<input class="form-control" type="text" id="nom_prod" name="nom_prod" onblur="mayusculas(this)">						
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><b>Descripci&oacute;n: </b></label>
					<div class="col-sm-8">
						<textarea class="form-control" type="text" id="desc_prod" name="desc_prod" style="height:120px;"></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><b>Presentaci&oacute;n: </b></label>
					<div class="col-sm-8">
						<div class="input-group">
							<input style="width:100px;" class="form-control" type="text" id="presentacion" name="presentacion" onkeypress="return soloNumeros();">
							<select style="width:250px;" class="form-control" id="idpresentacion" name="idpresentacion">
								<option value="">Seleccione presentaci&oacute;n:</option>
									<?php
									$sql = "SELECT * FROM presentacion_productos";
									$result=mysqli_query($enlace,$sql);
									while($rs=mysqli_fetch_assoc($result)){
										?>
										<option value="<?= $rs["id_presentacion"] ?>"><?= $rs["ab_presentacion"] ?></option>
										<?php
									}
									?>
							</select>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><b>Categor&iacute;a: </b></label>
						<div class="col-sm-8">
			                <select class="selectpicker categoria" id="idCat" name="idCat" data-live-search="true" onchange="buscaSubCategoria(this.value,'')">
			                    <option value=""></option>
			                                <?php
			                                include("funcionesphp/conex.php");
			                                $sql = mysqli_query($enlace,"SELECT * FROM categoria_productos") or die("Error: ".mysqli_error($enlace));
			                                while($rs=mysqli_fetch_assoc($sql)) 
			                                {
			                                ?>
			                                    <option value="<?= $rs["id_cat_prod"] ?>"><?=utf8_encode($rs["categoria"]);?></option>
			                                <?
			                                }
			                                ?>
			                </select>
						</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label"><b>Sub-Categor&iacute;a: </b></label>
						<div id="divSubCat" class="col-sm-5">
							Datos Sub-Categoria
						</div> 
	
				</div>	
				<div class="form-group">
					<label class="col-sm-4 control-label"><b>Compuesto: </b></label>
						<div class="col-sm-5">
							<select id="idcompuesto" name="idcompuesto" class="form-control">
								<option></option>
								<?php
								$sql 	= "SELECT * FROM compuesto";
								$result = mysqli_query($enlace,$sql);
								while ($rs = mysqli_fetch_assoc($result)) {
								?>
								<option value="<?= $rs["idcompuesto"] ?>"><?= $rs["compuesto"] ?></option>
								<?php
								}
								?>
							</select>
						</div><a onclick="abrir_ncompuesto()">+</a> 
				</div>	
				<div class="form-group" id="div_compuesto" style="display: none;">
					<label class="col-sm-4 control-label"><b>Nombre Compuesto: </b></label>
					<div class="col-sm-5">
						<input type="text" class="form-control" id="compuesto" name="compuesto">
						<button type="button" class="btn btn-default" onclick="guardarCompuesto()">Guardar</button>
					</div>
				</div> 
				<?= input_numero("<b>Alarma de Cant. Minima:</b>","cant_minima");	?>
				<?=input_check("Exento","exento","","","","if(this.checked){ this.value = 1; }else{ this.value = 0; }","")?>
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
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4>Nuevo Proveedor</h4>
	      </div>
	      <div class="modal-body">
			<?=input_text("Nombre","nombre","5","","","mayusculas(this)","")?>
			<?=input_textarea("Descripci&oacute;n","descripcion","5","$onclick","$onblur","$attr")?>
			<?=input_text("Nombre Contacto","contacto","5","","","","")?>
			<?=input_textarea("Direcci&oacute;n","direccion","5","$onclick","$onblur","$attr")?>
			<?=input_text("R.I.F","rif","5","","","","")?>
			<?=select("Tipo Persona","tipopersona","$ancho","$onchange","","","","","$onclick","$onblur","$attr","1;Persona Natural Residente,2;Persona Juridica Domiciliada,3;Persona natural no residente,4;Persona Juridica no domiciliada");?>
			<?=select("Tipo Retencion I.V.A.","tipo_retencion_iva","$ancho","$onchange","","","","","$onclick","$onblur","$attr","1;75%,2;100%");?>
			<?=input_check("Estatus","estatus","","","","if(this.checked){ this.value = 1; }else{ this.value = 0; }","")?>
			<?=select("Tipo","tipo","$ancho","$onchange","","","","","$onclick","$onblur","$attr","1;Nacional,2;Internacional");?>
			<?=input_text("Telefono","telefono","5","2","","","onkeyup='mascara(this,\"-\",patron4,true);'")?>
			<?=input_text("Telefono2","telefono2","5","2","","","onkeyup='mascara(this,\"-\",patron4,true);'")?>
			<?=input_text("Email","email","5","","","","")?>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
	        <button type="button" class="btn btn-primary" onclick="guardarProveedor()">Guardar Proveedor</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</div>
<div class="col-lg-6">
	<div class="panel panel-default">
		<div class="panel-body">					
	<?
	input_fecha("<b>Fecha recepci&oacute;n:</b>","f_rec","$ancho",$fecha,"$onclick","$onblur","$attr");
	?>
	<div class="form-group">
		<label class="col-sm-4 control-label"><b>Indica Lote:</b></label>
			<div class="col-sm-5">
				<input type="checkbox" id="indica_lote" onclick="mostrar_lote(this)">
			</div> 
	</div>
	<div class='form-group' id="div_lote" style="display: none;">
	<label class='col-sm-4 control-label'><b>Fecha lote:</b></label>
	<div class='col-sm-3'>
 		<div class='input-group'>
		<span class='input-group-addon'><i class='fa fa-calendar fa-fw'></i></span>
			<input class='form-control fecha' type='text' id='f_lot' name='f_lot' maxlength='10' onkeyup='mascara(this,\"/\",patron,true);' value='<?= date("d/m/Y") ?>'>
		</div>"
	</div>
	</div>
		</div>
	</div>
</div>
<div class="col-lg-6">
	<div class="panel panel-default" style="height: 286px;">
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
<!--<div class="col-lg-12">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-2 control-label"><b>Observaciones: </b></label>
				<div class="col-sm-4">
					<textarea class="form-control" id="observacion" name="observacion"></textarea>
				</div>
			</div>
			</div>
		</div>
</div>-->
<div class="col-lg-12">
	<div class="form-group" style="margin-top: 20px;">
		<label class="col-sm-2 control-label"><b>C&oacute;digo producto: </b></label>
			<div class="col-sm-3">
				<input class="form-control" type="text" id="cod_prod" name="cod_prod">						
			</div>
			<a class="btn btn-success" onclick="verificaCodigo(this.value)"><i class="fa fa-search"></i></a>
	</div>	
</div>
<div id="producto_buscado" style="display: none; padding: 10px; float: left;">
</div>
<table class="table table-bordered table-striped table-condensed table-responsive">
	<thead>
		<th>Producto</th>
		<th>Cantidad</th>
		<th>Precio Unitario</th>
		<th>IVA Total</th>
		<th>Total</th>
		<th></th>
	</thead>
	<tbody id="productos">
		<tr><td colspan="6" id="noproducts" style="text-align: center;"><b>No hay productos cargados</b></td></tr>
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

						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Facturas</div>

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

$('#cod_producto').on('keypress', function(e) {
    if (e.which == 32)
        return false;
});

function mostrar_lote(esto){
	if(esto.checked){
		$("#div_lote").css("display","inline");
		$("#f_lot").val("<?= date("d/m/Y") ?>");
	}else{
		$("#div_lote").css("display","none");
		$("#f_lot").val("");
	}
}

function abrir_ncompuesto(){
	if($("#div_compuesto").css("display")=="none"){
		$("#div_compuesto").css("display","inline");
	}else{
		$("#div_compuesto").css("display","none");
	}
}

function guardarFactura()
{
	if($("#productos #noproducts").length>0)
	{
		crear_dialog("Alerta","Agregue uno o mas productos a la factura","cod_prod");
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

	if($("#fecha_vencimiento").val()=="")

	{

		crear_dialog("Alerta","Indique la fecha de vencimiento","fecha_vencimiento");

		return false;

	}

	if($("#id_prod").val()=="")

	{

		crear_dialog("Alerta","Seleccione la categor&iacute;a del producto","id_prod");

		return false;

	}

	if($("#id_sub_cat").val()=="")

	{

		crear_dialog("Alerta","Seleccione la sub-categor&iacute;a del producto","id_prod");

		return false;

	}	

	if($("#f_rec").val()=="")

	{

		crear_dialog("Alerta","Seleccione la fecha de recepci&oacute;n","f_rec");

		return false;		

	}

	if($("#f_lot").val()=="")
	{
		crear_dialog("Alerta","Seleccione la fecha del lote","f_lot");
		return false;
	}

	total = $("#total_general").val();
	res = total.replace(".","");
	res = res.replace(",",".");
	total_general = parseFloat(res);
	$("#total_general").val(total_general)

	$("#accion").val("guardarFactura");

	$.post("inventario/productos_datos.php",$("#form1").serialize(),
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

	$.post("inventario/productos_datos.php",$("#form1").serialize(),

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
	if($("#tipo_retencion_iva").val()=="")

	{
		crear_dialog("Alerta","Indique el tipo de retencion del IVA","tipo_retencion_iva");
		return false;
	}


	if($("#email").val()=="")
	{
		crear_dialog("Alerta","Indique el email del Proveedor","email");
		return false;
	}


	var accion="";

	$("#accion").val("guardarProveedor");

	$.post("inventario/productos_datos.php",$("#form1").serialize(),
	function(resp){
		var json = eval("(" + resp + ")");
		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","limpiar(); ");
		else
			crear_dialog("Alerta",json.mensaje,"",""); //reload
	});
}


function guardarCompuesto()
{
	if($("#compuesto").val()=="")

	{
		crear_dialog("Alerta","Indique el nombre del Compuesto","compuesto");
		return false;
	}

	var accion="";

	$("#accion").val("guardarCompuesto");

	$.post("inventario/productos_datos.php",$("#form1").serialize(),
	function(resp){
		var json = eval("(" + resp + ")");
		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","limpiar_compuesto()");
		else
			crear_dialog("Alerta",json.mensaje,"",""); //reload
	});
}

function limpiar_compuesto(){
	$("#compuesto").val("");
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

			crear_dialog("Alerta",json.mensaje,"","verFacturas(); limpiar();");

		else

			crear_dialog("Alerta",json.mensaje,"","reload");

	});	

}

function verificaCodigo()

{
	Cod = $("#cod_prod").val()
	if(Cod!="")
	{
		$.post("inventario/productos_datos.php",
			{
				"accion":"verificaCodigo",
				"filCod":Cod
			},
			function(resp){
				var json = eval("(" + resp + ")");
				if(json.result==true)
				{	
					if(json.type==2){
						html = "<table class='tabla_producto'><tr><td><input type='hidden' id='tipo_prod' value='2' /><input type='hidden' id='idprod' value='"+json.id_prod+"' /><input type='text' class='form-control' id='nombre_producto' readonly value='"+json.nom_prod+"' /></td><td><input class='form-control' type='text' id='cant_prod' name='cant_prod' placeholder='Cantidad' onkeyup='this.value=ValidaNumero(event,this)' onkeypress='return soloNumeros();'></td><td><div class='input-group'><span class='input-group-addon'><i style='font-weight:bold;'>Bs.F.</i></span><input class='form-control' type='text' id='prec_prod' placeholder='Precio' name='prec_prod' onkeyup='this.value=ValidaNumero(event,this);' ></div></td><td><a class='btn btn-success' title='Agregar Producto' onclick='agregar("+json.id_prod+")'><i class='fa fa-plus'></i></a></td></tr></table>"
					}
					if(json.type==1){
						options = "<option></option>";
						$.each(json.data,function(k,v){
							options = options+"<option value='"+v.idproductoinventario+"' nombre='"+json.nom_prod+"' precio='"+v.prec_pro+"'>"+v.nom_prod+" "+v.prec_pro+"</option>";
						});
						options = options+"<option value='0' idproducto='"+json.id_prod+"' nombre='"+json.nom_prod+"'>"+json.nom_prod+" Otro Precio</option>";
						select = "<select onchange='cambia_producto()' id='idprod_inventario' name='idproductoinventario' class='form-control'>"+options+"</select>";
						html = "<table class='tabla_producto'><tr><td><input type='hidden' id='tipo_prod' value='1' />"+select+"</td><td><input class='form-control' type='text' id='cant_prod' name='cant_prod' placeholder='Cantidad' onkeyup='this.value=ValidaNumero(event,this)' onkeypress='return soloNumeros();'></td><td><div class='input-group'><span class='input-group-addon'><i style='font-weight:bold;'>Bs.F.</i></span><input class='form-control' type='text' id='prec_prod' placeholder='Precio' name='prec_prod' onkeyup='this.value=ValidaNumero(event,this);' ></div></td><td><a class='btn btn-success' title='Agregar Producto' onclick='agregar("+json.id_prod+")'><i class='fa fa-plus'></i></a></td></tr></table>";
					}
					$("#producto_buscado").css("display","inline")
					$("#producto_buscado").html(html);
					return false;

				}else{
					$("#producto_buscado").css("display","inline")
					$("#producto_buscado").html("Producto no encontrado. <a data-toggle='modal' data-target='#myModal' style='cursor: pointer;'>Registrar Producto</a>");
				}

			});

	}

}

function cambia_producto(){
	if($("#idprod_inventario").val()==0){
		$("#prec_prod").removeAttr("readonly");
		$("#prec_prod").val("");
		$("#tipo_prod").val(3);
	}else{
		$("#prec_prod").attr("readonly","readonly");
		precio = $('#idprod_inventario option:selected').attr('precio');
		$("#prec_prod").val(precio);
		$("#tipo_prod").val(1);
	}
}

function agregar(idprod){
var values = [];
$(".idproducto").each(function() {
    values.push($(this).val());
});
vals = values.toString();
res = vals.match(idprod);

if(res!=null){
	crear_dialog("Alerta","No puede ingresar el mismo producto 2 veces.","");
	return false;
}
	if($("#cant_prod").val()==""){
		crear_dialog("Alerta","Indique la cantidad del producto","cant_prod");
		return false;
	}
	if($("#prec_prod").val()==""){
		crear_dialog("Alerta","Indique el precio del producto","prec_prod");
		return false;
	}

	tipo = $("#tipo_prod").val();
	prec = $("#prec_prod").val();
	res = prec.replace(".","");
	res = res.replace(",",".");
	precio = parseFloat(res);
	cantidad = parseFloat($("#cant_prod").val());
	iva_producto = precio*0.12;
	iva = (precio*cantidad)*0.12;
	islr = (precio*cantidad)*0.03;
	total_producto = (cantidad*precio)+iva;
	if(tipo==1){
		idprod = $("#idprod_inventario").val();
		nombrecampo = "idproductoinventario";
		nombreproducto = $("#idprod_inventario option:selected").attr("nombre");
	}
	if(tipo==2){
		idprod = $("#idprod").val();
		nombrecampo = "idproducto";
		nombreproducto = $("#nombre_producto").val();
	}
	if(tipo==3){
		idprod = $("#idprod_inventario").val();
		nombrecampo = "idproducto";
		nombreproducto = $("#idprod_inventario option:selected").attr("nombre");
	}
	if($("#productos #noproducts").length==0){
		$("#productos").append("<tr id='fila_"+idprod+"'><td><input type='hidden' class='idproducto' id='idproducto' name='"+nombrecampo+"[]' value='"+idprod+"'>"+nombreproducto+"</td><td><input type='hidden' id='cantidad_prod_"+idprod+"' name='cantidad_prod_"+idprod+"' value='"+$("#cant_prod").val()+"'>"+$("#cant_prod").val()+"</td><td><input type='hidden' id='precio_prod_"+idprod+"' name='precio_prod_"+idprod+"' value='"+precio+"'>"+$("#prec_prod").val()+"</td><td>"+number_format(iva,2,".",",")+"</td><td>"+number_format(total_producto,2,".",",")+"</td><td><i class='fa fa-times' onclick='eliminar("+idprod+")' style='cursor: pointer; color: red; font-size: 25px;'></i></td></tr>");		
	}
	else{
		$("#productos").html("<tr id='fila_"+idprod+"'><td><input type='hidden' class='idproducto' id='idproducto' name='"+nombrecampo+"[]' value='"+idprod+"'>"+nombreproducto+"</td><td><input type='hidden' id='cantidad_prod_"+idprod+"' name='cantidad_prod_"+idprod+"' value='"+$("#cant_prod").val()+"'>"+$("#cant_prod").val()+"</td><td><input type='hidden' id='precio_prod_"+idprod+"' name='precio_prod_"+idprod+"' value='"+precio+"'>"+$("#prec_prod").val()+"</td><td>"+number_format(iva,2,".",",")+"</td><td>"+number_format(total_producto,2,".",",")+"</td><td><i onclick='eliminar("+idprod+")' class='fa fa-times' style='cursor: pointer; color: red; font-size: 25px;'></i></td></tr>");
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
	$("#producto_buscado").html("");
	$("#producto_buscado").css("display","none");
	$("#cod_prod").val("");
}

function eliminar(idprod){
	if($("#cant_prod_"+idprod).val()=="")
		prec = $("#precio_prod_"+idprod).val();
		res = prec.replace(".","");
		res = res.replace(",",".");
		precio = parseFloat(res);
		cantidad = parseFloat($("#cantidad_prod_"+idprod).val());
		iva = (precio*cantidad)*0.12;
		islr = (precio*cantidad)*0.03;
		if($("#sub_total").val()=="")
			sub_total = 0;
		sub_total = parseFloat(sub_total)-(parseFloat($("#cantidad_prod_"+idprod).val())*parseFloat($("#precio_prod_"+idprod).val()));
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
		$("#fila_"+idprod).remove();
		if($("#productos tr").length == 0)
			$("#productos").html("<tr><td colspan='6' id='noproducts' style='text-align: center;'><b>No hay productos cargados</b></td></tr>");
	else
		$("#sub_total").val(0);
		$("#total_impuesto").val(0);
		$("#retencion_islr").val(0);
		$("#retencion_iva").val(0);
		$("#total_general").val(0);
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

		$("#divSubCat").load("inventario/productos_datos.php",

			{

				"accion":"buscaSubCategorias",

				"idCat":idCategoria,

				"idSubCat_Fil":idSubCat

			});

	}

}



function verFacturas()

{

	$("#divDatos").load("inventario/productos_datos.php",{

		"accion":"verFacturas"

	});

}



function limpiar()

{

	$("form")[0].reset();

	$("#idProdMod").val("");

	$(".selectpicker").selectpicker("val",0);

	$("#divSubCat").html("");

	$("#productos").html("<tr><td colspan='6' id='noproducts' style='text-align: center;'><b>No hay productos cargados</b></td></tr>");

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



	$("#divDatos").load("inventario/productos_datos.php",{accion:accion,pg:pg},function()

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