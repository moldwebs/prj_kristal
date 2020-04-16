<?php if(!empty($block['data'])):?>
      <div class="row">
      <?php foreach($block['data'] as $item):?>
      <div class="col-sm-4 col-xs-12">
         <div class="frontPublicationItem js_linkBlock">
            <div class="frontPublicationItemPicture">
               <img src="/getimages/360x120x1/large/<?php e($item['ObjOptAttachDef']['attach'])?>" width="360" height="120" />
               <div class="frontPublicationItemDate"><span><span class="date-display-single"><?php e(date("d", strtotime($item['ObjItemList']['created'])))?></span></span> <span><span class="date-display-single"><?php e(ucfirst(___date("M", strtotime($item['ObjItemList']['created']))))?></span></span></div>
            </div>
            <a href="<?php eurl($item['ObjItemList']['alias'])?>" class="frontPublicationItemTitle"><?php eth($item['ObjItemList']['title'], 500)?></a>
         </div>
      </div>
      <?php endforeach;?>
      </div>
      <div>&nbsp;</div>
       <div class="row">
          <div class="col-xs-12"> <a class="btn btn-info" href="<?php eurl('/news')?>"><?php ___e('All news')?></a></div>
       </div>
<?php endif;?>