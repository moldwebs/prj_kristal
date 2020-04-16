<script src="/plugins/jcrop/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="/plugins/jcrop/jquery.Jcrop.min.css" type="text/css" />

<img src="/<?php e($item['ObjOptAttach']['location'])?>/large/<?php e($item['ObjOptAttach']['file'])?>" id="target" />

<script type="text/javascript">
  jQuery(function($){
    $('#target').Jcrop({
      onChange:   showCoords,
      onSelect:   showCoords
    });
  });
  
  function showCoords(c)
  {
    $('#crop_x1').val(c.x);
    $('#crop_y1').val(c.y);
    $('#crop_x2').val(c.x2);
    $('#crop_y2').val(c.y2);
    $('#crop_w').val(c.w);
    $('#crop_h').val(c.h);
  };
</script>
<form action="<?php e($this->here)?>" method="POST" class="ajx_submit">
    <input type="hidden" name="data[x1]" id="crop_x1" />
    <input type="hidden" name="data[y1]" id="crop_y1" />
    <input type="hidden" name="data[x2]" id="crop_x2" />
    <input type="hidden" name="data[y2]" id="crop_y2" />
    <input type="hidden" name="data[w]" id="crop_w" />
    <input type="hidden" name="data[h]" id="crop_h" />
    <input class="button primary" type="submit" value="<?php ___e('Save')?>" style="position: absolute; bottom: 20px; left: 48%; z-index: 1000;" />
</form>