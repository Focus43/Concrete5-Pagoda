
    var SchedulizerDashboard;

    $(function(){

        SchedulizerDashboard = (function(){

            var $document = $(document),
                _toolsURI = $('meta[name="schedulizer-tools"]', 'head').attr('content'),
                $actionMenu = $('#actionMenu');


            /**
             * Close the top-most dialog window
             * @param int _afterMilliseconds Close the window after...
             * @param function _callback Callback function once window closes
             */
            function closeTopDialog( _afterMilliseconds, _callback ){
                setTimeout(function(){
                    $.fn.dialog.closeTop();

                    // if a callback is passed...
                    if( $.isFunction(_callback) ){
                        _callback.call();
                    }
                }, _afterMilliseconds || 0);
            }


            /**
             * Ajaxify form handler
             */
            if( $.fn.ajaxifyForm ){
                $('form[data-method="ajax"]').ajaxifyForm();
                // on complete callback
                $(document).on('ajaxify_complete', 'form[data-method="ajax"]', function(ev, resp){
                    $('.ui-dialog-content').scrollTop(0);
                    if(resp.code === 1){
                        closeTopDialog(1000, function(){
                            $('#schedulizerCalendar').fullCalendar('refetchEvents');
                        });
                    }
                });
            }


            /**
             * Tab functionality (mimics twitter bootstrap's thats missing in C5)
             */
            $document.on('click', 'a[data-toggle="tab"]', function(_clickEvent){
                _clickEvent.preventDefault();
                var $this = $(this);
                $this.parent('li').addClass('active').siblings('li').removeClass('active');
                $( $this.attr('href') ).addClass('active').siblings('.tab-pane').removeClass('active');
                // emit custom on_show event
                $this.trigger('tab.show');

                // init functions, if applicable
                if( $this.attr('data-init') ){
                    switch( $this.attr('data-init') ){
                        case 'properties':
                            $('#calendar_timezone').chosen();
                            break;
                    }
                }
            });


            function editEventDialog( calEvent ){
                $.fn.dialog.closeTop();
                $.fn.dialog.open({
                    width: calEvent.isAlias === 1 ? 430 : 650,
                    height: calEvent.isAlias === 1 ? 110 : 550,
                    title: 'Editing Event: ' + calEvent.title,
                    href: _toolsURI + 'dashboard/events/edit?' + $.param({
                        eventID: calEvent.id,
                        isAlias: calEvent.isAlias//,
                        //eventCalendarStart: calEvent.start.toISOString()
                    })
                });
            }


            $(document).on('click', '#btnEditOriginal', function(){
                var $button  = $(this);
                editEventDialog({
                    id    : $button.attr('data-id'),
                    title : $button.attr('data-title')
                });
            });


            $(document).on('submit', '#frmDeleteEvent', function( _event ){
                _event.preventDefault();
                var $form = $(this),
                    _url =  $form.attr('action');
                if( confirm('Delete this event (and all events in series if this is a repeating event)?') ){
                    $.post(_url, function(resp){
                        $form.hide();
                        $('#eventSetupForm').empty().append('<h3 style="text-align:center;">'+resp.messages[0]+'</h3>');
                        closeTopDialog(1800, function(){
                            $('#schedulizerCalendar').fullCalendar('refetchEvents');
                        });
                    }, 'json');
                }
            });


            /**
             * Listen for on_show from the custom tab click handler (emits tab.show event),
             * and init the calendar. This runs *once* only, hence the .one() method.
             */
            function initCalendar(){
                var $calendar   = $('#schedulizerCalendar'),
                    _calendarID = +($calendar.attr('data-calendar-id'));

                $calendar.fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    editable: true,
                    defaultView: 'month',

                    // load event data
                    events: _toolsURI + 'dashboard/events/feed?' + $.param({
                        calendarID: _calendarID
                    }),

                    // open a dialog and create a new event on the specific day
                    dayClick: function(date, allDay, jsEvent, view){
                        var _data = $.param({
                            calendarID: _calendarID,
                            year: date.getUTCFullYear(),
                            month: date.getUTCMonth() + 1,
                            day: date.getUTCDate(),
                            hour: date.getUTCHours(),
                            min: date.getUTCMinutes(),
                            allDay: allDay
                        });

                        // launch the dialog and pass appropriate data
                        $.fn.dialog.open({
                            width:650,
                            height:550,
                            title: 'New Event: ' + date.toLocaleDateString(),
                            href: _toolsURI + 'dashboard/events/new?' + _data
                        });
                    },

                    // open a dialog to edit an existing event
                    eventClick: function(calEvent, jsEvent, view){
                        editEventDialog(calEvent);
                    },

                    eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc){
                        // if its a repeating event, show warning
                        if( event.isRepeating === 1 ){
                            if( event.repeatMethod !== 'daily' ){
                                ccmAlert.hud('Events that repeat ' + event.repeatMethod + ' cannot be dragged/dropped.', 2000, 'error');
                                revertFunc.call();
                                return;
                            }
                            if( ! confirm('This is a repeating event and will affect all other events in the series. Proceed?') ){
                                revertFunc.call();
                                return;
                            }
                        }

                        // append day and minute deltas to the event object
                        event.dayDelta    = dayDelta;
                        event.minuteDelta = minuteDelta;

                        // then send the whole shebang
                        $.post( _toolsURI + 'dashboard/events/calendar_handler_drop', event, function( _respData ){
                            if( _respData.code === 1 ){
                                ccmAlert.hud(_respData.msg, 2000, 'success');
                            }else{
                                ccmAlert.hud('Error occurred adjusting the event length', 2000, 'error');
                            }
                        }, 'json');
                    },

                    eventResize: function(event, dayDelta, minuteDelta, revertFunc){
                        // if its a repeating event, show warning
                        if( event.isRepeating === 1 ){
                            if( ! confirm('This is a repeating event and will affect all other events in the series. Proceed?') ){
                                revertFunc.call();
                                return;
                            }
                        }

                        // append day and minute deltas to the event object
                        event.dayDelta    = dayDelta;
                        event.minuteDelta = minuteDelta;

                        // then send the whole shebang
                        $.post( _toolsURI + 'dashboard/events/calendar_handler_resize', event, function( _respData ){
                            if( _respData.code === 1 ){
                                ccmAlert.hud(_respData.msg, 2000, 'success');
                            }else{
                                ccmAlert.hud('Error occurred adjusting the event length', 2000, 'error');
                            }
                        }, 'json');
                    }
                });
            };


            /**
             * When a parent element is wrapping a child checkbox or radio button,
             * this will ensure correct toggling.
             */
            $document.on('click', '[data-toggle-input]', function(){
                var $this  = $(this),
                    $input = $('input', this);

                // checkbox
                if( $this.attr('data-toggle-input') === 'checkbox' ){
                    $this.toggleClass('active');
                    $input.attr('checked', !($input.attr('checked')));
                    return;
                }

                // radio button
                $this.addClass('active');
                $input.attr('checked', true);
                var $siblings = $this.siblings('[data-toggle-input]').removeClass('active');
                $('input', $siblings).attr('checked', false);
            });


            /**
             * When a checkbox should toggle the visibility of some other stuff.
             */
            $document.on('change', '[data-viz-checked], [data-viz-unchecked]', function(){
                var $this        = $(this),
                    _state       = $this.is(':checked'),
                    $attrChecked = $this.attr('data-viz-checked');

                // if toggle to visible when checked
                if( $attrChecked ){ $( $attrChecked ).toggle(_state); return; }

                // otherwise toggle to visible when unchecked
                $( $this.attr('data-viz-unchecked')).toggle(!_state);
            });


            // select all checkboxes
            $('#checkAllBoxes').on('click', function(){
                var $this  = $(this),
                    checkd = $this.is(':checked');
                $(':checkbox', '#schedulizerSearchTable tbody').prop('checked', checkd).trigger('change');
            });


            // if any box is checked, enable the actions dropdown
            $('tbody', '#schedulizerSearchTable').on('change', ':checkbox', function(){
                if( $(':checkbox', '#schedulizerSearchTable > tbody').filter(':checked').length ){
                    $actionMenu.prop('disabled', false);
                    return;
                }
                $actionMenu.attr('disabled', true);
            });


            // actions menu
            $actionMenu.on('change', function(){
                var $this	= $(this),
                    tools  	= $('[name="schedulizer-tools"]').attr('content'),
                    $checkd = $('tbody', '#schedulizerSearchTable').find(':checkbox').filter(':checked'),
                    data   	= $checkd.serializeArray();

                switch( $this.val() ){
                    case 'delete':
                        if( confirm('Delete the selected calendars? This cannot be undone.') ){
                            $.post( tools + 'dashboard/delete_calendars', data, function(resp){
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


            /**
             * Initialize the calendar, if the container exists in the DOM
             */
            if( $('#schedulizerCalendar').length ){
                initCalendar();
            }


            /** Public methods; @return Object */
            return {
                // expose toolsURI property
                toolsURI: _toolsURI,

                initCalendar: initCalendar,

                // proxy directly to the internal closeTopModal method
                closeTopDialog: closeTopDialog,

                // called after a new/editable event management window is loaded
                initEventWindow: function( _settings ){
                    // datepickers
                    $('input.dp-element', '#eventSetupForm').datepicker({
                        onClose: function(){
                            if( $(this).is('#event_start_date') ){
                                var minDate = $('#event_start_date').datepicker('getDate');
                                $('#event_end_date').datepicker('option', 'minDate', minDate);
                                $('#event_repeat_end').datepicker('option', 'minDate', minDate);
                                $('#repeatMonthlySpecificDay').val( minDate.getDate() );
                            }
                        }
                    });

                    // jqChosen
                    $('select.chzn-element', '#eventSetupForm').chosen();

                    // recurring frequency change display text
                    $('[name*="repeatTypeHandle"]').on('change', function(){
                        var _value = this.value;

                        // set the text label
                        $('#recurTextLabel').text(function(){
                            switch(_value){
                                case 'daily': return 'days';
                                case 'weekly': return 'weeks';
                                case 'monthly': return 'months';
                                case 'yearly': return 'years';
                            };
                        });

                        // toggle visibility of extra repeat options (daily/monthly only),
                        // and if a container is *not* visible, disable its' inputs
                        $('.repeat-options', '#eventSetupForm').each(function(_idx, _container){
                            var $container = $(_container),
                                $inputs    = $(':input', _container);
                            $container.toggle( _value === $container.attr('data-show-on') );
                            $inputs.prop('disabled', !$container.is(':visible'));
                        });

                    }).trigger('change'); // fire it so that javascript sets the correct value on_load

                    // if its an all day event, hide the jqChosen time selections
                    if( _settings.isAllDay ){
                        $('#event_start_time_chzn, #event_end_time_chzn').toggle(false);
                    }
                }
            }
        })();

    });