<?php
include("../funcionesphp/funciones.php");


extract($_GET);


switch ($accion) {
	

	case 'guardar':


	//ESTABLECIENDO SI EL NOMBRE DEL CONDOMINIO NO SE ENCUENTRA EN USO
	$sql = "SELECT nombre FROM condominio WHERE nombre = '$n_condominio'";		  
	$consulta = mysqli_query($enlace,$sql);
	$result   = mysqli_num_rows($consulta);

	if ($result >= 1) {
		//NOMBRE DE CONDOMINIO EN USO
	echo json_encode(array("result"=>true,"msg"=>"El nombre de condominio (<u>".$n_condominio."</u>) ya se encuentra registrado, intenta con otro nombre!"));
	break;
	}else{
	    //NOMBRE DE CONDOMINIO DISPONIBLE
        // $n_condominio = utf8_encode($n_condominio);
        $n_condominio = ucwords(strtolower($n_condominio));
        $sqlInsertCondominio = "INSERT INTO condominio VALUES('','$n_condominio','1')";
        $ejecutaSql = mysqli_query($enlace, $sqlInsertCondominio);
                        
		if ($ejecutaSql) {
            //INSERTAMOS EL NOMBRE DE CONDOMINIO
            $sqlRecuperaIdCondominio = "SELECT id_condominio FROM condominio WHERE nombre = '$n_condominio'";
            $ejecutaSql = mysqli_query($enlace,$sqlRecuperaIdCondominio);
         
                if ($ejecutaSql) {
                    $arrayDatos = mysqli_fetch_array($ejecutaSql);
                    //RECUPERAMOS EL ID CONDOMINIO PARA CASARLO CON LAS TORRES
                    $IdCondominio = $arrayDatos['id_condominio'];
                    //INGRESAMOS LOS NOMBRES DE LAS TORRES QUE PERTENECEN A ESE CONDOMINIO
                    for ($i=1; $i <= $idTorre; $i++) {
                        $n_torres = ucwords(strtolower($nombresTorres[$i]));
                        $sqlInsertTorres = "INSERT INTO torres_condominio
                                            VALUES('',
                                                   '$IdCondominio',
                                                   '$n_torres',
                           	                       '1')";
						$ejecutaSql = mysqli_query($enlace, $sqlInsertTorres);		

							if ($ejecutaSql) {
								
							}else{
								echo json_encode(array("ejecutaSql"=>false,"msg"=>"Hubo un error al ingresar las torres del condominio"));
								break;
							}
					
							}//FIN CICLO FOR		

							echo json_encode(array("ejecutaSql"=>true,"msg"=>"El condominio se ha registrado exitosamente."));


                        }//LINEA 38				
		  			}  //LINEA 34
		 		}	 //LINEA 27

	}//FIN DEL SWITCH


?>