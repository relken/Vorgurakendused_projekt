var asInitVals = new Array();
$(document).ready(function() {
	var oTable = $('#filter').dataTable( {
			"oLanguage": {
			"sSearch": "Otsi üle kõikide väljade.."
			}
	} );
				
	$("tfoot input").keyup( function () {
	oTable.fnFilter( this.value, $("tfoot input").index(this) );
	} );
				
	$("tfoot input").each( function (i) {
	asInitVals[i] = this.value;
	} );
				
	$("tfoot input").focus( function () {
	if ( this.className == "search_init" )
		{
		this.className = "";
		this.value = "";
		}
	} );
				
	$("tfoot input").blur( function (i) {
		if ( this.value == "" )
			{
			this.className = "search_init";
			this.value = asInitVals[$("tfoot input").index(this)];
			}
	} );
} );

$( ".valilend" ).click(function() {
	var lend = $(this).text();
	if (typeof(user) != 'undefined' && user != null) {	
		jConfirm('Oled kindel et soovid sellele lennule broneerida?', 'Palun kinnita', function(kinnitus) {
			if (kinnitus) {$.post('?page=lisalend', {variable: lend});
				jAlert('Oled edukalt lennule broneeringu sooritanud!', 'Kinnitus');
			} else {
				jAlert('Pole midagi, proovi järgmine kord uuesti', 'Teade');
				}
		});
	} else {jAlert('Lendude broneerimiseks pead olema sisse logitud', 'Teade');}	
});