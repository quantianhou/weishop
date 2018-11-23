<?php
define('IN_API', true);
require_once './framework/bootstrap.inc.php';
load()->model('reply');
load()->app('common');
load()->classs('wesession');

$res = file_put_contents('./data/logs/point-'.date('Ymd').'.log',date('Y-m-d H:i:s').'start===>'."\r\n",FILE_APPEND);
$company = pdo_fetchall("select merchant_code,uni_account_id from ".tablename("b_users_uniaccount_relationship"));

$company_list = [];

if(!empty($company))
{
    foreach($company as $k => $v)
    {
        $company_list[$v['uni_account_id']] = $v['merchant_code'];
    }
}

$last_id = 0 ;
$run = true;
if(!empty($company_list))
{
    do{
        $member = pdo_fetchall("SELECT id,uniacid,openid,uid,cardId,credit1 FROM " . tablename('ewei_shop_member') . " WHERE cardId != '0' and cardId != '' and cardId is not null and id > {$last_id} order by id asc limit 10;");
        if(!empty($member))
        {
            $curl = new Curl();
            foreach($member as $k => $v){
                if(!empty($v['uid']))
                {
                    $update_point = $credit1 = 0;
                    $info = pdo_fetch('select id,point from '.tablename('ewei_shop_point')."where uid={$v['uid']} and uniacid={$v['uniacid']} order by id desc limit 1");
                    if(!empty($info))
                    {
                        $update_point = $info['point'];
                    }

                    $fans_info = pdo_fetch('select uid from '.tablename('mc_mapping_fans').' where openid="'.$v['openid'].'" and uniacid="'.$v['uniacid'].'"');
                    if(!empty($fans_info))
                    {
                        $mc_info = pdo_fetch('select uid,credit1 from '.tablename('mc_members').' where uid="'.$fans_info['uid'].'"');
                        if(!empty($mc_info)){
                            $credit1 = $mc_info['credit1'];
                        }

                        $syn_point = $credit1 - $update_point;

                        $data['cardId'] = $v['cardId'];
                        $data['companyNo'] = $company_list[$v['uniacid']];
                        $data['integration'] = $syn_point;
                        $data['tokenUrl'] =  $_W['siteroot'] .'/point.php';
                        $url = 'http://47.98.124.157:8822/api/v1/associator/update_integration';
                        file_put_contents('./data/logs/point-'.date('Ymd').'.log','request-data:'.json_encode($data)."\r\n",FILE_APPEND);
                        $java_info = $curl->callHttpPost($url,$data);
                        file_put_contents('./data/logs/point-'.date('Ymd').'.log','respone-data:'.json_encode($java_info)."\r\n",FILE_APPEND);
                    }
                }
                $last_id = $v['id'];
            }
        }else{
            $run = false;
        }

    }while($run);
}

file_put_contents('./data/logs/point-'.date('Ymd').'.log',date('Y-m-d H:i:s').'<====end'."\r\n",FILE_APPEND);


class Curl
{
    private function callHttpCommon($url, $type = 'GET', $useragent = '', $params = null, $header = '', $encoding = '', $referer = '', $cookie = '') {
        $ch = curl_init ();
        $timeout = 18;
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt ( $ch, CURLOPT_TIMEOUT, $timeout );
        if ('' != $useragent) {
            curl_setopt ( $ch, CURLOPT_USERAGENT, $useragent );
        }
        if ('' != $encoding) {
            curl_setopt ( $ch, CURLOPT_ENCODING, $encoding );
        }
        if ('' != $header) {
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header );
        }
        if (null != $params) {
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $params );
        }
        if ('' != $referer) {
            curl_setopt ( $ch, CURLOPT_REFERER, $referer );
        }
        if ('' != $cookie) {
            curl_setopt ( $ch, CURLOPT_COOKIE, $cookie );
        }
        switch ($type) {
            case "GET" :
                curl_setopt ( $ch, CURLOPT_HTTPGET, true );
                break;
            case "POST" :
                curl_setopt ( $ch, CURLOPT_POST, true );
                break;
            case "PUT" :
                curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "PUT" );
                break;
            case "DELETE" :
                curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, "DELETE" );
                break;
        }
        $result = curl_exec ( $ch );
        $curl_errno = curl_errno ( $ch );
        $curlinfo = curl_getinfo ( $ch );
        $requestTime = $curlinfo['total_time'] * 1000;
        curl_close ( $ch );
        if ($curl_errno > 0) {
            return false;
        }
        $ret = json_decode ( $result, TRUE );
//        @writeLog ( '.log', 'API', 'DATE:"' . date ( 'Y-m-d H:i:s' ) . '" URL:"' . $url . '" DATA:"' . $params . '" TIME:"' . $requestTime);
        return $result;
    }

    function callHttpGET($url,$params = null)
    {
        $get_url = '';
        foreach ($params as $key=>$value){
            if(!empty($value)){
                $get_url .= $key.'='.urlencode($value).'&';
            }else{
                $get_url .= $key.'='.$value.'&';
            }
        }
        $get_url = rtrim($get_url, '&');
        $url = $url . '?' .$get_url;
        $result = $this->callHttpCommon($url,'get');
        $result = json_decode($result,true);
        return $result;
    }

    function callHttpPost($url,$params = null)
    {
        $header = array("Content-Type: application/json; charset=utf-8;");
//        $post_url = '';
//        foreach ($params as $key=>$value){
//            if(!empty($value)){
//                $post_url .= $key.'='.urlencode($value).'&';
//            }else{
//                $post_url .= $key.'='.$value.'&';
//            }
//        }
//        $post_url = rtrim($post_url, '&');
        $result = $this->callHttpCommon($url,'POST','',json_encode($params),$header,'gzip');
        $result = json_decode($result,true);
        return $result;
    }

}




