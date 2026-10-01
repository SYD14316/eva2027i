function redirigir(pagina){
	if (pagina === undefined) { pagina = "."; }
	window.location.replace(pagina);
};

function abrir(pagina){
	if (pagina === undefined) { pagina = "."; }
	var params = [
		'height='+screen.height,
		'width='+screen.width,
		'fullscreen=yes',
		'directories=no',
		'toolbar=no',
		'resizable=no',
		'menubar=no',
		'titlebar=no',
		'scrollbars=no',
		'status=no',
		'location=no'
		].join(',');
	ventanaImpresion = window.open(pagina, "_blank", params);
	ventanaImpresion.moveTo(0,0);
};
function submitform(){
	document.getElementById('Regresar').submit();
};
