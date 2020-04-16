var remove_load = 1;

$(document).ready(function(){

    $("body").ajaxSend(function(event, xhr, options) {
        on_ajaxSend();
    }).ajaxStop(function() {
        on_ajaxStop();
    }).ajaxError(function(event, xhr, options, thrownError){
        mxalert(xhr.responseText);
        console.log(thrownError);
    });

	$('select').live('dblclick', function(){
        if($(this).children('option').length < 20) return false;
        var find = prompt("<?php ___e('Find by keyword')?>");
        if (find != null && find != '') {
            $(this).val($(this).find('option:contains('+find+')').val()).trigger('change');
        }
	});

    $.expr[":"].contains = $.expr.createPseudo(function(arg) {
        return function( elem ) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    $(".ui_date").datepicker({dateFormat:'yy-mm-dd'});

    $('.no-enter input').live('keypress', function (e) {
         var key = e.which;
         if(key == 13) return false;
    });

    if($('input.ui_autocomplete').length > 0){
        $('input.ui_autocomplete').each(function(){
            var obj = this;
            $(this).autocomplete({
                source: $(obj).attr('rel'),
                minLength: 2,
                select: function( event, ui ) {
                    if($(obj).attr('rel_input') != undefined){
                        $(this).val(ui.item.label);
                        $($(obj).attr('rel_input')).val(ui.item.id);
                    } else {
                        $(this).val(ui.item.id);
                    }
                    return false;
                }
            });
        });
    }

    if($('input.ui_time').length > 0){
        $("<link/>", {rel: "stylesheet", type: "text/css", href: "/plugins/timepicker/jquery.ui.timepicker.css"}).appendTo("head");
        $.getScript("/plugins/timepicker/jquery.ui.timepicker.js", function(){
            $('.ui_time').timepicker({showPeriodLabels: false});
        });
    }

    var timeoutId;
    $('.pop_menu_ico, .pop_menu, .ui-datepicker').bind({
        mouseover: function () {
            clearInterval(timeoutId);
            if($(this).hasClass('pop_menu_ico')) $('.pop_menu').hide();
            $(this).next().show();
        },
        mouseleave: function () {
            timeoutId = setTimeout(function () {
                $('.pop_menu').hide();
            }, 1000);
        }
    });

	$('.long_action').live('click', function(){
	   on_ajaxSend();
	});

	$('.long_action_redirect').live('click', function(){
        jQuery.ajax({
            async: true,
            type: 'get',
            url: $(this).attr('href'),
            timeout: 500
        });
       window.location = $(this).attr('href');
	});

	$('a.ajx_toggle').live('click', function(){
	   $(this).toggleClass('off');
       $.get($(this).attr('href'));
       return false;
	});
    
    $('[ajx_change]').live('change', function(){
        var send_val = ($(this).is(':checkbox') ? ($(this).is(':checked') ? '1' : '0') : $(this).val());
        var pos = $(this).attr('ajx_change').indexOf('?'); 
        if (pos == -1) {
            var to_url = $(this).attr('ajx_change') + '/' + send_val;
        } else {
            var to_url = $(this).attr('ajx_change').substr(0, pos) + '/' + send_val + $(this).attr('ajx_change').slice(pos);
        }
        $.get(to_url, function(data){
            if(data != '' && data != 'OK'){
                eval(data);
            }
        });
	});

    $('input[ajx_change]').live('click', function(){
       $(this).select();
	});
    
	$('.nw-table-check').live('change', function(){
	   if($(this).is(':checked')){
	       $(this).parents('table:first').find('input[type="checkbox"]').attr('checked', 'checked');
	   } else {
	       $(this).parents('table:first').find('input[type="checkbox"]').removeAttr('checked');
	   }
       return false;
	});

    $(".ajx_order_up,.ajx_order_down").click(function(){
        $.get($(this).attr('href'));
        var row = $(this).parents("tr:first");
        if ($(this).is(".ajx_order_up")) {
            row.insertBefore(row.prev());
        } else {
            row.insertAfter(row.next());
        }
        return false;
    });

	$('[image2path]').live('change', function(){
	   var obj = this;
        
        var reader = new FileReader();
        reader.onload = (function(theFile){
            return function(e){
                jQuery.ajax({
                    async: true,
                    type: 'post',
                    success: function(data) {
                        if(data != ''){
                            $('[name="'+$(obj).attr('image2path')+'"]').val(data);
                        }
                    },
                    data: { filename: theFile.name, data: e.target.result},
                    url: '/admin/system/save_image',
                });

            };
        })(this.files[0]); 
        reader.readAsDataURL(this.files[0]);
        
       return false;
	});

   
    $('.tabs-left').tabs();

    $( ".tabs-left" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    $( ".tabs-left li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
    
    $('form button').addClass('button primary');
    
    //console.log("LOG");

    $('a.ajx_win').live('click', function(){
        $.get(this.href, function(resp){
            ajx_win(resp);
        });
        return false;
    });
    
    $('form').live('submit', function(){
        var obj = this;
        $(this).find('.req:not([is_translate])').each(function(){
            if($(obj).hasClass('simple_req')){
                if($(this).val() == '') $(this).parent().addClass('error');
            } else {
                if($(this).val() == '') $(this).parent().addClass('error').append('<div class="error-message"><?php ___e('This field cannot be left blank')?></div>');
            }
            if(!($(this).is(':visible')) && $(this).hasClass('reqvis')) $(this).parent().removeClass('error').find('.error-message').remove();
        });
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        $(this).find('.req.email:not([is_translate])').each(function(){
            if($(obj).hasClass('simple_req')){
                if(!emailReg.test($(this).val())) $(this).parent().addClass('error');
            } else {
                if(!emailReg.test($(this).val())) $(this).parent().addClass('error').append('<div class="error-message"><?php ___e('This field cannot be left blank')?></div>');
            }
            if(!($(this).is(':visible')) && $(this).hasClass('reqvis')) $(this).parent().removeClass('error').find('.error-message').remove();
        });
        if($(this).find('.error').length > 0){
            if($(obj).hasClass('simple_req')) mxalert('<?php ___e('Please fill out the required fields.')?>');
            if($('.ui-tabs-panel').length > 0) $('.ui-tabs-panel').each(function(){
               if($(this).find('.error').length > 0){
                    $('#' + $(this).attr('aria-labelledby')).trigger('click');
                    return false;
               }
            });
            return false;
        }
    });

    $('.req').live('change', function(){
        $(this).parent().removeClass('error').find('.error-message').remove();
    });


    $('.is_err').live('change', function(){
        $(this).removeClass('is_err').parent().removeClass('error').find('.error-message').remove();
    });

    $('button[type=submit]').live('click', function(e) {
          $(this.form).data('clicked', this);
    });
    $('input[type=submit]').live('click', function(e) {
          $(this.form).data('clicked', this);
    });
    
    $('.make_uppercase').live('click', function(e) {
          $(this).parent().parent().next().val(ucfirst($(this).parent().parent().next().val()));
    });
    
    $('.make_translate').live('click', function(e) {
          var obj = $(this).parent().parent().find('[name^="data[Translates]"]:first');
          var text = $(obj).attr('name');
          var matches_source = text.match(/data\[Translates\]\[(\w+)\]\[(\w+)\]\[(\w+)\]/);
          var val_source = $(obj).val();
          $('[name^="data[Translates]['+matches_source[1]+']['+matches_source[2]+']"]').each(function(){
                if($(this).val() == ''){
                    var text = $(this).attr('name');
                    var matches = text.match(/data\[Translates\]\[(\w+)\]\[(\w+)\]\[(\w+)\]/);
                    if(matches[2] == 'title') $('[name="data[Translates]['+matches[1]+'][alias]['+matches[3]+']"]').empty();
                    $.set_translate(val_source, matches[3].substring(0, 2), '#' + $(this).attr('id'), matches_source[3].substring(0, 2));
                }
          });
    });

    $('.map_coordonates').live('click', function(e) {
        var coords = $(this).parent().next().val();
        var coords_input = $(this).parent().next().attr('id');
        
        $.getScript("http://maps.google.com/maps/api/js?sensor=false", function(){
            
              ajx_win('<div style="width: 500px; height: 400px;" id="mapCanvas"></div>');
              
              if(coords != ''){
                    var _latlng = coords.split(',');
                    var latLng = new google.maps.LatLng(parseFloat(_latlng[0]), parseFloat(_latlng[1]));
                    var zoom = 12;
              } else {
                    var latLng = new google.maps.LatLng(0, 0);
                    var zoom = 1;
              }
              
              var map = new google.maps.Map(document.getElementById('mapCanvas'), {
                zoom: zoom,
                center: latLng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
              });
              
              if(coords != ''){
                  var marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    draggable: true
                  });

                  google.maps.event.addListener(marker, 'dragend', function() {
                    var latLng = marker.getPosition();
                    $('#' + coords_input).val(latLng.lat() + ',' + latLng.lng());
                  });              

              } else {
                    google.maps.event.addListener(map, 'click', function(event) {
                        var marker = new google.maps.Marker({
                            position: event.latLng,
                            map: map,
                            draggable: true
                        });                    
                         $('#' + coords_input).val(event.latLng.lat() + ',' + event.latLng.lng());
                          google.maps.event.addListener(marker, 'dragend', function() {
                            var latLng = marker.getPosition();
                            $('#' + coords_input).val(latLng.lat() + ',' + latLng.lng());
                          });              
                        google.maps.event.clearListeners(map, 'click');
                    });
              }
        });
    });

    
    $('form.ajx_submit').live('submit', function(){
        var obj = this;
        jQuery.ajax({
            async: true,
            type: 'post',
            success: function(data) {
                eval(data);
            },
            data: jQuery(obj).serialize(),
            url: jQuery(obj).attr('action'),
        });
        return false;
    });

    $('form.ajx_replace').live('submit', function(){
        var obj = this;
        jQuery.ajax({
            async: true,
            type: 'post',
            success: function(data) {
                jQuery(obj).replaceWith(data);
            },
            data: jQuery(obj).serialize(),
            url: jQuery(obj).attr('action'),
        });
        return false;
    });

    $('form.ajx_validate').live('submit', function(){
        var obj = this;
        if(jQuery(obj).find('div.input.error').length > 0) return false;
        jQuery.ajax({
            async: true,
            type: 'post',
            dataType : 'json',
            success: function(data, textStatus) {
                console.log(data);
                if(data['status'] == 'SUCCESS'){
                    remove_load = 0;
                    jQuery(obj).removeClass('ajx_validate');
                    if($($(obj).data('clicked')).val() != ''){
                        $($(obj).data('clicked')).trigger('click');
                    } else {
                        jQuery(obj).submit();
                    }
                } else if(data['status'] == 'MESSAGE'){
                    mxalert(data['message']);
                } else {
                    jQuery.each(data['errors'], function(field, errors) {
                        if(typeof errors === 'object' && !errors[0]){
                            jQuery.each(errors, function(e_field, e_errors) {
                                if(typeof e_errors === 'object' && !e_errors[0]){
                                    jQuery.each(e_errors, function(e__field, e__errors) {
                                        if(typeof e__errors === 'object' && !e__errors[0]){
                                            jQuery.each(e__errors, function(e___field, e___errors) {
                                                $('[name$="['+field+']['+e_field+']['+e__field+']['+e___field+']"]').addClass('is_err').parent().addClass('error').append('<div class="error-message">' + e___errors[0] + '</div>');
                                            });
                                        } else {
                                            $('[name$="['+field+']['+e_field+']['+e__field+']"]').addClass('is_err').parent().addClass('error').append('<div class="error-message">' + e__errors[0] + '</div>');
                                        }
                                    });
                                } else {
                                    $('[name$="['+field+']['+e_field+']"]').addClass('is_err').parent().addClass('error').append('<div class="error-message">' + e_errors[0] + '</div>');
                                }
                            });
                        } else {
                            $('[name$="['+field+']"]').addClass('is_err').parent().addClass('error').append('<div class="error-message">' + errors[0] + '</div>');
                        }
                    });
                    $('.ui-tabs-panel').each(function(){
                       if($(this).find('.error').length > 0){
                            $('#' + $(this).attr('aria-labelledby')).trigger('click');
                            return false;
                       }
                    });
                    //mxalert('Please fill out the required fields.');
                }
            },
            data: jQuery(obj).serialize() + '&data[ajx_validate]=1&data[saction]=' + $($(this).data('clicked')).val(),
            url: jQuery(obj).attr('action'),
        });
        return false;
    });
    
    
    $('input[name*="[title]"]').live('change blur', function(){
        var obj = $(this).parent().parent().find('input[name*="'+$(this).attr('name').replace('data[', '[').replace('[title]', '[alias]')+'"]:first');
        if($(obj).length > 0){
            if($(obj).val() == '' || 1==2) $(obj).val(convertToSlug($(this).val())).trigger('change');
        }
    });
    
    
    //if($('input[name*="[alias]"]').length > 0){
    //    $('input[name*="alias"]').each(function(){
    //       var obj = this;
    //        /*
    //        $('input[name="'+$(this).attr('name').replace('[alias]', '[title]')+'"]').live('keyup', function(){
    //            if($(obj).val() == '') $(obj).val(convertToSlug($(this).val())).trigger('change');
    //        });
    //        */
    //        alert($(this).attr('name').replace('[alias]', '[title]'));
    //        $('input[name="'+$(this).attr('name').replace('[alias]', '[title]')+'"]').live('change blur', function(){
    //            if($(obj).val() == '') $(obj).val(convertToSlug($(this).val())).trigger('change');
    //        });
    //
    //    });
    //}

    if($('input[name*="[code]"]').length > 0){
        $('input[name*="code"]').each(function(){
            if(!$(this).hasClass('st_cond')){
                if($('input[name*="'+$(this).attr('name').replace('data[', '[').replace('[code]', '[alias]')+'"]').length > 0){
                    // CONTINUE
                } else {
                    var obj = this;
                    $('input[name*="'+$(this).attr('name').replace('data[', '[').replace('[code]', '[title]')+'"]:first').live('change blur', function(){
                        if($(obj).val() == '') $(obj).val(convertToSlug($(this).val())).trigger('change');
                    });
                }
            }
        });
    }
    
    if($('.multiselect').length > 0){
        $("<link/>", {rel: "stylesheet", type: "text/css", href: "/plugins/jquery-ui/jquery.multiselect.css"}).appendTo("head");
        $.getScript("/plugins/jquery-ui/jquery.multiselect.js", function(){
            $(".multiselect").multiselect({header: false, selectedList: 2, noneSelectedText: 'Choose...',});
            //alert($('#CmsSettingSiteLanguage').outerWidth());
        });
    }
                
    if($('.img_preview').length > 0){

		var img_preview_xOffset = -10;
		var img_preview_yOffset = 20;

    	$("a.img_preview").live('mouseenter', function(e){
    		this.t = this.title;
    		this.title = "";	
    		var c = (this.t != "") ? "<br/>" + this.t : "";
    		$("body").append("<p id='img_preview'><img style='max-width: 150px; max-heigth: 150px;' src='"+ $(this).attr('preview') +"' />"+ c +"</p>");								 
    		$("#img_preview")
    			.css("position",'absolute')
    			.css("top",(e.pageY - img_preview_xOffset) + "px")
    			.css("left",(e.pageX + img_preview_yOffset) + "px")
    			.fadeIn("fast");						
        });
    
    	$("a.img_preview").live('mouseleave', function(e){
    		this.title = this.t;	
    		$("#img_preview").remove();
    	});			
        
    	$("a.img_preview").live('mousemove', function(e){
    		$("#img_preview")
    			.css("top",(e.pageY - img_preview_xOffset) + "px")
    			.css("left",(e.pageX + img_preview_yOffset) + "px");
    	});			
    
        $("a.img_preview").live('click', function(){
            //return false;
        });
    }

    if($('.redactor').length > 0){
        CKEDITOR.config.height = 250;
        CKEDITOR.config.width = 'auto';
        CKEDITOR.config.allowedContent = true;
        <?php if(Configure::read('CMS.layout_css') != ''):?>
        CKEDITOR.config.contentsCss = <?php e(Configure::read('CMS.layout_css'));?>;
        <?php endif;?>
        CKEDITOR.replaceClass = 'redactor';
    }

    if($('.redactor').length > 0 && 1==2){
        $("<link/>", {rel: "stylesheet", type: "text/css", href: "/plugins/imperavi-redactor/redactor.css"}).appendTo("head");
        $.getScript("/plugins/imperavi-redactor/redactor.js", function(){
        $.getScript("/plugins/imperavi-redactor/plugins/fullscreen/fullscreen.js", function(){
        $.getScript("/plugins/imperavi-redactor/langs/ru.js", function(){
            $('.redactor').redactor({
                wym: true,
                linebreaks: false,
                minHeight: 300, 
                cleanup: true,
                iframe: true,
                css: ['/plugins/imperavi-redactor/redactor-iframe.css', '/editor/style.css'],
                convertLinks: false,
                convertDivs: false,
                removeEmptyTags: false,
                paragraphy: false,
                autoresize: false,
                emptyHtml: '', 
                lang: 'ru',
                fileUpload: '/admin/system/upload_file',
                fileGetJson: '/admin/system/get_file_list',
                imageUpload: '/admin/system/upload_image',
                imageGetJson: '/admin/system/get_image_list',
                plugins: ['fullscreen']
            });
            $('.redactor_min').redactor({
                wym: true,
                linebreaks: false,
                minHeight: 150, 
                cleanup: true,
                iframe: true,
                css: ['/plugins/imperavi-redactor/redactor-iframe.css', '/editor/style.css'],
                convertLinks: false,
                convertDivs: false,
                removeEmptyTags: false,
                paragraphy: false,
                autoresize: false,
                emptyHtml: '', 
                lang: 'ru',
                fileUpload: '/admin/system/upload_file',
                fileGetJson: '/admin/system/get_file_list',
                imageUpload: '/admin/system/upload_image',
                imageGetJson: '/admin/system/get_image_list'
            });
        });
        });
        });
        $('.redactor_init').live('change', function(){
            $(this).removeClass('redactor_init').redactor({
                wym: true,
                linebreaks: false,
                minHeight: 300, 
                cleanup: true,
                iframe: true,
                css: ['/plugins/imperavi-redactor/redactor-iframe.css', '/editor/style.css'],
                convertLinks: false,
                convertDivs: false,
                removeEmptyTags: false,
                paragraphy: false,
                autoresize: false,
                emptyHtml: '', 
                lang: 'ru',
                fileUpload: '/admin/system/upload_file',
                fileGetJson: '/admin/system/get_file_list',
                imageUpload: '/admin/system/upload_image',
                imageGetJson: '/admin/system/get_image_list',
                plugins: ['fullscreen']
            });
        });
        $('.redactor_min_init').live('change', function(){
            $(this).removeClass('redactor_min_init').redactor({
                wym: true,
                linebreaks: false,
                minHeight: 150, 
                cleanup: true,
                iframe: true,
                css: ['/plugins/imperavi-redactor/redactor-iframe.css', '/editor/style.css'],
                convertLinks: false,
                convertDivs: false,
                removeEmptyTags: false,
                paragraphy: false,
                autoresize: false,
                emptyHtml: '', 
                lang: 'ru',
                fileUpload: '/admin/system/upload_file',
                fileGetJson: '/admin/system/get_file_list',
                imageUpload: '/admin/system/upload_image',
                imageGetJson: '/admin/system/get_image_list'
            });
        });

        $('.redactor').live('change', function(){
            $(this).parent().find('iframe').contents().find('body').html($(this).val());
        });
    }
    
    if($('.uisortable').length > 0){
        $(".uisortable").sortable();
        //$(".uisortable").disableSelection();
    }
    
	if($('ol.sortable').length > 0){

        $('ol.sortable > li').each(function(){
            if($(this).attr('tree_parent_id') != ''){
                if($("#tree_ul_" + $(this).attr('tree_parent_id')).length == 0) $("#tree_" + $(this).attr('tree_parent_id')).append('<ol id="tree_ul_'+$(this).attr('tree_parent_id')+'"></ol>');
                $("#tree_ul_" + $(this).attr('tree_parent_id')).append($(this).clone());
                $(this).remove();
            }
        });

        $("<link/>", {rel: "stylesheet", type: "text/css", href: "/plugins/nestedSortable/nestedSortable.css"}).appendTo("head");
        $.getScript("/plugins/nestedSortable/jquery.mjs.nestedSortable.js", function(){
            $('ol.sortable').each(function(){
                $(this).nestedSortable({
        			forcePlaceholderSize: true,
        			handle: 'div',
        			helper:	'clone',
        			items: 'li',
        			opacity: .6,
        			placeholder: 'placeholder',
        			revert: 250,
        			tabSize: 25,
        			tolerance: 'pointer',
        			toleranceElement: '> div',
        			maxLevels: $(this).attr('maxLevel'),
        			isTree: true,
        			expandOnHover: 700,
        			startCollapsed: ($('ol.sortable').find('li').length > 40 ? true : false)
        		});
            });
    
    		$('.disclose').live('click', function() {
    			$(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
    		});
            
            $('.ns-collapse-all').click(function(){
                $('ol.sortable li.mjs-nestedSortable-expanded').removeClass('mjs-nestedSortable-expanded').addClass('mjs-nestedSortable-collapsed');
            });

            $('.ns-expand-all').click(function(){
                $('ol.sortable li.mjs-nestedSortable-collapsed').removeClass('mjs-nestedSortable-collapsed').addClass('mjs-nestedSortable-expanded');
            });

            $('.ns-check-all').click(function(){
                $('ol.sortable li input[type="checkbox"]').attr('checked', 'checked');
            });

            $('.ns-check-tree').click(function(){
                if($(this).is(':checked')){
                    $(this).closest('li').find('.ns-check-tree:visible').attr('checked', 'checked');
                } else {
                    $(this).closest('li').find('.ns-check-tree:visible').removeAttr('checked');
                }
            });

        });
	}
    
    $("<link/>", {rel: "stylesheet", type: "text/css", href: "/plugins/tipsy/tipsy.css"}).appendTo("head");
    $.getScript("/plugins/tipsy/jquery.tipsy.js", function(){
        $("[title]:not(.no_tipsy,.tipsy_bottom)").tipsy({gravity: 's', html: 'html'});
        $("[title].tipsy_bottom:not(.no_tipsy)").tipsy({gravity: 'n', html: 'html'});
    });
    
    if($('input.tags').length > 0){
        $("<link/>", {rel: "stylesheet", type: "text/css", href: "/plugins/tags/magicsuggest-1.3.1.css"}).appendTo("head");
        $.getScript("/plugins/tags/magicsuggest-1.3.1.js", function(){
            $('input.tags').each(function(){
                var obj_val = $(this).val();
                $(this).removeAttr('value');
                $(this).magicSuggest({
                    value: obj_val,
                    data: $(this).attr('rel_url'),
                    emptyText: ''
                });
            });


        });
    }

});

function on_ajaxSend(){
    if($('.ui-widget-overlay').length == 0){
        $('body').append('<div id="ajx_loading_overlay" class="ui-widget-overlay"></div><div class="ajx_loading"></div>');
        $('#ajx_loading_overlay').width($(document).width()).height($(document).height()).fadeIn();
    }
}

function on_ajaxStop(){
    if(remove_load == 1){
        jQuery('.ajx_loading').fadeOut("fast").remove();
        jQuery('#ajx_loading_overlay').fadeOut("fast").remove();
    }
}

var fileDownloadCheckTimer;
function get_download(url){
    var token = new Date().getTime();
    
    on_ajaxSend();
    
    fileDownloadCheckTimer = window.setInterval(function () {
        if(readCookie('fileDownloadToken') == token){
            window.clearInterval(fileDownloadCheckTimer);
            on_ajaxStop();
        }
    }, 1000);
    
    window.location = url + '&token=' + token;
}

function readCookie(name) {
    return (name = new RegExp('(?:^|;\\s*)' + ('' + name).replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&') + '=([^;]*)').exec(document.cookie)) && name[1];
}

function ajx_win(data){
    $('#ajx_window').remove();
    $('body').append('<div id="ajx_loading_overlay" class="ui-widget-overlay"></div>');
    $('#ajx_loading_overlay').width($(document).width()).height($(document).height()).fadeIn();
    $('#ajx_loading_overlay').live('click', function(){
        ajx_win_close();
    });
    $('body').append('<div id="ajx_window" class="ajx_window"><div class="content">'+data+'</div><a onclick="ajx_win_close();" class="ico_close"></a></div>');

    if($('#ajx_window > .content img').length > 0){
        $('#ajx_window > .content img').one('load',function() {
            var set_top = (($(window).height() - $('#ajx_window').height())/4) + $(window).scrollTop();
            var set_left = (($(window).width() - $('#ajx_window').width())/2);
            
            $('#ajx_window').css('top', (set_top > 0 ? set_top : '10') + 'px').css('left', (set_left > 0 ? set_left : '0') + 'px');
        });
    } else {
        var set_top = (($(window).height() - $('#ajx_window').height())/4) + $(window).scrollTop();
        var set_left = (($(window).width() - $('#ajx_window').width())/2);
        
        $('#ajx_window').css('top', (set_top > 0 ? set_top : '10') + 'px').css('left', (set_left > 0 ? set_left : '0') + 'px');
    }
    ajx_init_js();
}

function ajx_init_js(){
    $("[title]:not(.no_tipsy)").tipsy({gravity: 's', html: 'html'});
}

function ajx_win_close(){
    $('#ajx_window').remove();
    $('#ajx_loading_overlay').remove();
}

function ajx_win_url(url){
    $.get(url, function(resp){
        ajx_win(resp);
    });
}


function mxalert(data){
    alert(data);
}

// JQUERY FUNCS -----------------------------------------------------------------------------------------------------------------------------------

$.extend({
  translate : function(value, lng, callback, source){
    jQuery.ajax({
        url: 'https://www.googleapis.com/language/translate/v2',
        dataType: 'json',
        data: {
            'key': 'AIzaSyAeC3_cBjsRdePJMOdan_d5E5oT9ScPJbM',
            'q': value,
            'target': lng,
            'source': source
        },
        success: function(response){
            if ($.isFunction(callback)) callback(response.data.translations[0].translatedText || '');
        }
    });
  }
});

$.extend({
  set_translate : function(value, lng, obj, source){
    jQuery.ajax({
        url: 'https://www.googleapis.com/language/translate/v2',
        dataType: 'json',
        data: {
            'key': 'AIzaSyAeC3_cBjsRdePJMOdan_d5E5oT9ScPJbM',
            'q': value,
            'target': lng,
            'source': source
        },
        success: function(response){
            $(obj).val(response.data.translations[0].translatedText || '').trigger('change');
        }
    });
  }
});

$.fn.ajaxSubmit = function() {
    jQuery.ajax({
        async: true,
        type: 'post',
        data: jQuery(this).serialize(),
        url: jQuery(this).attr('action')
    });
}

// STD FUNCS -----------------------------------------------------------------------------------------------------------------------------------

function ucfirst(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

function convertToSlug(s, opt) {
	s = String(s);
	opt = Object(opt);
	
	var defaults = {
		'delimiter': '-',
		'limit': undefined,
		'lowercase': true,
		'replacements': {},
		'transliterate': (typeof(XRegExp) === 'undefined') ? true : false
	};
	
	// Merge options
	for (var k in defaults) {
		if (!opt.hasOwnProperty(k)) {
			opt[k] = defaults[k];
		}
	}
	
	var char_map = {
		// Latin
		'À': 'A', 'Á': 'A', 'Â': 'A', 'Ã': 'A', 'Ä': 'A', 'Å': 'A', 'Æ': 'AE', 'Ç': 'C', 
		'È': 'E', 'É': 'E', 'Ê': 'E', 'Ë': 'E', 'Ì': 'I', 'Í': 'I', 'Î': 'I', 'Ï': 'I', 
		'Ð': 'D', 'Ñ': 'N', 'Ò': 'O', 'Ó': 'O', 'Ô': 'O', 'Õ': 'O', 'Ö': 'O', 'Ő': 'O', 
		'Ø': 'O', 'Ù': 'U', 'Ú': 'U', 'Û': 'U', 'Ü': 'U', 'Ű': 'U', 'Ý': 'Y', 'Þ': 'TH', 
		'ß': 'ss', 
		'à': 'a', 'á': 'a', 'â': 'a', 'ã': 'a', 'ä': 'a', 'å': 'a', 'æ': 'ae', 'ç': 'c', 
		'è': 'e', 'é': 'e', 'ê': 'e', 'ë': 'e', 'ì': 'i', 'í': 'i', 'î': 'i', 'ï': 'i', 
		'ð': 'd', 'ñ': 'n', 'ò': 'o', 'ó': 'o', 'ô': 'o', 'õ': 'o', 'ö': 'o', 'ő': 'o', 
		'ø': 'o', 'ù': 'u', 'ú': 'u', 'û': 'u', 'ü': 'u', 'ű': 'u', 'ý': 'y', 'þ': 'th', 
		'ÿ': 'y',
 
		// Latin symbols
		'©': '(c)',
 
		// Greek
		'Α': 'A', 'Β': 'B', 'Γ': 'G', 'Δ': 'D', 'Ε': 'E', 'Ζ': 'Z', 'Η': 'H', 'Θ': '8',
		'Ι': 'I', 'Κ': 'K', 'Λ': 'L', 'Μ': 'M', 'Ν': 'N', 'Ξ': '3', 'Ο': 'O', 'Π': 'P',
		'Ρ': 'R', 'Σ': 'S', 'Τ': 'T', 'Υ': 'Y', 'Φ': 'F', 'Χ': 'X', 'Ψ': 'PS', 'Ω': 'W',
		'Ά': 'A', 'Έ': 'E', 'Ί': 'I', 'Ό': 'O', 'Ύ': 'Y', 'Ή': 'H', 'Ώ': 'W', 'Ϊ': 'I',
		'Ϋ': 'Y',
		'α': 'a', 'β': 'b', 'γ': 'g', 'δ': 'd', 'ε': 'e', 'ζ': 'z', 'η': 'h', 'θ': '8',
		'ι': 'i', 'κ': 'k', 'λ': 'l', 'μ': 'm', 'ν': 'n', 'ξ': '3', 'ο': 'o', 'π': 'p',
		'ρ': 'r', 'σ': 's', 'τ': 't', 'υ': 'y', 'φ': 'f', 'χ': 'x', 'ψ': 'ps', 'ω': 'w',
		'ά': 'a', 'έ': 'e', 'ί': 'i', 'ό': 'o', 'ύ': 'y', 'ή': 'h', 'ώ': 'w', 'ς': 's',
		'ϊ': 'i', 'ΰ': 'y', 'ϋ': 'y', 'ΐ': 'i',
 
		// Turkish
		'Ş': 'S', 'İ': 'I', 'Ç': 'C', 'Ü': 'U', 'Ö': 'O', 'Ğ': 'G',
		'ş': 's', 'ı': 'i', 'ç': 'c', 'ü': 'u', 'ö': 'o', 'ğ': 'g', 
 
		// Russian
		'А': 'A', 'Б': 'B', 'В': 'V', 'Г': 'G', 'Д': 'D', 'Е': 'E', 'Ё': 'Yo', 'Ж': 'Zh',
		'З': 'Z', 'И': 'I', 'Й': 'J', 'К': 'K', 'Л': 'L', 'М': 'M', 'Н': 'N', 'О': 'O',
		'П': 'P', 'Р': 'R', 'С': 'S', 'Т': 'T', 'У': 'U', 'Ф': 'F', 'Х': 'H', 'Ц': 'C',
		'Ч': 'Ch', 'Ш': 'Sh', 'Щ': 'Sh', 'Ъ': '', 'Ы': 'Y', 'Ь': '', 'Э': 'E', 'Ю': 'Yu',
		'Я': 'Ya',
		'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'yo', 'ж': 'zh',
		'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
		'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
		'ч': 'ch', 'ш': 'sh', 'щ': 'sh', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu',
		'я': 'ya',
 
		// Ukrainian
		'Є': 'Ye', 'І': 'I', 'Ї': 'Yi', 'Ґ': 'G',
		'є': 'ye', 'і': 'i', 'ї': 'yi', 'ґ': 'g',
 
		// Czech
		'Č': 'C', 'Ď': 'D', 'Ě': 'E', 'Ň': 'N', 'Ř': 'R', 'Š': 'S', 'Ť': 'T', 'Ů': 'U', 
		'Ž': 'Z', 
		'č': 'c', 'ď': 'd', 'ě': 'e', 'ň': 'n', 'ř': 'r', 'š': 's', 'ť': 't', 'ů': 'u',
		'ž': 'z', 
 
		// Polish
		'Ą': 'A', 'Ć': 'C', 'Ę': 'e', 'Ł': 'L', 'Ń': 'N', 'Ó': 'o', 'Ś': 'S', 'Ź': 'Z', 
		'Ż': 'Z', 
		'ą': 'a', 'ć': 'c', 'ę': 'e', 'ł': 'l', 'ń': 'n', 'ó': 'o', 'ś': 's', 'ź': 'z',
		'ż': 'z',
 
		// Latvian
		'Ā': 'A', 'Č': 'C', 'Ē': 'E', 'Ģ': 'G', 'Ī': 'i', 'Ķ': 'k', 'Ļ': 'L', 'Ņ': 'N', 
		'Š': 'S', 'Ū': 'u', 'Ž': 'Z', 
		'ā': 'a', 'č': 'c', 'ē': 'e', 'ģ': 'g', 'ī': 'i', 'ķ': 'k', 'ļ': 'l', 'ņ': 'n',
		'š': 's', 'ū': 'u', 'ž': 'z',
         
         // Romanian
        'Ă': 'A', 'Î': 'I', 'Ș': 'S', 'Â': 'A', 'Ț': 'T',
        'ă': 'a', 'î': 'i', 'ș': 's', 'â': 'a', 'ț': 't' ,'ţ': 't'
	};
	
	// Make custom replacements
	for (var k in opt.replacements) {
		s = s.replace(RegExp(k, 'g'), opt.replacements[k]);
	}
	
	// Transliterate characters to ASCII
	if (opt.transliterate) {
		for (var k in char_map) {
			s = s.replace(RegExp(k, 'g'), char_map[k]);
		}
	}
	
	// Replace non-alphanumeric characters with our delimiter
	var alnum = (typeof(XRegExp) === 'undefined') ? RegExp('[^a-z0-9]+', 'ig') : XRegExp('[^\\p{L}\\p{N}]+', 'ig');
	s = s.replace(alnum, opt.delimiter);
	
	// Remove duplicate delimiters
	s = s.replace(RegExp('[' + opt.delimiter + ']{2,}', 'g'), opt.delimiter);
	
	// Truncate slug to max. characters
	s = s.substring(0, opt.limit);
	
	// Remove delimiter from ends
	s = s.replace(RegExp('(^' + opt.delimiter + '|' + opt.delimiter + '$)', 'g'), '');
	
	return opt.lowercase ? s.toLowerCase() : s;
}

function serialize (mixed_value) {
    var _utf8Size = function (str) {
        var size = 0,
            i = 0,
            l = str.length,
            code = '';
        for (i = 0; i < l; i++) {
            code = str.charCodeAt(i);
            if (code < 0x0080) {
                size += 1;
            } else if (code < 0x0800) {
                size += 2;
            } else {
                size += 3;
            }
        }
        return size;
    };
    var _getType = function (inp) {
        var type = typeof inp,
            match;
        var key;
 
        if (type === 'object' && !inp) {
            return 'null';
        }
        if (type === "object") {
            if (!inp.constructor) {
                return 'object';
            }
            var cons = inp.constructor.toString();
            match = cons.match(/(\w+)\(/);
            if (match) {
                cons = match[1].toLowerCase();
            }
            var types = ["boolean", "number", "string", "array"];
            for (key in types) {
                if (cons == types[key]) {
                    type = types[key];
                    break;
                }
            }
        }
        return type;
    };
    var type = _getType(mixed_value);
    var val, ktype = '';
 
    switch (type) {
    case "function":
        val = "";
        break;
    case "boolean":
        val = "b:" + (mixed_value ? "1" : "0");
        break;
    case "number":
        val = (Math.round(mixed_value) == mixed_value ? "i" : "d") + ":" + mixed_value;
        break;
    case "string":
        val = "s:" + _utf8Size(mixed_value) + ":\"" + mixed_value + "\"";
        break;
    case "array":
    case "object":
        val = "a";

        var count = 0;
        var vals = "";
        var okey;
        var key;
        for (key in mixed_value) {
            if (mixed_value.hasOwnProperty(key)) {
                ktype = _getType(mixed_value[key]);
                if (ktype === "function") {
                    continue;
                }
 
                okey = (key.match(/^[0-9]+$/) ? parseInt(key, 10) : key);
                vals += this.serialize(okey) + this.serialize(mixed_value[key]);
                count++;
            }
        }
        val += ":" + count + ":{" + vals + "}";
        break;
    case "undefined":
        // Fall-through
    default:
        // if the JS object has a property which contains a null value, the string cannot be unserialized by PHP
        val = "N";
        break;
    }
    if (type !== "object" && type !== "array") {
        val += ";";
    }
    return val;
}