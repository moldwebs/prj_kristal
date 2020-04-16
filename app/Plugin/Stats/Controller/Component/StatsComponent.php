<?php
class StatsComponent extends Component {
    
    protected $_controller = null;

	public function initialize(Controller $controller) {
		$this->_controller = $controller;
        if(!$this->_controller->RequestHandler->isAjax() && !isset($this->_controller->request->params['requested']) && !isset($this->_controller->request->params['admin'])){

            $CmsStats = Classregistry::init('Stats.CmsStats');

            if(Cache::read('exec_stats') != date("Y-m-d")){
                Cache::write('exec_stats', date("Y-m-d"));
                $results = $CmsStats->find('iall', array('conditions' => array('`CmsStats`.`date` <' => date("Y-m-d"), "CmsStats.ipaddress NOT IN ('day_views', 'day_visitors', 'month_views', 'month_visitors')"), 'fields' => array('SUM(`CmsStats`.`views`) AS `views`', 'COUNT(`CmsStats`.`views`) AS `visitors`', '`CmsStats`.`date`'), 'group' => array('`CmsStats`.`date`')));
                foreach($results as $result){
                    
                    if($CmsStats->find('count', array('conditions' => array('CmsStats.date' => $result['CmsStats']['date'], 'CmsStats.ipaddress' => 'day_visitors'))) > 0) continue;
                    
                    $CmsStats->deleteAll(array('CmsStats.date' => $result['CmsStats']['date']));
                    $CmsStats->create();
                    $CmsStats->save(array('ipaddress' => 'day_views', 'date' => $result['CmsStats']['date'], 'views' => $result['CmsStats']['views']));
                    $CmsStats->create();
                    $CmsStats->save(array('ipaddress' => 'day_visitors', 'date' => $result['CmsStats']['date'], 'views' => $result['CmsStats']['visitors']));
                }

                $results = $CmsStats->find('iall', array('conditions' => array("DATE_FORMAT(`CmsStats`.`date`, '%Y-%m') <" => date("Y-m"), "DATE_FORMAT(`CmsStats`.`date`, '%Y-%m') NOT IN (SELECT DATE_FORMAT(`date`, '%Y-%m') FROM wb_cms_stats WHERE `ipaddress` = 'month_views')", "CmsStats.ipaddress NOT IN ('month_views', 'month_visitors')"), 'fields' => array("SUM(IF(`CmsStats`.`ipaddress` = 'day_views', `CmsStats`.`views`, NULL)) AS `views`", "SUM(IF(`CmsStats`.`ipaddress` = 'day_visitors', `CmsStats`.`views`, NULL)) AS `visitors`", "DATE_FORMAT(`CmsStats`.`date`, '%Y-%m') AS `date`"), 'group' => array("DATE_FORMAT(`CmsStats`.`date`, '%Y-%m')")));
                foreach($results as $result){
                    $CmsStats->create();
                    $CmsStats->save(array('ipaddress' => 'month_views', 'date' => $result['CmsStats']['date'] . '-01', 'views' => $result['CmsStats']['views']));
                    $CmsStats->create();
                    $CmsStats->save(array('ipaddress' => 'month_visitors', 'date' => $result['CmsStats']['date'] . '-01', 'views' => $result['CmsStats']['visitors']));
                }

                $CmsStats->deleteAll(array("DATE_FORMAT(`CmsStats`.`date`, '%Y') <" => date("Y"), 'CmsStats.ipaddress' => array('day_views', 'day_visitors')));
            }
            
            $CmsStats->increment_insert(array('ipaddress' => $this->_controller->RequestHandler->getClientIP(), 'date' => date("Y-m-d")), 'views');
        }
	}
}

?>