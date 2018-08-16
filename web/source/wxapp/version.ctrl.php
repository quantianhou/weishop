<?php
defined('IN_IA') or exit('Access Denied');

load()->model('module');
load()->model('wxapp');

$dos = array('display', 'manage', 'module_link_uniacid', 'search_link_account');
$do = in_array($do, $dos) ? $do : 'edit';
$_W['page']['title'] = '小程序 - 管理';

$uniacid = intval($_GPC['uniacid']);
$version_id = intval($_GPC['version_id']);
if (!empty($uniacid)) {
	$wxapp_info = wxapp_fetch($uniacid);
}
if (!empty($version_id)) {
	$version_info = wxapp_version($version_id);
	$wxapp_info = wxapp_fetch($version_info['uniacid']);
}

if ($do == 'display') {
	$wxapp_version_list = wxapp_version_all($uniacid);
	template('wxapp/version-display');
}

if ($do == 'manage') {
	if ($version_info['design_method'] == WXAPP_TEMPLATE) {
		$version_site_info = wxapp_site_info($version_info['multiid']);
	}

	template('wxapp/version-manage');
}

if ($do == 'module_link_uniacid') {
	$module_name = $_GPC['module_name'];
	
	$version_info = wxapp_version($version_id);

	if (checksubmit('submit')) {
		if (!empty($module_name) && !empty($uniacid) && !empty($version_info['modules'][$module_name])) {
			$module_update = array();
			foreach ($version_info['modules'] as $module) {
				$module_update[$module['name']] = array('name' => $module['name'], 'version' => $module['version'], 'uniacid' => $uniacid);
			}
			pdo_update('wxapp_versions', array('modules' => serialize($module_update)), array('id' => $version_id));
		}
		iajax(0, '关联公众号成功');
	}
	template('wxapp/version-module-link-uniacid');
}

if ($do == 'search_link_account') {
	$module_name = $_GPC['module_name'];
	if (empty($module_name)) {
		iajax(0, array());
	}
	$account_list = uni_owned();
	if ($_W['isfounder']) {
		iajax(0, $account_list);
	}
	if (!empty($account_list)) {
		foreach ($account_list as $i => $account) {
			$has_modules = uni_modules_by_uniacid($account['uniacid']);
			if (empty($has_modules[$module_name])) {
				unset($account_list[$i]);
			}
		}
	}
	iajax(0, $account_list);
}
