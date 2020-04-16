<link rel="stylesheet" href="/plugins/chart/chart.css" />
<script type="text/javascript" src="/plugins/chart/chart.js"></script>

<div class="grid_16">
    <div class="nw-table">
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
                <?php echo $this->Form->input('date', array('options' => $select, 'selected' => $this->params->query['date'], 'label' => false, 'empty' => false, 'after' => '&nbsp;', 'style' => 'width: 200px', 'onchange' => "window.location='/admin/stats/stats/index/?date=' + this.value"));?>
            </div>
        </div>
        <div class="nw-table-content">
            <table class="graph type-line dots tips" width="100%">
                <thead> 
                        <tr> 
                                <td></td> 
                                <?php foreach($items as $day => $count):?>
                                    <th scope="col"><?php e(date("d", strtotime($day)))?></th>
                                <?php endforeach;?> 
                        </tr> 
                </thead> 
                <tbody> 
                        <tr> 
                                <th scope="row"><?php ___e('Visitors')?></th> 
                                <?php foreach($items as $count):?>
                                    <td><?php e($count['day_visitors'])?></td>
                                <?php endforeach;?> 
                        </tr> 
                        <tr> 
                                <th scope="row"><?php ___e('Views')?></th> 
                                <?php foreach($items as $count):?>
                                    <td><?php e($count['day_views'])?></td>
                                <?php endforeach;?> 
                        </tr> 
                </tbody> 
            </table>
        </div>
    </form>
    </div>
</div>
<div class="clear"></div>