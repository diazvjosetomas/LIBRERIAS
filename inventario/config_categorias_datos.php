<?
include("../funcionesphp/conex.php");

//======> Variables
$accion 			=$_REQUEST["accion"];
if(!empty($_REQUEST["filtro_cliente"]))
	$filtro_cliente =$_REQUEST["filtro_cliente"];

//===> Datos para guardar una nueva categoria
if(!empty($_REQUEST["categoria"]))
	$categoria 	=utf8_decode($_REQUEST["categoria"]);
if(!empty($_REQUEST["subcateg"]))
	$subcateg 	=utf8_decode($_REQUEST["subcateg"]);
if(!empty($_REQUEST["idCatMod"]))
	$idCatMod 	=utf8_decode($_REQUEST["idCatMod"]);
if(!empty($_REQUEST["idCatEli"]))
	$idCatEli 	=utf8_decode($_REQUEST["idCatEli"]);

switch ($accion) 
{
	case "guardarCategoria":

		//===> Guardando la categoria
		//(categoria)
		$sql=mysqli_query($enlace,"INSERT INTO categoria_productos
			  					   VALUES (NULL,'$categoria') ") or die ("Error: ".mysqli_error($enlace));
		$lastCat=mysqli_insert_id($enlace);

		//===> Guardando las sub-categorias
		$exC =explode(",",$subcateg);

		for ($i=0; $i < count($exC); $i++) 
		{ 
			if(trim($exC[$i])!="")
			{
				$sql2=mysqli_query($enlace,"INSERT INTO sub_categoria_productos (id_cat_prod,sub_categoria) 
											VALUES ($lastCat,'".trim($exC[$i])."') ") or die ("Error: ".mysqli_error($enlace));
			}
		}
			
			if(!$sql)
				echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
			else
				echo json_encode(array('result' => true,'mensaje'=>"¡Registros guardados exitosamente!"));	

	break;

	case 'modificarCat':
		//===> Modifico categoria
		$sql=mysqli_query($enlace,"UPDATE categoria_productos 
			  SET categoria='$categoria' 
			  WHERE id_cat_prod=$idCatMod") or die ("Error: ".mysqli_error($enlace));

		//===> Elimino las sub-categorias
		$sql1=mysqli_query($enlace,"DELETE FROM sub_categoria_productos WHERE id_cat_prod=$idCatMod") or die ("Error: ".mysqli_error($enlace));
		
		//===> Ingreso las nuevas sub-categorias
		$exC =explode(",",$subcateg);

		for ($i=0; $i < count($exC); $i++) 
		{ 
			if(trim($exC[$i])!="")
			{
				$sql2=mysqli_query($enlace,"INSERT INTO sub_categoria_productos (id_cat_prod,sub_categoria) 
											VALUES ($idCatMod,'".trim($exC[$i])."') ") or die ("Error: ".mysqli_error($enlace));
			}
		}

		if($sql)
			echo json_encode(array('result' => true,'mensaje'=>"¡Registros modificados exitosamente!"));			
		else
			echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
	break;

	case 'eliminarCat':
		//==> Elimino categoria
		$sql=mysqli_query($enlace,"DELETE FROM categoria_productos WHERE id_cat_prod=$idCatEli") or die ("Error: ".mysqli_error($enlace));

		//==> Elimino sub-categorias
		$sql1=mysqli_query($enlace,"DELETE FROM sub_categoria_productos WHERE id_cat_prod=$idCatEli") or die ("Error: ".mysqli_error($enlace)); 
		
		if($sql && $sql1)
			echo json_encode(array('result' => true,'mensaje'=>"¡Registros eliminados exitosamente!"));			
		else
			echo json_encode(array('result' => false,'mensaje'=>"Error en la solicitud"));	
	break;

	case 'verCategorias':
			$sql=mysqli_query($enlace,"SELECT * FROM categoria_productos") or die ("Error: ".mysqli_error($enlace));
			?>
			<div class="container" style="margin-top:30px;">		
					<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;width:100%;">
						<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">Categor&iacute;as</div>
						<div class="panel-body" style="padding:0px;">
							<table class="table table-striped table-bordered table-responsive">
							<tr>
								<td align="center" style="background-color:#D3D3D3;"><b>Categor&iacute;a</b></td>
								<td width="40%" align="center" style="background-color:#D3D3D3;"><b>Sub-Categor&iacute;as</b></td>
								<td align="center" style="background-color:#D3D3D3;"><b>Opci&oacute;n</b></td>
							</tr>
							<?
								if($sql)
								{
									while($rs=mysqli_fetch_array($sql))
									{
										$sql1=mysqli_query($enlace,"SELECT * FROM sub_categoria_productos 
															WHERE id_cat_prod={$rs["id_cat_prod"]} ") or die ("Error: ".mysqli_error($enlace));
											$subCat="";
											while($rs1=mysqli_fetch_array($sql1))
											{
												if($subCat=="")
													$subCat=$rs1["sub_categoria"];
												else
													$subCat.=",".$rs1["sub_categoria"];
											}
											?><tr>
												<td title="<?=utf8_encode($subCat)?>"><?=utf8_encode($rs["categoria"])?></td>
												<td><?=utf8_encode($subCat)?></td>
												<td width="10%" align="center">
													<button type="button" class="btn btn-danger btn-sm" title="Eliminar categor&iacute;a" data-toggle="modal" data-target="#modal-cat<?=$rs["id_cat_prod"];?>"><i class="fa fa-trash-o fa-lg"></i></button>

												<!--Modal de eliminar Categorias-->
													<div id="modal-cat<?=$rs["id_cat_prod"];?>" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
													  <div class="modal-dialog modal-sm">
													    <div class="modal-content">
															<div class="modal-header">
																<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
																<h4 class="modal-title">Alerta</h4>
															</div> <!--Header-->
															<div class="modal-body">
																<p style="text-align:center;">¿Eliminar categor&iacute;a <?=utf8_encode($rs["categoria"])?>?</p>
															</div> <!--Body-->
													    	<div class="modal-footer">
													        	<button type="button" class="btn btn-danger" onclick="eliCat('<?=$rs["id_cat_prod"];?>')" data-dismiss="modal">Eliminar</button>
													        	<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
													      	</div> <!--Footer-->
													    </div> <!--Content-->
													  </div>
													</div>

													<button type="button" class="btn btn-info btn-sm" title="Modificar categor&iacute;a" onclick="modCat('<?=$rs["id_cat_prod"]?>','<?=$rs["categoria"]?>','<?=utf8_encode($subCat)?>')"><i class="fa fa-edit fa-lg"></i></button>
												</td>
											</tr><?
									}
								}?>
							</table>
						</div>
					</div>
			</div>
			<?
	break;
}
?>