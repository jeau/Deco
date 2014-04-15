<?php echo head(array('title'=>'Browse Items','bodyid'=>'items','bodyclass'=>'tags')); ?>

<div id="primary">
	
	<h1>Browse Tags</h1>
	
	<div class="navigation tags" id="secondary-nav">
		<?php echo deco_nav();?>
	</div>

	<?php echo tag_cloud($tags,'items/browse'); ?>

</div><!-- end primary -->

<?php echo foot(); ?>