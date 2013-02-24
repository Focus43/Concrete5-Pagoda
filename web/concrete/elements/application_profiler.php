<?php defined('C5_EXECUTE') or die("Access Denied."); // @app_profiler
	/** @var ApplicationProfilerReport $profile */
?>

	<style type="text/css">
		.apClearfix{*zoom:1;}.apClearfix:before,.apClearfix:after{display:table;content:"";}
		.apClearfix:after{clear:both;}
	
		#c5AppProfiler {font-size:12px;font-family:Arial, Helvetica, sans-serif;background:#1d1d1d;}
			#c5AppProfiler.active {border:1px solid #1d1d1d;border-bottom:0; -webkit-box-shadow: 0 1px 10px rgba(0,0,0,.5);-moz-box-shadow: 0 1px 10px rgba(0,0,0,.5);box-shadow: 0 1px 10px rgba(0,0,0,.5);}
			#c5AppProfiler a {color:#0085B2;cursor:pointer;}
		#c5ApWrap, #c5ApInner {color:#fff;position:relative;height:100% !important;min-height:100% !important;}
		#c5AppProfiler.active, #c5AppProfiler .roundTop {-webkit-border-radius:8px 8px 0 0;-moz-border-radius:8px 8px 0 0;border-radius:8px 8px 0 0;}
		
		#c5ApInner ul, #c5ApInner ul li {margin:0;padding:0;}
		
		#c5ApInner ul.apTypes {height:35px;border-bottom:1px solid #0a0a0a;display:block;margin:0;padding:0;list-style:none;background-color:#222;background-image:-moz-linear-gradient(top, #333333, #1d1d1d);background-image:-webkit-gradient(linear, 0 0, 0 100%, from(#333333), to(#1d1d1d));background-image:-webkit-linear-gradient(top, #333333, #1d1d1d);background-image:-o-linear-gradient(top, #333333, #1d1d1d);background-image:linear-gradient(to bottom, #333333, #1d1d1d);background-repeat:repeat-x;filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#33333333', endColorstr='#1d1d1d1d', GradientType=0);}
		#c5ApInner ul.apTypes li {display:inline-block;float:left;text-shadow: 0 1px 1px rgba(0,0,0,1);}
		#c5ApInner ul.apTypes li a,
		#c5ApInner ul.apTypes li span {color:#999;display:block;line-height:1em;padding:12px 14px 7px;}
		#c5ApInner ul.apTypes li.active a {color:#85B200;}
		#c5ApInner ul.apTypes li:hover a {color:#f1f1f1;}
		
		#c5AppPanes {position:absolute;top:36px;left:0;right:0;bottom:0;overflow:hidden;}
			#c5AppPanes div.panes {display:none;position:relative;width:100%;height:100% !important;min-height:100% !important;}
			#c5AppPanes div.panes.active {display:block;}
			#c5AppPanes div.panes div.left {position:absolute;left:0;top:0;bottom:0;right:80%;border-right:1px solid #000;overflow-y:auto;}
				#c5AppPanes div.panes div.left ul {list-style:none;margin:3px 0;padding:0;}
				#c5AppPanes div.panes div.left ul li {display:block;}
				#c5AppPanes div.panes div.left ul li a {display:block;padding:3px 8px;text-transform:capitalize;}
			#c5AppPanes div.panes div.right {position:absolute;left:20%;top:0;bottom:0;right:0;overflow-y:auto;}
			#c5AppPanes div.panes div.right.full {left:0;}
				#c5AppPanes div.panes div.right table.data {width:100%;max-width:100%;border-spacing:0;border-collapse:collapse;}
				#c5AppPanes div.panes div.right table.data thead tr th {font-weight:100;text-align:left;padding:5px 10px;text-transform:uppercase;font-size:11px;background:#333;white-space:nowrap;}
				#c5AppPanes div.panes div.right table.data tr td {vertical-align:top;white-space:nowrap;width:1%;padding:7px 10px;}
				#c5AppPanes div.panes div.right table.data tr td.tdLabel {text-transform:capitalize;}
				#c5AppPanes div.panes div.right table.data tr td.tdMiddle {width:100%;white-space:wrap;}
				#c5AppPanes div.panes div.right table.data tr td pre {font-family:inherit;padding:0;margin:0;display:block;white-space:pre-wrap;white-space:-moz-pre-wrap;white-space:-pre-wrap;white-space:-o-pre-wrap;word-wrap:break-word;}
		
				#c5AppPanes div.panes div.right table.data tr td .toggleable {display:none;}
				#c5AppPanes div.panes div.right table.data tr td .toggleable table {background:#2d2d2d;margin-top:8px;}
				#c5AppPanes div.panes div.right table.data tr td .toggleable table th,
				#c5AppPanes div.panes div.right table.data tr td .toggleable table td {background:none;padding:3px 8px;text-align:left;}
				
				#c5AppPanes h3 {font-size:16px;font-weight:100;}
				#c5AppPanes span.highlight {font-size:2em;background:rgba(0,0,0,.5);display:inline-block;line-height:1em;padding:9px;margin:0 5px;position:relative;top:5px; -webkit-border-radius:4px;-moz-border-radius:4px;border-radius:4px;}
				#c5AppPanes table.overview {width:70%;margin:40px auto;}
				#c5AppPanes table.overview tr td {text-align:center;}
				#c5AppPanes table.overview tr td p {font-size:2em;line-height:1em;margin:0 0 6px;}
	</style>

	<div id="c5ApInner">
		<ul class="apTypes apClearfix roundTop" data-toggle="pane">
			<li class="active" data-pane="#c5APOverview"><a>Overview</a></li>
			<li data-pane="#c5Console"><a>Console</a></li>
			<li data-pane="#c5Database"><a>Database</a></li>
			<li data-pane="#c5Memory"><a>Memory (Max: <?php echo $profile->getMemorySnapshot('profiler_end'); ?>)</a></li>
			<li data-pane="#c5Includes"><a>File Includes (Max: <?php echo count($profile->getLogObject('FileIncludeSnapshot', 'profiler_end')->data); ?>)</a></li>
			<li data-pane="#c5Exceptions"><a>Exceptions</a></li>
			<li style="float:right;"><span>Render: <?php echo $profile->calcRenderTime(); ?> s (Full Page Cache: <?php echo $profile->getLogObject('Mixed', 'page_cache_status')->data; ?>)</span></li>
		</ul>
		<div id="c5AppPanes">
			<div id="c5APOverview" class="panes active">
				<div class="right full">
					<div style="text-align:center;padding-top:20px;">
						<h3>Page Render Time <span class="highlight"><?php echo $profile->calcRenderTime(); ?> s</span> Full Page Cache? <span class="highlight"><?php echo $profile->getLogObject('Mixed', 'page_cache_status')->data; ?></span></h3>
						<table class="overview">
							<tr>
								<td>
									<p><?php echo $profile->countLogsByType('Mixed'); ?></p>
									Console Logs
								</td>
								<td>
									<p><?php echo $profile->countLogsByType('Database'); ?></p>
									Database Queries
								</td>
								<td>
									<p><?php echo $profile->countLogsByType('Memory'); ?></p>
									Memory Logs
								</td>
								<td>
									<p><?php echo $profile->countLogsByType('FileIncludeSnapshot'); ?></p>
									File Include Snapshots
								</td>
								<td>
									<p><?php echo $profile->countLogsByType('Database'); ?></p>
									Database Queries
								</td>
								<td>
									<p><?php echo $profile->countLogsByType('Exception'); ?></p>
									Exception Logs
								</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div id="c5Console" class="panes">
				<div class="left">
					<ul class="navToRow">
						<?php $consoleLogs = $profile->getLogsByType('Mixed');
						if( !empty($consoleLogs) ){ foreach( $consoleLogs AS $consoleLog ): ?>
							<li><a><?php echo $consoleLog->label; ?></a></li>
						<?php endforeach; } ?>
					</ul>
				</div>
				<div class="right">
					<table class="data">
						<thead>
							<tr>
								<th>Label</th>
								<th>Entity Type</th>
								<th>Entity</th>
								<th>Time Point</th>
							</tr>
						</thead>
						<tbody>
							<?php if( !empty($consoleLogs) ){ foreach( $consoleLogs AS $consoleLog ): ?>
								<tr>
									<td class="tdLabel"><?php echo $consoleLog->label; ?></td>
									<td><?php echo $consoleLog->type; ?></td>
									<td class="tdMiddle"><pre><?php echo $consoleLog->data; ?></pre></td>
									<td class="tdTime"><?php echo $profile->elapsedTime( $consoleLog->occurred ); ?></td>
								</tr>
							<?php endforeach; } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="c5Database" class="panes">
				<div class="right full">
					<table class="data">
						<thead>
							<tr>
								<th>Type</th>
								<th>SQL Query</th>
								<th>Execution Time ("Select" Only)</th>
							</tr>
						</thead>
						<tbody>							
							<?php $dbLogs = $profile->getLogsByType('Database');
							if( !empty($dbLogs) ){ foreach( $dbLogs AS $dbLog ): ?>
								<tr class="data-row">
									<td class="tdLabel">
										<?php if( !is_null($dbLog->data->explain)): ?>
											<a data-toggle="toggleable"><?php echo $dbLog->data->type; ?></a>
										<?php else: echo $dbLog->data->type; endif; ?>
									</td>
									<td class="tdMiddle">
										<pre><?php echo $dbLog->data->sql; ?></pre>
										<?php if( !is_null($dbLog->data->explain) ): ?>
											<div class="toggleable">
												<table>
													<thead>
														<tr>
															<?php foreach( array_keys($dbLog->data->explain) AS $key ){ ?>
																<th><?php echo $key; ?></th>
															<?php } ?>
														</tr>
													</thead>
													<tbody>
														<tr>
															<?php foreach( array_values($dbLog->data->explain) AS $v ){ ?>
																<th><?php echo $v; ?></th>
															<?php } ?>
														</tr>
													</tbody>
												</table>
											</div>
										<?php endif; ?>
									</td>
									<td class="tdTime"><?php echo $dbLog->data->time; ?></td>
								</tr>
							<?php endforeach; } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="c5Memory" class="panes">
				<div class="right full">
					<table class="data">
						<thead>
							<tr>
								<th>Label</th>
								<th>Memory Usage At Log Point</th>
								<th>Time Point</th>
							</tr>
						</thead>
						<tbody>
							<?php $memoryLogs = $profile->getLogsByType('Memory'); 
							if( !empty($memoryLogs) ){ foreach( $memoryLogs AS $memLog ): ?>
								<tr>
									<td class="tdLabel"><?php echo $memLog->label; ?></td>
									<td class="tdMiddle"><pre><?php echo $profile->getReadableFileSize( $memLog->data ); ?></pre></td>
									<td class="tdTime"><?php echo $profile->elapsedTime($memLog->occurred); ?></td>
								</tr>
							<?php endforeach; } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="c5Includes" class="panes">
				<div class="left">
					<ul class="navToRow">
						<?php $includesLogs = $profile->getLogsByType('FileIncludeSnapshot');
						if( !empty($includesLogs) ){ foreach( $includesLogs AS $include ): ?>
							<li><a><?php echo $include->label; ?></a></li>
						<?php endforeach; } ?>
					</ul>
				</div>
				<div class="right">
					<table class="data">
						<thead>
							<tr>
								<th>Label</th>
								<th>Logged Entity</th>
								<th>Time Point</th>
							</tr>
						</thead>
						<tbody>
							<?php if( !empty($includesLogs) ){ foreach( $includesLogs AS $include ): ?>
								<tr>
									<td class="tdLabel"><?php echo $include->label; ?></td>
									<td class="tdMiddle"><pre><?php print_r( $include->data ); ?></pre></td>
									<td class="tdTime"><?php echo $profile->elapsedTime( $include->occurred ); ?></td>
								</tr>
							<?php endforeach; } ?>
						</tbody>
					</table>
				</div>
			</div>
			<div id="c5Exceptions" class="panes">
				<div class="right full">
					<table class="data">
						<thead>
							<tr>
								<th>Label</th>
								<th>Logged Entity</th>
								<th>Time Point</th>
							</tr>
						</thead>
						<tbody>
							<?php $exceptionLogs = $profile->getLogsByType('Exception'); 
							if( !empty($exceptionLogs) ){ foreach( $exceptionLogs AS $handle => $exception ): ?>
								<tr class="data-row">
									<td class="tdLabel"><a data-toggle="toggleable"><?php echo $handle; ?></a></td>
									<td class="tdMiddle">
										<pre><?php echo $exception->label; ?> (File: <?php echo $exception->data->file; ?>, Line: <?php echo $exception->data->line; ?>)</pre>
										<div class="toggleable">
											<pre><?php print_r( $exception->data->object ); ?></pre>
										</div>
									</td>
									<td class="tdTime"><?php echo $profile->elapsedTime( $exception->occurred ); ?></td>
								</tr>
							<?php endforeach; } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>