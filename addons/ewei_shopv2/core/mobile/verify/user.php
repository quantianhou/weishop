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
//        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where openid=:openid limit 1', array(':openid' => $openid));

        //信息不全去ewei_shop_member寻找信息
//        if(empty($userInfo) || (!empty($userInfo) && $userInfo['mobile'] <= 0)){
//            $menberInfo = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where openid=:openid and uniacid=:uniacid limit 1', array(':openid' => $openid, ':uniacid' => $_W['uniacid']));
//            //查询到对应信息
//            if(!empty($menberInfo)){
//                if(empty($userInfo)){
//                    //写入
//                    $log = array(
//                        'uniacid' => $_W['uniacid'],
//                        'openid' => $_W['openid'],
//                        'mobile'=>$menberInfo['carrier_mobile'],
//                        'salername' => $_W['fans']['nickname']
//                    );
//                    pdo_insert('ewei_shop_saler', $log);
//                }else{
//                    //更新
//                    pdo_update('ewei_shop_saler', array('mobile' => $menberInfo['carrier_mobile']), array('id' => $userInfo['id']));
//
//                }
//            }
//        }

        //重新获取
        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where d_openid=:openid limit 1', array(':openid' => $openid));

        //获取卡种id
        $storesql = 'SELECT * FROM ' . tablename('ewei_shop_sysset') . ' WHERE uniacid = ' . intval($_W['uniacid']);
        $store = pdo_fetch($storesql);
        $storeInfo = iunserializer($store['sets']);


        //通过手机号判断是否需要同步storeid
        if($userInfo['storeid'] == 0 && $userInfo['mobile'] > 0){
            $res = json_decode($this->http_get_data('http://47.98.124.157:8822/api/v1/employee/query_employee?employeePhone='.$userInfo['mobile']),true);
            $thisitem = current($res['data']['items']);
            if(isset($thisitem['storeNo'])){
                //更新storeid 获取门店信息
                $store = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where erp_shop_code=:erp_shop_code limit 1', array(':erp_shop_code' => $thisitem['storeNo']));
                if(!empty($store) && isset($store['id'])){
                    //更新
                    pdo_update('ewei_shop_saler', array('storeid' => $store['id'],'uniacid'=>$store['uniacid'],'status' => 1), array('id' => $userInfo['id']));
                }
            }
        }

        //重新获取
        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where d_openid=:openid limit 1', array(':openid' => $openid));

        include $this->template();

    }

    /**
     * @param $url
     * @return string
     */

    public function http_get_data($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();
        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return $return_content;
    }

    /**
     * 用户信息
     */
    public function info(){

        global $_W;
        global $_GPC;

        $openid = $_W['openid'];
        $uniacid = $_W['uniacid'];

        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where d_openid=:openid limit 1', array(':openid' => $openid));

        //查询门店信息
        $storeInfo = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id limit 1', array(':id' => $userInfo['storeid']));

        include $this->template();
    }

    /**
     * 核销页面
     */
    public function verify(){
        global $_W;
        global $_GPC;

        $openid = $_W['openid'];

        $uniacid = $_W['uniacid'];

        $dataid = $_GPC['dataid'];

        $paras = array(':uniacid' => $uniacid,':id' => $dataid);

        $sql = 'select cd.*,c.couponname,c.storeid, from_unixtime(cd.usetime) as usetime,cd.usetime as usedtime,c.giftname from' . tablename('ewei_shop_coupon_data') . ' cd LEFT JOIN '.tablename('ewei_shop_coupon').' c ON cd.couponid=c.id where cd.id=:id ORDER BY usetime DESC  ';

        $info = pdo_fetch($sql, $paras);

        //查询当前用户是否是核销员 并且有权限
        $userInfo = pdo_fetch('select storeid from' . tablename('ewei_shop_saler') . ' where d_openid=:openid', [':openid' => $openid]);
        //判断是否有权限
        $is_ok = false;
        if(!empty($userInfo) && !empty($info)){
            $stores = explode(',',$info['storeid']);
            if(in_array($userInfo['storeid'],$stores)){
                $is_ok = true;
            }
        }

        $is_use  = false;
        if($info['usedtime'] > 0){
            $is_use = true;
        }

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
        $coupon_data = pdo_fetch('select * from '.tablename('ewei_shop_coupon_data').' where id=:id limit 1',[':id'=>$dataid]);
        pdo_update('ewei_shop_coupon_data', array('usetime' => time(),'verify_openid'=>$_W['openid']), array('id' => $dataid));

        //优惠券信息
        if(!empty($coupon_data))
        {
            $store_name = '门店';
            $saler = pdo_fetch('select storeid from '.tablename('ewei_shop_saler') .' where d_openid=:openid limit 1',[':openid' => $_W['openid']]);
            if(!empty($saler))
            {
                $storename = pdo_fetch('select storename from '.tablename('ewei_shop_store') .' where id=:storeid limit 1',[':storeid' => $saler['storeid']]);
                if(!empty($storename))
                {
                    $store_name = $storename['storename'];
                }
            }
            $coupon_info = pdo_fetch('select * from '.tablename('ewei_shop_coupon').' where id=:id limit 1',[':id'=>$coupon_data['couponid']]);
            $postdata = [
                'first' => ['value' => '您的优惠券已经核销成功，期待再次光临！','color' => '#173177'],
                'keyword1' => ['value' => $coupon_info['couponname'],'color' => '#173177'],
                'keyword2' => ['value' => $coupon_data['qrcode'],'color' => '#173177'],
                'keyword3' => ['value' => date("Y-m-d H:i:s"),'color' => '#173177'],
                'keyword4' => ['value' => $store_name,'color' => '#173177'],
                'remark' => ['value' => '感谢您的使用','color' => '#173177']
            ];
            $data['touser'] = $coupon_data['openid'];
            $data['template_id'] = '0-JpjcpxM6VnqTpCU-VhobOQOFf9TA3Cp23VZVJilhE';
            $data['data'] = $postdata;
            $data = json_encode($data);
            $token = $token = WeAccount::token();
            $post_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$token}";
            $response = ihttp_request($post_url, $data);
            if(is_error($response)) {
                return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
            }
        }
        include $this->template();
    }

    /**
     * 我的推广码
     */
    public function code(){

        global $_W;

        $openid = $_W['openid'];

        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where d_openid=:openid limit 1', array(':openid' => $openid));

        //查询member中是否有手机号与
        $memberInfo = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where uniacid=:uniacid and carrier_mobile=:carrier_mobile limit 1', array(':carrier_mobile' => $userInfo['mobile'], ':uniacid' => $userInfo['uniacid']));

        if(empty($memberInfo)){
            exit('请确保您已成为您所在商城的会员并已激活会员卡');
        }
        //查询门店信息
        $storeInfo = pdo_fetch('select * from ' . tablename('ewei_shop_store') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $userInfo['storeid'], ':uniacid' => $_W['uniacid']));

        //获取推广二维码
        $dirname = '../addons/ewei_shopv2/data/qrcode/' . $_W['uniacid'] . '/' . 'tuiguang/';

        $url = $dirname . '/' . $userInfo['id'] . '.png';

        if (!file_exists($url)){
            $token = WeAccount::token();
            $customMessageSendUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $token;
            $postJosnData = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$userInfo['id'].'}}}';
            $ch = curl_init($customMessageSendUrl);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postJosnData);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $data = curl_exec($ch);
            $ticket = json_decode($data, true);
            $qr_url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket['ticket'];
            load()->func('file');
            mkdirs($dirname);
            $fileContents = file_get_contents($qr_url);
            file_put_contents($dirname . '/' . $userInfo['id'] . '.png', $fileContents);
        }



        include $this->template();
    }

    /**
     * 发送电话
     */
    public function getcode(){

        global $_W;
        global $_GPC;

        $mobile = $_GPC['mobile'];

        $code = rand(1000,9999);

        if(!preg_match('/\d{11}/',$mobile)){
            show_json(0,'手机号错误');
        }

        if(time() - $_SESSION['code_time'] <= 60){
            show_json(0,'一分钟只能发送一条');
        }

        //判断是否该手机号已经注册
        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where mobile=:mobile limit 1', array(':mobile' => $mobile));

        if(!empty($userInfo)){
            show_json(10,'该手机号已注册');
        }

        $response = sendSms($mobile, 'SMS_145235565', array('code'=>$code));
        if($response->Code =='OK'){
            //成功
            $_SESSION['code'] = $code;
            $_SESSION['code_time'] = time();

            show_json(1,'发送成功');
        }else{
            //发送失败，给前端报错
            show_json(0,'发送失败');
        }
    }

    /**
     * 绑定手机号
     */
    public function bind(){

        global $_W;
        global $_GPC;

        $mobile = $_GPC['mobile'];
        $code = $_GPC['code'];

        if(!preg_match('/\d{11}/',$mobile)){
            show_json(0,'手机号错误');
        }

        //判断验证码
        if(!$_SESSION['code'] || $_SESSION['code'] != $code){
            show_json(0,'验证码错误');
        }

        //判断是否该手机号已经注册
        $userInfo = pdo_fetch('select * from ' . tablename('ewei_shop_saler') . ' where mobile=:mobile limit 1', array(':mobile' => $mobile));

        if(!empty($userInfo)){
            show_json(10,'该手机号已注册');
        }

        //进行绑定
        pdo_update('ewei_shop_saler', array('mobile' => $mobile), array('d_openid' => $_W['openid']));

        show_json(1);
    }
}