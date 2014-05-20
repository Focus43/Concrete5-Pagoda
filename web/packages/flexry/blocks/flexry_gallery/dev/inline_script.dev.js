/**
 * This file gets compiled to the inline_script.js.txt file in the parent directory.
 * Its output as a .txt file so that Concrete5 won't ever auto-include it accidentally;
 * and we just take the contents from the text file and output it to the bottom of any
 * page where a flexry_gallery block is included.
 */
try {
    jQuery(function(_stack){
        for( var i = 0; i < _stack.length; i++ ){
            _stack[i].call();
        }
    }( window._flexry || [] ));
}catch(err){/* fail gracefully */};