<?php echo head(array('bodyid'=>'home')); ?> 
    <div id="primary">
        <!--About text and Featured Slideshow -->
	      <?php 
	      if(get_theme_option('slideshowtop')==1){
	          	echo deco_homepage_gallery();
	          	echo deco_get_about();            
		  	}else{
	          	echo deco_get_about();            
			  	echo deco_homepage_gallery();				  	
		  	}
		  ?> 		

	
        <!-- Featured Exhibit -->
        <div id="featured-exhibits">
            <?php echo deco_exhibit_builder_display_random_featured_exhibit(); ?>
        </div><!-- end featured collection -->
        
    	<!-- Featured Collection -->
    	<div id="featured-collection">    
    		<?php echo deco_display_random_featured_collection();?>
       	</div><!-- end featured collection -->
        

            
    </div><!-- end primary -->
    
    <div id="secondary">

    <!-- Recent Items --> 
        <div id="recent-items">   
        
        
            <h2>Recent Items</h2>
            <?php 
            $deco_get_recent_number=deco_get_recent_number();            
	        set_loop_records('items', get_recent_items($deco_get_recent_number));
	        if (has_loop_records('items')){    
	                
            ?>            
            
            <div class="items-list">
            <?php 
            foreach (loop('Items') as $item): ?>

                <div class="item">

                    <h3><?php echo link_to_item(); ?></h3>

					<div class="item-img">
					<?php 
					$type=$item->getItemType();	
					if (metadata('Item','has_thumbnail')){
						echo link_to_item(item_image('square_thumbnail',array('width'=>'120px','height'=>'auto'))); 
					}elseif( $type['name'] == 'Moving Image' ){
						echo ($video_thumb=get_theme_option('video_thumb')) ? link_to_item('<img src="'.WEB_ROOT.'/files/theme_uploads/'.$video_thumb.'">') : null;
					}elseif( $type['name'] == 'Sound' ){
						echo ($audio_thumb=get_theme_option('audio_thumb')) ? link_to_item('<img src="'.WEB_ROOT.'/files/theme_uploads/'.$audio_thumb.'">') : null;
					}elseif( $type['name'] == 'Document' ){
						echo ($doc_thumb=get_theme_option('doc_thumb')) ? link_to_item('<img src="'.WEB_ROOT.'/files/theme_uploads/'.$doc_thumb.'">') : null;
					}elseif( $type['name'] == 'Website' ){
						echo ($website_thumb=get_theme_option('website_thumb')) ? link_to_item('<img src="'.WEB_ROOT.'/files/theme_uploads/'.$website_thumb.'">') : null;
					}
				    ?>
					</div>

                    <?php if($desc = metadata($item,array('Dublin Core', 'Description'), array('snippet'=>190))){ ?>

                        <div class="item-description"><?php echo $desc; ?>
                        <?php echo link_to_item(' ...more',(array('class'=>'show'))) ?>
                        </div>

                    <?php } ?> 

                </div>


            <?php 
            endforeach;
            } ?>
            
            </div>
	
            <p class="view-items-link"><a href="<?php echo html_escape(url('items')); ?>">View All Items</a></p>
            
        </div><!--end recent-items -->
        
    </div><!-- end secondary -->
    
<?php echo foot(); ?>