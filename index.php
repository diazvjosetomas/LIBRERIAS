<?

session_start();

include("funcionesphp/funciones.php");

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<!-- <link type="image/x-icon" href="img/logo.ico" rel="shortcut icon"/> -->

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta charset="UTF-8">

<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

<link rel="stylesheet" type="text/css" href="css/bootstrap-select.css" />

<link rel="stylesheet" type="text/css" href="css/font-awesome.css" />

<link rel="stylesheet" type="text/css" href="css/jquery-ui.css" />

<link rel="stylesheet" type="text/css" href="css/sweet-alert.css">

    <link href="css/style.css" rel="stylesheet">

    <link href="css/style-responsive.css" rel="stylesheet">

<script type="text/javascript" src="js/jquery-2.0.3.min.js"></script>

<script type="text/javascript" src="js/jquery-ui.js"></script>

<!--Esto es un puente para evitar el conflicto del tooltip de jQuery UI y el de Boostrap-->

<script type="text/javascript">$.widget.bridge('uitooltip', $.ui.tooltip);</script>

<script type="text/javascript" src="js/bootstrap.min.js"></script>

<script type="text/javascript" src="js/bootstrap-dialog.js"></script>

<script type="text/javascript" src="js/bootstrap-select.js"></script>

<script type="text/javascript" src="js/funciones.js"></script>

<script type="text/javascript" src="js/jquery.blockUI.js"></script>

<script type="text/javascript" src="js/jquery.bootstrap-autohidingnavbar.js"></script>

<script type="text/javascript" src="js/sweet-alert.js"></script>

	<script class="include" type="text/javascript" src="js/jquery.dcjqaccordion.2.7.js"></script>

<title>Administrativo</title>



</head>

<style type="text/css">

.panel-heading{

	/*font-family: 'Aubrey';*/

}

.panel-heading,.modal-header{

	background-color:#DEEBFA !important;

}

.panel-default{

	box-shadow: 2px 2px 7px rgb(102, 153, 210) !important;

}

.modal-header{

	border-top-right-radius: 4px;

	border-top-left-radius: 4px;

}

.form-control:focus{

	border-color: #3995EF !important;

	box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(73, 205, 236, 0.6) !important;

}

body{

	background: rgba(220, 237, 250, 1);

	background: -moz-linear-gradient(top, rgba(220, 237, 250, 1) 0%, rgba(255,255,255,1) 100%);

	background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(220, 237, 250, 1)), color-stop(100%, rgba(255,255,255,1)));

	background: -webkit-linear-gradient(top, rgba(220, 237, 250, 1) 0%, rgba(255,255,255,1) 100%);

	background: -o-linear-gradient(top, rgba(220, 237, 250, 1) 0%, rgba(255,255,255,1) 100%);

	background: -ms-linear-gradient(top, rgba(220, 237, 250, 1) 0%, rgba(255,255,255,1) 100%);

	background: linear-gradient(to bottom, rgba(220, 237, 250, 1) 0%, rgba(255,255,255,1) 100%);

	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#edfaf2', endColorstr='#ffffff', GradientType=0 );

	background-repeat: no-repeat;

	background-attachment: fixed;

	height: 100%;

}

</style>

<body>






<form id="form1" name="form1" class="form-horizontal" role="form">

<?

	//==> Hiddens de valores globales

	input_hidden("accion","");

	input_hidden("idusuario","".$_SESSION["idUser"]."");

	input_hidden("iva","".($_SESSION['iva']/100)."");

	input_hidden("islr","".($_SESSION['islr']/100)."");

	input_hidden("margenG","".($_SESSION['margenG']/100)."");



		//include("includes/menu.php");

		if(empty($_REQUEST["val"]) && $_SESSION["aut"]=="" || ($_REQUEST["val"]!="" && $_SESSION["aut"]=="") )

		{

			include("includes/form_sesion.php");

		}

		elseif(empty($_REQUEST["val"]) && $_SESSION["aut"]=="SI")

		{

			include("includes/menu.php");

		}

		if($_REQUEST["val"]==1 && $_SESSION["aut"]=="SI")

		{

			include("includes/menu.php");

		}

	?>



</form>

</body>

<div id="mensaje"></div>

<div style="width:100%;height:65px;"></div>

</html>

	<script src="js/jquery.scrollTo.min.js"></script>

	<script src="js/jquery.nicescroll.js" type="text/javascript"></script>

	<script src="js/common-scripts.js"></script>



<script type="text/javascript">

		$(document).ready(function() 

		{ 

			$("#user").focus();

		});



function enter(e)

{

	if(e.keyCode==13)

	{

		validar();

	}

}



	function validar()

	{

		if($("#user").val()=="")

		{

			$("#user").popover("show");

			return false;

		}



		if($("#pass").val()=="")

		{

			$("#pass").popover("show");

			return false;

		}



		$(document).ready(function() 

		{ 

			$("#spin_bt").show("fast");



		        $.blockUI({ css: { 

		            border: 'none', 

		            padding: '15px', 

		            backgroundColor: '#000', 

		            '-webkit-border-radius': '10px', 

		            '-moz-border-radius': '10px', 

		            opacity: .5, 

		            color: '#fff' 

		        } }); 



		 		var destino=$("#mensaje");



				$("#accion").val("iniciosesion");

				$.post("verifica.php", $("#form1").serialize(), function(resp)

				{

				   $("#spin_bt").hide("fast");

					var json = eval("(" + resp + ")");

					setTimeout($.unblockUI); 

					if(json.result==true)

					{

						$("#form1")[0].reset();

						$("#div_form_sesion").css("display","none");

						//crear_dialog("Informaci&oacute;n",json.mensaje,"","reload");

						crear_modal("Bienvenido","Datos correctos","success","","window.location.reload()","");

						//window.location.reload();

					}

					else{

						crear_modal("","Error: Usuario o Clave incorrecta","error","","","");

					}

				});

		}); 

	}

</script>



