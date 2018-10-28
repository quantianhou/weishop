<?php
defined('IN_IA') or exit('Access Denied');

class CoreModuleProcessor extends WeModuleProcessor {

    public function respond() {

        $reply_type = $this->reply_type;
        $rule = pdo_fetch('select * from ' . tablename('rule') . ' where id=:id limit 1', array(':id' => $this->rule));
        $replyall = $rule['replyall'];
        $resp = [];
        foreach($reply_type as $v)
        {
            $tmp = [];
            switch($v) {
                case 'basic':
                    $result = $this->basic_respond();
                    if(empty($result))
                    {
                        return $result;
                    }
                    $tmp =  $this->respTextCmlove($result);
                    $resp = array_merge($resp,$tmp);
                    break;
                case 'image':
                    $result = $this->image_respond();
                    if(empty($result))
                    {
                        return $result;
                    }
                    $tmp = $this->respImageCmlove($result);
                    $resp = array_merge($resp,$tmp);
                    break;
                case 'music':
                    $result = $this->music_respond();
                    if(empty($result))
                    {
                        return $result;
                    }
                    foreach($result as $v)
                    {
                        $resp[] = $this->respMusic(array(
                            'Title'	=> $result['title'],
                            'Description' => $result['description'],
                            'MusicUrl' => $result['url'],
                            'HQMusicUrl' => $result['hqurl'],
                        ));
                    }
                    break;
                case 'news':
                    $result = $this->news_respond();
                    if(empty($result))
                    {
                        return $result;
                    }
                    $resp[] = $this->respNews($result);
                    break;
                case 'voice':
                    $result = $this->voice_respond();
                    if(empty($result))
                    {
                        return $result;
                    }
                    $tmp =  $this->respVoiceCmlove($result);
                    $resp = array_merge($resp,$tmp);
                    break;
                case 'video':
                    $result = $this->video_respond();
                    if(empty($result))
                    {
                        return $result;
                    }
                    foreach($result as $v)
                    {
                        $resp[] = $this->respVideo(array(
                            'MedaId' => $result['mediaid'],
                            'Title' => $result['title'],
                            'Description' => $result['description']
                        ));
                    }
                    break;
                case 'wxcard':
                    $result = $this->wxcard_respond();
                    if(empty($result))
                    {
                        return $result;
                    }
                    $resp[] = $this->respWxcardCmlove($result);
                    break;
            }
        }
        if($replyall == 2){
            if(!empty($resp))
            {
                $key = array_rand($resp);
                return $resp[$key];
            }
        }

        return $resp;

    }

    //fanhailong add
    private function wxcard_respond() {

        global $_W;
        $set = pdo_fetch('select sets from ' . tablename('ewei_shop_sysset') . ' WHERE uniacid = '.$_W['uniacid'].' order by id asc limit 1');
        $sets = iunserializer($set['sets']);
        if (is_array($sets) && is_array($sets['membercard']) && !empty($sets['membercard']['card_id'])) {
            //$card_data = array();
            //$card_data['touser'] = $_W['openid'];
            //$card_data['msgtype'] = 'wxcard';
            //$card_data['wxcard'] = array("card_id"=>$sets['membercard']['card_id']);
            //$account_api = WeAccount::create($_W['acid']);
            //$result = $account_api->sendCustomNotice($card_data);
            return $sets['membercard']['card_id'];
        }
        return false;
    }

    private function basic_respond() {
        $sql = "SELECT * FROM " . tablename('basic_reply') . " WHERE `rid` IN ({$this->rule})";
        $reply = pdo_fetchall($sql);
        if (empty($reply)) {
            return false;
        }
        $contents = [];
        foreach($reply as $k => $v)
        {
            $tmp = '';
            $tmp = htmlspecialchars_decode($v['content']);
            $tmp = str_replace(array('<br>', '&nbsp;'), array("\n", ' '), $v['content']);
            $tmp = strip_tags($v['content'], '<a>');
            $contents[] = $tmp;
        }
        return $contents;
    }
    private function image_respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT `mediaid` FROM " . tablename('images_reply') . " WHERE `rid`=:rid";
        $mediaid = pdo_fetchall($sql, array(':rid' => $rid));
        if (empty($mediaid)) {
            return false;
        }
        return $mediaid;
    }
    private function music_respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('music_reply') . " WHERE `rid`=:rid";
        $item = pdo_fetchall($sql, array(':rid' => $rid));
        if (empty($item)) {
            return false;
        }
        return $item;
    }
    private function news_respond() {
        global $_W;
        load()->model('material');
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('news_reply') . " WHERE rid = :id AND parent_id = -1 ORDER BY displayorder DESC, id ASC LIMIT 8";
        $commends = pdo_fetchall($sql, array(':id' => $rid));
        if (empty($commends)) {
            $sql = "SELECT * FROM " . tablename('news_reply') . " WHERE rid = :id AND parent_id = 0 ORDER BY RAND()";
            $main = pdo_fetch($sql, array(':id' => $rid));
            if(empty($main['id'])) {
                return false;
            }
            $sql = "SELECT * FROM " . tablename('news_reply') . " WHERE id = :id OR parent_id = :parent_id ORDER BY parent_id ASC, displayorder DESC, id ASC LIMIT 8";
            $commends = pdo_fetchall($sql, array(':id'=>$main['id'], ':parent_id'=>$main['id']));
        }
        if(empty($commends)) {
            return false;
        }
        $news = array();
        foreach($commends as $commend) {
            $row = array();
            if (!empty($commend['media_id']) && intval($commend['media_id']) == 0) {
                $news = material_build_reply($commend['media_id']);
                break;
            } else {
                $row['title'] = $commend['title'];
                $row['description'] = $commend['description'];
                !empty($commend['thumb']) && $row['picurl'] = tomedia($commend['thumb']);
                $row['url'] = empty($commend['url']) ? $this->createMobileUrl('detail', array('id' => $commend['id'])) : $commend['url'];
                $news[] = $row;
            }
        }
        return $news;
    }
    private function voice_respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT `mediaid` FROM " . tablename('voice_reply') . " WHERE `rid`=:rid";
        $mediaid = pdo_fetchall($sql, array(':rid' => $rid));
        if (empty($mediaid)) {
            return false;
        }
        return $mediaid;
    }
    private function video_respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('video_reply') . " WHERE `rid`=:rid";
        $item = pdo_fetchall($sql, array(':rid' => $rid));
        if (empty($item)) {
            return false;
        }
        return $item;
    }
}
