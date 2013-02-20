<form id="frmRootDomain" method="post" action="<?php echo $this->action('create'); ?>">
	<div class="span10 offset1">
		<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Multisite Manager'), t('Manage multiple domains / subdomains'), false, false ); ?>
		<div id="msManager" class="ccm-pane-body">
			<div class="container-fluid">
				<h3 class="lead">Root Domain Settings <small>Configure settings for a <i>base</i> domain (eg. mydomain.com)</small></h3>
				<div class="row-fluid">
					<div class="span7">
						<div class="well">
							<label>Domain Name <span class="muted">Format: mydomain.com</span></label>
							<?php echo $form->text('root_domain', '', array('class'=>'input-block-level','placeholder'=>'Exclude http:// or www.')); ?>
							<label style="margin-top:.8em;">Resolves To</label>
							<?php echo $form_page_selector->selectPage('pageID'); ?>
						</div>
					</div>
					<div class="span5">
						<label class="checkbox">
							<?php echo $form->checkbox('resolveWildcards', 1); ?> Resolve Wildcard Subdomains
							<i class="icon-question-sign helpTip" title="Wildcard Subdomains" data-content="Enables dynamic subdomain routing. With wildcard DNS settings, one could enter http://alpha.mydomain.com, which would search for page /wildcard-parent-path/alpha, if the page exists."></i>
						</label>
						<div class="toggleable" data-listen="resolveWildcards">
							<div class="well">
								<label>Wildcard Parent</label>
								<?php echo $form_page_selector->selectPage('wildcardParentID'); ?>
							</div>
						</div>
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
