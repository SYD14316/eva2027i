<?php
	session_start();
	session_destroy();
	require_once 'lib/config.php';
?>
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
					<span>Página de ingreso</span>
				</div>
				<div class="card-body"> <p>La evaluación no ha iniciado.</p> </div>
			</div>
		</div>
	</body>
</html>
