<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Activation_EweiShopV2Page extends MobileLoginPage
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		$iserror = false;
		$card_id = $_GPC['card_id'];
		$encrypt_code = $_GPC['encrypt_code'];
		if (empty($card_id) || empty($encrypt_code)) 
		{
			$iserror = true;
		}
		$encrypt_code = htmlspecialchars_decode($encrypt_code, ENT_QUOTES);
		$result = com_run('wxcard::wxCardCodeDecrypt', $encrypt_code);
		if (is_wxerror($result)) 
		{
			$iserror = true;
		}
		$code = $result['code'];
		if (empty($_W['openid'])) 
		{
			$iserror = true;
		}
		$item = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where uniacid=:uniacid and openid =:openid limit 1 ', array(':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));
		if ($iserror)
		{
			$this->message(array('message' => '激活链接错误!', 'title' => '激活链接错误!', 'buttondisplay' => true), mobileUrl('member'), 'error');
		}
		$arr = array('membercardid' => $card_id, 'membercardcode' => $code, 'membershipnumber' => $code, 'membercardactive' => 0);
		$CardActivation = m('common')->getSysset('memberCardActivation');
//		dd($CardActivation);
//        $CardActivation['realname'] = 1;
//        $CardActivation['mobile'] = 1;
//        $CardActivation['sms_active'] = 0;
//        $CardActivation['openactive']  = 1;
		if (empty($CardActivation['openactive']))
		{
			pdo_update('ewei_shop_member', $arr, array('openid' => $_W['openid'], 'uniacid' => $_W['uniacid']));
			$result = com_run('wxcard::ActivateMembercardbyopenid', $_W['openid']);
			if (is_wxerror($result))
			{
				$this->message(array('message' => '会员卡激活失败!', 'title' => '激活链接错误!', 'buttondisplay' => true), mobileUrl('member'), 'error');
			}
			else
			{
				pdo_update('ewei_shop_member', array('membercardactive' => 1), array('openid' => $_W['openid'], 'uniacid' => $_W['uniacid']));
				$this->sendGift($_W['openid']);
				$this->message(array('message' => '您的会员卡已成功激活!', 'title' => '激活成功!', 'buttondisplay' => true), mobileUrl('member'), 'success');
			}
		}
		if (empty($CardActivation))
		{
			$needrealname = 0;
			$needmobile = 0;
			$needsmscode = 0;
		}
		else
		{
			$needrealname = $CardActivation['realname'];
			$needmobile = $CardActivation['mobile'];
			$needsmscode = $CardActivation['sms_active'];
		}

		include $this->template();
	}
	public function submit() 
	{
		global $_W;
		global $_GPC;
		$iserror = false;
		$card_id = $_GPC['card_id'];
		$encrypt_code = $_GPC['encrypt_code'];
		if (empty($card_id) || empty($encrypt_code)) 
		{
			show_json(0, '激活链接错误!');
		}
		$encrypt_code = htmlspecialchars_decode($encrypt_code, ENT_QUOTES);
		$result = com_run('wxcard::wxCardCodeDecrypt', $encrypt_code);
		if (is_wxerror($result)) 
		{
			show_json(0, '激活链接错误!');
		}
		$code = $result['code'];
		if (empty($_W['openid'])) 
		{
			show_json(0, '激活链接错误!');
		}

        $this_store_id = $_COOKIE[$_W['config']['cookie']['pre'] . 'store_id'];

		$item = pdo_fetch('select * from ' . tablename('ewei_shop_member') . ' where uniacid=:uniacid and openid =:openid limit 1 ', array(':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));
		$arr = array('membercardid' => $card_id, 'membercardcode' => $code, 'membershipnumber' => $code, 'membercardactive' => 0);
		$CardActivation = m('common')->getSysset('memberCardActivation');
		/**同步java数据 start**/
        $merchant_code_info = pdo_fetch('select * from '.tablename('b_users_uniaccount_relationship').' where uni_account_id=:uni_account_id limit 1',[':uni_account_id' => $_W['uniacid']]);
		$url = 'http://47.98.124.157:8822/api/v1/associator/register';
		$data['associatorAddress'] = $data['associatorBirthday'] = $data['associatorIdentityCard'] = '';
		$data['associatorName'] = $data['associatorPhone'] = $data['companyNo'] = $data['erpCardId'] = '';
		$data['erpCardNo'] = $data['sex'] = $data['storeNo'] = $data['tokenUrl'] = '';
        $data['associatorBirthday'] = '1990-01-01';
        $data['storeNo'] = $this_store_id;

        if(isset($merchant_code_info['merchant_code']) && !empty($merchant_code_info['merchant_code']))
        {
            $data['companyNo'] = $merchant_code_info['merchant_code'];
        }

		if(isset($_GPC['mobile']) && !empty($_GPC['mobile']))
        {
            $data['associatorPhone'] = $_GPC['mobile'];
        }

        if(isset($_GPC['realname']) && !empty($_GPC['realname']))
        {
            $data['associatorName'] = $_GPC['realname'];
        }

        if(isset($_GPC['birth']) && !empty($_GPC['birth']))
        {
            $data['associatorBirthday'] = date('Y-m-d',strtotime($_GPC['birth']));
        }

        if(isset($_GPC['sex']) && !empty($_GPC['sex']))
        {
            $data['sex'] = $_GPC['sex'];
        }else{
            $data['sex'] = 3;
        }

        $data['tokenUrl'] = $_W['siteroot'].'member.php';
        $url = 'http://47.98.124.157:8822/api/v1/associator/register';
		$java_info = com('curl')->callHttpPost($url,$data);
        /**同步java数据 end**/

		if (!(empty($CardActivation['openactive']))) 
		{
			if (!(empty($CardActivation['sms_active'])) && !(empty($CardActivation['mobile']))) 
			{
				@session_start();
				$key = '__ewei_shopv2_member_verifycodesession_' . $_W['uniacid'] . '_' . trim($_GPC['mobile']);
				$code = $_SESSION[$key];
				if (empty($code)) 
				{
					show_json(0, '请获取验证码!');
				}
				if (trim($_GPC['sms_code']) != $code) 
				{
					show_json(0, '验证码错误!');
				}
			}
			if (!(empty($CardActivation['realname']))) 
			{
				if (empty($_GPC['realname'])) 
				{
					show_json(0, '真实姓名不能为空!');
				}
				$arr['realname'] = trim($_GPC['realname']);
			}
			if (!(empty($CardActivation['mobile']))) 
			{
				if (empty($_GPC['mobile'])) 
				{
					show_json(0, '电话号码不能为空');
				}
				$arr['mobile'] = trim($_GPC['mobile']);
			}

			if(isset($_GPC['birth']) && !empty($_GPC['birth']))
            {
                $arr['birthyear'] = date('Y' , strtotime($_GPC['birth']));
                $arr['birthmonth'] = date('m' , strtotime($_GPC['birth']));
                $arr['birthday'] = date('d' , strtotime($_GPC['birth']));
            }

            if(isset($java_info['success']) && $java_info['success'] && isset($java_info['code']) && $java_info['code'] == 1
                && isset($java_info['data']['cardId']) && !empty($java_info['data']['cardId']))
            {
                $arr['cardId'] = $java_info['data']['cardId'];
            }

			pdo_update('ewei_shop_member', $arr, array('openid' => $_W['openid'], 'uniacid' => $_W['uniacid']));
			$result = com_run('wxcard::ActivateMembercardbyopenid', $_W['openid']);
			if (is_wxerror($result)) 
			{
				show_json(0, '会员卡激活失败');
			}
			else 
			{
				if (empty($item['membercardactive'])) 
				{
					$this->sendGift($_W['openid']);
				}
				pdo_update('ewei_shop_member', array('membercardactive' => 1), array('openid' => $_W['openid'], 'uniacid' => $_W['uniacid']));

				$this->member($_W['openid'],$_W['uniacid']);
				show_json(1, '您的会员卡已成功激活');
			}
		}
		else 
		{
			show_json(0);
		}
	}
	public function sendGift($openid) 
	{
		$CardActivation = m('common')->getSysset('memberCardActivation');
		$credit1 = intval($CardActivation['credit1']);
		$credit2 = intval($CardActivation['credit2']);
		$couponid = intval($CardActivation['couponid']);
		$levelid = intval($CardActivation['levelid']);
		if (!(empty($credit1))) 
		{
			m('member')->setCredit($openid, 'credit1', $credit1);
		}
		if (!(empty($credit2))) 
		{
			m('member')->setCredit($openid, 'credit2', $credit2);
		}
		if (!(empty($couponid))) 
		{
			$member = m('member')->getMember($openid);
			com('coupon')->poster($member, $couponid, 1, 10);
		}
		if (!(empty($levelid))) 
		{
			$member = m('member')->upgradeLevelByLevelId($openid, $levelid);
		}
	}
	public function verifycode() 
	{
		global $_W;
		global $_GPC;
		@session_start();
		$mobile = trim($_GPC['mobile']);
		if (empty($mobile)) 
		{
			show_json(0, '请输入手机号');
		}
		if (!(empty($_SESSION['verifycodesendtime'])) && (time() < ($_SESSION['verifycodesendtime'] + 60))) 
		{
			show_json(0, '请求频繁请稍后重试');
		}
		$member = pdo_fetch('select id,openid,mobile,pwd,salt from ' . tablename('ewei_shop_member') . ' where mobile=:mobile and openid <>:openid  and mobileverify=1 and uniacid=:uniacid limit 1', array(':mobile' => $mobile, ':openid' => $_W['openid'], ':uniacid' => $_W['uniacid']));
		if (!(empty($member))) 
		{
			show_json(0, '该手机号已经被绑定');
		}
		$CardActivation = m('common')->getSysset('memberCardActivation');
		$sms_id = $CardActivation['sms_id'];
		if (empty($sms_id)) 
		{
			show_json(0, '短信发送失败(NOSMSID)');
		}
		$key = '__ewei_shopv2_member_verifycodesession_' . $_W['uniacid'] . '_' . $mobile;
		$code = random(5, true);
		$shopname = $_W['shopset']['shop']['name'];
		$ret = com('sms')->send($mobile, $sms_id, array('验证码' => $code, '商城名称' => (!(empty($shopname)) ? $shopname : '商城名称')));
		if ($ret['status']) 
		{
			$_SESSION[$key] = $code;
			$_SESSION['verifycodesendtime'] = time();
			show_json(1, '短信发送成功');
		}
		show_json(0, $ret['message']);
	}
	public function success() 
	{
		$this->message(array('message' => '您的会员卡已成功激活!', 'title' => '激活成功!', 'buttondisplay' => true), mobileUrl('member'), 'success');
	}

    private function member($openid = '',$uniacid = 0){

        if(!$openid || !$uniacid){
            return false;
        }

        //扫码关注信息
        $subscribe = pdo_fetch('SELECT * FROM ' .tablename('ewei_subscribe') . ' WHERE uniacid = :uniacid AND scan_openid = :scan_openid', array(':uniacid' => $uniacid,':scan_openid' => $openid));

        if(empty($subscribe)){
            return false;
        }

        //先获取父saler信息
        $saler = pdo_fetch('SELECT * FROM ' .tablename('ewei_shop_saler') . ' WHERE id = :id', array(':id' => $subscribe['scene_id']));

        //查询父member信息
        $pMember = pdo_fetch('SELECT * FROM ' .tablename('ewei_shop_member') . ' WHERE uniacid = :uniacid AND carrier_mobile = :mobile', array(':uniacid' => $uniacid,':mobile' => $saler['mobile']));

        //查询子member信息
        $sMember = pdo_fetch('SELECT * FROM ' .tablename('ewei_shop_member') . ' WHERE uniacid = :uniacid AND openid = :openid', array(':uniacid' => $uniacid,':openid' => $subscribe['scan_openid']));

        if(empty($pMember) || empty($sMember)){
            return false;
        }

        //添加父子关系
        if($sMember['agentid'] != 0){
            return false;
        }

        pdo_update('ewei_shop_member', ['agentid' => $pMember['id'],'isagent' => 1], array('id' => $sMember['id']));
        return true;
    }
}
?>