<?php Loader::packageElement('flash_message', 'fluid_dns', array('flash' => $flash, 'span_offset' => $span_offset));
	$formAction = $domainObj->getID() >= 1 ? $this->action('update', $domainObj->getID()) : $this->action('create');
?>

<form id="frmRootDomain" method="post" action="<?php echo $formAction; ?>">
	<div class="span10 offset1">
		<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('FluidDNS Manager'), t('Route Domains/Subdomains to Specific Page Roots'), false, false ); ?>
		<div id="msManager" class="ccm-pane-body">
			<div class="container-fluid">
				<h3 class="lead">Domain Route Settings <small>Configure root or subdomain routes to specific pages.</h3>
				<div class="row-fluid">
					<div class="span7">
						<div class="well">
							<label>Domain Name <span class="muted">Format: mydomain.com</span></label>
							<?php echo $form->text('root_domain', $domainObj->getDomain(), array('class'=>'input-block-level','placeholder'=>'Exclude http:// or www.')); ?>
							<label style="margin-top:.8em;">Resolves To</label>
							<?php echo $form_page_selector->selectPage('pageID', $domainObj->getPageID()); ?>
						</div>
					</div>
					<div class="span5">
						<label class="checkbox">
							<?php echo $form->checkbox('resolveWildcards', 1, $domainObj->getResolveWildcards()); ?> Resolve Wildcard Subdomains
							<i class="icon-question-sign helpTip" title="Wildcard Subdomains" data-content="Enables dynamic subdomain routing. With wildcard DNS settings, one could enter http://alpha.mydomain.com, which would search for page /wildcard-parent-path/alpha, if the page exists."></i>
						</label>
						<div class="toggleable" data-listen="resolveWildcards" style="<?php if ((bool)$domainObj->getResolveWildcards()){ echo 'display:block;'; } ?>">
							<div class="well">
								<label>Wildcard Parent</label>
								<?php echo $form_page_selector->selectPage('wildcardParentID', $domainObj->getWildcardParentID()); ?>
							</div>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<input type="submit" class="btn primary block btn-large" value="Save Root Domain" />
					</div>
				</div>
			</div>
		</div>
		
		<div class="ccm-pane-footer"></div>
		<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>
	</div>
</form>
