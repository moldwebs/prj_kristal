<?php

set_time_limit(0);
ini_set('display_errors', 1);

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

if (!defined('ROOT')) {
	define('ROOT', dirname(dirname(dirname(__FILE__))));
}

if (!defined('APP_DIR')) {
	define('APP_DIR', basename(dirname(dirname(__FILE__))));
}

date_default_timezone_set('Europe/Chisinau');

require_once ROOT . DS . 'web_config' . DS . 'database.php';
$db_con = new DATABASE_CONFIG();
$dbi = mysql_connect($db_con->default['host'], $db_con->default['login'], $db_con->default['password'], TRUE);
if(!$dbi) die('Could not connect: ' . mysql_error());
mysql_select_db($db_con->default['database'], $dbi);
$r = mysql_query("SELECT id FROM wb_user WHERE `uid` = '0' AND `password` IS NOT NULL LIMIT 1", $dbi);
$row = mysql_fetch_assoc($r);
print_r($row);

echo "<hr>";
echo "PHP TIME: " . date("Y-m-d H:i:s"); 
echo "<hr>";

//mysql_query("SET GLOBAL time_zone = '+3:00';", $dbi);

$r = mysql_query("SELECT NOW() as time", $dbi);
$row = mysql_fetch_assoc($r);
echo "<hr>";
echo "MYSQL TIME: " . $row['time']; 
echo "<hr>";
echo $_SERVER['REMOTE_ADDR'];
echo "<hr>";

?>

<?
extension_check(array( 
	'curl',
	'dom', 
	'gd', 
	'hash',
	'iconv',
	'mcrypt',
	'pcre', 
	'pdo', 
	'pdo_mysql', 
	'simplexml'
));

function extension_check($extensions) {
	$fail = '';
	$pass = '';
	
	if(version_compare(phpversion(), '5.3.0', '<')) {
		$fail .= '<li>You need<strong> PHP 5.3.0</strong> (or greater '.phpversion().')</li>';
	}
	else {
		$pass .='<li>You have<strong> PHP 5.3.0</strong> (or greater '.phpversion().')</li>';
	}

	if(!ini_get('safe_mode')) {
		$pass .='<li>Safe Mode is <strong>off</strong></li>';
		preg_match('/[0-9]\.[0-9]+\.[0-9]+/', shell_exec('mysql -V'), $version);
		
		if(version_compare($version[0], '4.1.20', '<')) {
			$fail .= '<li>You need<strong> MySQL 4.1.20</strong> (or greater)</li>';
		}
		else {
			$pass .='<li>You have<strong> MySQL 4.1.20</strong> (or greater)</li>';
		}
	}
	else { $fail .= '<li>Safe Mode is <strong>on</strong></li>';  }

	foreach($extensions as $extension) {
		if(!extension_loaded($extension)) {
			$fail .= '<li> You are missing the <strong>'.$extension.'</strong> extension</li>';
		}
		else{	$pass .= '<li>You have the <strong>'.$extension.'</strong> extension</li>';
		}
	}
	
	if($fail) {
		echo '<p><strong>Your server does not meet the following requirements in order to install Magento.</strong>';
		echo '<br>The following requirements failed, please contact your hosting provider in order to receive assistance with meeting the system requirements for Magento:';
		echo '<ul>'.$fail.'</ul></p>';
		echo 'The following requirements were successfully met:';
		echo '<ul>'.$pass.'</ul>';
	} else {
		echo '<p><strong>Congratulations!</strong> Your server meets the requirements for Magento.</p>';
		echo '<ul>'.$pass.'</ul>';

	}
}
?>