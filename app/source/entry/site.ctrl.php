<?php
 
defined('IN_IA') or exit('Access Denied');

$site = WeUtility::createModuleSite($entry['module']);
if(!is_error($site)) {
	$method = 'doMobile' . ucfirst($entry['do']);
	exit($site->$method());
}
exit();