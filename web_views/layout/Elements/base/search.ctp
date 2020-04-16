<form name="search" action="/search/" method="get"> 
<div id="search">
<div class="inner">
<div onclick="$(this).parents('form:first').submit();" class="button-search"><i class="fa fa-search"></i></div>
<input name="search" placeholder="<?php ___e('Search')?>" value="<?php e($_GET['search'])?>" type="search">
</div>
</div>
</form>

