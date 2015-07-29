<?php
include("conex.php");
include("funciones_form.php");
//=>Deshabilitar alertas
error_reporting (E_ALL & ~E_NOTICE & ~E_DEPRECATED);

//=> Zona horaria Caracas
date_default_timezone_set('America/Caracas'); 

/*Convierte la fecha normal a fecha MySQL 'Y-m-d' */
function ConvFecha($fecha)
{
	$fec=explode("/", $fecha);
	$fechafinal=$fec[2]."-".$fec[1]."-".$fec[0];
	return $fechafinal;
}

/*Convierte la fecha formato Mysql a fecha normal 'd/m/Y' */
function DevuelveFecha($fecha)
{
	$fec=explode("-", $fecha);
	$fechafinal=$fec[2]."/".$fec[1]."/".$fec[0];
	return $fechafinal;
}

/*Convierte un TIMESTAMP formato Mysql a fecha normal 'd/m/Y H:i:s' */
function DevuelveFechaTimeStamp($fecha){
	$fec=explode(" ", $fecha);
	$fec1=explode("-", $fec[0]);
	$fechafinal=$fec1[2]."/".$fec1[1]."/".$fec1[0];
	return $fechafinal." ".$fec[1];
}

function compara_fechas($fecha1,$fecha2) 
{ 
     if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1)) 
           list($dia1,$mes1,$año1)=explode("/",$fecha1); 
     if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1)) 
           list($dia1,$mes1,$año1)=explode("-",$fecha1); 
     if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2)) 
           list($dia2,$mes2,$año2)=explode("/",$fecha2); 
     if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2)) 
            list($dia2,$mes2,$año2)=explode("-",$fecha2); 
     $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2); 

/*     if($dif<0)
     {
     	return "Fecha 1 menor que fecha 2";
     }
     else if($dif>0)
     {
     	return "fecha 1 mayor a fecha 2";
     }
     else
     {
     	return "Fechas iguales";
     }
*/     
    return ($dif);                          
}

// Formato: dd-mm-yy
function calcular_edad($sep,$fecha)
{
	$dias = explode($sep, $fecha, 3);
	$dias = mktime(0,0,0,$dias[1],$dias[0],$dias[2]);
	$edad = (int)((time()-$dias)/31556926 );
	return $edad;
}

function ConvierteNombreArchivo($archivo)
{
	 if (gettype($archivo)=="array")
	 {
		 $_FILES['archivo']=$archivo;
		 
		 $nombretemp=$_FILES['archivo']['tmp_name'];
		 $nombref=$_FILES['archivo']['name'];
		 $nombre=explode(".",$_FILES['archivo']['name']);
		 $Nombrefinal=str_replace(" ","_",trim($nombre[0]));
		 $extension=$nombre[1];
		 
		 $NombreArchivo=EliminarAcentos($Nombrefinal."_".date("dmY")."_".date("h").".".$extension);
		 return $NombreArchivo;
	 }
	 else
	 {
		$mensaje="<script language=\"javascript\">alert(\"No es un arreglo\");</script>";
		return $mensaje;			
	 }
}
 
/**
* @Descripcion: Elimina los acentos de una cadena
* @param $string
* @return $string sin acentos
*/

function limpiaString($String)
{
	$String=str_replace(array("'","\""),array("",""),$String);
	$String=utf8_decode($String);
	return $String;
}

/**
* @Descripcion: Elimina los acentos de una cadena
* @param $string
* @return $string sin acentos
*/

function EliminarAcentos($String)
{
$tofind = "ÀÁÂÄÅàáâäÒÓÔÖòóôöÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚÛÜùúûüÿÑñ";
$replac = "AAAAAaaaaOOOOooooEEEEeeeeCcIIIIiiiiUUUUuuuuyNn";
return utf8_encode(strtr(utf8_decode($String), utf8_decode($tofind),$replac));
}

/**
* @Descripcion: Prevee una inyeccion SQL
* @param $cadena
* @return $cadena limpia 
*/

function antinyeccion($cadena)
{
	$cadena=str_replace(array("'","\""," AND "," and "," OR "," or "), array("","","","","",""), $cadena);
	return $cadena;
}

/**
* @Descripcion Funcion para enviar correo simple
* @param $cadena tipo array
*/
function enviar_correo($cadena) 
{
	if (gettype($cadena) == "array") {
		// ----------------------------------------------------------------------
		// Correo
		// ----------------------------------------------------------------------
		$nombre   = $datos_correo['nombre'];
		$correo   = $datos_correo['correo'];
		$login 	 = $datos_correo['login'];
		$password = $datos_correo['password'];
		// Cabecera
		$cabeceras  = "MIME-Version: 1.0\r\n";
		$cabeceras .= "Content-Transfer-Encoding: 8Bit\r\n";
		$cabeceras .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
		$cabeceras .= "Reply-to: example@outlook.com\r\n";
		$cabeceras .= "From: DE \r\n";
		$cabeceras .= "Errors-To: example@outlook.com\r\n";

		// ----------------------------------------------------------------------
		// Cuerpo
		$cuerpo = "Esto es un simple prueba...<br><br>\r\n";

		// ----------------------------------------------------------------------
		// Envio
		// Correo, Titulo, Cuerpo, Cabecera
		mail( "$nombre <$correo>", "Prueba de correo ", $cuerpo, $cabeceras );
	}
}

