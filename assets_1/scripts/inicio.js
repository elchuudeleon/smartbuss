$(document).ready(function(e){
	$('.moneda').mask('#.##0', {reverse: true});
	$('.numero').on('input', function () { 
	    this.value = this.value.replace(/[^0-9]/g,'');
	});
})