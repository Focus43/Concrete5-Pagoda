
	var Multisite;

	$(function(){
		
		Multisite = new function(){
			
			var _self = $(this);
			
			// tabs
			$('a', '#msTabs').on('click', function( _event ){
				_event.preventDefault();
				var $this = $(this),
					$targ = $( $this.attr('href') );
				$this.parent('li').addClass('active').siblings('li').removeClass('active');
				$targ.addClass('active').siblings('.tab-pane').removeClass('active');
			});
			
		}
		
	});