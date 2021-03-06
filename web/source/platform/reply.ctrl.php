<?php
defined('IN_IA') or exit('Access Denied');
load()->model('reply');
load()->model('module');

$dos = array('display', 'post', 'delete', 'change_status', 'change_keyword_status');
$do = in_array($do, $dos) ? $do : 'display';

$m = empty($_GPC['m']) ? 'keyword' : trim($_GPC['m']);
if (in_array($m, array('keyword', 'special', 'welcome', 'default', 'apply', 'service'))) {
	uni_user_permission_check('platform_reply');
} else {
	$modules = uni_modules();
	$_W['current_module'] = $modules[$m];
}
$_W['page']['title'] = '自动回复';
if (empty($m)) {
	itoast('错误访问.', '', '');
}

if ($m == 'special') {
	$mtypes = array(
		'image' => '图片消息',
		'voice' => '语音消息',
		'video' => '视频消息',
		'shortvideo' => '小视频消息',
		'location' => '位置消息',
		'trace' => '上报地理位置',
		'link' => '链接消息',
		'merchant_order' => '微小店消息',
		'ShakearoundUserShake' => '摇一摇:开始摇一摇消息',
		'ShakearoundLotteryBind' => '摇一摇:摇到了红包消息',
		'WifiConnected' => 'Wifi连接成功消息'
	);
}
$sysmods = system_modules();

if (in_array($m, array('custom'))) {
	$site = WeUtility::createModuleSite('reply');
	$site_urls = $site->getTabUrls();
}


