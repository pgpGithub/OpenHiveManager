/* ------------------------------------------------------------------------------
*
*  # Login page
*
*  Specific JS code additions for login and registration pages
*
*  Version: 1.0
*  Latest update: Aug 1, 2015
*
* ---------------------------------------------------------------------------- */

$(function() {

	// Style checkboxes and radios
	$('.styled').uniform();
        
        if( !$('input:checkbox:checked').val() ){
            $("#bouton_submit").hide(); 
        }

});

$("input:checkbox").change(function(){    
    if( $('input:checkbox:checked').val() ){
        $("#bouton_submit").show('slow'); 
    }else{
        $("#bouton_submit").hide('slow'); 
    }
});