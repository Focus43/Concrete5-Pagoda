/**
 * This file gets compiled to the inline_script.js.txt file in the parent directory.
 * Its output as a .txt file so that Concrete5 won't ever auto-include it accidentally;
 * and we just take the contents from the text file and output it to the bottom of any
 * page where a flexry_gallery block is included.
 */
try {
    if( jQuery ){
        jQuery(function(){
            if( ! window._flexry ){ return; }
            for(var _i = 0; _i < _flexry.length; _i++){
                _flexry[_i].call();
            }
        });
    }
}catch(err){ console.log(err); }