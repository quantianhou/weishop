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

    /**
     * 核销页面
     */
    public function verify(){
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];

        $dataid = $_GPC['dataid'];

        $paras = array(':uniacid' => $uniacid,':id' => $dataid);

        $sql = 'select cd.*,c.couponname,from_unixtime(cd.usetime) as usetime from ' . tablename('ewei_shop_coupon_data') . ' cd LEFT JOIN '.tablename('ewei_shop_coupon').' c ON cd.couponid=c.id where cd.uniacid = :uniacid and cd.id=:id ORDER BY usetime DESC  ';

        $info = pdo_fetch($sql, $paras);

        include $this->template();
    }

    /**
     * 核销成功
     */
    public function success(){
        global $_W;
        global $_GPC;

        $uniacid = $_W['uniacid'];

        $dataid = $_GPC['dataid'];

        $paras = array(':uniacid' => $uniacid,':id' => $dataid);

        pdo_update('ewei_shop_coupon_data', array('usetime' => time(),'verify_openid'=>$_W['openid']), array('id' => $dataid));

        include $this->template();
    }

    /**
     * 我的推广码
     */
    public function code(){

        global $_W;

        $openid = $_W['openid'];

        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where openid=:openid and uniacid=:uniacid limit 1', array(':openid' => $openid, ':uniacid' => $_W['uniacid']));

        //查询门店信息
        $storeInfo = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $userInfo['storeid'], ':uniacid' => $_W['uniacid']));

        include $this->template();
    }
}