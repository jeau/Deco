<?php
$pageTitle = __('Search Omeka ') . __('(%s total)', $total_results);
echo head(array('title' => $pageTitle, 'bodyclass' => 'search browse'));
$searchRecordTypes = get_search_record_types();
$query = (isset($_GET['query']) ? $_GET['query'] : null);
$num=($total_results) ? ': <span class="item-number">'.$total_results.'</span>' : '';
$refine=(($query) ? '&nbsp;<span id="refine-search">['.link_to_item_search($text = 'refine search').']</span>' : '' );
?>
<div id="primary">
	
	<h1>
		<?php echo ($query) ? 'Search Results for "'.$query.'"'.$num.$refine : 'Search Results'; ?>
	</h1>



<div class="items navigation" id="secondary-nav">
	<?php echo deco_nav();?>
</div>

<div id="pagination-top" class="pagination"><?php echo pagination_links(); ?></div>



<?php if ($total_results): ?>
<?php foreach (loop('search_texts') as $searchText): ?>
<?php $record = get_record_by_id($searchText['record_type'], $searchText['record_id']); ?>
<?php if(get_class($record) =='Item'):
set_current_record('Item',$record);
$item= get_current_record('Item');			
?>
	<div class="item hentry">    
		<div class="item-meta">
		    
		<h2><?php echo link_to_item(metadata('Item',array('Dublin Core', 'Title'), array('class'=>'permalink'))); ?></h2>


		<?php if (metadata('Item','has_thumbnail')): ?>
		<div class="item-img">
		<?php echo link_to_item(item_image('square_thumbnail',array('width'=>'120px','height'=>'auto'))); ?>	
		</div>
		<?php endif; ?>
		
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
			<?php echo tag_string($item, url('items/browse')); ?> </p>
			</div>
			<?php endif; ?>				
		</div>
		
		
		<?php echo fire_plugin_hook('items_browse_each'); ?>

		</div><!-- end class="item-meta" -->
	</div><!-- end class="item hentry" -->
	<?php endif;?>
<?php endforeach; ?>


<?php echo pagination_links(); ?>
<?php else: ?>
<div id="no-results">
    <p><?php echo __('Your query returned no results.');?></p>
</div>
<?php endif; ?>

</div>

<?php echo foot(); ?>