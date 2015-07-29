<div id="div_form_sesion" class="container" style="padding-top:200px;">		

		<div class="panel panel-default" style="box-shadow:2px 2px 5px;margin:0 auto;">

			<div class="panel-heading" style="text-align: center;font-size: 25px;padding: 20px;">

				<!-- <img class="img-responsive" src="img/logoClinica.png" style="margin:0 auto;"> -->

			</div>

			<div class="panel-body">

					<div class="form-group">

					<label class="col-sm-2 control-label" style="width:24%;"><b>Usuario:</b></label>

						<div class="col-sm-8">

							<div class="input-group">

								<span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>

								<input type="text" class="form-control" id="user" name="user" placeholder="" data-container="body" data-toggle="popover" data-placement="right" data-content="Ingrese usuario.">

							</div>

						</div>

					</div>



					<div class="form-group">

					<label class="col-sm-2 control-label" style="width:24%;"><b>Contrase&ntilde;a:</b></label>

						<div class="col-sm-8">

							<div class="input-group">

								<span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>

								<input type="password" class="form-control" id="pass" name="pass" placeholder="" data-container="body" data-toggle="popover" data-placement="right" data-content="Ingrese contrase&ntilde;a." onkeypress="return enter(event);">

							</div>

						</div>

					</div>



					<div class="form-group" style="text-align:center;">

					    <button type="button" class="btn btn-success" onclick="validar();"><i id="spin_bt" style="display:none;" class="fa fa-spinner fa-spin"></i> Iniciar sesi&oacute;n</button>

					</div>

			</div>

		</div>

</div><br>

				<div id="msj_alert" name="msj_alert" style="display:none;width:30%;margin:0 auto;" class="alert alert-danger">

		    	<strong>Alerta:</strong> Datos incorrectos.

		    	</div>