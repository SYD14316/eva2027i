<?php
	session_start();
	session_destroy();
	require_once 'lib/config.php';
?>
<html>
	<?php include_once HEADER; ?>
	<body>
		<div class="container text-center">
			<div class="card">
				<div class="card-header"><?php include_once HEAD; ?></div>
				<div class="card-body"><p>La evaluación ya está cerrada.</p></div>
			</div>
		</div>
	</body>
</html>
