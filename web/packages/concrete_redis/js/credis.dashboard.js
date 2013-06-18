
	var Credis;
	
	$(function(){
		
		Credis = new function(){
			
			var $actionMenu = $('#actionMenu');
			
			// select all checkboxes
			$('#checkAllBoxes').on('click', function(){
				var $this  = $(this),
					checkd = $this.is(':checked');
				$(':checkbox', 'table#tblRedisData tbody').prop('checked', checkd).trigger('change');
			});
			
			
			// if any box is checked, enable the actions dropdown
			$('tbody', '#tblRedisData').on('change', ':checkbox', function(){
				if( $(':checkbox', '#tblRedisData > tbody').filter(':checked').length ){
					$actionMenu.prop('disabled', false);
					return;
				}
				$actionMenu.attr('disabled', true);
			});
			
			
			// actions menu
			$actionMenu.on('change', function(){
				var $this	= $(this),
					$checkd = $('tbody', '#tblRedisData').find(':checkbox').filter(':checked'),
					data   	= $checkd.serializeArray();
				
				switch( $this.val() ){
					case 'delete':
						if( confirm('Purge the selected keys from Redis?') ){
							var _path = $('#redisDeleteKeyPath').attr('value');
							$.post( _path, data, function(resp){
								if( resp.code == 1 ){
									$checkd.parents('tr').fadeOut(150);
								}else{
									alert('An error occurred. Try again later.');
								}
							}, 'json');
						}
						break;
				}
				
				// reset the menu
				$this.val('');
			});
			
		}
		
	});
