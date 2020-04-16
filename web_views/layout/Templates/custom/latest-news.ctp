<div class="block lastestnews">
    <div class="box-title">
       <h3 class="title_block"><span>LATEST</span> BLOG.</h3>
    </div>
    <ul class="lastest_posts">
       <?php for($i=1;$i<=5;$i++):?>
       <li class="post">
          <div class="container-content">
             <div class="post_image">
                <a href="/sp_agood/en/smartblog/23_Laze-duma-kaze-suma-pola.html"><img alt="Laze duma kaze suma pola" class="feat_img" src="/sp_agood/modules/smartblog/images/23-home-default.jpg"></a>
                <!-- <div class="date_added"> 
                   <span class="day">15</span>
                   <span class="month">Jun</span>
                   </div> -->
             </div>
             <div class="post_content">
                <div class="post_title"><a href="/sp_agood/en/smartblog/23_Laze-duma-kaze-suma-pola.html">Laze duma kaze suma pola</a></div>
                <div class="desc">
                   In velit duis enim est sint fugiat reprehenderit anim ea jowl bacon sed pig. Do lorem nulla chuck, esse frankfurter pastrami est...
                </div>
                <div class="read-more"><a href="/sp_agood/en/smartblog/23_Laze-duma-kaze-suma-pola.html">Read more</a></div>
             </div>
          </div>
          <div class="sdsarticle-info">
          </div>
       </li>
       <?php endfor;?>
       

    </ul>
 </div>
                     
<script type="text/javascript">
    jQuery(document).ready(function($) {
    	var slider_post = $(".lastest_posts");
    	slider_post.owlCarousel({
    
    		responsive:{
    			0:{
    				items:1
    			},
    			480:{
    				items:1
    			},
    			768:{
    				items:2
    			},
    			992:{
    				items:3
    			},
    			1200:{
    				items:3
    			}
    		},
    		
    		autoplay:false,
    		loop:true,
    		nav : true, // Show next and prev buttons
    		dots: false,
    		autoplaySpeed : 500,
    		navSpeed : 500,
    		dotsSpeed : 500,
    		autoplayHoverPause: true,
    		margin:30,
    
    	});	 
    
    });	
 </script>