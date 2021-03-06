<?php
if (!(defined('IN_IA')))
{
    exit('Access Denied');
}
class Showstorelist_EweiShopV2Page extends MobilePage
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

        //获取商户门店
        $prefix_cookie = $_W['config']['cookie']['pre'];
        $storeids = trim($coupon['storeid'], ',');

        if(isset($coupon['coupontype']) && $coupon['coupontype'] == 2)
        {
            $shoplist = pdo_fetchall('select *  from ' . tablename('ewei_shop_store') . ' og  where og.uniacid=:uniacid and og.id IN('.$storeids.') and type in(2,3)', array(':uniacid' => $_W['uniacid']));
        }else{
            $shoplist = pdo_fetchall('select *  from ' . tablename('ewei_shop_store') . ' og  where og.uniacid=:uniacid and og.id IN('.$storeids.')', array(':uniacid' => $_W['uniacid']));
        }
        //fanhailong add, 门店logo如果不包含resource.ymkchen.com，则加上图片显示相对路径/attachment/
        foreach($shoplist as $key=>$val){
            if(strpos($val['logo'], 'resource.ymkchen.com') !== false){
            }else{
                //如果不包含resource.ymkchen.com，则是b端人人商城后台上传的logo，用相对路径
                $shoplist[$key]['logo'] = "/attachment/{$val['logo']}";
            }
        }

        //print_r($shoplist);exit;
        include $this->template();
    }
}
?>