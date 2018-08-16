<?php

if (!defined('IN_IA')) {
	exit('Access Denied');
}


return array(
'version' => '1.0', 
'id' => 'polyapi', 
'name' => '网店管家',
'v3' => true,
	'menu'    => array(
		'title'     => '网店管家',
		'plugincom' => 1,
		'icon'      => 'page',
		'items'     => array(
			array('title' => '基础设置', 'route' => 'set'),
			)
			)

);

?>