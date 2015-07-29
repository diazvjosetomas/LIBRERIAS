<?
if(file_exists("../funcionesphp/seguridad.php"))
	include("../funcionesphp/seguridad.php");
else
	include("funcionesphp/seguridad.php");
antiChismoso();
?>
<input type="hidden" id="idCatMod" name="idCatMod" value="">
<div class="container" style="padding-top:0px;">		
		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Configuraci&oacute;n de categor&iacute;as y Sub-categor&iacute;as</div>
			<div class="panel-body">
			    <div class="form-group">
			    <input type="hidden" id="total" name="total" value="0">
		            <label class="col-sm-3 control-label"><b>Categor&iacute;a:</b></label>
		            <div class="col-sm-5">
		            	<input class="form-control" type="text" id="cate" name="cate" placeholder="">
		            </div>
                </div>
                <div class="form-group">
		            <label class="col-sm-3 control-label"><b>Sub-categor&iacute;as:</b></label>
		            <div class="col-sm-5">
						<textarea class="form-control" id="subcat" name="subcat" placeholder="Ej: Sub1,Sub2,Sub3"></textarea>
					</div>
	          	</div>
	      			<div style="text-align:center;">
						<em>Ingrese sub-categor&iacute;as separadas por comas (",")</em>
					</div>
				<div class="form-group" style="text-align:center;margin-top:15px;">
				    <button type="button" class="btn btn-success" onclick="guardaCategorias();"><i id="spin_bt" style="display:none;" class="fa fa-spinner fa-spin"></i> Guardar</button>
				    <button type="button" class="btn btn-default" onclick="limpiar();"><i class="fa fa-eraser"></i> Limpiar</button>
				</div>
			</div>
		</div>
</div>
<div id="divDatos" style="margin:0 auto;"></div>

<script type="text/javascript">
//===> Al parecer la pag carga muy rapido y no toma esta funcion si no la pones a que espere un momento...
setTimeout(function() {
    $('#cate').focus();
    verCategorias();
}, 0);

function guardaCategorias()
{
	if($("#cate").val()=="")
	{
		crear_dialog("Alerta","Indique el nombre de la categor&iacute;a","cate");
		return false;		
	}
	if($("#subcat").val()=="")
	{
		crear_dialog("Alerta","Indique al menos una sub-categor&iacute;a","subcat");
		return false;		
	}

	var accion="";

	if($("#idCatMod").val()!="")
		accion="modificarCat";
	else
		accion="guardarCategoria";

	var categoria =$("#cate").val();
	var subcateg  =$("#subcat").val();
	var idCatMod  =$("#idCatMod").val();

	$.post("inventario/config_categorias_datos.php",{
		"accion":accion,
		"categoria":categoria,
		"subcateg":subcateg,
		"idCatMod":idCatMod
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verCategorias(); limpiar(); ");
		else
			crear_dialog("Alerta",json.mensaje,"","reload");
	});
}

function verCategorias()
{
	$("#divDatos").load("inventario/config_categorias_datos.php",{
		"accion":"verCategorias"
	});
}

function modCat(idCatMod,categorie,subcats)
{
	$("#idCatMod").val(idCatMod);
	$("#cate").val(categorie);
	$("#subcat").val(subcats);
}

function eliCat(idCatEli)
{
	$.post("inventario/config_categorias_datos.php",{
		"accion":"eliminarCat",
		"idCatEli":idCatEli
	},
	function(resp){
		var json = eval("(" + resp + ")");

		if(json.result==true)
			crear_dialog("Alerta",json.mensaje,"","verCategorias(); limpiar(); ");
		else
			crear_dialog("Alerta",json.mensaje,"","reload");
	});	
}

function abreSubCat(Cat,subCat)
{
	crear_dialog("Sub-categor&iacute;as de "+Cat,subCat,"","");
}

function limpiar()
{
	$("form")[0].reset();
	$("#idCatMod").val("");

	setTimeout(function() {
	    $('#cate').focus();
	    verCategorias();
	}, 0);
}

</script>