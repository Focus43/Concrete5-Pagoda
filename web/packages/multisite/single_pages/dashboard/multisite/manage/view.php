<?php Loader::packageElement('flash_message', 'multisite', array('flash' => $flash)); ?>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Multisite Manager'), t('Manage multiple domains / subdomains'), false, false ); ?>
	
	<div id="msManager" class="ccm-pane-body">
		<ul id="msTabs" class="nav nav-tabs clearfix">
			<li class="active"><a href="#msPane1">Root Domains</a></li>
			<li class="pull-right">
				<select class="pull-right">
					<option value="">Actions</option>
				</select>
			</li>
		</ul>
		
		<div class="tab-content">
			<div id="msPane1" class="tab-pane active">
				<table border="0" cellspacing="0" cellpadding="0" class="ccm-results-list">
					<thead>
						<tr>
							<th>Domain</th>
							<th>Root Page</th>
							<th>Resolves To (Sitemap Location)</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($domainsList AS $domainObj): ?>
							<tr>
								<td><?php echo $domainObj->getDomain(); ?></td>
								<td><?php echo Page::getByPath( $domainObj->getPath() )->getCollectionName(); ?></td>
								<td><?php echo $domainObj->getPath(); ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<div class="btns clearfix">
					<a class="btn primary pull-right" href="<?php echo $this->action('add'); ?>">Add Root Domain</a>
				</div>
			</div>
			<div id="msPane2" class="tab-pane">
				subs
			</div>
		</div>
	</div>
	
	<div class="ccm-pane-footer"></div>
	
<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>