<?php if($this->Paginator->numbers() != ''):?>
    <div class="pagination">
        <?php if($this->Paginator->hasPrev()) echo $this->Paginator->prev("&laquo", array('tag' => false, 'escape' => false));?>
        <?php e($this->Paginator->numbers(array('currentClass' => 'current', 'separator' => false, 'tag' => false, 'modulus' => 4, 'first' => 1, 'last' => 1)))?>
        <?php if($this->Paginator->hasNext()) echo $this->Paginator->next("&raquo;", array('tag' => false, 'escape' => false));?>
    </div>
<?php endif;?>