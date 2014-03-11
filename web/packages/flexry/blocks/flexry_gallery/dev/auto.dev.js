$(function(){
    /**
     * Make items sortable. Should be called after new image are added to ensure always
     * initialized.
     * @return void
     */
    function initInteractions(){
        $('.inner', '#imageSelections').sortable({handle:'.icon-move',items: '.item', cursor: 'move', containment: 'parent', opacity: .65, tolerance: 'pointer'});
    }


    /**
     * Run after new images are added; remove if any duplicates occurred and show the alert
     * message.
     * @return void
     */
    function scanAndPurgeDuplicates(){
        // variables; _duplicates defaults to false
        var _list = [], _duplicates = false;
        $('input[type="hidden"]', '#imageSelections').each(function(idx, input){
            var $input = $(input), _fileID = $input.val();
            if( _list.indexOf(_fileID) === -1 ){
                _list.push(_fileID);
            }else{
                _duplicates = true;
                $input.parent('.item').remove();
            }
        });
        // toggle class
        $('#tabPaneImages').toggleClass('dups', _duplicates);
    }


    /**
     * Launch the asset manager and select one or more files.
     */
    $('#chooseImg').on('click', function(){
        var _stack = [], _timeOut;
        // Handler function, called once for each image added via custom selection. The setTimeout
        // is used to make sure the function are run only once after this func is called a bunch of times.
        ccm_chooseAsset = function(obj){
            _stack.push(obj);
            clearTimeout(_timeOut);
            _timeOut = setTimeout(function(){
                $.each(_stack, function(idx, obj){
                    var $newItem = $('<div class="item" />');
                    $newItem.append('<i class="icon-minus-sign"></i><i class="icon-move"></i><input type="hidden" name="fileIDs[]" value="'+obj.fID+'" />');
                    $newItem.css('background-image', 'url('+obj.thumbnailLevel2+')');
                    $('.inner', '#imageSelections').append($newItem);
                });
                initInteractions();
                scanAndPurgeDuplicates();
            }, 250);
        }

        // Launch the file picker.
        ccm_alLaunchSelectorFileManager();
    });


    /**
     * Tooltips
     */
    $('#chooseImg, .show-tooltip').tooltip({animation:false, placement:'bottom'});


    /**
     * Popovers
     */
    $('.show-popover').popover({animation:false, placement:'top'});


    /**
     * Edit the image properties, or remove it?
     */
    $('#imageSelections').on('click', '.item', function( _clickEv ){
        var $this = $(this);
        if( $(_clickEv.target).hasClass('icon-minus-sign') ){
            $this.remove();
        }else{
            $.fn.dialog.open({
                width:  650,
                height: 450,
                title:  'File Properties',
                href:   '/tools/required/files/properties?fID=' + $('input',$this).val()
            });
        }
    });


    /**
     * Hide the duplicates warning message.
     */
    $('.close', '.dups-warning').on('click', function(){
        $('#tabPaneImages').removeClass('dups');
    });


    /**
     * Clear all images in custom gallery.
     */
    $('#flexryClearAll').on('click', function( _ev ){
        _ev.preventDefault();
        if( confirm('This will remove all images and reset your selections. Continue?') ){
            $('.item', '#imageSelections').remove();
        }
    });


    /**
     * Tabs
     */
    $('a', '.nav-tabs').on('click', function( _clickEv ){
        var $this = $(this), $targ = $( $this.attr('data-tab') );
        $this.parent('li').add($targ).addClass('active').siblings().removeClass('active');
        // show the Add Images button?
        $('#flexryOptionsRight').toggle( $this.attr('data-tab') === '#tabPaneImages' );
    });


    /**
     * Use original image? (settings area)
     */
    $('#fullUseOriginal').on('change', function(){
        if( $(this).is(':checked') ){
            $('#fullWidth, #fullHeight, #fullCrop').attr('disabled', 'disabled');
        }else{
            $('#fullWidth, #fullHeight, #fullCrop').removeAttr('disabled');
        }
    }).trigger('change'); // do this so when auto.js is initialized, it'll set appropriately


    /**
     * Enable lightbox? (settings area)
     */
    $('.enableLightboxCheckbox').on('change', function(){
        $('.flexry-lightbox-settings').toggle( $(this).is(':checked') );
    }).trigger('change'); // do this so when auto.js is initialized, it'll set appropriately


    /**
     * File Source dropdown switcher
     */
    $('#fileSourceMethod').on('change', function(){
        var _val = $(this).val(), $btn  = $('#chooseImg');
        $('.fileSourceMethod', '#flexryGallery').removeClass('active').filter('[data-method='+_val+']').addClass('active');
        $btn.toggle( +(_val) === +($btn.attr('data-method')) );
        initHandler();
    }).trigger('change'); // do this so when auto.js is initialized, it'll set appropriately


    /**
     * Template forms selector
     */
    $('#flexryTemplateHandle').on('change', function(){
        var _val = $(this).val();
        $('.template-form', '#tabPaneTemplates').removeClass('active').filter('[data-tmpl="'+_val+'"]').addClass('active');
    });


    /**
     * Show "recommend crop to fit" alerts.
     */
    var $cropFitAlert = $('.alert-crop-fit', '#flexryGallery'),
        $cropCheckbox = $('#thumbCrop');
    // alert message "click here to enable" handler
    $('.check-crop-fit', '#flexryGallery').on('click', function(){
        $cropCheckbox.prop('checked', true);
        $cropFitAlert.hide();
    });
    // if the crop checkbox is changed, or unchecked, reshow the alert
    $cropCheckbox.on('change.ctf', function(){
        $cropFitAlert.toggle( !this.checked );
    }).trigger('change.ctf');


    /**
     * On init, determine what to run.
     */
    function initHandler(){
        switch( +($('.fileSourceMethod.active').attr('data-method')) ){
            case 0: initInteractions(); break; // custom gallery
            case 1: $('#fileSetPicker').chosen(); break; // sets
        }
        jscolor.bind();
    }


    initHandler();

});
