<? defined('C5_EXECUTE') or die("Access Denied."); ?>

	<?=Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('File Storage Locations'), false, 'span6 offset3', false)?>

	<form method="post" class="form-inline" id="file-access-storage" action="<?=$this->url('/dashboard/system/environment/file_storage_locations', 'save')?>">
	<div class="ccm-pane-body">
			<?=$validation_token->output('file_storage');?>
			<fieldset>
			<legend><?=t('Standard File Location')?></legend>
			<div class="control-group">
			<label class="control-label" for="DIR_FILES_UPLOADED"><?=t('Path')?></label>
			<div class="controls">
			<?=$form->text('DIR_FILES_UPLOADED', DIR_FILES_UPLOADED, array('rows'=>'2','class' => 'span5'))?>
			</div>
			</div>
			
			</fieldset>
			<fieldset>
			<legend><?=t('Alternate Storage Directory')?></legend>
			
			<div class="control-group">
			<label for="fslName" class="control-label"><?=t('Location Name')?></label>
			<div class="controls">
			<?=$form->text('fslName', $fslName, array('class' => 'span5'))?>
			</div></div>
			<div class="control-group">
			<label for="fslDirectory" class="control-label"><?=t('Path')?></label>
			<div class="controls">
			<?=$form->text('fslDirectory', $fslDirectory, array('rows' => '2', 'class' => 'span5'))?>
			</div></div>
			</fieldset>
	</div>
	<div class="ccm-pane-footer">
		<?php		
			$b1 = $concrete_interface->submit(t('Save'), 'file-storage', 'right', 'primary');
			print $b1;
		?>		
		<? if (is_object($fsl)) { ?>
			<button type="submit" name="delete" value="1" onclick="return confirm('<?=t('Are you sure? (Note: this will not remove any files, it will simply remove the pointer to the directory, and reset any files that are set to this location.)')?>')" class="pull-right btn btn-danger"><?=t('Delete Alternate')?></button>
		<? } ?>

	</div>
	</form>

	<?=Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false)?>
