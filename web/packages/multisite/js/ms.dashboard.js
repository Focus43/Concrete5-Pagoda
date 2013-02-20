
	var Multisite;

	$(function(){
		
		Multisite = new function(){
			
			var _self		= $(this),
				$document	= $(document),
				$actionMenu = $('#actionMenu');
			
			// tabs
			$('a', '#msTabs').on('click', function( _event ){
				_event.preventDefault();
				var $this = $(this),
					$targ = $( $this.attr('href') );
				$this.parent('li').addClass('active').siblings('li').removeClass('active');
				$targ.addClass('active').siblings('.tab-pane').removeClass('active');
			});
			
			
			// tooltips and popover bindings
			$document.tooltip({
				animation: false,
				selector: '.helpTooltip',
				trigger: 'hover'
			}).popover({
				animation: false,
				selector: '.helpTip',
				placement: 'bottom'
			});
			
			
			// used to show/hide options on the add root domain page
			$('#frmRootDomain').on('click', ':checkbox', function(){
				var $this  = $(this),
					checkd = $this.is(':checked'),
					name   = $this.attr('name');
				$('[data-listen="'+name+'"]').toggle(checkd);
			});
			
			
			// select all checkboxes
			$('#checkAllBoxes').on('click', function(){
				var $this  = $(this),
					checkd = $this.is(':checked');
				$(':checkbox', 'table#domainsList tbody').prop('checked', checkd).trigger('change');
			});
			
			
			// if any box is checked, enable the actions dropdown
			$('tbody', '#domainsList').on('change', ':checkbox', function(){
				if( $(':checkbox', '#domainsList > tbody').filter(':checked').length ){
					$actionMenu.prop('disabled', false);
					return;
				}
				$actionMenu.attr('disabled', true);
			});
			
			
			// actions menu
			$actionMenu.on('change', function(){
				var $this	= $(this),
					tools  	= $('#multisite_tools').attr('value'),
					$checkd = $('tbody', '#domainsList').find(':checkbox'),
					data   	= $checkd.serializeArray();
				
				switch( $this.val() ){
					case 'delete':
						if( confirm('Delete the selected domains?') ){
							$.post( tools + 'delete_records', data, function(resp){
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