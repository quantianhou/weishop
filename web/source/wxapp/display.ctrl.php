<?php
defined('IN_IA') or exit('Access Denied');
load()->model('wxapp');
load()->model('account');

$_W['page']['title'] = '小程序列表';

$dos = array('display', 'switch', 'rank');
$do = in_array($do, $dos) ? $do : 'display';

if ($do == 'rank' || $do == 'switch') {
	$uniacid = intval($_GPC['uniacid']);
	if (!empty($uniacid)) {
		$wxapp_info = wxapp_fetch($uniacid);
		if (empty($wxapp_info)) {
			itoast('小程序不存在', referer(), 'error');
		}
	}
}

if ($do == 'display') {
	$pindex = max(1, intval($_GPC['page']));
	$psize = 20;
	$start = ($pindex - 1) * $psize;

	$condition = '';
	$param = array();
	$keyword = trim($_GPC['keyword']);
	if (!empty($_W['isfounder'])) {
		$condition .= " WHERE a.default_acid <> 0 AND b.isdeleted <> 1 AND b.type = " . ACCOUNT_TYPE_APP_NORMAL;
		$order_by = " ORDER BY a.`rank` DESC";
	} else {
		$condition .= "LEFT JOIN ". tablename('uni_account_users')." as c ON a.uniacid = c.uniacid WHERE a.default_acid <> 0 AND c.uid = :uid AND b.isdeleted <> 1 AND b.type = " . ACCOUNT_TYPE_APP_NORMAL;
		$param[':uid'] = $_W['uid'];
		$order_by = " ORDER BY c.`rank` DESC";
	}

	if(!empty($keyword)) {
		$condition .=" AND a.`name` LIKE :name";
		$param[':name'] = "%{$keyword}%";
	}
	if(isset($_GPC['letter']) && strlen($_GPC['letter']) == 1) {
		$letter = trim($_GPC['letter']);
		if(!empty($letter)){
			$condition .= " AND a.`title_initial` = :title_initial";
			$param[':title_initial'] = $letter;
		}else {
			$condition .= " AND a.`title_initial` = ''";
		}
	}
	$tsql = "SELECT COUNT(*) FROM " . tablename('uni_account'). " as a LEFT JOIN". tablename('account'). " as b ON a.default_acid = b.acid {$condition} {$order_by}, a.`uniacid` DESC";
	$sql = "SELECT * FROM ". tablename('uni_account'). " as a LEFT JOIN". tablename('account'). " as b ON a.default_acid = b.acid  {$condition} {$order_by}, a.`uniacid` DESC LIMIT {$start}, {$psize}";
	$total = pdo_fetchcolumn($tsql, $param);
	$wxapp_lists = pdo_fetchall($sql, $param, 'uniacid');
	if(!empty($wxapp_lists)) {
		foreach($wxapp_lists as &$account) {
			$account['url'] = url('wxapp/display/switch', array('uniacid' => $account['uniacid']));
			$account['details'] = uni_accounts($account['uniacid']);
			if(!empty($account['details'])) {
				foreach ($account['details'] as  &$account_val) {
					$account_val['thumb'] = tomedia('headimg_'.$account_val['acid']. '.jpg').'?time='.time();
				}
			}
			$account['role'] = uni_permission($_W['uid'], $account['uniacid']);
			$account['setmeal'] = uni_setmeal($account['uniacid']);
			$current_versions = pdo_fetch("SELECT * FROM " . tablename('wxapp_versions'). ' WHERE uniacid = :uniacid ORDER BY version DESC', array(':uniacid' => $account['uniacid']));
			$account['versions'] = $current_versions;
		}
		unset($account_val);
		unset($account);
	}
	$pager = pagination($total, $pindex, $psize);
	template('wxapp/account-display');
} elseif ($do == 'switch') {
	$module_name = $_GPC['module'];
	$version_id = intval($_GPC['version_id']);
	
	if (!empty($module_name) && !empty($version_id)) {
		$version_info = wxapp_version($version_id);
		if (empty($version_id) || empty($version_info['modules'][$module_name])) {
			itoast('版本信息错误');
		}
		$uniacid = !empty($version_info['modules'][$module_name]['account']['uniacid']) ? $version_info['modules'][$module_name]['account']['uniacid'] : $version_info['uniacid'];
		uni_account_switch($uniacid, url('home/welcome/ext/', array('m' => $module_name)));
	}
	wxapp_save_switch($uniacid);
	header('Location: ' . url('wxapp/version/manage', array('version_id' => $wxapp_info['version']['id'])));
	exit;
} elseif ($do == 'rank') {
	uni_account_rank_top($uniacid);
	itoast('更新成功', '', '');
}