<?php echo head(array('title' => metadata('Item',array('Dublin Core', 'Title')), 'bodyid'=>'items','bodyclass' => 'show item')); ?>

<h1><?php echo metadata('Item',array('Dublin Core','Title')); ?></h1>


<div id="primary">
	<div id="item-metadata">



<!-- display files -->	
	<div id="item-images">

	<?php
	
	$img = array('image/jpeg','image/jpg','image/png','image/jpeg','image/gif');
	$videoJS = array('video/mp4','video/mpeg','video/ogg','video/quicktime','video/webm');
	$videoJS_h264 = array('video/mp4','video/mpeg','video/quicktime');
	$videoJS_webM = array('video/webm');
	$videoJS_ogg = array('video/ogg');
	$wma_video = array('audio/wma','audio/x-ms-wma');
	$wmv_video = array('video/avi','video/msvideo','video/x-msvideo','video/x-ms-wmv');
	$audio = array('application/ogg','audio/aac','audio/aiff','audio/midi','audio/mp3','audio/mp4','audio/mpeg','audio/mpeg3','audio/mpegaudio','audio/mpg','audio/ogg','audio/wav','audio/x-mp3','audio/x-mp4','audio/x-mpeg','audio/x-mpeg3','audio/x-midi','audio/x-mpegaudio','audio/x-mpg','audio/x-ogg','audio/x-wav','audio/x-aac','audio/x-aiff','audio/x-midi','audio/x-mp3','audio/x-mp4','audio/x-mpeg','audio/x-mpeg3','audio/x-mpegaudio','audio/x-mpg');	
	$index=0;
	$videoIndex=0;
	
	// If an item has an "embed code," treat it like a file and place at the top, then hide the duplicate in the item metadata record
	if(element_exists('Item Type Metadata', 'Embed code')){
		
		echo ( $embedded=metadata($item, array('Item Type Metadata', 'Embed code'))) ?  $embedded : null;
		echo '<style>div[id*="item-type-metadata-embed-code"]{display:none;}</style>';
		
	}  
	
	// Docs Viewer custom placement
	deco_docs_viewer($item,$files=null);
	
	foreach (loop('files', $item->Files) as $file){
		$mime = metadata($file,'MIME Type');
		
		if 
		// Image
		(array_search($mime,$img) !== false) deco_image($file,$mime,$img,$index);
		
		elseif 
		// VideoJS-compatible video
		(array_search($mime,$videoJS) !== false) deco_videojs($file,$videoIndex,$mime,$videoJS_h264,$videoJS_webM,$videoJS_ogg);
		
		elseif
		// WMA video 
		(array_search($mime,$wma_video) !== false) echo file_markup($file, array('scale'=>'tofit', 'width' => 600, 'height' => 338));
		
		elseif
		// WMV video 
		(array_search($mime,$wmv_video) !== false) echo file_markup($file, array('scale'=>'tofit', 'width' => 600, 'height' => 338));
		
		elseif 
		// Audio 		
		(array_search($mime,$audio) !== false) echo file_markup($file, array('width' => 600, 'height' => 20));
		
		else file_markup($file);
		
		$index++;	
		
	}

		
	?>
	</div>
	<!-- end display files -->
    	
	
    <?php echo all_element_texts($item); ?>
    	
	<!-- all other plugins-->
	<?php fire_plugin_hook('public_items_show', array('item' => $item, 'view'=> $this)); ?>  	
	
	</div><!-- end item-metadata -->


	
</div><!-- end primary -->
<div id="sidebar">	

<!-- download links -->
	<div id="itemfiles" class="element">
	    <h3>File(s)</h3>
		<div class="element-text">
		    <?php 			
			foreach (loop('Files',$item->Files) as $file):
			echo '<div style="clear:both;padding:2px;">
			<a class="download-file" name="files" href="'. file_display_url($file,'original'). '">'.
			$file->original_filename.'</a>
			&nbsp; ('.metadata($file, 'MIME Type').')
			</div>';
			endforeach;			
			?>
		</div>
	</div>
<!-- end download links -->
	
<!-- If the item belongs to a collection, the following creates a link to that collection. -->
	<?php if (metadata($item, 'Collection Name')): ?>
        <div id="collection" class="element">
            <h3>Collection</h3>
            <div class="element-text"><?php echo link_to_collection_for_item(); ?></div>
        </div>
    <?php endif; ?>

<!-- The following prints a list of all tags associated with the item -->
	<?php if (metadata($item,'has tags')): ?>
	<div id="item-tags" class="element">
		<h3>Tags</h3>
		<div class="element-text"><?php echo tag_string('item'); ?></div> 
	</div>
	<?php endif;?>
	
<!-- The following prints a citation for this item. -->
	<div id="item-citation" class="element">
    	<h3>Citation</h3>
    	<div class="element-text"><?php echo metadata('Item','Citation',array('no_escape' => true)); ?></div>
	</div>

</div> <!-- end sidebar-->


<!-- the edit button for logged in superusers and admins -->
<?php if (is_allowed($item, 'edit')){
echo __('<p><a class="edit" href="/admin/items/edit/'.$item->id.'">{Edit Item}</a></p>');
} ?> 
<!-- end edit button -->


	
	
<ul class="item-pagination navigation">
<li id="previous-item" class="previous">
	<?php echo link_to_previous_item_show('Previous Item'); ?>
</li>
<li id="next-item" class="next">
	<?php echo link_to_next_item_show('Next Item'); ?>
</li>
</ul>

<!-- Video JS init -->
<script type="text/javascript">VideoJS.setupAllWhenReady();</script>

<?php echo foot(); ?>