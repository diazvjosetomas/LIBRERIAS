<?
include("../funcionesphp/funciones.php");

$accion =$_REQUEST["accion"];

if(!empty($_REQUEST["tipo_user"]) || $_REQUEST["tipo_user"]==0)
	$tipo_user =$_REQUEST["tipo_user"];

switch ($accion) 
{
	case 'guardarPermisos':
		$i=0;
		foreach ($_REQUEST as $campo => $value) 
		{
			$var=explode("check_", $campo);
			$idprograma=$var[1];

			if($idprograma!="")
			{
				if($i==0)
				{
					//===> Elimino los permisos anteriores para agregar los nuevos
					$delete_perm=mysqli_query($enlace,"DELETE FROM programa_usuarios WHERE tipo_user=$tipo_user ");
				}
				//echo $idprograma."<br>";
				//===> Busco el programa
				$sql=mysqli_query($enlace,"SELECT * FROM programas_sistema WHERE id_programa=$idprograma ");
				$rs=mysqli_fetch_assoc($sql);

				//===> Busco si el usuario tiene acceso al modulo
				$sql_modul=mysqli_query($enlace,"SELECT * FROM modulos_usuarios WHERE id_modulo={$rs["id_modulo"]} AND tipo_user='$tipo_user' ");
				if(mysqli_num_rows($sql_modul)<=0) //===> Le da acceso 
					$insert_usuario_modul=mysqli_query($enlace,"INSERT INTO modulos_usuarios() 
																VALUES ('{$rs["id_modulo"]}','$tipo_user') ");

				//====> Doy acceso al programa
				$insert_perm=mysqli_query($enlace,"INSERT INTO programa_usuarios() 
												   VALUES('$idprograma','$tipo_user') ");
				$i++;
			}
			else //=== No hay nada seleccionado, no tiene permiso a nada
			{
					//===> Elimino los permisos anteriores
					$delete_perm=mysqli_query($enlace,"DELETE FROM programa_usuarios WHERE tipo_user=$tipo_user ");
			}
		}
	
		if($insert_perm || $delete_perm)
			echo json_encode(array("result"=>true,"mensaje"=>"Permisos guardados exitosamente"));
		else
			echo json_encode(array("result"=>false,"mensaje"=>"Hubo un error."));
	break;

	case 'verPermisos':	

			$sql=mysqli_query($enlace,"SELECT ms.* 
									   FROM modulos_sistema ms
									   WHERE estatus=1
									   ORDER BY id_modulo ASC") or die("Error: ".mysqli_error($enlace));
				
				?><table class="table table-bordered table-striped" width="100%"><?
					while($rs=mysqli_fetch_assoc($sql)) //===> BUSCO LOS MODULOS DEL SISTEMA
					{
						?><tr>
							<td style="background-color: rgb(220, 220, 224);"><b>M&oacute;dulo: <?=utf8_encode($rs["modulo"])?></b></td>
							<td style="background-color: rgb(220, 220, 224);width:15%;"><?=input_check("<b>Todos: </b>","check_".$rs['id_modulo']."","$ancho","$value","marcaTodosCheck(\"{$rs["id_modulo"]}\")","$onchange","$onblur","$attr")?></td>
						</tr><?
						$sqlp=mysqli_query($enlace,"SELECT * 
													FROM programas_sistema
													WHERE id_modulo={$rs["id_modulo"]} 
													ORDER BY nombre_programa ASC");
						while($rsP=mysqli_fetch_assoc($sqlp)) //===> BUSCO LOS PROGRAMAS DE ESE MODULO
						{
							//====> Busco los programas que tiene habilitado el usuario
							$checked="";
							$sql_usuario=mysqli_query($enlace,"SELECT * FROM programa_usuarios 
															   WHERE id_programa={$rsP["id_programa"]}
															   AND tipo_user=$tipo_user ") or die("Error: ".mysqli_error($enlace));
							$cont=mysqli_num_rows($sql_usuario);
							if($cont>=1)
								$checked="checked=checked";

							?><tr>
								<td align="right"><?=utf8_encode($rsP["nombre_programa"])?></td>
								<td align="right"><?=input_check("&nbsp;","check_".$rsP['id_programa']."","$ancho","prog_".$rsP['id_programa']."","$onclick","$onchange","$onblur","".$checked."","{$rs["id_modulo"]}")?></td>
							</tr><?
						}
					}
				?></table>
				<?
	break;
}

?>