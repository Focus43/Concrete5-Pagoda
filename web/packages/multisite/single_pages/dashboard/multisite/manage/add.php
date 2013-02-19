
	<form method="post" action="<?php echo $this->action('create'); ?>">
		<div class="span10 offset1">
			<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Multisite Manager'), t('Manage multiple domains / subdomains'), false, false ); ?>
			<div id="msManager" class="ccm-pane-body">
				<div class="container-fluid">
					<div class="row-fluid spaced">
						<div class="span12">
							<label>Root Domain <span class="label">Exclude http:// or www.</span></label>
							<?php echo $form->text('root_domain', '', array('class'=>'input-block-level','placeholder'=>'mydomain.com')); ?>
						</div>
					</div>
					<div class="row-fluid spaced">
						<div class="span12">
							<label>Resolves To</label>
							<?php echo $form_page_selector->selectPage('pageID'); ?>
						</div>
					</div>
					<div class="row-fluid">
						<div class="span12">
							<input type="submit" class="btn primary block" value="Add Root Domain" />
						</div>
					</div>
				</div>
			</div>
			
			<div class="ccm-pane-footer"></div>
			<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>
		</div>
	</form>
	