<?php
if (!defined('IN_IA')) {
    exit('Access Denied');
}

class Curl_EweiShopV2ComModel extends ComModel
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

?>
