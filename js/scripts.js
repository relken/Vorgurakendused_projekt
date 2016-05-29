var asInitVals = new Array();
$(document).ready(function() {
	var oTable = $('#example').dataTable( {
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
alert($(this).text());
$.post('?page=lisalend', {variable: $(this).text()});
});

