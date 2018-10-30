<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Detail_EweiShopV2Page extends MobilePage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		$openid = $_W['openid'];
		$id = intval($_GPC['id']);
		$coupon = pdo_fetch('select * from ' . tablename('ewei_shop_coupon') . ' where id=:id and uniacid=:uniacid  limit 1', array(':id' => $id, ':uniacid' => $_W['uniacid']));
        //fanhailong add 读取当前公众号下的店铺数量
        $shop_count_param = array('uniacid' => $_W['uniacid']);
        $sshop_count_sql = "SELECT count(1) as count FROM ". tablename('ewei_shop_store'). "  WHERE uniacid = :uniacid";
        $shop_count = pdo_fetch($sshop_count_sql, $shop_count_param);
		if (empty($coupon)) 
		{
			header('location: ' . mobileUrl('sale/coupon'));
			exit();
		}
		$coupon = com('coupon')->setCoupon($coupon, time());
		include $this->template();
	}
}
?>