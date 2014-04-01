
<h2 id="page_title">
	<?php echo $current->name; ?>
</h2>

<!-- Div containing all galleries -->
<div class="galleries_container" id="gallery_image">
	<?php if (! is_null($prev)) { ?>
		<a href="<?php echo site_url('galleries/'.$gallery->slug.'/'.$prev->id); ?>" class="gallery-image" rel="gallery-image" data-src="<?php echo site_url('files/large/'.$prev->file_id); ?>" title="<?php echo $prev->name; ?>">
			<strong> << </strong>
		</a>
	<?php } ?>
	<?php if (! is_null($next)) { ?>
		<a href="<?php echo site_url('galleries/'.$gallery->slug.'/'.$next->id); ?>" class="gallery-image" rel="gallery-image" data-src="<?php echo site_url('files/large/'.$next->file_id); ?>" title="<?php echo $next->name; ?>">
			<strong> >> </strong>
		</a>
	<?php } ?>

	<div class="gallery clearfix">		
		<!-- Div containing the full sized image -->
		<div class="gallery_image_full">
			<img src="<?php echo site_url('files/large/'.$current->file_id); ?>" alt="<?php echo $current->name; ?>" />
		</div>
		<?php if ( ! empty($current->description) ):?>
		<!-- An image needs a description.. -->
		<div class="gallery_image_description">
			<p><?php echo $current->description; ?></p>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php if ($gallery->enable_comments == 1): ?>
	<div id="existing-comments">
		<h4><?php echo lang('comments:title') ?></h4>
		<?php echo $this->comments->display() ?>
	</div>
	<?php echo $this->comments->form() ?>
<?php endif; ?>
