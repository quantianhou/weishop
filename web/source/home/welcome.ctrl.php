<?php
defined('IN_IA') or exit('Access Denied');
//fanhailong add
global $_W;
//print_r($_W);exit('aa');
//$_W['uniacid'] = 17;
checkaccount();

load()->model('welcome');

$dos = array('platform', 'ext', 'get_fans_kpi');
$do = in_array($do, $dos) ? $do : 'platform';

if ($do == 'platform') {
	define('FRAME', 'account');

	if (empty($_W['account']['endtime']) && !empty($_W['account']['endtime']) && $_W['account']['endtime'] < time()) {
		message('公众号已到服务期限，请续费', referer(), 'info', true);
	}
	//公告
	$notices = pdo_getall('article_notice', array('is_display' => 1), array('id', 'title', 'createtime'), '', 'createtime DESC', array(1,5));
	if(!empty($notices)) {
		foreach ($notices as $key => $notice_val) {
			$notices[$key]['url'] = url('article/notice-show/detail', array('id' => $notice_val['id']));
			$notices[$key]['createtime'] = date('Y-m-d', $notice_val['createtime']);
		}
	}
//	$frames = buildframes(FRAME);print_r($frames);exit;exit('aaa');
	template('home/welcome');
} elseif ($do == 'ext') {
	$modulename = $_GPC['m'];
	if (!empty($modulename)) {
		$modules = uni_modules();
		$_W['current_module'] = $modules[$modulename];
	}
    $site = WeUtility::createModule($modulename);
	if (!is_error($site)) {
		$method = 'welcomeDisplay';
		if(method_exists($site, $method)){
			define('FRAME', 'module_welcome');
			$entries = module_entries($modulename, array('menu', 'home', 'profile', 'shortcut', 'cover', 'mine'));
			$site->$method($entries);
			exit;
		}
	}
	define('FRAME', 'account');
	define('IN_MODULE', $modulename);
	$frames = buildframes('account');
	foreach ($frames['section'] as $secion) {
		foreach ($secion['menu'] as $menu) {
			if (!empty($menu['url'])) {
				header('Location: ' . $_W['siteroot'] . 'web/' . $menu['url']);
				exit;
			}
		}
	}
	template('home/welcome-ext');
} elseif ($do == 'get_fans_kpi') {
	uni_update_week_stat();
	$_W['page']['title'] = '平台相关数据';
	//今日昨日指标
	$yesterday = date('Ymd', strtotime('-1 days'));
	$yesterday_stat = pdo_get('stat_fans', array('date' => $yesterday, 'uniacid' => $_W['uniacid']));
	$yesterday_stat['new'] = intval($yesterday_stat['new']);
	$yesterday_stat['cancel'] = intval($yesterday_stat['cancel']);
	$yesterday_stat['jing_num'] = intval($yesterday_stat['new']) - intval($yesterday_stat['cancel']);
	$yesterday_stat['cumulate'] = intval($yesterday_stat['cumulate']);
	//今日粉丝详情
	$today_stat = pdo_get('stat_fans', array('date' => date('Ymd'), 'uniacid' => $_W['uniacid']));
	$today_stat['new'] = intval($today_stat['new']);
	$today_stat['cancel'] = intval($today_stat['cancel']);
	$today_stat['jing_num'] = $today_stat['new'] - $today_stat['cancel'];
	$today_stat['cumulate'] = intval($today_stat['jing_num']) + $yesterday_stat['cumulate'];
	if($today_stat['cumulate'] < 0) {
		$today_stat['cumulate'] = 0;
	}
	message(error(0, array('yesterday' => $yesterday_stat, 'today' => $today_stat)), '', 'ajax', true);
}