if ($do == 'display') {
	if ($m == 'keyword' || !in_array($m, $sysmods)) {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 8;
		$cids = $parentcates = $list =  array();
		$condition = 'uniacid = :uniacid AND module != "cover" AND module != "userapi"';
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		if (isset($_GPC['type']) && !empty($_GPC['type'])) {
			if ($_GPC['type'] == 'apply') {
				$condition .= ' AND module NOT IN ("basic", "news", "images", "voice", "video", "music", "wxcard", "reply")';
			} else {
				$condition .= " AND (FIND_IN_SET(:type, `containtype`) OR module = :type)";
				$params[':type'] = $_GPC['type'];	
			}
		}
		if (!in_array($m, $sysmods)) {
			$condition .= " AND `module` = :type";
			$params[':type'] = $m;
		}
		if (isset($_GPC['keyword'])) {
			$condition .= ' AND `name` LIKE :keyword';
			$params[':keyword'] = "%{$_GPC['keyword']}%";
		}
		$replies = reply_search($condition, $params, $pindex, $psize, $total);
		$pager = pagination($total, $pindex, $psize);
		if (!empty($replies)) {
			foreach ($replies as &$item) {
				$condition = '`rid`=:rid';
				$params = array();
				$params[':rid'] = $item['id'];
				$item['keywords'] = reply_keywords_search($condition, $params);
				$item['allreply'] = reply_contnet_search($item['id']);
				$entries = module_entries($item['module'], array('rule'),$item['id']);
				if (!empty($entries)) {
					$item['options'] = $entries['rule'];
				}
								if (!in_array($item['module'], array("basic", "news", "images", "voice", "video", "music", "wxcard", "reply"))) {
					if (file_exists(IA_ROOT.'/addons/'.$item['module'].'/icon-custom.jpg')) {
						$item['logo'] = tomedia(IA_ROOT.'/addons/'.$item['module'].'/icon-custom.jpg');
					} elseif (file_exists(IA_ROOT.'/addons/'.$item['module'].'/icon.jpg')) {
						$item['logo'] = tomedia(IA_ROOT.'/addons/'.$item['module'].'/icon.jpg');
					} else {
						$item['logo'] = './resource/images/11.png';
					}
				}
			}
			unset($item);
		}
		$entries = module_entries($m, array('rule'));
	}
	if ($m == 'special') {
		$setting = uni_setting_load('default_message', $_W['uniacid']);
		$setting = $setting['default_message'] ? $setting['default_message'] : array();
		$module = uni_modules();
	}
	if ($m == 'welcome') {
		$setting = uni_setting($_W['uniacid'], array('welcome'));
		$ruleid = pdo_getcolumn('rule_keyword', array('uniacid' => $_W['uniacid'], 'content' => $setting['welcome']), 'rid');
	}
	if ($m == 'default') {
		$setting = uni_setting($_W['uniacid'], array('default'));
		$ruleid = pdo_getcolumn('rule_keyword', array('uniacid' => $_W['uniacid'], 'content' => $setting['default']), 'rid');
	}
	if ($m == 'service') {
		$userapi_config = pdo_getcolumn('uni_account_modules', array('uniacid' => $_W['uniacid'], 'module' => 'userapi'), 'settings');
		$userapi_config = iunserializer($userapi_config);
		$userapi = reply_search("`uniacid` = 0 AND module = 'userapi' AND `status`=1");
		$userapi_list = array();
		if (!empty($userapi)) {
			foreach ($userapi as $key => $userapi) {
				$description = pdo_getcolumn('userapi_reply', array('rid' => $userapi['id']), 'description');
				$userapi['description'] = $description ? $description : '';
				$userapi['switch'] = $userapi_config[$userapi['id']] == 'checked' ? 'checked' : '';
				$userapi_list[$userapi['id']] = $userapi;
			}
		}
	}
	if ($m == 'userapi') {
		$pindex = max(1, intval($_GPC['page']));
		$psize = 8;
		
		$condition = 'uniacid = :uniacid AND `module`=:module';
		$params = array();
		$params[':uniacid'] = $_W['uniacid'];
		$params[':module'] = 'userapi';
		if(isset($_GPC['keyword'])) {
			$condition .= ' AND `name` LIKE :keyword';
			$params[':keyword'] = "%{$_GPC['keyword']}%";
		}

		$replies = reply_search($condition, $params, $pindex, $psize, $total);
		$pager = pagination($total, $pindex, $psize);
		if (!empty($replies)) {
			foreach($replies as &$item) {
				$condition = '`rid`=:rid';
				$params = array();
				$params[':rid'] = $item['id'];
				$item['keywords'] = reply_keywords_search($condition, $params);
			}
		}
	}
	template('platform/reply');
}
if ($do == 'post') {
	if ($m == 'keyword' || $m == 'userapi' || !in_array($m, $sysmods)) {
		$module['title'] = '关键字自动回复';
		if ($_W['isajax'] && $_W['ispost']) {
			$sql = 'SELECT `rid` FROM ' . tablename('rule_keyword') . " WHERE `uniacid` = :uniacid  AND `content` = :content";
			$result = pdo_fetchall($sql, array(':uniacid' => $_W['uniacid'], ':content' => $_GPC['keyword']));
			if (!empty($result)) {
				$keywords = array();
				foreach ($result as $reply) {
					$keywords[] = $reply['rid'];
				}
				$rids = implode($keywords, ',');
				$sql = 'SELECT `id`, `name` FROM ' . tablename('rule') . " WHERE `id` IN ($rids)";
				$rules = pdo_fetchall($sql);
				iajax(0, @json_encode($rules), '');
			}
			iajax(-1, '');
		}
		$rid = intval($_GPC['rid']);
		if (!empty($rid)) {
			$reply = reply_single($rid);
			if (empty($reply) || $reply['uniacid'] != $_W['uniacid']) {
				itoast('抱歉，您操作的规则不在存或是已经被删除！', url('platform/reply', array('m' => $m)), 'error');
			}
			foreach ($reply['keywords'] as &$kw) {
				$kw = array_elements(array('type', 'content'), $kw);
			}
			unset($kw);
		}
		if (checksubmit('submit')) {
			if (empty($_GPC['rulename'])) {
				itoast('必须填写回复规则名称.', '', '');
			}
			$keywords = @json_decode(htmlspecialchars_decode($_GPC['keywords']), true);
			if (empty($keywords)) {
				itoast('必须填写有效的触发关键字.', '', '');
			}
			$containtype = '';
			$_GPC['reply'] = (array)$_GPC['reply'];
			foreach ($_GPC['reply'] as $replykey => $replyval) {
				if (!empty($replyval)) {
					$containtype .= substr($replykey, 6).',';
				}
			}
			$rule = array(
				'uniacid' => $_W['uniacid'],
				'name' => $_GPC['rulename'],
				'module' => $m == 'keyword' ? 'reply' : $m,
				'containtype' => $containtype,
				'reply_type' => intval($_GPC['reply_type']) == 2 ? 2 : 1,
				'replyall' => intval($_GPC['replyall']) == 2 ? 2 : 1,
				'status' => $_GPC['status'] == 'true' ? 1 : 0,
				'displayorder' => intval($_GPC['displayorder_rule']),
			);
			if ($_GPC['istop'] == 1) {
				$rule['displayorder'] = 255;
			} else {
				$rule['displayorder'] = range_limit($rule['displayorder'], 0, 254);
			}

			if ($m == 'userapi') {
				$module = WeUtility::createModule('userapi');
			} else {
				$module = WeUtility::createModule('core');
			}
			$msg = $module->fieldsFormValidate();

			$module_info = module_fetch($m);
			if (!empty($module_info) && empty($module_info['issystem'])) {
				$user_module = WeUtility::createModule($m);
				if (empty($user_module)) {
					itoast('抱歉，模块不存在请重新选择其它模块！', '', '');
				}
				$user_module_error_msg = $user_module->fieldsFormValidate();
			}
			if ((is_string($msg) && trim($msg) != '') || (is_string($user_module_error_msg) && trim($user_module_error_msg) != '')) {
				itoast($msg.$user_module_error_msg, '', '');
			}
			if (!empty($rid)) {
				$result = pdo_update('rule', $rule, array('id' => $rid));
			} else {
				$result = pdo_insert('rule', $rule);
				$rid = pdo_insertid();
			}

			if (!empty($rid)) {
				$sql = 'DELETE FROM '. tablename('rule_keyword') . ' WHERE `rid`=:rid AND `uniacid`=:uniacid';
				$pars = array();
				$pars[':rid'] = $rid;
				$pars[':uniacid'] = $_W['uniacid'];
				pdo_query($sql, $pars);
				$rowtpl = array(
					'rid' => $rid,
					'uniacid' => $_W['uniacid'],
					'module' => $m == 'keyword' ? 'reply' : $m,
					'status' => $rule['status'],
					'displayorder' => $rule['displayorder'],
				);
				foreach ($keywords as $kw) {
					$krow = $rowtpl;
					$krow['type'] = range_limit($kw['type'], 1, 4);
					$krow['content'] = $kw['content'];
					pdo_insert('rule_keyword', $krow);
				}
				$kid = pdo_insertid();
				$module->fieldsFormSubmit($rid);
				if (!empty($module_info) && empty($module_info['issystem'])) {
					$user_module->fieldsFormSubmit($rid);
				}
				itoast('回复规则保存成功！', url('platform/reply', array('m' => $m)), 'success');
			} else {
				itoast('回复规则保存失败, 请联系网站管理员！', url('platform/reply', array('m' => $m)), 'error');
			}
		}
		template('platform/reply-post');
	}
	if ($m == 'special') {
		$type = trim($_GPC['type']);
		$setting = uni_setting_load('default_message', $_W['uniacid']);
		$setting = $setting['default_message'] ? $setting['default_message'] : array();
		if (checksubmit('submit')) {
			$rule_id = intval(trim(htmlspecialchars_decode($_GPC['reply']['reply_keyword']), "\""));
			$module = trim(htmlspecialchars_decode($_GPC['reply']['reply_module']), "\"");
			if ((empty($rule_id) && empty($module)) || $_GPC['status'] === '0') {
				$setting[$type] = array('type' => '', 'module' => $module, 'keyword' => $rule_id);
				uni_setting_save('default_message', $setting);
				itoast('关闭成功', url('platform/reply', array('m' => 'special')), 'success');
			}
			$reply_type = empty($rule_id) ? 'module' : 'keyword';
			$reply_module = WeUtility::createModule('core');
			$result = $reply_module->fieldsFormValidate();
			if (is_error($result)) {
				itoast($result['message'], '', 'info');
			}
			if ($reply_type == 'module') {
				$setting[$type] = array('type' => 'module', 'module' => $module);
			} else {
				$rule = pdo_get('rule_keyword', array('rid' => $rule_id, 'uniacid' => $_W['uniacid']));
				$setting[$type] = array('type' => 'keyword', 'keyword' => $rule['content']);
			}
			uni_setting_save('default_message', $setting);
			itoast('发布成功', url('platform/reply', array('m' => 'special')), 'success');
		}
		if ($setting[$type]['type'] == 'module') {
			$rule_id = $setting[$type]['module'];
		} else {
			$rule_id = pdo_getcolumn('rule_keyword', array('uniacid' => $_W['uniacid'], 'content' => $setting[$type]['keyword']), 'rid');
		}
		template('platform/specialreply-post');
	}
	if ($m == 'welcome') {
		if (checksubmit('submit')) {
			$rule_id = intval(trim(htmlspecialchars_decode($_GPC['reply']['reply_keyword']), "\""));
			if (!empty($rule_id)) {
				$rule = pdo_get('rule_keyword', array('rid' => $rule_id, 'uniacid' => $_W['uniacid']));
				$settings = array(
					'welcome' => $rule['content']
				);
			} else {
				$settings = array('welcome' => '');
			}
			$item = pdo_fetch ('SELECT uniacid FROM ' . tablename ('uni_settings') . " WHERE uniacid=:uniacid", array (':uniacid' => $_W['uniacid']));
			if (!empty($item)) {
				pdo_update ('uni_settings', $settings, array ('uniacid' => $_W['uniacid']));
			} else {
				$settings['uniacid'] = $_W['uniacid'];
				pdo_insert ('uni_settings', $settings);
			}
			cache_delete("unisetting:{$_W['uniacid']}");
			itoast('系统回复更新成功！', url('platform/reply', array('m' => 'welcome')), 'success');
		}
	}
	if ($m == 'default') {
		if (checksubmit('submit')) {
			$rule_id = intval(trim(htmlspecialchars_decode($_GPC['reply']['reply_keyword']), "\""));
			if (!empty($rule_id)) {
				$rule = pdo_get('rule_keyword', array('rid' => $rule_id, 'uniacid' => $_W['uniacid']));
				$settings = array(
					'default' => $rule['content']
				);
			} else {
				$settings = array('default' => '');
			}
			$item = pdo_fetch('SELECT uniacid FROM '.tablename('uni_settings')." WHERE uniacid=:uniacid", array(':uniacid' => $_W['uniacid']));
			if (!empty($item)){
				pdo_update('uni_settings', $settings, array('uniacid' => $_W['uniacid']));
			} else {
				$settings['uniacid'] = $_W['uniacid'];
				pdo_insert('uni_settings', $settings);
			}
			cache_delete("unisetting:{$_W['uniacid']}");
			itoast('系统回复更新成功！', url('platform/reply', array('m' => 'default')), 'success');
		}
	}
	if ($m == 'apply') {
		include IA_ROOT . '/framework/library/pinyin/pinyin.php';
		$pinyin = new Pinyin_Pinyin();
		$module['title'] = '应用关键字';
		$installedmodulelist = uni_modules();
		foreach ($installedmodulelist as $key => &$value) {
			if ($value['type'] == 'system') {
				unset($installedmodulelist[$key]);
			}
			$value['official'] = empty($value['issystem']) && (strexists($value['author'], 'WeEngine Team') || strexists($value['author'], ''));
		}
		unset($value);
		foreach ($installedmodulelist as $name => $module) {
			if (empty($module['isrulefields']) && $name != "core") {
				continue;
			}
			$module['title_first_pinyin'] = $pinyin->get_first_char($module['title']);
			if ($module['issystem']) {
				$path = '../framework/builtin/' . $module['name'];
			} else {
				$path = '../addons/' . $module['name'];
			}
			$cion = $path . '/icon-custom.jpg';
			if (!file_exists($cion)) {
				$cion = $path . '/icon.jpg';
				if(!file_exists($cion)) {
					$cion = './resource/images/nopic-small.jpg';
				}
			}
			$module['icon'] = $cion;
			
			if ($module['enabled'] == 1) {
				$enable_modules[$name] = $module;
			} else {
				$unenable_modules[$name] = $module;
			}
		}
		$current_user_permissions = pdo_getall('users_permission', array('uid' => $_W['user']['uid'], 'uniacid' => $_W['uniacid']), array(), 'type');
		if (!empty($current_user_permissions)) {
			$current_user_permission_types = array_keys($current_user_permissions);
		}
		$moudles = true;
		template('platform/reply-post');
	}
}

