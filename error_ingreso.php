<?php require_once 'lib/config.php'; ?>
<html>
	<?php include_once HEADER; ?>
	<script type="text/javascript" src="lib/functions.js"></script>
	<body>
		<div class="container">
			<div class="card">
				<div class="card-header">
					<h1>
						<img src="lib/img/blanco.png" class="img-form-left" />
						Evaluación Docente y de Servicios <?php echo $ciclo['LICENCIATURA'] . " / " . $ciclo['BACHILLERATO']; ?>
					</h1>
					<span>Error en datos de ingreso</span>
				</div>
				<div class="card-body">
					<form accept-charset="UTF-8">
						<p>Usuario y/o contraseña incorrectos, dé clic en el botón "Regresar" para volver a la página de ingreso.</p>
						<br />
						<div>
							<input type="button" class="btn btn-primary" name="regresar" value="Regresar" onclick="redirigir();" />
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>