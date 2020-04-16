<ul class="ratings">
<?php for($i=1;$i<=5;$i++):?>
        <li><a href=""><i class="fa fa-star<?php e($i <= $item['RelationValue']['rating_rate'] ? '' : ($i - 0.5 <= $item['RelationValue']['rating_rate'] ? '-half-o' : '-o'))?>"></i></a></li>
    <?php endfor;?>
</ul>
<div class="clear"></div>