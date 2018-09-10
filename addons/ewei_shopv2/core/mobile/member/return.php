<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Return_EweiShopV2Page extends MobileLoginPage
{
	public function main() 
	{
		global $_W;
		global $_GPC;


//        pdo_update('ewei_shop_member', array('membercardactive' => 1), array('openid' => $_W['openid'], 'uniacid' => $_W['uniacid']));
	}
}
?>