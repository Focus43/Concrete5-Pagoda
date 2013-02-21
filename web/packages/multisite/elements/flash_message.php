<?php if( is_array($flash) && !empty($flash) ): ?>
	<div class="ccm-ui">
		<div class="row">
			<div class="<?php echo $span_offset; ?>">
				<div class="alert alert-<?php echo $flash['type']; ?>">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<?php echo $flash['msg']; ?>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>