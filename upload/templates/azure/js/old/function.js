// JavaScript Document
/* sticky function */
$(document).ready(function() {
   'use strict';
   
/*$(window).scroll(function() {
    if ($(this).scrollTop() > 1){  
        $('.header').addClass("sticky");
    }
    else{
        $('.header').removeClass("sticky");
    }
});*/

 $('.sliderfull').slick({
		  dots:true,
		  infinite: true,
		  autoplay:true,
		  arrows:false,
		  autoplaySpeed: 5000,
		  cssEase: 'linear'
	  });
 $('.testimonialslider').slick({
		  dots:true,
		  infinite: true,
		  autoplay:true,
		  arrows:false,
		  autoplaySpeed: 4000,
		  cssEase: 'linear'
	  });

});
 (jQuery);
jQuery(document).ready(function($){
			$(".accordion_example").smk_Accordion();			
		});
 (function($) {
				$(document).ready(function() {
					$.slidebars();
				});
			}) (jQuery);		  	
	      
