<?

/**
* @Descripcion: Hidden => input_hidden("$id","$value")
* @param $id, $value
* @return input Hidden
*/

function input_hidden($id="",$value="")
{
	$input="";
	$input.="<input type='hidden' id='$id' name='$id' value='$value'>";
	echo $input;
}

/**
* @Descripcion: Input texto normal => label("$label","$id","$ancho","$contenido");
* @param $label, $id, $ancho, $contenido
* @return input label
*/

function label($label="label",$id="",$ancho="3",$contenido="")
{
	if($ancho=="")
		$ancho=3;

	$input="";
	$input.="<div class='form-group'>";
	$input.="	<label class='col-sm-4 control-label'>$label</label>";
	$input.="	<div class='col-sm-$ancho'>";
	$input.="		<label id='$id' class='control-label'>".$contenido."</label>";
	$input.="	</div>";
	$input.="</div>";
	echo $input;
}



/**
* @Descripcion: Input texto normal + clase => label("$label","$id","$ancho","$contenido");
* usada para agregar labels que contienen iconos creados con font-awesome
* @param $label, $id, $ancho, $contenido
* @return input label
*/

function label_class($label="label",$id="",$ancho="3",$contenido="")
{
	if($ancho=="")
	   $ancho=3;

	$input="";
	$input.="<div class='form-group'>";
	$input.="	<label class='col-sm-4 control-label'>$label</label>";
	$input.="	<div class='col-sm-$ancho'>";
	$input.="		<label id='$id' class='control-label'>".$contenido."$class</label>";
	$input.="	</div>";
	$input.="</div>";
	echo $input;
}







/**
* @Descripcion: Input texto normal => input_text("$label","$id","$ancho",$tipo,"$onclick","$onblur","$attr","$type","$ancholabel");
* @param $label, $id, $onclick, $onblur, $attr
* @return input text
*/

function input_text($label="label",$id="",$ancho="3",$tipo="1",$onclick="",$onblur="",$attr="",$type="",$ancholabel="")
{
	if($ancholabel=="")
		$ancholabel=4;

	if($ancho=="")
		$ancho=3;

	if($tipo==1) //===> Solo letras
		$onkeypress="onkeypress='return soloLetras(event)'";	
	elseif($tipo==2) //===> Solo numeros
		$onkeypress="onkeypress='return soloNumeros();'";
	else 			//===> Ambos
		$onkeypress="";

	if($type=="")
		$type="text";

	$input="";
	$input.="<div class='form-group'>";
	$input.="	<label class='col-sm-$ancholabel control-label'>$label</label>";
	$input.="	<div class='col-sm-$ancho'>";
	$input.="		<input class='form-control' type='$type' id='$id' name='$id' onclick='$onclick' onblur='$onblur' $onkeypress $attr >";
	$input.="	</div>";
	$input.="</div>";
	echo $input;
}

/**
* @Descripcion: Input textarea => input_textarea("$label","$id","$ancho","$onclick","$onblur","$attr","$height");
* @param $label, $id, $onclick, $onblur, $attr
* @return input textarea
*/

function input_textarea($label="label",$id="",$ancho="3",$onclick="",$onblur="",$attr="",$height="")
{
	if($ancho=="")
		$ancho=3;

	if($height!="")
		$alto="style='height:$height;'";
	else
		$alto="";

	$input="";
	$input.="<div class='form-group'>";
	$input.="	<label class='col-sm-4 control-label'>$label</label>";
	$input.="	<div class='col-sm-".$ancho."'>";
	$input.="		<textarea class='form-control' $alto id='$id' col='16' name='$id' onclick='$onclick' onblur='$onblur' $attr ></textarea>";
	$input.="	</div>";
	$input.="</div>";
	echo $input;
}

/**
* @Descripcion: Input texto numero => input_numero("$label","$id","$onclick","$onblur","$attr");
* @param $label, $id, $onclick, $onblur, $attr
* @return input numero
*/

function input_numero($label="label",$id="",$ancho="3",$onclick="",$onblur="",$attr="")
{
	if($ancho=="")
		$ancho=3;

	$input="";
	$input.="<div class='form-group'>";
	$input.="	<label class='col-sm-4 control-label'>$label</label>";
	$input.="	<div class='col-sm-".$ancho."'>";
	$input.="		<input class='form-control' type='text' id='$id' name='$id' onclick='$onclick' onblur='$onblur' onkeyup='this.value=ValidaNumero(event,this)' onkeypress='return soloNumeros();' $attr >";
	$input.="	</div>";
	$input.="</div>";
	echo $input;
}

/**
* @Descripcion: Input texto monto => input_monto("$label","$id","$ancho","$onclick","$onblur","$attr")
* @param $label, $id, $onclick, $onblur, $attr
* @return input monto
*/

