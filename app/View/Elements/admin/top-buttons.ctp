<div id="top_buttons">
	<?php
        if(Configure::read('top_buttons_replace')){
            echo $this->Layout->top_buttons_replace();
            echo $this->Layout->top_buttons_add();
        } else {
    		echo Configure::read('top_buttons');
            echo $this->Layout->top_buttons_add();
        }
	?>
</div>