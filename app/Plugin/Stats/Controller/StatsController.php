<?php
class StatsController extends StatsAppController {

    public $uses = array('Stats.CmsStats');

    public function beforeFilter() {
        parent::beforeFilter();

        Configure::write('top_buttons_add', array(
            'day' => array('title' => ___('Month'), 'url' => array('plugin' => 'stats', 'controller' => 'stats', 'action' => 'index')),
            'month' => array('title' => ___('Year'), 'url' => array('plugin' => 'stats', 'controller' => 'stats', 'action' => 'month')),
        ));
    }
    
    public function admin_index(){

        if(empty($this->params->query['date'])) $this->params->query['date'] = (date("d") == '01' ? date("Y-m", time() - 86400) : date("Y-m"));
        $select = $this->CmsStats->find('ilist', array('conditions' => array('CmsStats.ipaddress' => array('day_views', 'day_visitors')), 'fields' => array("DATE_FORMAT(`CmsStats`.`date`, '%Y-%m') AS CmsStats__date"), 'group' => 'CmsStats__date'));
        foreach($select as $key => $val) $select[$key] = date("M, Y", strtotime($val . '-01'));
        
        $_items = $this->CmsStats->find('all', array('conditions' => array("DATE_FORMAT(`CmsStats`.`date`, '%Y-%m') = '{$this->params->query['date']}'", 'CmsStats.ipaddress' => array('day_views', 'day_visitors')), 'order' => array('CmsStats.date')));
        foreach($_items as $item) $items[$item['CmsStats']['date']][$item['CmsStats']['ipaddress']] = $item['CmsStats']['views'];
        
        $this->set('page_title', ___('Statistics'));
        $this->set('select', $select);
        $this->set('items', $items);
    }

    public function admin_month(){

        if(empty($this->params->query['date'])) $this->params->query['date'] = (date("m") == '01' ? date("Y")-1 : date("Y"));
        $select = $this->CmsStats->find('ilist', array('conditions' => array('CmsStats.ipaddress' => array('month_views', 'month_visitors')), 'fields' => array("DATE_FORMAT(`CmsStats`.`date`, '%Y') AS CmsStats__date"), 'group' => 'CmsStats__date'));

        $_items = $this->CmsStats->find('all', array('conditions' => array('CmsStats.ipaddress' => array('month_views', 'month_visitors'), "DATE_FORMAT(`CmsStats`.`date`, '%Y') = '{$this->params->query['date']}'"), 'order' => array('CmsStats.date')));
        foreach($_items as $item) $items[$item['CmsStats']['date']][$item['CmsStats']['ipaddress']] = $item['CmsStats']['views'];
        
        $this->set('page_title', ___('Statistics'));
        $this->set('select', $select);
        $this->set('items', $items);
    }
}
