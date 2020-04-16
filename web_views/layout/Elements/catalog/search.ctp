<?php //if(empty($block['data'])) $block['data'] = $tpl->get('/catalog/base/get_tree')?>
<form class="wa-search-bar" id="searchbox" method="get" action="/catalog" >
   <input autocomplete="off" type="text" name="fltr_lk__title" value="<?php e($_GET['fltr_lk__title'])?>" placeholder="<?php ___e('Product search')?>" rel_url="/catalog/item/get_autocomplete" />
   <button type="submit" class="default-btn"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
</form>