<?php echo head(array('title'=>'Browse Items','bodyid'=>'items','bodyclass' => 'browse')); ?>

	<div id="primary">
		
		<h1>
		
		<?php
		$collection_id = (isset($_GET['collection']) ? $_GET['collection'] : null);
		$tag = (isset($_GET['tags']) ? $_GET['tags'] : null);
		$query = (isset($_GET['search']) ? $_GET['search'] : null);
		$advanced = (isset($_GET['advanced']) ? true : false);
		
		if ( ($collection_id) && !($query) ) {
		$collection_id=get_record_by_id('Collection',$collection_id);
		echo 'Items from "'.metadata($collection_id,array('Dublin Core', 'Title')).'"';
		}
		elseif ( ($tag) && !($query) ) {
		echo 'Items tagged "'.$tag.'"';
		}
		elseif ($query) {
		echo (!($advanced) ? 'Search Results for "'.$query.'"':'Advanced Search Results');
		}	
		else{
		echo 'Browse Items';
		}	
		echo ': <span class="item-number">'.$total_results.'</span>';
		echo (($query) ? '&nbsp;<span id="refine-search">['.link_to_item_search($text = 'refine search').']</span>' : '' )
		?>
		
		</h1>

		<div class="items navigation" id="secondary-nav">
			<?php echo deco_nav();?>
		</div>
		
		<div id="pagination-top" class="pagination"><?php echo pagination_links(); ?></div>
		
		<?php 
		foreach(loop('Items') as $item):
		set_current_record('Item',$item);
		$item= get_current_record('Item');		
		$type=$item->getItemType();		
		?>
			<div class="item hentry">    
				<div class="item-meta">
				    
				<h2><?php echo link_to_item(metadata('Item',array('Dublin Core', 'Title'), array('class'=>'permalink'))); ?></h2>

				<div class="item-img">
				<?php 
				if (metadata('Item','has_thumbnail')){
					echo link_to_item(item_image('square_thumbnail',array('width'=>'120px','height'=>'auto'))); 
				}elseif( $type['name'] == 'Moving Image' ){
					echo ($video_thumb=get_theme_option('video_thumb')) ? link_to_item('<img src="'.WEB_ROOT.'/files/theme_uploads/'.$video_thumb.'">') : null;
				}elseif( $type['name'] == 'Sound' ){
					echo ($audio_thumb=get_theme_option('audio_thumb')) ? link_to_item('<img src="'.WEB_ROOT.'/files/theme_uploads/'.$audio_thumb.'">') : null;
				}elseif( $type['name'] == 'Document' ){
					echo ($doc_thumb=get_theme_option('doc_thumb')) ? link_to_item('<img src="'.WEB_ROOT.'/files/theme_uploads/'.$doc_thumb.'">') : null;
				}
				?>
				</div>
				
				<div class="item-description">				
					<?php if ($text = metadata('Item',array('Item Type Metadata', 'Text'), array('snippet'=>350))): ?>
					<p><?php echo $text; ?></p>
					<?php elseif ($description = metadata('Item',array('Dublin Core', 'Description'), array('snippet'=>350))): ?>
	
					<?php echo $description; ?>
	
					<?php else : ?>	
					View full record for details.
					<?php endif; ?>		
				

					<?php if (metadata($item, 'has_tags')): ?>
					<div class="tags"><p><strong>Tags:</strong>
					<?php echo tag_string('Item'); ?> </p>
					</div>
					<?php endif; ?>	
												
				</div>
				
				
				<?php echo fire_plugin_hook('items_browse_each'); ?>

				</div><!-- end class="item-meta" -->
			</div><!-- end class="item hentry" -->
		<?php endforeach; ?>
	
		<div id="pagination-bottom" class="pagination"><?php echo pagination_links(); ?></div>
		
		<?php echo fire_plugin_hook('items_browse'); ?>
			
	</div><!-- end primary -->
	
<?php echo foot(); ?>