/**
* @Descripcion Envia un correo con el error
* @param $sql,$sql_error,$php
* @return Error
*/
function enviarcorreoError($sql,$sql_error,$php)
{

		// Cabecera
		$cabeceras  = "MIME-Version: 1.0\r\n";
		$cabeceras .= "Content-Transfer-Encoding: 8Bit\r\n";
		$cabeceras .= "Content-Type: text/html; charset=\"utf-8\"\r\n";
		$cabeceras .= "Reply-to: example@outlook.com\r\n";
		$cabeceras .= "From: DE \r\n";
		$cabeceras .= "Errors-To: example@outlook.com\r\n";

		// ----------------------------------------------------------------------
		// Cuerpo
		$cuerpo = "<b>Error en: </b> $php <br><br>\r\n";
		$cuerpo .= "<b>SQL: </b><br>\r\n";
		$cuerpo .= "$sql <br><br>\r\n";
		$cuerpo .= "<b>Error:</b><br>\r\n";
		$cuerpo .= "$sql_error <br><br>\r\n";
		// ----------------------------------------------------------------------
		// Envio
		// Correo, Titulo, Cuerpo, Cabecera
		mail( "Leo <leoguillen8@outlook.com>", "Error ", $cuerpo, $cabeceras );
		return "<b style='color:red;'>Error en consulta</b>";
}

/**
* @Descripcion Devuelve la paginacion dependiendo del paginado
* @param $paginado
* @return Paginacion
*/
function paginacion($paginado,$pag_actual)
{
	$pag="";
	$pag.="<div class='container' style='text-align: center;'><ul class='pagination' style='margin:0 auto;'>";

		//==> Si la pagina actual es la primera, este boton no tendra funcion
		if($pag_actual>1)
			$funcion_atr=' onclick="pag('.($pag_actual-1).')" ';
		else
			$funcion_atr=" ";

	$pag.='<li><a '.$funcion_atr.' style="cursor:pointer;">&laquo;</a></li>';
	    for ($a=0;$a<($paginado+1);$a++)
	    {
	    	$pag.='<li id="pag'.($a+1).'"><a onclick="pag('.($a+1).')" style="cursor:pointer;"><b>'.($a+1).'</b></a></li>';
		}	
		//==> Si la pagina actual es la ultima, este boton no tendra funcion
		if($paginado>=$pag_actual)
			$funcion_sig=' onclick="pag('.($pag_actual+1).')" ';
		else
			$funcion_sig=" ";

	$pag.='<li><a '.$funcion_sig.' style="cursor:pointer;">&raquo;</a></li>';	
	$pag.="</ul></div>";

	echo $pag;
}

/**
* @Descripcion Devuelve un monto con decimales para insertar en la BD
* @param $monto
* @return Monto formateado
*/
function formateaMonto($monto)
{
	$p=explode(",",$monto);

	if(count($p)==1) //===> Si no tiene decimales...
	{
		$pFinal=$monto.",00";
	}
	else if(count($p)==2) //===> Si tiene decimales...
	{
		if(strlen($p[1])==1 ) //===> Si tiene 1 solo decimal
			$pFinal=$monto."0";
		else if(strlen($p[1])==2) //===> Si tiene los dos decimales
			$pFinal=$monto;
		else if(strlen($p[1])>=3 ) //===> Si tiene mas de 3 decimales
			$pFinal=$p[0].",".substr($p[1],0,2);
	}
	//exit(); //1 Si no tiene decimales... 2 Si tiene decimales

	$precProd =str_replace(array(".",","),array("","."),$pFinal);
	
	//==> Tomo la longitud del precio a ver si es igual a 10 o menor (ESTO ES PARA LA IMPRESORA FISCAL)
/*	$precFactura 	=str_replace(array(".",","),array("",""),$pFinal);
		$longPrecio =strlen($precFactura); 
			$resta  =10-$longPrecio;
			if( $resta<10 )
				$precFactura=str_pad($precFactura,10,"0",STR_PAD_LEFT);

			$precFactura="!".$precFactura;*/
	return $precProd;
}

function exportar()
{
	$datos="";
	$datos.='<div style="float:right;margin-bottom:10px;">';
	$datos.='	<button type="button" data-toggle="tooltip" data-placement="left" data-original-title="Exportar a excel" class="btn btn-success btn-sm excel" onclick="exportarE()"><i style="font-size:14px;" class="fa fa-file-excel-o"></i> Exportar a Excel</button>';
	$datos.='	<button type="button" data-toggle="tooltip" data-placement="top" data-original-title="Exportar a Word" class="btn btn-info btn-sm excel" onclick="exportarW()"><i style="font-size:14px;" class="fa fa-file-word-o"></i> Exportar a Word</button>';
	$datos.='	<button type="button" data-toggle="tooltip" data-placement="right" data-original-title="Exportar a PDF" class="btn btn-danger btn-sm excel" onclick="exportarP()"><i style="font-size:14px;" class="fa fa-file-pdf-o"></i> Exportar a PDF</button>';
	$datos.="	<script type='text/javascript'>$(\"[data-toggle='tooltip']\").tooltip();</script>";
	$datos.='</div>';
	echo $datos;
}



?>
