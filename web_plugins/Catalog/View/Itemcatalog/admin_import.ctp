<div class="grid_16">
    <div class="nw-table">
        <?php echo $this->Form->create('Import', array('type' => 'file'));?>
        <div class="nw-table-title">
            <div class="fl"><?php echo $page_title?></div>
            <div class="fr">
            </div>
        </div>
        <div class="nw-table-content">
            <div class="tabs-left">
                <ul>
                    <li><a href="#node-1"><span><?php echo ___('Import')?></span></a></li>
                </ul>
                <div id="node-1">
                    <div class="n5 cl">
                    <?php echo $this->Form->input('ImportData.col_code', array('label' => ___('Code Column'), 'class' => 'req', 'value' => $import_data['col_code']));?>
                    <?php echo $this->Form->input('ImportData.col_name', array('label' => ___('Name Column'), 'class' => 'req', 'value' => $import_data['col_name']));?>
                    <?php echo $this->Form->input('ImportData.col_desc', array('label' => ___('Description Column'), 'value' => $import_data['col_desc']));?>
                    <?php echo $this->Form->input('ImportData.col_qnt', array('label' => ___('Quantity Column'), 'class' => 'req', 'value' => $import_data['col_qnt']));?>
                    <?php echo $this->Form->input('ImportData.col_wrnt', array('label' => ___('Warranty Column'), 'value' => $import_data['col_wrnt']));?>
                    <?php echo $this->Form->input('ImportData.col_extra_1', array('label' => ___('Extra 1 Column'), 'value' => $import_data['col_extra_1']));?>
                    <?php echo $this->Form->input('ImportData.col_extra_2', array('label' => ___('Extra 2 Column'), 'value' => $import_data['col_extra_2']));?>
                    <?php echo $this->Form->input('ImportData.col_extra_3', array('label' => ___('Extra 3 Column'), 'value' => $import_data['col_extra_3']));?>
                    <?php echo $this->Form->input('ImportData.col_extra_4', array('label' => ___('Extra 4 Column'), 'value' => $import_data['col_extra_4']));?>
                    <?php echo $this->Form->input('ImportData.col_extra_5', array('label' => ___('Extra 5 Column'), 'value' => $import_data['col_extra_5']));?>
                    </div>
                    <div class="n4 cl">
                    <?php echo $this->Form->input('ImportData.col_price', array('label' => ___('Price Column'), 'class' => 'req', 'value' => $import_data['col_price']));?>
                    <?php echo $this->Form->input('ImportData.col_currency', array('label' => ___('Price Currency'), 'options' => $currencies, 'value' => $import_data['col_currency']));?>
                    <?php echo $this->Form->input('ImportData.new_prd', array('label' => ___('Create not found products'), 'options' => ws_ny(), 'selected' => $import_data['new_prd']));?>
                    <?php echo $this->Form->input('ImportData.hide_qnt', array('label' => ___('Hide no qnt. products'), 'options' => ws_ny(), 'selected' => $import_data['hide_qnt']));?>
                    </div>
                    <?php echo $this->Form->input('pricelist', array('label' => ___('Pricelist'), 'type' => 'file'));?>
                </div>
            </div>
        </div>
        <div class="nw-table-footer">
            <?php echo $this->Form->button(___('Save'))?>
        </div>
        <?php echo $this->Form->end();?>
    </div>
</div>

<div class="clear"></div>