<div class="add_container">
	<div class="question">
		<p><?php e($base['ObjItemTree']['title'])?></p>
	</div>
    <?php foreach($items as $item):?>
        <?php $totals = $totals + $item['ObjItemTree']['extra_3']?>
    <?php endforeach;?>
	<div class="input-wrapper">
		<div class="vote_radio" id="sondaj">
            <div style="text-align:left;" class="pollAns">
                <?php foreach($items as $key => $item):?>
					<div class="answer">
						<b><?php e($key+1)?>.</b>
                        <span><?php e($item['ObjItemTree']['title'])?> (<?php e(round((100/$totals)*$item['ObjItemTree']['extra_3']))?>%)<br><?php ___e('Votes')?>: <?php e($item['ObjItemTree']['extra_3'])?></span>
						<div style="padding-top:3px;">
							<div style="width:<?php e(round((100/$totals)*$item['ObjItemTree']['extra_3']))?>%" class="ic<?php e($key+1)?>">
							</div>
						</div>
					</div>
    			<?php endforeach;?>
            </div>
		</div>
	</div>
</div>