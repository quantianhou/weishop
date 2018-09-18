<?php
define('IN_MOBILE', true);

require '../framework/bootstrap.inc.php';
load()->app('common');
load()->app('template');
load()->model('app');
require IA_ROOT . '/app/common/bootstrap.app.inc.php';

$acl = array(
	'home' => array(
		'default' => 'home',
	),
	'mc' => array(
		'default' => 'home'
	)
);

if ($_W['setting']['copyright']['status'] == 1) {
	$_W['siteclose'] = true;
	message('抱歉，站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason']);
}

//进商城的时候，根据当前公众号隶属的商家，去查当前商家下的门店记录，有没有公众号id为0的，如果有，更新为商家下的唯一公众号id
$merchant_code_param[':uni_account_id'] = $_W['uniacid'];
$sql = "SELECT merchant_code FROM ". tablename('b_users_uniaccount_relationship'). "  WHERE uni_account_id = :uni_account_id";
$merchant_code = pdo_fetch($sql, $merchant_code_param);

$merchant_param[':merchant_code'] = $merchant_code['merchant_code'];
$sql = "SELECT id FROM ". tablename('a_merchant'). "  WHERE merchant_code = :merchant_code";
$merchant_id = pdo_fetch($sql, $merchant_param);

$need_update_param = array('a_merchant_id' => $merchant_id['id'], 'uniacid' => 0);
$sql = "SELECT count(1) as c FROM ". tablename('ewei_shop_store'). "  WHERE a_merchant_id = :a_merchant_id AND uniacid = :uniacid";
$need_update_count = pdo_fetch($sql, $need_update_param);
if($need_update_count['c'] > 0){
    $update_store_res = pdo_update('ewei_shop_store', array('uniacid' => $_W['uniacid']), array('a_merchant_id' => $merchant_id['id'], 'uniacid' => 0));
}

$multiid = intval($_GPC['t']);
if(empty($multiid)) {
		$multiid = intval($unisetting['default_site']);
	unset($setting);
}

$multi = pdo_fetch("SELECT * FROM ".tablename('site_multi')." WHERE id=:id AND uniacid=:uniacid", array(':id' => $multiid, ':uniacid' => $_W['uniacid']));
$multi['site_info'] = @iunserializer($multi['site_info']);

$styleid = !empty($_GPC['s']) ? intval($_GPC['s']) : intval($multi['styleid']);
$style = pdo_fetch("SELECT * FROM ".tablename('site_styles')." WHERE id = :id", array(':id' => $styleid));

$templates = uni_templates();
$templateid = intval($style['templateid']);
$template = $templates[$templateid];

$_W['template'] = !empty($template) ? $template['name'] : 'default';
$_W['styles'] = array();

if(!empty($template) && !empty($style)) {
	$sql = "SELECT `variable`, `content` FROM " . tablename('site_styles_vars') . " WHERE `uniacid`=:uniacid AND `styleid`=:styleid";
	$params = array();
	$params[':uniacid'] = $_W['uniacid'];
	$params[':styleid'] = $styleid;
	$stylevars = pdo_fetchall($sql, $params);
	if(!empty($stylevars)) {
		foreach($stylevars as $row) {
			if (strexists($row['variable'], 'img')) {
				$row['content'] = tomedia($row['content']);
			}
			$_W['styles'][$row['variable']] = $row['content'];
		}
	}
	unset($stylevars, $row, $sql, $params);
}

$_W['page'] = array();
$_W['page']['title'] = $multi['title'];
if(is_array($multi['site_info'])) {
	$_W['page'] = array_merge($_W['page'], $multi['site_info']);
}
unset($multi, $styleid, $style, $templateid, $template, $templates);

if ($controller == 'wechat' && $action == 'card' && $do == 'use') {
	header("location: index.php?i={$_W['uniacid']}&c=entry&m=paycenter&do=consume&encrypt_code={$_GPC['encrypt_code']}&card_id={$_GPC['card_id']}&openid={$_GPC['openid']}&source={$_GPC['source']}");
	exit;
}
$controllers = array();
$handle = opendir(IA_ROOT . '/app/source/');
if(!empty($handle)) {
	while($dir = readdir($handle)) {
		if($dir != '.' && $dir != '..') {
			$controllers[] = $dir;
		}
	}
}
if(!in_array($controller, $controllers)) {
	$controller = 'home';
}
$init = IA_ROOT . "/app/source/{$controller}/__init.php";
if(is_file($init)) {
	require $init;;
}

$actions = array();
$handle = opendir(IA_ROOT . '/app/source/' . $controller);
if(!empty($handle)) {
	while($dir = readdir($handle)) {
		if($dir != '.' && $dir != '..' && strexists($dir, '.ctrl.php')) {
			$dir = str_replace('.ctrl.php', '', $dir);
			$actions[] = $dir;
		}
	}
}

if(empty($actions)) {
	$str = '';
	if(uni_is_multi_acid()) {
		$str = "&j={$_W['acid']}";
	}
	header("location: index.php?i={$_W['uniacid']}{$str}&c=home?refresh");
}
if(!in_array($action, $actions)) {
	$action = $acl[$controller]['default'];
}
if(!in_array($action, $actions)) {
	$action = $actions[0];
}
//echo $controller,'-',$action;exit;
require _forward($controller, $action);

function _forward($c, $a) {
	$file = IA_ROOT . '/app/source/' . $c . '/' . $a . '.ctrl.php';
	return $file;
}
