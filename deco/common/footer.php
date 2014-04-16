</div><!-- end content -->

<div id="footer">
		<?php echo public_nav_main();?>
		
		
		
    <p>&copy; <?php echo date('Y');?> <?php echo html_escape(option('author'));?>
    <br/>Powered by <a href="http://omeka.org">Omeka</a><?php echo deco_display_theme_credit();?>
    <?php echo ($footertext=get_theme_option('custom_footer')) ? '<br>'.$footertext : '';?></p>

	<script type="text/javascript">
	jQuery(document).ready(function() {
	    var $window = jQuery(window);
	        
	        // exists? function
	        jQuery.fn.exists = function(){return this.length>0;}
	        
	        // Function to handle changes to style classes based on window width
	        // Also swaps in thumbnails for larger views where user can utilize Fancybox image viewer
	        // Also swaps #hero images in items/show header
	        
	        function checkWidth() {
	        var breakpoint = 625;
	        if ($window.width() < breakpoint) {	    
		 	
		 	jQuery('body').removeClass('big').addClass('small');
		 	
		 	
			// menu button
			if (jQuery("body").hasClass('small')){
				jQuery('#mobile-menu-button').show();
				jQuery(function() {
				jQuery('#primary-nav').hide();

				});		
			}
			
			}
			if ($window.width() >= breakpoint) {
				jQuery('body').removeClass('small').addClass('big');
				if (jQuery("body").hasClass('big')){
					jQuery('#primary-nav').show();
					jQuery('#mobile-menu-button').hide();
				}
			}
			
			}
	
	
	// Execute on load
	checkWidth();
	
	// Bind event listener
	jQuery($window).resize(checkWidth);	
	});


    // resizable site title
    jQuery("#site-title").fitText(2, { minFontSize: '25px', maxFontSize: '45px' });
 	jQuery("body#home #content h2").fitText(0, { minFontSize: '23px', maxFontSize: '27px' });

	// create slideshow on home
	window.mySwipe = new Swipe(document.getElementById('slider'), {
	  startSlide: 0,
	  speed: 300,
	  auto: 5000,
	  continuous: true,
	  disableScroll: false,
	  stopPropagation: false,
	  callback: function(index, elem) {},
	  transitionEnd: function(index, elem) {}
	});  	
	
	jQuery("a.fancyitem").fancybox({
	type:'image', 
	autoScale:'true',
	overlayOpacity:0.75,
	overlayColor:'#000',
	titlePosition: 'over',
	speedIn:500, 
	speedOut:300,
	'titleFormat': function(title, currentArray, currentIndex, currentOpts) {
		return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) 
		+ ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
	}
		});
		
	// create the dropdown select	
	(function(jQuery) { jQuery(function() {
	    var jQueryselect = jQuery('<select>')
	        .appendTo('#exhibit-pages');
	
	    jQuery('nav#exhibit-pages li').each(function() {
	        var jQueryli    = jQuery(this),
	            jQuerya     = jQueryli.find('> a'),
	            jQueryp     = jQueryli.parents('li'),
	            prefix = new Array(jQueryp.length + 1).join('-');
	
	        var jQueryoption = jQuery('<option>')
	            .text(prefix + ' ' + jQuerya.text())
	            .val(jQuerya.attr('href'))                       
	            .appendTo(jQueryselect);
	
	        if (jQueryli.hasClass('current')) {
	            jQueryoption.attr('selected', 'selected');
	        }
	    });
	    
	    
	});})(jQuery);		


	// Bind dropdown select to change the page
    jQuery(function(){
      // bind change event to select
      jQuery('nav#exhibit-pages select').bind('change', function () {
          var url = jQuery(this).val(); // get selected value
          if (url) { // require a URL
              window.location = url; // redirect
          }
          return false;
      });
    });



	// Get the link to the first section page and place link on summary page
	jQuery('<a class="view-exhibit" href="">View Exhibit&nbsp;&rarr;</a>').appendTo("#exhibit-start");
	jQuery(function() {
		var url = jQuery("nav#exhibit-pages ul li:first-child a").attr("href");
		jQuery("#exhibit-start a.view-exhibit").attr("href", ''+url+'' );
	});
	
	
	
	// Mobile Menu button
	jQuery('#mobile-menu-button').click( function() {
	  jQuery('#primary-nav').slideToggle('fast', function(){
		if (jQuery('#primary-nav').is(':visible')) {
		 jQuery('#mobile-menu-button a').text('Hide Menu') 
		} else {
			jQuery('#mobile-menu-button a').text('Show Menu') 
		  }
	    });
	  });
	  
</script>

		
	
</div><!-- end footer -->
</div><!-- end wrap -->
</body>

</html>