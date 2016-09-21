/**
 * some jquery stuff
 */

$(document).ready(function(){
	
	// check / uncheck all boxes

    $('#report-checkall').on('click', function(){
    	  // Change values
    	  $('.report-checkbox').prop('checked', ($(this).val() == 'Check'));
    	  // Change caption of this button
    	  $(this).val( ($(this).val() == 'Check' ? 'Uncheck' : 'Check') );
    	});
	
	// we could hide all placeholder but would have conflicts with our is(':empty') check below
    
	//$('.report-item').hide();
	
	$( '.ajax' ).click( function(e){
		
		e.preventDefault();
		
		// let's extract out report serial
		
		var reportserial = $(this).attr('href').match(/report=([0-9]+)/)[1];
		var divid = "report-serial-" + reportserial;
		// console.log( divid );
		
		// let's get the params from the link
		var param = $(this).attr('href').split('?')[1];
		// console.log( param );
		
		// if our placeholder div is empty then do AJAX call and fill up the div with data
		if ($('#' + divid).is(':empty')){
			
			$.ajax({
		        type: 'GET',
		        url: 'ajax.php',
		        data: param, // send our report serial param
		        success: function(data, textStatus, XMLHttpRequest) {
		        	
		        	// our current element
		        	var current = $('#' + divid);
		        	// console.log( current );
		        	
		        	// here we can hide all .report-item but not the current
		        	
		        	// $(".report-item").not($(this)).hide();
		        	// $(".report-item").not( current ).hide();
		        	// $('#' + divid).show();
		        	
		            // $('#' + divid).html('');
		            $('#' + divid).append( data );
		            //$('#' + divid).hide();
		            //$('#ajax-response').append(data);
		            
		            $('#' + divid).toggle();
		            
		        },
		        error: function(MLHttpRequest, textStatus, errorThrown) {
		            alert(errorThrown);
		        }
		        
		    });	
			
		}
		
		// toggle current div report-serial-xx
		$('#' + divid).toggle();
		
	 });
	
});