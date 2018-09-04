<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

return array(
	'version' => '1.0',
	'id'      => 'taobao',
	'name'    => '淘宝助手',
	'v3'      => true,
	'menu'    => array(
		'plugincom' => 1,
		'items'     => array(
			array('title' => '商家商品库', 'route' => 'taobaocsv')
			)
		)
	);

?>
