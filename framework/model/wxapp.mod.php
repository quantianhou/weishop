<?php
defined('IN_IA') or exit('Access Denied');


function wxapp_getpackage($data, $if_single = false) {
	load()->classs('cloudapi');

	$api = new CloudApi();
	$result = $api->post('wxapp', 'download', $data, 'html');

	if (is_error($result)) {
			return error(-1, $result['message']);
	} else {
		if (strpos($result, 'error:') === 0 ) {
			return error(-1, substr($result, 6));
		}
	}
	return $result;
}

function wxapp_account_create($account) {
	$uni_account_data = array(
		'name' => $account['name'],
		'description' => $account['description'],
		'groupid' => 0,
	);
	if (!pdo_insert('uni_account', $uni_account_data)) {
		return error(1, '添加公众号失败');
	}
	$uniacid = pdo_insertid();
	
	$account_data = array(
		'uniacid' => $uniacid, 
		'type' => $account['type'], 
		'hash' => random(8)
	);
	pdo_insert('account', $account_data);
	
	$acid = pdo_insertid();
	
	$wxapp_data = array(
		'acid' => $acid,
		'token' => random(32),
		'encodingaeskey' => random(43),
		'uniacid' => $uniacid,
		'name' => $account['name'],
		'account' => $account['account'],
		'original' => $account['original'],
		'level' => $account['level'],
		'key' => $account['key'],
		'secret' => $account['secret'],
	);
	pdo_insert('account_wxapp', $wxapp_data);
	
	if (empty($_W['isfounder'])) {
		pdo_insert('uni_account_users', array('uniacid' => $uniacid, 'uid' => $_W['uid'], 'role' => 'owner'));
	}
	pdo_update('uni_account', array('default_acid' => $acid), array('uniacid' => $uniacid));
	
	return $uniacid;
}


function wxapp_support_wxapp_modules() {
	global $_W;
	load()->model('user');
	
	$modules = user_modules($_W['uid']);
	if (!empty($modules)) {
		foreach ($modules as $module) {
			if ($module['wxapp_support'] == MODULE_SUPPORT_WXAPP) {
				$wxapp_modules[$module['name']] = $module;
			}
		}
	}
	if (empty($wxapp_modules)) {
		return array();
	}
	$bindings = pdo_getall('modules_bindings', array('module' => array_keys($wxapp_modules), 'entry' => 'page'));
	if (!empty($bindings)) {
		foreach ($bindings as $bind) {
			$wxapp_modules[$bind['module']]['bindings'][] = array('title' => $bind['title'], 'do' => $bind['do']);
		}
	}
	return $wxapp_modules;
}


function wxapp_fetch($uniacid, $version_id = '') {
	$wxapp_info = array();
	if (empty($uniacid)) {
		return $wxapp_info;
	}
	
	$wxapp_info = pdo_get('account_wxapp', array('uniacid' => $uniacid));
	if (empty($wxapp_info)) {
		return $wxapp_info;
	}
	
	if (empty($version_id)) {
		$sql ="SELECT * FROM " . tablename('wxapp_versions') . " WHERE `uniacid`=:uniacid ORDER BY `id` DESC";
		$wxapp_version_info = pdo_fetch($sql, array(':uniacid' => $uniacid));
	} else {
		$wxapp_version_info = pdo_get('wxapp_versions', array('id' => $version_id));
	}
	if (!empty($wxapp_version_info)) {
		$wxapp_info['version'] = $wxapp_version_info;
		$wxapp_info['version_num'] = explode('.', $wxapp_version_info['version']);
	}
	return  $wxapp_info;
}

function wxapp_version_all($uniacid) {
	load()->model('module');
	$wxapp_versions = array();
	if (empty($uniacid)) {
		return $wxapp_versions;
	}
	
	$wxapp_versions = pdo_getall('wxapp_versions', array('uniacid' => $uniacid), array(), '', array("id DESC"), array());
	foreach ($wxapp_versions as &$modules_val) {
		$modules_val['modules'] = iunserializer($modules_val['modules']);
		foreach ($modules_val['modules'] as &$module_val) {
			$module_val['module_info'] = module_fetch($module_val['name']);
		}
	}
	unset($module_val, $modules_val);
	return $wxapp_versions;
}


function wxapp_version($version_id) {
	$version_info = array();
	$version_id = intval($version_id);
	
	if (empty($version_id)) {
		return $version_info;
	}
	
	$version_info = pdo_get('wxapp_versions', array('id' => $version_id));
	if (empty($version_info)) {
		return $version_info;
	}
	if (!empty($version_info['modules'])) {
		$version_info['modules'] = unserialize($version_info['modules']);
		if (!empty($version_info['modules'])) {
			foreach ($version_info['modules'] as $i => $module) {
				if (!empty($module['uniacid'])) {
					$account = uni_fetch($module['uniacid']);
				}
				$version_info['modules'][$module['name']] = module_fetch($module['name']);
				$version_info['modules'][$module['name']]['account'] = $account;
			}
		}
	}
	if (!empty($version_info['quickmenu'])) {
		$version_info['quickmenu'] = unserialize($version_info['quickmenu']);
	}
	return $version_info;
}


function wxapp_save_switch($uniacid) {
	global $_W, $_GPC;
	if (empty($_GPC['__switch'])) {
		$_GPC['__switch'] = random(5);
	}
	
	$cache_key = cache_system_key(CACHE_KEY_ACCOUNT_SWITCH, $_GPC['__switch']);
	$cache_lastaccount = cache_load($cache_key);
	if (empty($cache_lastaccount)) {
		$cache_lastaccount = array(
			'wxapp' => $uniacid,
		);
	} else {
		$cache_lastaccount['wxapp'] = $uniacid;
	}
	cache_write($cache_key, $cache_lastaccount);
	isetcookie('__switch', $_GPC['__switch']);
	return true;
}

function wxapp_site_info($multiid) {
	$site_info = array();
	if (empty($multiid)) {
		return array();
	}
	
	$site_info['slide'] = pdo_getall('site_slide', array('multiid' => $multiid));
	$site_info['nav'] = pdo_getall('site_nav', array('multiid' => $multiid));
	if (!empty($site_info['nav'])) {
		foreach($site_info['nav'] as &$nav) {
			$nav['css'] = iunserializer($nav['css']);
		}
		unset($nav);
	}
	$site_info['recommend'] = pdo_getall('site_article', array('uniacid' => $_GPC['uniacid']));
	return $site_info;
}
