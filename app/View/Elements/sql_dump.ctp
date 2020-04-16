<?php
/**
 * SQL Dump element. Dumps out SQL log information
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Elements
 * @since         CakePHP(tm) v 1.3
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

if (!class_exists('ConnectionManager') || Configure::read('debug') < 2) {
	return false;
}
$noLogs = !isset($sqlLogs);
if ($noLogs):
	$sources = ConnectionManager::sourceList();

	$sqlLogs = array();
	foreach ($sources as $source):
		$db = ConnectionManager::getDataSource($source);
		if (!method_exists($db, 'getLog')):
			continue;
		endif;
		$sqlLogs[$source] = $db->getLog();
	endforeach;
endif;

if ($noLogs || isset($_forced_from_dbo_)):
	foreach ($sqlLogs as $source => $logInfo):
		$text = $logInfo['count'] > 1 ? 'queries' : 'query';
		printf(
			'<table class="cake-sql-log" id="cakeSqlLog_%s" summary="Cake SQL Log" cellspacing="0">',
			preg_replace('/[^A-Za-z0-9_]/', '_', uniqid(time(), true))
		);
        echo "<caption>TOTAL EXEC TIME: " . round(microtime(true) - TIME_START, 4) . "s (" . round(TIME_START_1 - TIME_START, 4) . "s/" . round(TIME_START_2 - TIME_START_1, 4) . "s/" . round(microtime(true) - TIME_START_2, 4) . "s)</caption>";
        echo "<caption>TPL EXEC TIME: " . round(TIME_START_TPL_3 - TIME_START_TPL, 4) . "s (" . round(TIME_START_TPL_2 - TIME_START_TPL_1, 4) . "s/" . round(TIME_START_TPL_3 - TIME_START_TPL_2, 4) . "s/" . ")</caption>";
        //$exec_time_logs = Configure::read('EXEC_TIME_LOGS');
        //if(!empty($exec_time_logs)) foreach($exec_time_logs as $exec_time_log){
        //    echo "<caption>{$exec_time_log}</caption>";
        //}
        $exec_time_logs_read = Configure::read('EXEC_TIME_LOGS_read');
        if(!empty($exec_time_logs_read)) foreach($exec_time_logs_read as $exec_time_log){
            echo "<caption>{$exec_time_log}</caption>";
        }
        $oth_logs = Configure::read('SYS_LOGS');
        if(!empty($oth_logs)) foreach($oth_logs as $oth_log){
            echo "<caption>{$oth_log}</caption>";
        }
		printf('<caption>(%s) %s %s took %s ms</caption>', $source, $logInfo['count'], $text, $logInfo['time']);
	?>
	<thead>
		<tr><th>Nr</th><th>Query</th><th>Error</th><th>Affected</th><th>Num. rows</th><th>Took (ms)</th></tr>
	</thead>
	<tbody>
	<?php

        function log_sort($a, $b)
        {
            if ($a["took"] == $b["took"]) {
                return 0;
            }
            return ($a["took"] > $b["took"]) ? -1 : 1;
        }
        
        uasort($logInfo['log'], "log_sort");

		foreach ($logInfo['log'] as $k => $i) :
			$i += array('error' => '');
			if (!empty($i['params']) && is_array($i['params'])) {
				$bindParam = $bindType = null;
				if (preg_match('/.+ :.+/', $i['query'])) {
					$bindType = true;
				}
				foreach ($i['params'] as $bindKey => $bindVal) {
					if ($bindType === true) {
						$bindParam .= h($bindKey) . " => " . h($bindVal) . ", ";
					} else {
						$bindParam .= h($bindVal) . ", ";
					}
				}
				$i['query'] .= " , params[ " . rtrim($bindParam, ', ') . " ]";
			}
			printf('<tr><td>%d</td><td>%s</td><td>%s</td><td style="text-align: right">%d</td><td style="text-align: right">%d</td><td style="text-align: right">%d</td></tr>%s',
				$k + 1,
				h($i['query']),
				$i['error'],
				$i['affected'],
				$i['numRows'],
				$i['took'],
				"\n"
			);
		endforeach;
	?>
	</tbody></table>
	<?php
	endforeach;
else:
	printf('<p>%s</p>', __d('cake_dev', 'Encountered unexpected %s. Cannot generate SQL log.', '$sqlLogs'));
endif;
