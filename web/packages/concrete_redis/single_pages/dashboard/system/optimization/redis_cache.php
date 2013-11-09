<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('Redis Cache'), t('Use Redis as a Cache to speed things up'), false, false ); ?>

    <div id="redisExplorer" class="ccm-pane-body">

        <?php if( $this->controller->getTask() == 'explore_key' ): ?>

            <h3 style="font-weight:200;margin-bottom:.6em;">
                <?php
                if( !($hashName === null) ){
                    echo "<strong>Hash Name:</strong> {$hashName}, <strong>Key:</strong> {$redisKey}";
                }else{
                    echo "<strong>Key:</strong> {$redisKey}";
                }
                ?>
            </h3>

            <div class="well">
                <h4 style="margin-bottom:.6em;">Object Data</h4>
				<pre>
					<?php if( !$keyData ){ echo $rawKeyData; }else{ print htmlspecialchars(print_r($keyData, true)); } ?>
				</pre>
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

            <div class="clearfix">
                <h3 class="lead pull-left">ConcreteRedis <small>Explore Redis Cache Data</small></h3>
                <div class="pull-right">
                    <select id="actionMenu" class="pull-right" disabled="disabled">
                        <option value="">-- Actions --</option>
                        <option value="delete">Delete</option>
                    </select>
                </div>
            </div>

            <table id="tblRedisData" border="0" cellspacing="0" cellpadding="0" class="ccm-results-list" style="margin-bottom:0;">
                <thead>
                <tr>
                    <th><input id="checkAllBoxes" type="checkbox" /></th>
                    <th>Redis Key</th>
                    <th>Type</th>
                    <th>Object Length</th>
                    <th>TTL</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($cacheKeys AS $cKey => $data): ?>
                    <tr>
                        <td><?php echo $form->checkbox('redisKeyID[]', $cKey); ?></td>
                        <td class="key">
                            <?php if($data->type == 'hash'){ ?>
                                <a href="<?php echo $this->action('explore_hash', $cKey); ?>"><?php echo $cKey; ?></a>
                            <?php }else{ ?>
                                <a href="<?php echo $this->action('explore_key', $cKey); ?>"><?php echo $cKey; ?></a>
                            <?php } ?>
                        </td>
                        <td><?php echo ucfirst( $data->type ); ?></td>
                        <td><?php echo $data->len; ?></td>
                        <td><?php echo $data->ttl; ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <!-- output the ajax delete path only on this "page" because it can't be used
                 anywhere else -->
            <meta id="redisDeleteKeyPath" value="<?php echo $this->action('delete_keys'); ?>" />

        <?php endif; ?>

    </div>

    <div class="ccm-pane-footer"></div>

<?php echo Loader::helper('concrete/dashboard')->getDashboardPaneFooterWrapper(false); ?>