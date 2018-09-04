<?php
defined('IN_IA') or exit('Access Denied');

load()->model('account');
load()->model('wxapp');

$dos = array('get_setting', 'display');
$do = in_array($do, $dos) ? $do : 'display';
uni_user_permission_check('wxapp_payment', true, 'wxapp');
$_W['page']['title'] = '支付参数';

$pay_setting = wxapp_payment_param();

if ($do == 'get_setting') {
	iajax(0, $pay_setting, '');
}

if ($do = 'display') {
	
}
template('wxapp/payment');