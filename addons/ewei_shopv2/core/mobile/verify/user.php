<?php
if (!(defined('IN_IA')))
{
    exit('Access Denied');
}
class User_EweiShopV2Page extends MobilePage
{
    public function main(){

        global $_W;
        global $_GPC;

        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];

        //判断当期啊openid 有没有绑定过核销员的身份
        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where openid=:openid and uniacid=:uniacid limit 1', array(':openid' => $openid, ':uniacid' => $_W['uniacid']));

        include $this->template();

    }

    /**
     * 用户信息
     */
    public function info(){

        global $_W;
        global $_GPC;

        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];

        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where openid=:openid and uniacid=:uniacid limit 1', array(':openid' => $openid, ':uniacid' => $_W['uniacid']));

        //查询门店信息
        $storeInfo = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $userInfo['storeid'], ':uniacid' => $_W['uniacid']));

        include $this->template();
    }
}