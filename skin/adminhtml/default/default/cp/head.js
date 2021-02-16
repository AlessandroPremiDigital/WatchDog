;jQuery(document).ready(function() {
	jQuery('#nav li span').each(function (){
	    //jQuery(this).text() == 'CustomerParadigm';
	    var text 	= jQuery(this).text();
	    var text	= text.trim();
		//console.log(text);
	    if(text == 'CustomerParadigm') {
		var li = jQuery(this).parent().parent().addClass('cp');
		//jQuery(this).parent('li').removeClass('cp');
	    }
		
	});
	
});
