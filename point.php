<?php
define('IN_API', true);
require_once './framework/bootstrap.inc.php';
load()->model('reply');
load()->app('common');
load()->classs('wesession');

file_put_contents('./data/logs/point-javacallback-'.date('Ymd').'.log',date('Y-m-d H:i:s').'start===>'."\r\n",FILE_APPEND);
file_put_contents('./data/logs/point-javacallback-'.date('Ymd').'.log',json_encode($_GPC['__input'])."\r\n",FILE_APPEND);
// $_GPC['__input'] = '{"success":true,"code":"200","msg":"\u6ce8\u518c\u6210\u529f","data":{"companyNo":"100003","cardId":"erp55338","integration":5}}';
if (isset($_GPC['__input']) && !empty($_GPC['__input'])) {
    $info = json_decode($_GPC['__input'], true);
    if (!empty($info) && isset($info['success']) && $info['success']
        && isset($info['code']) && $info['code'] == 200
        && isset($info['data']) && !empty($info['data'])) {
        $cardid = $companyno = $uniacid = '';
        $point = 0 ;

        if (isset($info['data']['integration']) && !empty($info['data']['integration'])) {
            $point = $info['data']['integration'];
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

       if(!empty($uniacid) && !empty($cardid))
       {
           $uinfo = pdo_fetch("SELECT id,uniacid,uid,cardId,credit1 FROM " . tablename('ewei_shop_member') . " WHERE cardId = '{$cardid}' and uniacid = {$uniacid};");

           if(!empty($uinfo))
           {
               $arr = [];
               $update_point = 0 ;
               $info = pdo_fetch('select id,point from '.tablename('ewei_shop_point')."where uid={$uinfo['uid']} and uniacid={$uinfo['uniacid']} order by id desc limit 1");
               if(!empty($info))
               {
                   $update_point = $info['point'];
               }

               $update_point = $point - $update_point;

               $arr['credit1'] = $uinfo['credit1'] + $update_point;

               pdo_update('ewei_shop_member', $arr, array('id' => $uinfo['id']));

               $idata['uid'] = $uinfo['uid'];
               $idata['point'] = $arr['credit1'];
               $idata['uniacid'] = $uinfo['uniacid'];
               $idata['create_time'] = time();
               $idata['update_time'] = time();
               pdo_insert('ewei_shop_point',$idata);
           }
       }
    }
}
file_put_contents('./data/logs/point-javacallback-'.date('Ymd').'.log',date('Y-m-d H:i:s').'<====end'."\r\n",FILE_APPEND);
exit(json_encode(['code' => 200]));