function input_monto($label="label",$id="",$ancho="3",$onclick="",$onblur="",$attr="")
{
	if($ancho=="")
		$ancho=3;

	$input="";
	$input.="<div class='form-group'>";
	$input.="	<label class='col-sm-4 control-label'>$label</label>";
	$input.="	<div class='col-sm-".$ancho."'>";
	$input.=" 		<div class='input-group'>";
	$input.="			<span class='input-group-addon'><i style='font-weight:bold;'>Bs.F.</i></span>";
	$input.="			<input class='form-control monto' type='text' id='$id' name='$id' onclick='$onclick' onblur='$onblur' onkeyup='this.value=ValidaNumero(event,this);' $attr >";
	$input.="		</div>";	
	$input.="	</div>";
	$input.="</div>";
	echo $input;
}

/**
* @Descripcion: Input fecha => input_fecha("$label","$id","$ancho","$fecha","$onclick","$onblur","$attr");
* @param $label, $id, $onclick, $onblur, $attr
* @return input fecha
*/

function input_fecha($label="label",$id="",$ancho="2",$fecha="",$onclick="",$onblur="",$attr="")
{
	if($ancho=="")
		$ancho=2;

	if($fecha=="")
		$fecha=date("d/m/Y");

	$input="";
	$input.="<div class='form-group'>";
	$input.="	<label class='col-sm-4 control-label'>$label</label>";
	$input.="	<div class='col-sm-".$ancho."'>";
	$input.=" 		<div class='input-group'>";
	$input.="		<span class='input-group-addon'><i class='fa fa-calendar fa-fw'></i></span>";
	$input.="			<input class='form-control fecha' type='text' id='$id' name='$id' maxlength='10' onclick='$onclick' onblur='$onblur' onkeyup='mascara(this,\"/\",patron,true);' value='$fecha' $attr >";
	$input.="		</div>";
	$input.="	</div>";
	$input.="</div>";
	echo $input;
}

/**
* @Descripcion: Select => select("$label","$id","$ancho","$onchange","$tabla","$where","$idvalue","$campotexto","$onclick","$onblur","$attr","$options","$selected",$class="");
* options = "value;text,value;text"
* @param $label, $id, $onclick, $onblur, $attr
* @return input fecha
*/

function select($label="label",$id="",$ancho="3",$onchange="",$tabla="",$where="",$idvalue="",$campotexto="",$onclick="",$onblur="",$attr="",$options="",$selected="",$class="",$ancholabel="")
{
	include("conex.php");

	if($ancholabel=="")
		$ancholabel=4;

	if($ancho=="")
		$ancho=6;

	if($where=="")
		$where="";
	elseif($where!="")
		$where=" WHERE ".$where;

	$input="";
	$input.="<div class='form-group'>";
	$input.="	<label class='col-sm-$ancholabel control-label'>$label</label>";
	$input.="	<div class='col-sm-".$ancho."'>";
	$input.="			<select class='selectpicker $class' id='$id' name='$id' data-live-search='true' onchange='$onchange' $attr>";
	$input.="			<option value=''>SELECCIONE: </option>";
	if($options==""){
		if($tabla!=""){
						$sql = "SELECT * FROM $tabla $where";
						//echo $sql; die();
						$result=mysqli_query($enlace,$sql);
						while($rs=mysqli_fetch_assoc($result))
						{
							if($selected!="")
							{
								if($rs[$idvalue]==$selected)
									$sel="selected='selected'";
								else
									$sel="";
							}
							$input.="<option $sel value='".$rs[$idvalue]."'>".utf8_encode($rs[$campotexto])."</option>";
						}
		}
	}
	else{
		$opciones = split(",", $options);
		foreach ($opciones as $key => $value) {
			$values   = split(";", $value);
			$input.="<option value='".$values[0]."'>".$values[1]."</option>";
		}
	}
	$input.="  			</select>";
	$input.="	</div>";
	$input.="</div>";
	echo $input;
}

/**
* @Descripcion: Input check => input_check("$label","label","$id","$ancho","$value","$onclick","$onchange","$onblur","$attr","$class")
* @param $label, $id, $ancho, $value, $onclick, $onchange, $onblur, $attr
* @return input check
*/

function input_check($label="label",$id="",$ancho="2",$value="",$onclick="",$onchange="",$onblur="",$attr="",$class="")
{
	if($ancho=="")
		$ancho=2;

	$input="";
	$input.="<div class='form-group'>";
	$input.="	<label class='col-sm-4 control-label'>$label</label>";
	$input.="	<div class='col-sm-".$ancho."'>";
	$input.="			<input class='checkbox $class' type='checkbox' id='$id' name='$id' onclick='$onclick' onblur='$onblur' onchange='$onchange' value='$value' $attr >";
	$input.="	</div>";
	$input.="</div>";
	echo $input;
}
?>