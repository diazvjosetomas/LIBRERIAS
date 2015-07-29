<?

/*	

	CHULETA PARA LOS INPUTS: 



input_hidden("$id","$value");

label("$label","$ancho","$contenido");

input_text("$label","$id","$ancho","$tipo","$onclick","$onblur","$attr","$type");

input_textarea("$label","$id","$ancho","$onclick","$onblur","$attr","$height");

input_numero("$label","$id","$ancho","$onclick","$onblur","$attr");

input_monto("$label","$id","$ancho","$onclick","$onblur","$attr");

input_fecha("$label","$id","$ancho","$fecha","$onclick","$onblur","$attr");

select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options","$selected","$class");

input_check("$label","$id","$ancho","$value","$onclick","$onchange","$onblur","$attr","$class")



*/

?>

<div class="container" style="padding-top:0px;">		

		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">

			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Reporte de Movimientos</div>

			<div class="panel-body">

				<div class="form-group" style="margin: 1px;">

					<i class="fa fa-check-circle"></i> <em>Filtros de b&uacute;squeda</em>

					<hr style="margin-top:11px;width:85%;float:right;"></hr>

				</div>
			<div class="form-group">
				<label class="col-sm-4 control-label"><b>Almacen: </b></label>
					<div class="col-sm-6">
						<select class="form-control" id="idalmacen" name="idalmacen">
							<option></option>
							<?php
								$sql = "SELECT * FROM almacen";
								$result=mysqli_query($enlace,$sql);
								while($rs=mysqli_fetch_array($result)){
							?>
								<option value="<?= $rs["idalmacen"] ?>"><?= $rs["nombre"] ?></option>
							<?php
								}
							?>
						</select>					
					</div>

			</div>

				<div class="form-group" style="text-align:center;margin-top:15px;">

				    <button type="button" class="btn btn-success" onclick="buscaPacientes()">Buscar</button>

				    <button type="button" class="btn btn-default" onclick="limpiar()">Limpiar</button>

				</div>

			</div>

		</div>

</div>

<div id="divDatos" style="margin:0 auto;max-height:600px;over-flow:auto;"></div>



<script type="text/javascript">

function buscaPacientes()

{

	var idalmacen   =$("#idalmacen").val();

	var nombre_producto =$("#nom_prod").val();

	$("#divDatos").load("inventario/r_movimiento_datos.php",{

		"accion":"verInventario",

		"nombre_producto":nombre_producto,

		"idalmacen":idalmacen

	});

}



function limpiar()

{

	$("form")[0].reset();

}

</script>