<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Redis Cache'), t('Use Redis as a Cache to speed things up'), false, false ); ?>

	<div id="redisExplorer" class="ccm-pane-body">

		<?php if( $this->controller->getTask() == 'explore_key' ): ?>
			
			<h3 style="font-weight:200;margin-bottom:.6em;">
				<?php
					if( !($hashName === null) ){
						echo "<strong>Hash Name:</strong> {$hashName}, <strong>Key:</strong> {$hashKey}";
					}else{
						echo "<strong>Key:</strong> {$hashKey}";
					}
				?>
			</h3>
			
			<div class="well">
				<h4 style="margin-bottom:.6em;">Object Data</h4>
				<pre><?php print_r( $keyData ); ?></pre>
			</div>

		<?php elseif( $this->controller->getTask() == 'explore_hash' ): ?>
			
			<?php if( is_array($hashKeys) ){ ?>
				
				<div class="container-fluid">
					<?php foreach( array_chunk($hashKeys, 3) AS $group ): ?>
						<div class="row-fluid">
							<?php foreach( $group AS $hKey ){ ?>
								<div class="span4"><a href="<?php echo $this->action('explore_key', $hKey, $hashName); ?>"><?php echo $hKey; ?></a></div>
							<?php } ?>
						</div>
					<?php endforeach; ?>
				</div>
				
			<?php } ?>
			
		<?php else: ?>
			
			<style type="text/css">
				#redisExplorer tbody tr td {white-space:nowrap;padding-left:8px;padding-right:16px;}
				#redisExplorer tbody tr td.key {width:99%;}
			</style>
			
			<h3 class="lead" style="margin-bottom:.6em;">ConcreteRedis <small>Explore Redis Cache Data</small></h3>
			
			<table id="redisExplorer" border="0" cellspacing="0" cellpadding="0" class="ccm-results-list" style="margin-bottom:0;">
				<thead>
					<tr>
						<th>Redis Key</th>
						<th>Type</th>
						<th>Object Length</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($cacheKeys AS $cKey => $data): ?>
						<tr>
							<td class="key">
								<?php if($data->type == 'hash'){ ?>
									<a href="<?php echo $this->action('explore_hash', $cKey); ?>"><?php echo $cKey; ?></a>
								<?php }else{ ?>
									<a href="<?php echo $this->action('explore_key', $cKey); ?>"><?php echo $cKey; ?></a>
								<?php } ?>
							</td>
							<td><?php echo ucfirst( $data->type ); ?></td>
							<td><?php echo $data->len; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		
		<?php endif; ?>
	
	</div>

	<div class="ccm-pane-footer"></div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>