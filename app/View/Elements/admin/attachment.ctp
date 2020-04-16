<script type="text/javascript" src="/plugins/jquery.md5.min.js"></script>
<script>
    $('#<?php e($id)?>').live('change', function(){
        var obj = this;
        $(this).parent().after('<div>' + $(this).parent().html() + '</div>');
        $(this).parent().find('#<?php e($id)?>').attr('id', (new Date()).getTime());;
        $(this).parent().hide();
        var reader_init_count = 0;
        var reader_total_count = this.files.length;
        var reader_append = [];
        for (var i = 0; i < this.files.length; i++) {
            var reader = new FileReader();
            var currFile = this.files[i];
            reader.onload = (function(theFile, count){
                var is_img = (theFile.type.match('image.*') ? '1' : '0');
                var file_ext = theFile.name.substr((theFile.name.lastIndexOf('.') + 1));
                //var file_name = (is_img == '1' ? '' : theFile.name.substr(0, (theFile.name.lastIndexOf('.'))));
                var file_name = (is_img == '1' ? '' : theFile.name);
                var new_id = $.md5(theFile.name);
                return function(e){
                    reader_init_count++;
                    reader_append[count] = '<div>'
                    + '<div class="Attach Attach_new" style="float: left; position: relative;">'
                    + '<img src="' + (is_img == '1' ? e.target.result : '/img/ext/' + file_ext + '.png') + '" />' 
                    + '<span></span>'
                    + '</div>'
                    + '<div style="float: left; width: 900px;"><div class="n4 cl">'
                    + '<input type="hidden" name="data[attachments_args][order][new_' + new_id + ']" value="1">'
                    + '<div class="input text"><label><?php ___e('Title')?></label><input name="data[attachments_args][title][new_' + new_id + ']" value="'+file_name+'" type="text" /></div>'
                    + '<div class="input text"><label><?php ___e('Source')?></label><input name="data[attachments_args][source][new_' + new_id + ']" type="text" /></div>'
                    + '<div class="input text"><label><?php ___e('Type')?></label><input name="data[attachments_args][type][new_' + new_id + ']" style="width: 80px;" type="text" /></div>'
                    + '</div></div>'
                    + '<div style="clear: both;"></div>'
                    + '</div>';
                    if(reader_init_count == reader_total_count){
                        for (var _i = 1; _i <= reader_total_count; _i++) {
                            $(obj).parent().parent().prev('.uisortable:first').append(reader_append[_i]);
                        }
                    }
                };
            })(currFile, i+1); 
            reader.readAsDataURL(currFile);
        }
    });
</script>
