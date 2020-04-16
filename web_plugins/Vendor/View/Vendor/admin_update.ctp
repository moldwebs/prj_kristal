
<div class="grid_12">
    <div class="nw-table">
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content pd">
            <div>Loading...</div>
            <div style="word-wrap: break-word;"><?php echo file_get_contents(LOGS . DS . 'vendor_update.log')?></div>
            <script>window.location = '/admin/vendor/vendor/update?tb=1&continue=1';</script>
        </div>

    </div>
</div>


<div class="clear"></div>
