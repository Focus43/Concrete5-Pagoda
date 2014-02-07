<?php if( is_array($flash) && !empty($flash) ): ?>
    <div id="schedulizerFlash" class="message alert alert-<?php echo $flash['type']; ?>">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <ul class="unstyled">
            <?php
            if(is_array($flash['msg'])){
                foreach($flash['msg'] AS $message){
                    echo "<li>{$message}</li>";
                }
            }else{
                echo "<li>{$flash['msg']}</li>";
            }
            ?>
        </ul>
    </div>
<?php endif; ?>

<!-- target if generated via ajax -->
<div class="ajax-flash-target"></div>
