<?php
 
defined('IN_IA') or exit('Access Denied');
if (strexists($_SERVER['HTTP_REFERER'], 'https://servicewechat.com/')) {
	$referer_url = parse_url($_SERVER['HTTP_REFERER']);
	list($appid, $version) = explode('/', ltrim($referer_url['path'], '/'));
}
$site = WeUtility::createModuleWxapp($entry['module']);
if(!is_error($site)) {
	$site->appid = $appid;
	$site->version = $version;
	$method = 'doPage' . ucfirst($entry['do']);
	if (!empty($site->token)) {
		if (!$site->checkSign()) {
			message(error(1, '签名错误'), '', 'ajax');
		}
	}
	if (!empty($_GPC['i'])) {
		$version_info = pdo_get('wxapp_versions', array('uniacid' => $_GPC['i'], 'version' => $_GPC['v']), array('id', 'uniacid', 'redirect', 'template', 'connection'));
		$connection = json_decode($version_info['connection'], true);
		$_W['uniacid'] = !empty($connection[$entry['module']]) ? $connection[$entry['module']] : $version_info['uniacid'];
	}
	exit($site->$method());
}
exit();