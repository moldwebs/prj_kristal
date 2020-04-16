//$.getScript("/plugins/jquery-migrate.min.js");
$(document).ready(function(){
    
    $(":checkbox").attr("autocomplete", "off");
        
    $("body").ajaxSend(function(event, xhr, options) {
        if($('.ui-widget-overlay').length == 0){
            $('body').append('<div id="ajx_loading_overlay" class="ui-widget-overlay"></div><div class="ajx_loading"></div>');
            $('#ajx_loading_overlay').width($(document).width()).height($(document).height()).fadeIn();
        }
    }).ajaxStop(function() {
        jQuery('.ajx_loading').fadeOut("fast").remove();
        jQuery('#ajx_loading_overlay').fadeOut("fast").remove();
    }).ajaxError(function(event, xhr, options, thrownError){
        console.log('ajaxError: ' + thrownError);
    });

    $('#upload_attachments').live('change', function(){
        var obj = this;
        $(this).after($(this)[0].outerHTML);
        var new_id = 'attch_' + (new Date()).getTime();
        $(this).attr('id', new_id).hide();
        for (var i = 0; i < $(this)[0].files.length; i++) {
            var is_img = (this.files[i].type.match('image.*') ? '1' : '0');
            var file_ext = this.files[i].name.substr((this.files[i].name.lastIndexOf('.') + 1));
            //if(is_img != '1') continue;
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#upload_attachments_box').append('<div style="display: inline-block; position: relative; margin-right: 20px; margin-bottom: 10px; margin-top: 10px; padding: 2px; border: 1px solid #CCCCCC;">'
                + '<img style="height: 70px;" src="' + (is_img == '1' ? e.target.result : '/img/ext/' + file_ext + '.png') + '" /><a onclick="$(this).parent().remove();$(\'#'+new_id+'\').remove();" style="position: absolute; right: -8px; top: -8px;"><img src="/ico/remove.png"></a>'
                + '<input type="hidden" name="data[attachments_exist][]" value="1" />'
                + '</div>'
                );
            }
            reader.readAsDataURL(this.files[i]);
        }
    });

    if($('.jq_timer').length > 0){
        var CurrentDate = new Date();
        if($('.jq_timer .jq_timer_date').length > 0) CurrentDate.setTime($('.jq_timer .jq_timer_date').text());
        setInterval(function() {
            $('.jq_timer .jq_timer_d').text((CurrentDate.getDate() < 10 ? '0' + CurrentDate.getDate() : CurrentDate.getDate()));
            $('.jq_timer .jq_timer_m').text(((CurrentDate.getMonth() + 1) < 10 ? '0' + (CurrentDate.getMonth() + 1) : (CurrentDate.getMonth() + 1)));
            $('.jq_timer .jq_timer_Y').text(CurrentDate.getFullYear());
            $('.jq_timer .jq_timer_h').text((CurrentDate.getHours() < 10 ? '0' + CurrentDate.getHours() : CurrentDate.getHours()));
            $('.jq_timer .jq_timer_i').text((CurrentDate.getMinutes() < 10 ? '0' + CurrentDate.getMinutes() : CurrentDate.getMinutes()));
            $('.jq_timer .jq_timer_s').text((CurrentDate.getSeconds() < 10 ? '0' + CurrentDate.getSeconds() : CurrentDate.getSeconds()));
            CurrentDate.setTime((CurrentDate.getTime() + 1000));
        }, 1000);
    }
    
    if($('.ui_autocomplete').length > 0){
        $('.ui_autocomplete').each(function(){
            $(this).autocomplete({
              source: $(this).attr('rel_url'),
              minLength: 2,
              select: function( event, ui ) {
                if(ui.item.url){
                    window.location = ui.item.url;
                }
              },
              search: function( event, ui ) {
                $(this).css('cursor', 'wait');
              },
              response: function( event, ui ) {
                $(this).css('cursor', 'auto');
              }
            });
        });
    }

    if($('.ui_autocomplete_catalog').length > 0){
        $('.ui_autocomplete_catalog').each(function(){
            $(this).autocomplete({
              source: $(this).attr('rel_url'),
              minLength: 2,
              select: function( event, ui ) {
                if(ui.item.url){
                    window.location = ui.item.url;
                }
              },
              search: function( event, ui ) {
                $(this).css('cursor', 'wait');
              },
              response: function( event, ui ) {
                $(this).css('cursor', 'auto');
              }
            }).data("ui-autocomplete")._renderItem = function( ul, item ) {
                return $("<li></li>")
                    .data("item.autocomplete", item )
                    .append('<a href="'+item.url+'"><div style="height: 50px;"><div style="text-align: center;" class="col-md-2 col-sm-2 col-xs-2"><img src="'+item.image+'" /></div><div class="col-md-6 col-sm-6 col-xs-6"><div style="display: table-cell;vertical-align: middle;height: 50px;">'+item.title+'</div></div><div style="text-align: right; line-height: 50px;" class="col-md-4 col-sm-4 col-xs-4"><b>'+item.price+'</b></div></div></a>')
                    .appendTo(ul);
           };
        });
    }
    
    
    
    if($('.ajx_init_load').length > 0){
        $('.ajx_init_load').each(function(){
            var obj = this;
            jQuery(obj).html('<img style="margin-left: 40%; margin-top: 30%; opacity: 0.6;" src="/img/l22.gif">');
            jQuery.ajax({
                async: true,
                success: function(data) {
                    jQuery(obj).html(data);
                },
                url: jQuery(obj).attr('rel')
            });
        });
    }
    
    if($('.ajx_page_load').length > 0){
        $('.ajx_page_load a.ajx_href').live('click', function(){
            var obj = this;
            jQuery(obj).parents('.ajx_page_load:first').css('opacity', '0.2');
            jQuery(obj).parents('.ajx_page_load:first').parent().css('position', 'relative').append('<img id="load_img" style="left: 40%; top: 30%; position: absolute; opacity: 0.6;" src="/img/l22.gif">');
            jQuery.ajax({
                async: true,
                success: function(data) {
                    jQuery(obj).parents('.ajx_page_load:first').css('opacity', '1');
                    jQuery('#load_img').remove();
                    jQuery(obj).parents('.ajx_page_load:first').html(data);
                },
                url: jQuery(obj).attr('href')
            });
            return false;
        });
    }

	$('a.ajx_toggle').live('click', function(){
	   $(this).toggleClass('off');
       $.get($(this).attr('href'));
       return false;
	});

	$('a.ajx_toggle_on').live('click', function(){
	   $(this).toggleClass('is_on');
       $.get($(this).attr('href'), function(data){
            if($(this).attr('rel') != '') $('#' + $(this).attr('rel')).val(data);
       });
       return false;
	});

    $('form').live('submit', function(){
        var obj = this;
        $(this).find('.req:not([is_translate])').each(function(){
            if($(this).attr('name')){
                if($(this).val() == '' || $(this).val() == null) $(this).parent().addClass('error');
                if(!($(this).is(':visible')) && $(this).val() != null) $(this).parent().removeClass('error').find('.error-message').remove();
            }
        });
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        $(this).find('.req.email:not([is_translate])').each(function(){
            if(!emailReg.test($(this).val())) $(this).parent().addClass('error');
            if(!($(this).is(':visible'))) $(this).parent().removeClass('error').find('.error-message').remove();
        });
        if($(this).find('.error').length > 0){
            mxalert('<?php ___e('Please fill out the required fields.')?>');
            return false;
        }
    });

    $('.req').live('change', function(){
        $(this).parent().removeClass('error').find('.error-message').remove();
    });

    $('.form-error').live('change', function(){
        $(this).parent().removeClass('error').find('.error-message').remove();
    });

    $('.is_err').live('change', function(){
        $(this).removeClass('is_err').parent().removeClass('error').find('.error-message').remove();
    });


    $('.ajx_options').live('change', function(){
        var obj = this;
        if(jQuery(obj).val() == ''){
            jQuery(jQuery(obj).attr('data-place')).html('').trigger('change');
            return false;
        }
        jQuery.ajax({
            async: true,
            type: 'get',
            success: function(data) {
                jQuery(jQuery(obj).attr('data-place')).html(data).trigger('change');
            },
            data: {id : jQuery(obj).val(), selected: jQuery(obj).attr('data-selected')},
            url: jQuery(obj).attr('data-url'),
        });
        return false;
    }).trigger('change');

    $('form.ajx_submit').live('submit', function(){
        if($(this).find('.error').length > 0) return false;
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
        if($(this).find('.error').length > 0) return false;
        var obj = this;
        jQuery.ajax({
            async: true,
            type: 'post',
            success: function(data) {
                jQuery(obj).replaceWith(data);
            },
            data: jQuery(obj).serialize(),
            url: jQuery(obj).attr('action')
        });
        return false;
    });

    $('form.ajx_load').live('submit', function(){
        if($(this).find('.error').length > 0) return false;
        var obj = this;
        jQuery.ajax({
            async: true,
            type: 'post',
            success: function(data) {
                $(jQuery(obj).attr('ajx_load')).html(data);
            },
            data: jQuery(obj).serialize(),
            url: jQuery(obj).attr('action')
        });
        return false;
    });


    $('form.ajx_validate').live('submit', function(){
        if($(this).find('.error').length > 0) return false;
        var errors = 0;
        $(this).find('[reqmsg]').each(function(){
            if($(this).val() == '' || ($(this).is(':checkbox') && !$(this).is(":checked"))){
                errors = 1;
                alert($(this).attr('reqmsg'));
                return false;
            }
        });
        if(errors == 1){
            return false;
        } else {
            var obj = this;
            jQuery.ajax({
                async: true,
                type: 'post',
                success: function(data) {
                    if(data == 'OK'){
                        jQuery(obj).removeClass('ajx_validate');
                        jQuery(obj).submit();
                    } else {
                        alert(data);
                    }
                },
                data: jQuery(obj).serialize() + '&data[ajx_validate]=1',
                url: jQuery(obj).attr('action')
            });
            return false;
        }
    });


    $('img[src*="/system/captcha"]').live('click', function(){
        $(this).attr('src', '/system/captcha/rnd:' + Math.random());
    }).css('cursor', 'pointer');

    if($('.fancybox').length > 0){
        $("<link/>", {rel: "stylesheet", type: "text/css", href: "/plugins/fancybox/jquery.fancybox.css"}).appendTo("head");
        $.getScript("/plugins/fancybox/jquery.fancybox.js", function(){
            $(".fancybox").fancybox();
        });
    }

    if($('.wysibb').length > 0){
        $("<link/>", {rel: "stylesheet", type: "text/css", href: "/plugins/wysibb/theme/default/wbbtheme.css"}).appendTo("head");
        $.getScript("/plugins/wysibb/jquery.wysibb.min.js", function(){
            $(".wysibb").wysibb({buttons: "bold,italic,underline,|,img,video,link,|,bullist,|,quote"});
        });
    }
    
    if($('.filter_slider').length > 0){
        $('.filter_slider').each(function(){
            var obj = this;
            
            var sel_min = (jQuery(obj).parent().find('.filter_slider_min').val() != '' ? jQuery(obj).parent().find('.filter_slider_min').val() : jQuery(obj).attr('rel_min'));
            var sel_max = (jQuery(obj).parent().find('.filter_slider_max').val() != '' ? jQuery(obj).parent().find('.filter_slider_max').val() : jQuery(obj).attr('rel_max'));
            
            //if(jQuery(obj).parent().find('.filter_slider_min').val() == '') jQuery(obj).parent().find('.filter_slider_min').val(jQuery(obj).attr('rel_min'));
            //if(jQuery(obj).parent().find('.filter_slider_max').val() == '') jQuery(obj).parent().find('.filter_slider_max').val(jQuery(obj).attr('rel_max'));
            
            jQuery(obj).parent().find('.filter_slider_text_min').text(sel_min);
            jQuery(obj).parent().find('.filter_slider_text_max').text(sel_max);

            jQuery(obj).slider({
              range: true,
              step: parseFloat(jQuery(obj).attr('rel_step')),
              min: jQuery(obj).attr('rel_min'),
              max: jQuery(obj).attr('rel_max'),
              values: [sel_min, sel_max],
              slide: function( event, ui ) {
                //console.log(event);
                //console.log(ui);
                jQuery(event.target).parent().find('.filter_slider_min').val(ui.values[0]);
                jQuery(event.target).parent().find('.filter_slider_max').val(ui.values[1]);
                jQuery(event.target).parent().find('.filter_slider_text_min').text(ui.values[0]);
                jQuery(event.target).parent().find('.filter_slider_text_max').text(ui.values[1]);
              },
              stop: function( event, ui ) {
                jQuery(event.target).parent().find('.filter_slider_min').val(ui.values[0]);
                jQuery(event.target).parent().find('.filter_slider_max').val(ui.values[1]).trigger('change');
              }
            }).show();
        });
    }

    if($('.slider-range').length > 0){
        $('.slider-range').each(function(){
            var change_min   = $(this).data('change-min');
            var change_max   = $(this).data('change-max');
            var min             = $(this).data('min');
            var max             = $(this).data('max');
            var unit            = $(this).data('unit');
            var value_min       = $(this).data('value-min') > 0 ? $(this).data('value-min') : '0';
            var value_max       = $(this).data('value-max') > 0 ? $(this).data('value-max') : $(this).data('max');
            var label_reasult   = $(this).data('label-reasult');
            var t               = $(this);
            $( this ).slider({
              range: true,
              min: min,
              max: max,
              values: [ value_min, value_max ],
              slide: function( event, ui ) {
                var result = label_reasult +" "+ ui.values[ 0 ] + ' ' + unit + ' - '+ ui.values[ 1 ] + ' ' + unit;
                //console.log(t);
                t.closest('.slider-range-box').find('.range-values').html(result);
                $('[name="'+change_min+'"]').val(ui.values[ 0 ]);
                $('[name="'+change_max+'"]').val(ui.values[ 1 ]);
                $('#'+change_min).val(ui.values[ 0 ]);
                $('#'+change_max).val(ui.values[ 1 ]);
              },
              stop: function( event, ui ) {
                $('[name="'+change_min+'"]').trigger('change');
              }
            });
            t.closest('.slider-range-box').find('.range-values').html(label_reasult +" "+ value_min + ' ' + unit + ' - '+ value_max + ' ' + unit);
            //t.closest('.slider-range-box').find('.range-values-textbox').find('[name="'+change_min+'"]').val(value_min);
            //t.closest('.slider-range-box').find('.range-values-textbox').find('[name="'+change_max+'"]').val(value_max);
            t.closest('.slider-range-box').find('.range-values-textbox').find('#'+change_min).val(value_min);
            t.closest('.slider-range-box').find('.range-values-textbox').find('#'+change_max).val(value_max);
            $('#'+change_min).live('change', function(){
                $('[name="'+change_min+'"]').val($(this).val()).trigger('change');
                t.slider('values',0,$(this).val());
            });
            $('#'+change_max).live('change', function(){
                $('[name="'+change_max+'"]').val($(this).val()).trigger('change');
                t.slider('values',1,$(this).val());
            });
        });
    }
    
    $('.pjax .pjax_change').live('change', function(){
        $(this).parents('form:first').submit();
    });

    $('.pjax [name^="fltr_"]').change(function(){
        $(this).parents('form:first').submit();
    });
    
    $(document).on('click', 'a:not([onclick]):not([href^="/getimages/"]):not([href^="/uploads/"]):not([href^="/system/toggle"]):not([href^="/users/collections"]):not([target="_blank"]):not([class^="ajx"])', function(e){
        //history.pushState({page: this.href}, '', this.href);
    });

    if($('.pjax').length > 0){
        $.getScript("/plugins/jquery.pjax.js");
    }

    $(document).on('submit', 'form.pjax', function(event) {
      if(window.location.pathname != $(this).attr('action')) return true;
      $.pjax.submit(event, '#ajax_content', {
            fragment: '#ajax_content',
            push: true,
            scrollTo: false,
            is_ajax_simple: true,
            timeout: 60000
      });
    });
        
    $(window).on('popstate', function(e){
        if (e.state) {
            location.reload();
        }
    });

});

function mxwin(url){
    $.fancybox.open({
        href: url,
        type: "ajax",
        autoWidth : true
    });
}

function geteval(url){
    $.get(url, function(result){
        if(result != 'OK'){
            eval(result);
        }
    });
}

function ajax_url(url, div){
    History.pushState({div: div}, url, url);

    jQuery.ajax({
        async: true,
        type: 'get',
        success: function(data) {
            $(div).html(data);
        },
        url: url
    });
}

function mxalert(data){
    $.fancybox.open({
        content: '<div style="padding: 20px 10px;">'+data+'</div>'
    });
    //alert(data);
    return false;
}

/*
http://stackoverflow.com/questions/12310634/pushstate-dismissing-remember-password-dialog
(function($) {
  var firstLoad=true;
  $(window).on('load', function (){
    if (firstLoad!==true)
      window.parent.history.pushState(null, "index.php", "index.php");
    firstLoad=false;
  });
})(jQuery);
*/