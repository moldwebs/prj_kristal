<div class="body">
    <form class="search-form container bloc bloc_paddingMTop" action="/base/search" method="get" id="search-form" accept-charset="UTF-8">
       <div>
          <div class="container-inline input-group form-wrapper" id="edit-basic">
             <input class="form-control form-text" type="text" name="search" value="<?php e($_GET['search'])?>" size="40" maxlength="255" />
             <div class="input-group-btn form-wrapper" id="edit-input-group-btn"><input class="btn btn-info form-submit" type="submit" id="edit-submit" value="<?php ___e('Find')?>" /></div>
          </div>
          
       </div>
    </form>
    <div class="bloc bloc_paddingMTop">
       <div class="container">
          <div class="row">
             <div class="col-xs-12 searchResult">
                <h1><?php ___e('Search results')?></h1>
                <div class="list-group">
                   <?php foreach($items as $item):?>
                   <a href="<?php eurl($item['ObjItemList']['alias'])?>" class="list-group-item">
                      <h4 class="list-group-item-heading"><?php e($item['ObjItemList']['title'])?></h4>
                      <p class="list-group-item-text"><?php eth($item['ObjItemList']['body'], 150)?></p>
                   </a>
                   <?php endforeach;?>
                </div>
                <div class="item-list"></div>
             </div>
          </div>
          
          <?php echo $this->telement('pages')?>
          
       </div>
    </div>
 </div>