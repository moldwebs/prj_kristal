<!DOCTYPE html>
<html lang="en">
    <head>
    <?php echo $tpl->telement('tpl_head');?>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--[if IE]>
        <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
        <![endif]-->
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
        
        <!-- Favicon -->
        <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
        <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
        <!-- Master Css -->
        <link href="main.css" rel="stylesheet">
        <link href="assets/css/color.css" rel="stylesheet" id="colors">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <!--//==Preloader Start==//-->
        <div class="preloader">
            <div class="cssload-container">
                <div class="cssload-loading">
                    <div id="object"><i class="fa fa-bath" aria-hidden="true"></i></div>
                </div>
                <h4 class="title"><?php ___e('Loading')?></h4>
            </div>
        </div>
        <!--//==Preloader End==//-->  
        <!--//==Header Start==//-->
        <header id="main-header">
            <!--//==Topbar Start==//-->
            <div id="top-bar" class="hidden-xs">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 text-left">
                            <!-- Logo Desktop-->
                            <a class="logo hidden-xs" href="<?php eurl('/')?>">
                            <img class="site_logo" alt="<?php e($cfg['base']['title'])?>"  src="<?php e($cfg['base']['logo'] ? $cfg['base']['logo'] : 'assets/img/logo.png')?>" />
                            </a>
                        </div>
                        <div class="col-md-6 col-sm-4 col-xs-12">
                            <?php echo $tpl->element('catalog/search');?>
                        </div>
                        <div class="col-md-3 col-sm-5 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <?php echo $tpl->element('base/language');?>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <?php echo $tpl->element('currency/currency');?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--//==Topbar End==//-->
            <!--//==Navbar Start==//-->
            <div id="main-menu" class="wa-main-menu">
                <div class="wathemes-menu relative">
                    <div class="navbar navbar-default navbar-bg-light" role="navigation">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <div class="navbar-header">
                                        <!-- Button For Responsive toggle -->
                                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                                        <span class="sr-only">Toggle navigation</span> 
                                        <span class="icon-bar"></span> 
                                        <span class="icon-bar"></span> 
                                        <span class="icon-bar"></span></button> 
                                        <!-- Logo -->
                                        <a class="navbar-brand hidden-lg hidden-md hidden-sm" href="<?php eurl('/')?>">
                                        <img class="site_logo" alt="<?php e($cfg['base']['title'])?>"  src="<?php e($cfg['base']['logo'] ? $cfg['base']['logo'] : 'assets/img/logo-2.png')?>" />
                                        </a>
                                    </div>
                                    <!-- Navbar Collapse -->
                                    <div class="navbar-collapse collapse">
                                        <!-- Right nav Start -->
                                        <?php echo $tpl->menu('top-navigation');?>
                                        <!-- /.Right nav  End-->

                                        <div class="col-md-6 col-sm-4 col-xs-12 hidden-lg hidden-md hidden-sm">
                                            <?php echo $tpl->element('catalog/search');?>
                                        </div>
                                        <div class="col-md-6 col-sm-4 col-xs-12 hidden-lg hidden-md hidden-sm">
                                            &nbsp;
                                        </div>
                                        <div class="col-md-3 col-sm-5 col-xs-12 hidden-lg hidden-md hidden-sm">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <?php echo $tpl->element('base/language');?>
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                    <?php echo $tpl->element('currency/currency');?>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.navbar-collapse -->
                                </div>
                                <!-- /.nav Col -->
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <ul class="inline top-ecommerce-icons">
                                        <li>
                                            <a><?php echo $tpl->block('phone');?></a>
                                        </li>

                                        <li class="hidden-xs">
                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </li>
                                        
                                        <li>
                                            <a href="<?php eurl('/catalog/wishlist')?>" id="whishlistIcon">
                                                <i class="fa fa-heart-o" aria-hidden="true"></i>
                                            </a>
                                            <!--end shopping-cart -->
                                        </li>
                                        <li>
                                            <a href="<?php eurl('/shop/order/history')?>" id="userIcon">
                                                <i class="fa fa-user-o" aria-hidden="true"></i>
                                            </a>
                                            <!--end user-menu -->
                                        </li>
                                        <li>
                                            <a href="<?php eurl('/shop/basket/')?>" id="cartIcon">
                                                <i class="fa fa-shopping-basket" aria-hidden="true"></i>											
                                                <span><?php e($basket_data['qnt'])?></span>
                                            </a>
                                            <!--end shopping-cart -->
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container -->
                    </div>
                </div>
            </div>

            <div id="top-bar" class="hidden-lg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-3 col-sm-3 text-left">
                            <!-- Logo Desktop-->
                            <a class="logo hidden-xs" href="<?php eurl('/')?>">
                            <img class="site_logo" alt="<?php e($cfg['base']['title'])?>"  src="<?php e($cfg['base']['logo'] ? $cfg['base']['logo'] : 'assets/img/logo.png')?>" />
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!--//==Navbar End==//-->
        </header>
        <!--//==Header End==//-->

        <?php if($uri == '/'):?>
        <div class="wa_main_bn_wrap">
            <?php echo $tpl->slider('homepage')?>
        </div>
        <?php endif;?>

        <?php if($uri == '/'):?>
            <?php echo $tpl->blocks('homepage')?>
        <?php else:?>

            <div class="page-header black-overlay hidden-xs">
                <div class="container breadcrumb-section">
                    <div class="row pad-s15">
                        <div class="col-md-12">
                            <h2><?php et($title_for_action, 30); ?></h2>
                            <div class="clear"></div>
                            <?php echo $tpl->element('base/breadcrumb');?>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo $this->fetch('content'); ?>
        <?php endif;?>

        <!--//=========Footer Start=========//-->
        <footer id="main-footer" class="dark-footer footer-style1">
            <div class="bottom-footer">
                <div class="container">
                    <div class="row pad-s15">
                        <div class="col-md-12 copy-right text-center">
                            <p><?php e($cfg['base']['copyright'])?></p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!--//=========Footer End=========//-->	 		

        <!--//=========Quickview Popup Start =========//-->	 
        <section class="quick-view-popup">
        <div class="container">
         <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-12 col-sm-offset-0 col-xs-12 col-xs-offset-0">
               <div class="popup-inner">
                     <a href="javascript:void(0)" class="close-quick-view"><i class="fa fa-times"></i></a>
                     <div class="popup-content padT15 padB15">
                     </div>
               </div>
            </div>
         </div>
        </div>
        </section>

        <!--//=========Quickview Popup End=========//-->	 

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="assets/js/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="assets/plugins/menu/js/hover-dropdown-menu_1.js"></script> 
        <script type="text/javascript" src="assets/plugins/menu/js/jquery.hover-dropdown-menu-addon.js"></script>	
        <script src="assets/plugins/owl-carousel/js/owl.carousel.js"></script>
        <script type="text/javascript" src="assets/plugins/switcher/switcher.js"></script>   
        <script src="assets/js/main.js"></script>
        <?php echo $tpl->telement('tpl_footer');?>
        <style>
            .content{
                min-height: 200px;
                padding-top: 50px;
                padding-bottom: 50px;
            }
            form li{
                list-style-type: none;
            }
            body, em, h1, h2, h3, h4, h5, h6, p {
                line-height: 1.6em;
            }
            .wa-products-caption h2{
                min-height: 105px;
                line-height: 1em;
            }
            ul.top-ecommerce-icons {
                padding: 10px 0;
            }
            .navbar-nav > li > a {
                padding: 15px 20px;
            }
            .page-header {
                padding: 10px 0;
            }
            a {
                color: #171616;
            }
           .dropdown-menu .menu-items{
                min-height: 270px;
                min-width: 240px;
                width: 240px;
            }
            .dropdown-menu{
                width: 1300px !important;
                max-width: 1300px !important;
            }
            .mega-menu .dropdown-menu{
                max-width: 1300px !important;
            }
            .menu-items h6{
                min-height: 45px;
            }
            .carousel-style-1 .owl-buttons {
                margin-top: -45px;
            }
            #home-blog-carousel .carousel-style-1 .owl-buttons {
                margin-top: 15px;
            }
        </style>
        <script>
            $('.quickview-box-btn').click(function(){
                $('.quick-view-popup').find('.popup-content').html('').load($(this).attr('href'));
            });
        </script>
    </body>
</html>