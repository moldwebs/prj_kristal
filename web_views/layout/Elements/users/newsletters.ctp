<section id="st_news_letter_17" class="st_news_letter_17 block col-sm-12 col-md-12">
   <div class="footer_block_content keep_open text-center ">
      <div class="st_news_letter_box">
         <div class="st_news_letter_content style_content">
            <h5 style="font-family: Roboto;"><?php ___e('SIGN UP FOR SPECIAL PROMOTIONS')?></h5>
            <div class="mar_b1"><?php ___e('Sign up today for free and be the first to get notified on our new updates, discounts and special Offers.')?></div>
         </div>
         <div class="alert alert-danger hidden"></div>
         <div class="alert alert-success hidden"></div>
         <form action="/newsletter/add" method="post" class="st_news_letter_form">
            <div class="form-group st_news_letter_form_inner" > <input class="inputNew form-control st_news_letter_input" type="email" name="data[email]"  required="required" size="18" value="" placeholder="<?php ___e('Enter your email address')?>" /> <button type="submit" name="submitStNewsletter" class="btn btn-medium st_news_letter_submit"> <?php ___e('Subscribe')?> </button> <input type="hidden" name="action" value="0" /></div>
         </form>
      </div>
   </div>
</section>