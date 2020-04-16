<link rel="stylesheet" type="text/css" media="screen" href="/plugins/filemanager/css/elfinder.full.css">
<script type="text/javascript" src="/plugins/filemanager/js/elfinder.min.js"></script>

<!-- Mac OS X Finder style for jQuery UI smoothness theme (OPTIONAL) -->
<link rel="stylesheet" type="text/css" media="screen" href="/plugins/filemanager/css/theme.css">

<div class="grid_16">
    <div class="nw-table">
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content pd">
            <div id="elfinder"></div>
        </div>

    </div>
</div>

<div class="clear"></div>


<script type="text/javascript" charset="utf-8">
    $().ready(function() {
        var elf = $('#elfinder').elfinder({
            // lang: 'ru',             // language (OPTIONAL)
            url : '/plugins/filemanager/php/connector.php'  // connector URL (REQUIRED)
        }).elfinder('instance');            
    });
</script>
