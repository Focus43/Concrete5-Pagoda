$(function(){

    var $calendarSelectList     = $('#calendarIDPicker'),
        $dataSourcesContainer   = $('#calendarDataSources');

    // add calendar to data source
    $calendarSelectList.on('change', function(){
        // hide the muted message
        $('span.muted').toggle(false);

        var $selected   = $('option:selected', this),
            _calendarID = $selected.val(),
            _name       = $selected.text();
        $selected.remove();

        // clone dom configuration options
        var $clonable = $('.calendarConfigs.clonable', '#schedulizerCalendarView'),
            $clone    = $clonable.clone(true).removeClass('clonable');
        $clone.appendTo($dataSourcesContainer).attr('data-calendar-id', _calendarID);

        // add title
        $('h4', $clone).text(_name);

        // set hidden input id
        $('input[name*="cal"]', $clone).each(function(idx, el){
            $(el).removeAttr('disabled');
            $(el).attr('name', $(el).attr('name').replace('#ID', _calendarID));
        });

        // bind color picker
        var $colorPickerBg = $('.calColorSwatch.bg', $clone);
        $colorPickerBg.ColorPicker({
            onSubmit: function(hsb, hex, rgb, el){
                $('input', $colorPickerBg).val('#'+hex);
                $colorPickerBg.css('background', '#'+hex);
                $(el).hide();
            },
            onShow: function(){
                $(this).ColorPickerSetColor( $('input', $colorPickerBg).val() );
            }
        });

        var $colorPickerText = $('.calColorSwatch.text', $clone);
        $colorPickerText.ColorPicker({
            onSubmit: function(hsb, hex, rgb, el){
                $('input', $colorPickerText).val('#'+hex);
                $colorPickerText.css('background', '#'+hex);
                $(el).hide();
            },
            onShow: function(){
                $(this).ColorPickerSetColor( $('input', $colorPickerText).val() );
            }
        });

        // reset it
        this.value = '';
    });

    // remove
    $dataSourcesContainer.on('click', '.removable', function(){
        var $calendarConfig = $(this).parent('.calendarConfigs'),
            _calendarID     = $calendarConfig.attr('data-calendar-id'),
            _name           = $('h4', $calendarConfig).text();

        // append back to the select list
        $calendarSelectList.append('<option value="'+_calendarID+'">'+_name+'</option>');

        // remove config area
        $calendarConfig.remove();

        // show the muted instructional message?
        if( !$('.calendarConfigs', $dataSourcesContainer).length ){
            $('span.muted').toggle(true);
        }
    });

    // toggle swatch pickers
    $dataSourcesContainer.on('change', 'input[data-toggle]', function(){
        var $this = $(this);
        $($this.attr('data-toggle'), $this.parents('.calendarConfigs')).toggle( $this.is(':checked') );
    });

    // tabs
    $('ul.nav.nav-tabs', '#blockFormSchedulizer').on('click', 'li', function(){
        if( !$(this).hasClass('pull-right') ){
            var $this = $(this);
            $this.addClass('active').siblings('li').removeClass('active');
            $( $this.attr('data-target')).addClass('active').siblings('.tab-pane').removeClass('active');
        }
    });
	
});
