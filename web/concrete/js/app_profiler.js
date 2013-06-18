
	// @app_profiler namespace	
	var C5AppProfiler;
	
	$(function(){
		
		// css declarations
		var _cssContainer = {position:'fixed',bottom:0,left:7,right:7,top:'100%','font-family':'Arial, Verdana, sans-serif','z-index':9999},
			_cssWrapper	  = {position:'relative'},
			_cssTrigger   = {background:'#222',color:'#fff',position:'absolute',bottom:'100%','margin-bottom':7,right:0,padding:'3px 5px 2px','line-height':'1em','font-size':10,'text-transform':'uppercase',cursor:'pointer',border:'1px solid #000',opacity:.5,'border-radius':20};
		
		// instantiate
		C5AppProfiler = new function(){
			
			var _self 		= this,
				$document	= $(document),
				$body		= $('body'),
				$metaTag	= $('meta[id="c5App-Profiler"]'),
				// DOM elements to append
				$container	= $('<div id="c5AppProfiler" />').css(_cssContainer),
				$wrapper	= $('<div id="c5ApWrap" />').css(_cssWrapper),
				$trigger	= $('<a id="c5ApTrigger">Show Profiler</a>').css(_cssTrigger);

			
			function displayToggle(){
				if( $trigger.data('show') ){
					$container.addClass('active').animate({top:'34%'}, 200);
					$trigger.text('Hide Profiler');
				}else{
					$container.animate({top:'100%'}, 200, function(){
						$container.removeClass('active');
						$trigger.text('Show Profiler');
					});
				}
			}
			
			
			function initBindings(){
				// [data-toggle] is the parent (this is for top tabs)
				$('[data-toggle]', $container).on('click', '[data-pane]', function(){
					var $this = $(this),
						_pane = $this.attr('data-pane');
					$this.addClass('active').siblings('[data-pane]').removeClass('active');
					$(_pane).addClass('active').siblings('.panes').removeClass('active');
				});
				
				// "hideable" extra data
				$container.on('click', 'a[data-toggle]', function(){
					var $this = $(this),
						what  = $this.attr('data-toggle');
					$this.parents('tr.data-row').find('.' + what).slideToggle(200);
					$('table.data > tbody', 'panes.active').find('tr').hide().filter(':eq('+index+')').show();
				});
				
				// nav to data row
				$('ul.navToRow', $container).on('click', 'a', function(){
					var $this = $(this),
						$li	  = $this.parent('li'),
						index = $li.index();
					$('.panes.active table.data tbody tr', $container).hide().filter(':eq('+index+')').show();
				});
			}
			
				
			// does the meta tag exist on the page? then proceed
			if( $metaTag.length ){
				
				// append DOM elements
				$container.append( $wrapper.append($trigger) ).appendTo($body);
				
				// hide or display the profiler; load data if first run
				$trigger.on('click', function(){
					$trigger.data({show: !$trigger.data('show') });
					
					// need to load the profiler?
					if( !$trigger.data('loaded') ){
						$trigger.text('Loading ...');
						
						var profileID	= $metaTag.attr('value'),
							profileURL	= $metaTag.attr('data-url');
							
						// ajax call; load profiler
						$.get( profileURL, {id: profileID}, function( _html ){
							$trigger.data({loaded: true});
							$wrapper.append( _html );
							initBindings();
							displayToggle();
						}, 'html');
						
						return;
					}

					displayToggle();
				});
				
			}else{
				console.log('App profiler enabled; but error occurred.');
			}
		}
		
	});
