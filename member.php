<?php
define('IN_API', true);
require_once './framework/bootstrap.inc.php';
load()->model('reply');
load()->app('common');
load()->classs('wesession');

global $_W;
global $_GPC;
file_put_contents('./data/logs/user-javacallback-'.date('Ymd').'.log',date('Y-m-d H:i:s').'start===>'."\r\n",FILE_APPEND);
file_put_contents('./data/logs/user-javacallback-'.date('Ymd').'.log',json_encode($_GPC['__input'])."\r\n",FILE_APPEND);
// $_GPC['__input'] = '{"success":true,"code":"200","msg":"\u6ce8\u518c\u6210\u529f","data":{"companyNo":"100003","associatorPhone":"15900000000","cardId":"123"}}';
file_put_contents('./data/logs/fanuser-javacallback-'.date('Ymd').'.log',var_export($_GPC['__input'], true),FILE_APPEND);
file_put_contents('./data/logs/fanuser-javacallback-'.date('Ymd').'.log','-------11111111----------',FILE_APPEND);
//file_put_contents('./data/logs/fanuser-javacallback-'.date('Ymd').'.log',var_export(json_decode($_GPC['__input'], true)),FILE_APPEND);
if (isset($_GPC['__input']) && !empty($_GPC['__input'])) {
    //$info = json_decode($_GPC['__input'], true);
    file_put_contents('./data/logs/fanuser-javacallback-'.date('Ymd').'.log','-------22222222222----------',FILE_APPEND);
    $info = $_GPC['__input'];file_put_contents('./data/logs/fanuser-javacallback-'.date('Ymd').'.log',var_export($info, true),FILE_APPEND);
    file_put_contents('./data/logs/fanuser-javacallback-'.date('Ymd').'.log','-------33333333333----------',FILE_APPEND);
    if (!empty($info) && isset($info['success']) && $info['success']
        && isset($info['code']) && $info['code'] == 200
        && isset($info['data']) && !empty($info['data'])) {
        $phone = $cardid = $companyno = $uniacid = '';

        if (isset($info['data']['associatorPhone']) && !empty($info['data']['associatorPhone'])) {
            $phone = $info['data']['associatorPhone'];
        }

        if (isset($info['data']['cardId']) && !empty($info['data']['cardId'])) {
            $cardid = $info['data']['cardId'];
        }

        if (isset($info['data']['companyNo']) && !empty($info['data']['companyNo'])) {
            $companyno = $info['data']['companyNo'];
        }
        if (!empty($companyno)) {
            $merchant_code_info = pdo_fetch('select * from ' . tablename('b_users_uniaccount_relationship') . ' where merchant_code=:merchant_code limit 1', [':merchant_code' => $companyno]);
            if (!empty($merchant_code_info)) {
                $uniacid = $merchant_code_info['uni_account_id'];
            }
        }

        if (!empty($uniacid) && !empty($phone)) {
            $member_info = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where mobile=:mobile and uniacid=:uniacid limit 1', [':mobile' => $phone, ':uniacid' => $uniacid]);
            if (!empty($member_info) && !empty($cardid)) {
                $arr['cardId'] = $cardid;
                pdo_update('ewei_shop_member', $arr, array('id' => $member_info['id']));
            }
        }


    }
}
file_put_contents('./data/logs/user-javacallback-'.date('Ymd').'.log',date('Y-m-d H:i:s').'<====end'."\r\n",FILE_APPEND);
echo json_encode(['code' => 200]);exit;

