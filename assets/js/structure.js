$(document).ready(function() {
	document.title = 'CyberHRM - Struktur Perusahaan';
	
	$('.ui.accordion').accordion('open', 0);
	$('#mnuStructure').state('activate');
	$('.menu .item').tab();	
});