if($do == 'delete') {
	$rids = $_GPC['rid'];
	if (!is_array($rids)) {
		$rids = array($rids);
	}
	if(empty($rids)) {
		itoast('非法访问.', '', '');
	}
	foreach ($rids as $rid) {
		$rid = intval($rid);
		$reply = reply_single($rid);
		if (empty($reply) || $reply['uniacid'] != $_W['uniacid']) {
			itoast('抱歉，您操作的规则不在存或是已经被删除！', url('platform/reply', array('m' => $m)), 'error');
		}
				if (pdo_delete('rule', array('id' => $rid))) {
			pdo_delete('rule_keyword', array('rid' => $rid));
						pdo_delete('stat_rule', array('rid' => $rid));
			pdo_delete('stat_keyword', array('rid' => $rid));
						if (!in_array($m, $sysmods)) {
				$reply_module = $m;
			} else {
				if ($m == 'userapi') {
					$reply_module = 'userapi';
				}else {
					$reply_module = 'reply';
				}
			}
			$module = WeUtility::createModule($reply_module);
			if (method_exists($module, 'ruleDeleted')) {
				$module->ruleDeleted($rid);
			}
		}
	}
	itoast('规则操作成功！', referer(), 'success');
}

if ($do == 'change_status') {
	$m = $_GPC['m'];
	if ($m == 'service') {
		$rid = intval($_GPC['rid']);
		$userapi_config = pdo_getcolumn('uni_account_modules', array('uniacid' => $_W['uniacid'], 'module' => 'userapi'), 'settings');
		$config = iunserializer($userapi_config);
		$config[$rid] = $config[$rid] ? false : true;
		$module_api = WeUtility::createModule('userapi');
		$module_api->saveSettings($config);
		iajax(0, '');
	} else {
		$type = $_GPC['type'];
		$setting = uni_setting_load('default_message', $_W['uniacid']);
		$setting = $setting['default_message'] ? $setting['default_message'] : array();
		if (empty($setting[$type]['type'])) {
			if (!empty($setting[$type]['keyword'])) {
				$setting[$type]['type'] = 'keyword';
			}
			if (!empty($setting[$type]['module'])) {
				$setting[$type]['type'] = 'module';
			}
			if (empty($setting[$type]['type'])) {
				iajax(1, '请先设置回复内容', '');
			}
		} else {
			$setting[$type]['type'] = '';
		}
		$result = uni_setting_save('default_message', $setting);
		if ($result) {
			iajax(0, '更新成功！');
		}
	}
}

if ($do == 'change_keyword_status') {
	
	$id = intval($_GPC['id']);
	$result = pdo_get('rule', array('id' => $id), array('status'));
	if (!empty($result)) {
		$rule = $rule_keyword = false;
		if ($result['status'] == 1) {
			$rule = pdo_update('rule', array('status' => 0), array('id' => $id));
			$rule_keyword = pdo_update('rule_keyword', array('status' => 0), array('uniacid' => $_W['uniacid'], 'rid' => $id));
		}else {
			$rule = pdo_update('rule', array('status' => 1), array('id' => $id));
			$rule_keyword = pdo_update('rule_keyword', array('status' => 1), array('uniacid' => $_W['uniacid'], 'rid' => $id));
		}
		if ($rule && $rule_keyword) {
			iajax(0, '更新成功！', '');
		} else {
			iajax(-1, '更新失败！', '');
		}
	}
	iajax(-1, '更新失败！', '');
}