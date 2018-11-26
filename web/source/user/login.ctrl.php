<?php
defined('IN_IA') or exit('Access Denied');
define('IN_GW', true);
if (checksubmit() || $_W['isajax']) {
	_login($_GPC['referer']);
}
$setting = $_W['setting'];
template('user/login');

function _login($forward = '') {
	global $_GPC, $_W;
	load()->model('user');
	$member = array();
	$username = trim($_GPC['username']);
	pdo_query('DELETE FROM'.tablename('users_failed_login'). ' WHERE lastupdate < :timestamp', array(':timestamp' => TIMESTAMP-300));
	$failed = pdo_get('users_failed_login', array('username' => $username, 'ip' => CLIENT_IP));
	if ($failed['count'] >= 5) {
		itoast('输入密码错误次数超过5次，请在5分钟后再登录',referer(), 'info');
	}
	if (!empty($_W['setting']['copyright']['verifycode'])) {
		$verify = trim($_GPC['verify']);
		if (empty($verify)) {
			itoast('请输入验证码', '', '');
		}
		$result = checkcaptcha($verify);
		if (empty($result)) {
			itoast('输入验证码错误', '', '');
		}
	}
	if (empty($username)) {
		itoast('请输入要登录的用户名', '', '');
	}
	$member['username'] = $username;
	$member['password'] = $_GPC['password'];
	if (empty($member['password'])) {
		itoast('请输入密码', '', '');
	}
	$record = user_single($member);
	if (!empty($record)) {
		if ($record['status'] == 1) {
			itoast('您的账号正在审核或是已经被系统禁止，请联系网站管理员解决！', '', '');
		}
		$founders = explode(',', $_W['config']['setting']['founder']);
		$_W['isfounder'] = in_array($record['uid'], $founders);
		if (empty($_W['isfounder'])) {
			if (!empty($record['endtime']) && $record['endtime'] < TIMESTAMP) {
				itoast('您的账号有效期限已过，请联系网站管理员解决！', '', '');
			}
		}
		if (!empty($_W['siteclose']) && empty($_W['isfounder'])) {
			itoast('站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason'], '', '');
		}

		if(empty($_W['isfounder'])){//如果不是超级管理员，就要验证账号状态
            $merchant_status = pdo_fetch('SELECT status FROM ' . tablename('a_merchant') . " WHERE id = {$record['a_merchant_id']}");
            if($merchant_status['status']!=7){
                itoast('您的账号已被冻结，请联系网站管理员解决！', '', '');
            }
        }

		$cookie = array();
		$cookie['uid'] = $record['uid'];
		$cookie['lastvisit'] = $record['lastvisit'];
		$cookie['lastip'] = $record['lastip'];
		$cookie['hash'] = md5($record['password'] . $record['salt']);
		$session = base64_encode(json_encode($cookie));
		isetcookie('__session', $session, !empty($_GPC['rember']) ? 7 * 86400 : 0, true);
		$status = array();
		$status['uid'] = $record['uid'];
		$status['lastvisit'] = TIMESTAMP;
		$status['lastip'] = CLIENT_IP;
		user_update($status);
		if ($record['type'] == ACCOUNT_OPERATE_CLERK) {
			$role = uni_permission($record['uid'], $record['uniacid']);
			isetcookie('__uniacid', $record['uniacid'], 7 * 86400);
			isetcookie('__uid', $record['uid'], 7 * 86400);
			
			if ($_W['role'] == 'clerk' || $role == 'clerk') {
				itoast('登陆成功', url('activity/desk', array('uniacid' => $record['uniacid'])), 'success');
			}
		}
		if (empty($forward)) {
			$forward = $_GPC['forward'];
		}
		//范海龙add

		$_GPC['__uniacid']= 17;
		if (empty($forward)) {
			if (!empty($_GPC['__uniacid'])) {
				$forward = './index.php?c=home&a=welcome';
			} else {
				$forward = './index.php?c=account&a=display';
			}
		}
		if ($record['uid'] != $_GPC['__uid']) {
			isetcookie('__uniacid', '', -7 * 86400);
			isetcookie('__uid', '', -7 * 86400);
		}
		pdo_delete('users_failed_login', array('id' => $failed['id']));
		itoast("欢迎回来，{$record['username']}。", $forward, 'success');
	} else {
		if (empty($failed)) {
			pdo_insert('users_failed_login', array('ip' => CLIENT_IP, 'username' => $username, 'count' => '1', 'lastupdate' => TIMESTAMP));
		} else {
			pdo_update('users_failed_login', array('count' => $failed['count'] + 1, 'lastupdate' => TIMESTAMP), array('id' => $failed['id']));
		}
		itoast('登录失败，请检查您输入的用户名和密码！', '', '');
	}
}