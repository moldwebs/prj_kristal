<section class="page_single">

   <div class="container content">

      <?php if(!empty($item['ObjOptAttachs']['images'])):?>
      <figure class="dark-theme">
         <img src="/getimages/0x0/large/<?php e($item['ObjOptAttachs']['images'][0]['attach'])?>" alt="<?php eth($item['ObjItemList']['title'])?>">
      </figure>
      <div>&nbsp;</div>
      <div>&nbsp;</div>
      <?php endif;?>


      <?php e($item['ObjItemList']['body'])?>
   </div>

</section>