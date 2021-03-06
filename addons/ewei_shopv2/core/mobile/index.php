<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Index_EweiShopV2Page extends MobilePage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		$_SESSION['newstoreid'] = 0;

		$urlstoreid = $_GPC['storeid'];
		if($urlstoreid){
            $urlstoreid && isetcookie('store_id', $urlstoreid, 7 * 86400);
        }

		//查看是否有cookie
		$this_store_id = $urlstoreid ?: $_COOKIE[$_W['config']['cookie']['pre'] . 'store_id'];
		$lat = $_COOKIE[$_W['config']['cookie']['pre'] . 'lat'];
		$lng = $_COOKIE[$_W['config']['cookie']['pre'] . 'lng'];
        $order  = '';

        if($lat && $lng){
            $order = "(ABS(`lat`-$lat) + ABS(`lng`-$lng)) ASC";
        }else{
            $order = 'id DESC';
        }
		if(!$this_store_id){
            //获取第一个门店
            $shopInfo = pdo_fetch('select *  from ' . tablename('ewei_shop_store') . ' og  where status=1 and og.uniacid=:uniacid ORDER BY '.$order, array(':uniacid' => $_W['uniacid']));
            $shopInfo && isetcookie('store_id', $shopInfo['id'], 7 * 86400);
            $this_store_id = $shopInfo['id'];
        }

        //获取门店信息
        $shopInfo || $shopInfo = pdo_fetch('select *  from ' . tablename('ewei_shop_store') . ' og  where og.id=:id ', array(':id' => $this_store_id));
        //如果当前门店信息不隶属于当前商户 重新获取门店信息
        if($shopInfo['uniacid'] != $_W['uniacid'] || $shopInfo['status'] != 1){
            isetcookie('store_id', 0, 7 * 86400);
            exit('<script language="JavaScript">document.location.reload()</script>');
        }
        //fanhailong add, 门店logo如果不包含resource.ymkchen.com，则加上图片显示相对路径/attachment/
        if(strpos($shopInfo['logo'], 'resource.ymkchen.com') !== false){
        }else{
            //如果不包含resource.ymkchen.com，则是b端人人商城后台上传的logo，用相对路径
            $shopInfo['logo'] = "/attachment/{$shopInfo['logo']}";
        }

        $this->diypage('home');
		$trade = m('common')->getSysset('trade');
		if (empty($trade['shop_strengthen'])) 
		{
			$order = pdo_fetch('select id,price  from ' . tablename('ewei_shop_order') . ' where uniacid=:uniacid and status = 0 and openid=:openid order by createtime desc limit 1', array(':uniacid' => $_W['uniacid'], ':openid' => $_W['openid']));
			if (!(empty($order))) 
			{
				$goods = pdo_fetchall('select g.*,og.total as totals  from ' . tablename('ewei_shop_order_goods') . ' og inner join ' . tablename('ewei_shop_goods') . ' g on og.goodsid = g.id   where og.uniacid=:uniacid    and og.orderid=:orderid  limit 3', array(':uniacid' => $_W['uniacid'], ':orderid' => $order['id']));
				$goodstotal = pdo_fetchcolumn('select COUNT(*)  from ' . tablename('ewei_shop_order_goods') . ' og inner join ' . tablename('ewei_shop_goods') . ' g on og.goodsid = g.id   where og.uniacid=:uniacid    and og.orderid=:orderid ', array(':uniacid' => $_W['uniacid'], ':orderid' => $order['id']));
			}
		}
		$mid = intval($_GPC['mid']);
		$index_cache = $this->getpage($shopInfo);
		if (!(empty($mid))) 
		{
			$index_cache = preg_replace_callback('/href=[\\\'"]?([^\\\'" ]+).*?[\\\'"]/', function($matches) use($mid) 
			{
				$preg = $matches[1];
				if (strexists($preg, 'mid=')) 
				{
					return 'href=\'' . $preg . '\'';
				}
				if (!(strexists($preg, 'javascript'))) 
				{
					$preg = preg_replace('/(&|\\?)mid=[\\d+]/', '', $preg);
					if (strexists($preg, '?')) 
					{
						$newpreg = $preg . '&mid=' . $mid;
					}
					else 
					{
						$newpreg = $preg . '?mid=' . $mid;
					}
					return 'href=\'' . $newpreg . '\'';
				}
			}
			, $index_cache);
		}
		$shop_data = m('common')->getSysset('shop');
		$cpinfos = com('coupon')->getInfo();

		//获取门店ID 没有的话写入一个
		include $this->template();
	}

	//资质证明展示
    public function zzzm(){
        global $_W;
        global $_GPC;

        $store_id = $_GPC['store_id'];
        $thishop = pdo_fetch('select *  from ' . tablename('ewei_shop_store') . ' s where s.id=:id ', array(':id' => $store_id));

        echo '<img src="http://qth-test.oss-cn-hangzhou.aliyuncs.com/'.$thishop['business_license_img'].'">';
        echo '<img src="http://qth-test.oss-cn-hangzhou.aliyuncs.com/'.$thishop['drug_license_img'].'">';
    }

	public function getstoreinfo(){
        global $_W;
        global $_GPC;
        $uniacid = $_W['uniacid'];

	    $store_id = $_GPC['stroeid'];
        $thishop = pdo_fetch('select *  from ' . tablename('ewei_shop_store') . ' s where s.id=:id ', array(':id' => $store_id));
        //fanhailong add, 门店logo如果不包含resource.ymkchen.com，则加上图片显示相对路径/attachment/
        if(strpos($thishop['logo'], 'resource.ymkchen.com') !== false){
        }else{
            //如果不包含resource.ymkchen.com，则是b端人人商城后台上传的logo，用相对路径
            $thishop['logo'] = "/attachment/{$thishop['logo']}";
        }

        //获取营业时间配置
        $yysj = pdo_fetch('select *  from ' . tablename('ewei_shop_city_express') . ' s where s.uniacid=:id ', array(':id' => $uniacid));

        //配送价格 ewei_shop_dispatch
        $dispath = pdo_fetch('select *  from ' . tablename('ewei_shop_dispatch') . ' s where s.uniacid=:id ', array(':id' => $uniacid));
        include $this->template('common/storeinfo');
    }

	public function get_recommand() 
	{
		global $_W;
		global $_GPC;
		$args = array('page' => $_GPC['page'], 'pagesize' => 6, 'isrecommand' => 1, 'order' => 'displayorder desc,createtime desc', 'by' => '');
//        $store_id = (isset($_GPC['storeid']) &&!empty($_GPC['storeid'])) ? $_GPC['storeid'] : 0;
        $urlstoreid = $_GPC['storeid'];
        if($urlstoreid){
            $urlstoreid && isetcookie('store_id', $urlstoreid, 7 * 86400);
        }

        //查看是否有cookie
        $args['shop_id'] = $urlstoreid ?: $_COOKIE[$_W['config']['cookie']['pre'] . 'store_id'];
		$recommand = m('goods')->getList($args);
		show_json(1, array('list' => $recommand['list'], 'pagesize' => $args['pagesize'], 'total' => $recommand['total'], 'page' => intval($_GPC['page'])));
	}
	private function getcache() 
	{
		global $_W;
		global $_GPC;
		return m('common')->createStaticFile(mobileUrl('getpage', NULL, true));
	}
	public function getpage($shopInfo)
	{
		global $_W;
		global $_GPC;
		$uniacid = $_W['uniacid'];
		$defaults = array( 'adv' => array('text' => '幻灯片', 'visible' => 1), 'search' => array('text' => '搜索栏', 'visible' => 1), 'nav' => array('text' => '导航栏', 'visible' => 1), 'notice' => array('text' => '公告栏', 'visible' => 1), 'cube' => array('text' => '魔方栏', 'visible' => 1), 'banner' => array('text' => '广告栏', 'visible' => 1), 'goods' => array('text' => '推荐栏', 'visible' => 1) );
		$sorts = ((isset($_W['shopset']['shop']['indexsort']) ? $_W['shopset']['shop']['indexsort'] : $defaults));
		$sorts['recommand'] = array('text' => '系统推荐', 'visible' => 1);
        $this_store_id = $shopInfo['id'];

		$advs = pdo_fetchall('select id,advname,link,thumb from ' . tablename('ewei_shop_adv') . ' where uniacid=:uniacid and iswxapp=0 and enabled=1 and storeid like "%,":store_id",%" order by displayorder desc', array(':uniacid' => $uniacid,':store_id'=>$this_store_id));
		//fanhailong add，后台设置导航图标的商品时，选择时是选择商家商品库的商品，但插入到库里时是转换为门店商品库的商品，前台需要根据当前店铺有没有这个商品来决定是否显示这个导航图标
        foreach($advs as $key=>$val){
            if(strpos($val['link'], 'goods.detail&id') === false){
            }else{
                //由于数据库里存的是link，所以先截取取出当前商品id，然后读取商家商品库商品信息
                $id = explode("id=", $val['link']);
                $id = $id[1];
                $tsql = "SELECT b.* FROM " . tablename('ewei_shop_goods'). " as a LEFT JOIN". tablename('ewei_business_goods'). " as b ON a.business_goods_id = b.id WHERE a.id={$id}";
                $goods = pdo_fetch($tsql, $param);
                if(empty($goods) || !$goods){
                    unset($advs[$key]);
                    continue ;
                }
                //再读取出当前店铺是否有这个商品，如果有，替换为当前店铺的商品id，没有，则删除
                $tsql = "SELECT id FROM " . tablename('ewei_shop_goods'). " as a WHERE a.business_goods_id={$goods['id']} AND a.shop_id = {$this_store_id}";
                $curren_shop_goods = pdo_fetch($tsql, $param);
                if(empty($curren_shop_goods) || !$curren_shop_goods){
                    unset($advs[$key]);
                    continue ;
                }
                $advs[$key]['link'] = str_replace($id, $curren_shop_goods['id'], $advs[$key]['link']);
            }
        }

		$navs = pdo_fetchall('select id,navname,url,icon from ' . tablename('ewei_shop_nav') . ' where uniacid=:uniacid and iswxapp=0 and status=1 and storeid like "%,":store_id",%" order by displayorder desc', array(':uniacid' => $uniacid,':store_id'=>$this_store_id));
		//fanhailong add，后台设置轮播图的商品时，选择时是选择商家商品库的商品，但插入到库里时是转换为门店商品库的商品，前台需要根据当前店铺有没有这个商品来决定是否显示这个广告
        foreach($navs as $key=>$val){
            if(strpos($val['url'], 'goods.detail&id') === false){
            }else{
                //由于数据库里存的是url，所以先截取取出当前商品id，然后读取商家商品库商品信息
                $id = explode("id=", $val['url']);
                $id = $id[1];
                $tsql = "SELECT b.* FROM " . tablename('ewei_shop_goods'). " as a LEFT JOIN". tablename('ewei_business_goods'). " as b ON a.business_goods_id = b.id WHERE a.id={$id}";
                $goods = pdo_fetch($tsql, $param);
                if(empty($goods) || !$goods){
                    unset($navs[$key]);
                    continue ;
                }
                //再读取出当前店铺是否有这个商品，如果有，替换为当前店铺的商品id，没有，则删除
                $tsql = "SELECT id FROM " . tablename('ewei_shop_goods'). " as a WHERE a.business_goods_id={$goods['id']} AND a.shop_id = {$this_store_id}";
                $curren_shop_goods = pdo_fetch($tsql, $param);
                if(empty($curren_shop_goods) || !$curren_shop_goods){
                    unset($navs[$key]);
                    continue ;
                }
                $navs[$key]['url'] = str_replace($id, $curren_shop_goods['id'], $navs[$key]['url']);
            }
        }

		$cubes = ((is_array($_W['shopset']['shop']['cubes']) ? $_W['shopset']['shop']['cubes'] : array()));
        //fanhailong add，后台设置魔方推荐的商品时，选择时是选择商家商品库的商品，但插入到库里时是转换为门店商品库的商品，前台需要根据当前店铺有没有这个商品来决定是否显示这个魔方
        foreach($cubes as $key=>$val){
            if(strpos($val['url'], 'goods.detail&id') === false){
            }else{
                //由于数据库里存的是url，所以先截取取出当前商品id，然后读取商家商品库商品信息
                $id = explode("id=", $val['url']);
                $id = $id[1];
                $tsql = "SELECT b.* FROM " . tablename('ewei_shop_goods'). " as a LEFT JOIN". tablename('ewei_business_goods'). " as b ON a.business_goods_id = b.id WHERE a.id={$id}";
                $goods = pdo_fetch($tsql, $param);
                if(empty($goods) || !$goods){
                    unset($cubes[$key]);
                    continue ;
                }
                //再读取出当前店铺是否有这个商品，如果有，替换为当前店铺的商品id，没有，则删除
                $tsql = "SELECT id FROM " . tablename('ewei_shop_goods'). " as a WHERE a.business_goods_id={$goods['id']} AND a.shop_id = {$this_store_id}";
                $curren_shop_goods = pdo_fetch($tsql, $param);
                if(empty($curren_shop_goods) || !$curren_shop_goods){
                    unset($cubes[$key]);
                    continue ;
                }
                $cubes[$key]['url'] = str_replace($id, $curren_shop_goods['id'], $cubes[$key]['url']);
            }
        }

		$banners = pdo_fetchall('select id,bannername,link,thumb from ' . tablename('ewei_shop_banner') . ' where uniacid=:uniacid and iswxapp=0 and enabled=1 and storeid like "%,":store_id",%" order by displayorder desc', array(':uniacid' => $uniacid,':store_id'=>$this_store_id));
		//fanhailong add，后台设置魔方推荐的商品时，选择时是选择商家商品库的商品，但插入到库里时是转换为门店商品库的商品，前台需要根据当前店铺有没有这个商品来决定是否显示这个魔方
        foreach($banners as $key=>$val){
            if(strpos($val['link'], 'goods.detail&id') === false){
            }else{
                //由于数据库里存的是link，所以先截取取出当前商品id，然后读取商家商品库商品信息
                $id = explode("id=", $val['link']);
                $id = $id[1];
                $tsql = "SELECT b.* FROM " . tablename('ewei_shop_goods'). " as a LEFT JOIN". tablename('ewei_business_goods'). " as b ON a.business_goods_id = b.id WHERE a.id={$id}";
                $goods = pdo_fetch($tsql, $param);
                if(empty($goods) || !$goods){
                    unset($banners[$key]);
                    continue ;
                }
                //再读取出当前店铺是否有这个商品，如果有，替换为当前店铺的商品id，没有，则删除
                $tsql = "SELECT id FROM " . tablename('ewei_shop_goods'). " as a WHERE a.business_goods_id={$goods['id']} AND a.shop_id = {$this_store_id}";
                $curren_shop_goods = pdo_fetch($tsql, $param);
                if(empty($curren_shop_goods) || !$curren_shop_goods){
                    unset($banners[$key]);
                    continue ;
                }
                $banners[$key]['link'] = str_replace($id, $curren_shop_goods['id'], $banners[$key]['link']);
            }
        }


		$bannerswipe = $_W['shopset']['shop']['bannerswipe'];
		if (isset($_W['shopset']['shop']['indexrecommands']) && !empty(isset($_W['shopset']['shop']['indexrecommands'])))
		{
            $goodsids = [];
            $store_id = (isset($_GPC['storeid']) &&!empty($_GPC['storeid'])) ? $_GPC['storeid'] : 0;
            if(!empty($store_id)){
                $store_id && isetcookie('store_id', $store_id, 7 * 86400);
            }else{
                $store_id = $_COOKIE[$_W['config']['cookie']['pre'] . 'store_id'];
            }
            if(!empty($store_id) && isset($_W['shopset']['shop']['indexrecommands'][$store_id]) && !empty($_W['shopset']['shop']['indexrecommands'][$store_id]))
            {
                $goodsids = $_W['shopset']['shop']['indexrecommands'][$store_id];
            }
			if (!(empty($goodsids)))
			{
//				$indexrecommands = pdo_fetchall('select id, title, thumb, marketprice,ispresell,presellprice, productprice, minprice, total from ' . tablename('ewei_shop_goods') . ' where id in( ' . $goodids . ' ) and uniacid=:uniacid and status=1 order by instr(\'' . $goodids . '\',id),displayorder desc', array(':uniacid' => $uniacid));
				$indexrecommands = pdo_fetchall('select bg.title as bgtitle , bg.thumb as bgthumb , sg.* from '. tablename('ewei_business_goods') .' as bg right join '. tablename('ewei_shop_goods') .' as sg on bg.id = sg.business_goods_id where bg.id in ('. implode(',' , $goodsids).') and sg.uniacid=:uniacid and sg.shop_id = '. $store_id.' and sg.status=1 order by instr(\'' . implode(',',$goodsids) . '\',bg.id), sg.displayorder desc', array(':uniacid' => $uniacid));
//				var_dump($indexrecommands);exit;
				foreach ($indexrecommands as $key => $value )
				{
					if (0 < $value['ispresell']) 
					{
						$indexrecommands[$key]['minprice'] = $value['presellprice'];
					}
				}
			}
		}
		$goodsstyle = $_W['shopset']['shop']['goodsstyle'];

		$notices = pdo_fetchall('select id, title, link, thumb from ' . tablename('ewei_shop_notice') . ' where uniacid=:uniacid and iswxapp=0 and status=1 and storeid like "%,":store_id",%" order by displayorder desc limit 5', array(':uniacid' => $uniacid,':store_id'=>$this_store_id));
		foreach($notices as $key=>$val){
            if(strpos($val['link'], 'goods.detail&id') === false){
            }else{
                //由于数据库里存的是link，所以先截取取出当前商品id，然后读取商家商品库商品信息
                $id = explode("id=", $val['link']);
                $id = $id[1];
                $tsql = "SELECT b.* FROM " . tablename('ewei_shop_goods'). " as a LEFT JOIN". tablename('ewei_business_goods'). " as b ON a.business_goods_id = b.id WHERE a.id={$id}";
                $goods = pdo_fetch($tsql, $param);
                if(empty($goods) || !$goods){
                    unset($notices[$key]);
                    continue ;
                }
                //再读取出当前店铺是否有这个商品，如果有，替换为当前店铺的商品id，没有，则删除
                $tsql = "SELECT id FROM " . tablename('ewei_shop_goods'). " as a WHERE a.business_goods_id={$goods['id']} AND a.shop_id = {$this_store_id}";
                $curren_shop_goods = pdo_fetch($tsql, $param);
                if(empty($curren_shop_goods) || !$curren_shop_goods){
                    unset($notices[$key]);
                    continue ;
                }
                $notices[$key]['link'] = str_replace($id, $curren_shop_goods['id'], $notices[$key]['link']);
            }
        }

		$seckillinfo = plugin_run('seckill::getTaskSeckillInfo');
		ob_start();
		ob_implicit_flush(false);
        //获取商户门店
        $prefix_cookie = $_W['config']['cookie']['pre'];
        $shoplist = pdo_fetchall('select *  from ' . tablename('ewei_shop_store') . ' og  where status = 1 and og.uniacid=:uniacid ', array(':uniacid' => $_W['uniacid']));;
        //fanhailong add, 门店logo如果不包含resource.ymkchen.com，则加上图片显示相对路径/attachment/
        foreach($shoplist as $key=>$val){
            if(strpos($val['logo'], 'resource.ymkchen.com') !== false){
            }else{
                //如果不包含resource.ymkchen.com，则是b端人人商城后台上传的logo，用相对路径
                $shoplist[$key]['logo'] = "/attachment/{$val['logo']}";
            }
        }
        require $this->template('index_tpl');
		return ob_get_clean();
	}
	public function seckillinfo() 
	{
		$seckillinfo = plugin_run('seckill::getTaskSeckillInfo');
		include $this->template('shop/index/seckill_tpl');
		exit();
	}
	public function qr() 
	{
		global $_W;
		global $_GPC;
		$url = trim($_GPC['url']);
		require IA_ROOT . '/framework/library/qrcode/phpqrcode.php';
		QRcode::png($url, false, QR_ECLEVEL_L, 16, 1);
	}
}
?>