<?php
load()->model('user');
load()->func('tpl');
$_W['token'] = token();
$session = json_decode(base64_decode($_GPC['__session']), true);
if(is_array($session)) {
	$user = user_single(array('uid'=>$session['uid']));
	if(is_array($user) && $session['hash'] == md5($user['password'] . $user['salt'])) {
		$_W['uid'] = $user['uid'];
		$_W['username'] = $user['username'];
		$user['currentvisit'] = $user['lastvisit'];
		$user['currentip'] = $user['lastip'];
		$user['lastvisit'] = $session['lastvisit'];
		$user['lastip'] = $session['lastip'];
		$_W['user'] = $user;
		$founders = explode(',', $_W['config']['setting']['founder']);
        $_W['isfounder'] = in_array($_W['uid'], $founders);
//		$_W['isfounder'] = 1;
		unset($founders);
	} else {
		isetcookie('__session', false, -100);
	}
	unset($user);
}
unset($session);

if(!empty($_GPC['__uniacid'])) {
	//在这里写死从数据库读取当前账户唯一的一条公众号
	$_W['uniacid'] = intval($_GPC['__uniacid']);
} else {
	$_W['uniacid'] = uni_account_last_switch();
}
$_W['uniacid'] = getUniacidByUser();//默认主页的公众号为当前账号下的公众号；通过这句防止住当前账号看到其他账号下的公众号，微擎的机制是每个管理员都能查看其他的公众号，我们的需求不允许
if (!empty($_W['uniacid'])) {
	$_W['uniaccount'] = $_W['account'] = uni_fetch($_W['uniacid']);
	$_W['acid'] = $_W['account']['acid'];
	$_W['weid'] = $_W['uniacid'];
}
if(!empty($_W['uid'])) {
	$_W['role'] = uni_permission($_W['uid']);
}
$_W['template'] = 'default';
if(!empty($_W['setting']['basic']['template'])) {
	$_W['template'] = $_W['setting']['basic']['template'];
}
load()->func('compat.biz');