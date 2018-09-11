<?php
defined('IN_IA') or exit('Access Denied');

class CoreModuleProcessor extends WeModuleProcessor {

    public function respond() {

        $reply_type = $this->reply_type;
        $key = array_rand($reply_type);
        $type = $reply_type[$key];
        switch($type) {
            case 'basic':
                $result = $this->basic_respond();
                return $this->respText($result);
                break;
            case 'image':
                $result = $this->image_respond();
                return $this->respImage($result);
                break;
            case 'music':
                $result = $this->music_respond();
                return $this->respMusic(array(
                    'Title'	=> $result['title'],
                    'Description' => $result['description'],
                    'MusicUrl' => $result['url'],
                    'HQMusicUrl' => $result['hqurl'],
                ));
                break;
            case 'news':
                $result = $this->news_respond();
                return $this->respNews($result);
                break;
            case 'voice':
                $result = $this->voice_respond();
                return $this->respVoice($result);
                break;
            case 'video':
                $result = $this->video_respond();
                return $this->respVideo(array(
                    'MediaId' => $result['mediaid'],
                    'Title' => $result['title'],
                    'Description' => $result['description']
                ));
                break;
            case 'wxcard':
                $result = $this->wxcard_respond();
                return $this->respWxcard($result);
                break;
        }
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
        $sql = "SELECT * FROM " . tablename('basic_reply') . " WHERE `rid` IN ({$this->rule})  ORDER BY RAND() LIMIT 1";
        $reply = pdo_fetch($sql);
        if (empty($reply)) {
            return false;
        }
        $reply['content'] = htmlspecialchars_decode($reply['content']);
        $reply['content'] = str_replace(array('<br>', '&nbsp;'), array("\n", ' '), $reply['content']);
        $reply['content'] = strip_tags($reply['content'], '<a>');
        return $reply['content'];
    }
    private function image_respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT `mediaid` FROM " . tablename('images_reply') . " WHERE `rid`=:rid";
        $mediaid = pdo_fetchcolumn($sql, array(':rid' => $rid));
        if (empty($mediaid)) {
            return false;
        }
        return $mediaid;
    }
    private function music_respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('music_reply') . " WHERE `rid`=:rid ORDER BY RAND()";
        $item = pdo_fetch($sql, array(':rid' => $rid));
        if (empty($item['id'])) {
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
        $mediaid = pdo_fetchcolumn($sql, array(':rid' => $rid));
        if (empty($mediaid)) {
            return false;
        }
        return $mediaid;
    }
    private function video_respond() {
        global $_W;
        $rid = $this->rule;
        $sql = "SELECT * FROM " . tablename('video_reply') . " WHERE `rid`=:rid";
        $item = pdo_fetch($sql, array(':rid' => $rid));
        if (empty($item)) {
            return false;
        }
        return $item;
    }
}
