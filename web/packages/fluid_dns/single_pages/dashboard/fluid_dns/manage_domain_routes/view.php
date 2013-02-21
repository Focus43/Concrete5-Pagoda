<?php Loader::packageElement('flash_message', 'fluid_dns', array('flash' => $flash)); ?>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('FluidDNS Manager'), t('Route Domains/Subdomains to Specific Page Roots'), false, false ); ?>
	
	<div id="msManager" class="ccm-pane-body">
		<div class="clearfix">
			<h3 class="lead pull-left">FluidDNS <small>Dynamically route domains/subdomains to page roots.</small></h3>
			<div class="pull-right">
				<select id="actionMenu" class="pull-right" disabled="disabled">
					<option value="">-- Actions --</option>
					<option value="delete">Delete</option>
				</select>
			</div>
		</div>
		
		<table id="domainsList" border="0" cellspacing="0" cellpadding="0" class="ccm-results-list">
			<thead>
				<tr>
					<th><input id="checkAllBoxes" type="checkbox" /></th>
					<th>Domain</th>
					<th>Root Page</th>
					<th>Resolve Wildcard Subdomains</th>
					<th>Match Wildcards Under</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($domainsList AS $domainObj): ?>
					<tr>
						<td><?php echo $form->checkbox('domainID[]', $domainObj->getID()); ?></td>
						<td><a href="<?php echo $this->action('edit', $domainObj->getID()) ?>"><?php echo $domainObj->getDomain(); ?></a></td>
						<td class="helpTooltip" title="<?php echo $domainObj->getPath(); ?>"><?php echo Page::getByID( $domainObj->getPageID() )->getCollectionName(); ?></td>
						<td><?php echo (bool) $domainObj->getResolveWildcards() ? 'Yes' : 'No'; ?></td>
						<td class="helpTooltip" title="<?php echo $domainObj->getWildcardRootPath(); ?>"><?php echo Page::getByID( $domainObj->getWildcardParentID() )->getCollectionName(); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="btns clearfix">
			<a id="updateDomainCache" class="btn pull-left">Update Domain Cache</a>
			<a class="btn primary pull-right" href="<?php echo $this->action('add'); ?>">Add Root Domain</a>
		</div>
		
	</div>
	
	<div class="ccm-pane-footer"></div>
	
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>