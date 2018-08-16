<?php
defined('IN_IA') or exit('Access Denied');

load()->model('account');
load()->model('mc');

if(!empty($_W['uniacid'])) {
	if($_W['account']['level'] >= ACCOUNT_TYPE_OFFCIAL_AUTH) {
		$fans = pdo_getall('mc_mapping_fans', array('uniacid' => $_W['uniacid'], 'follow' => 1), array('fanid', 'openid', 'acid', 'uid', 'uniacid'), 'fanid', 'fanid DESC', '10');
		if(!empty($fans)) {
			foreach($fans as $row) {
				mc_init_fans_info($row['openid']);
			}
		}
	}
}