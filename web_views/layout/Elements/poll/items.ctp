<div id="pool_block">
<form action="/poll/poll_item/vote/<?php e($block['data']['base']['ObjItemTree']['id'])?>" method="POST" class="ajx_replace" >
	<div class="add_container">
		<div class="question">
			<p><?php e($block['data']['base']['ObjItemTree']['title'])?></p>
		</div>
		<div class="input-wrapper">
			<div class="vote_radio">
                <?php foreach($block['data']['items'] as $item):?>
				<div class="answer">
                    <input type="radio" style="vertical-align:middle;" value="<?php e($item['ObjItemTree']['id'])?>" name="data[id]" id="q_<?php e($item['ObjItemTree']['id'])?>"> 
                    <label for="q_<?php e($item['ObjItemTree']['id'])?>" style="vertical-align: text-top;display:inline;"><?php e($item['ObjItemTree']['title'])?></label>
                </div>
				<?php endforeach;?>
			</div>
			<button class="btn" type="submit"><?php ___e('Votează')?></button>
		</div>
	</div>
</form>
</div>
<?php if($tplcookie->read("Poll.{$block['data']['base']['ObjItemTree']['id']}") == '1'):?>
    <script>
        $('#pool_block').html('').load('/poll/poll_item/vote/<?php e($block['data']['base']['ObjItemTree']['id'])?>');
    </script>
<?php endif;?>