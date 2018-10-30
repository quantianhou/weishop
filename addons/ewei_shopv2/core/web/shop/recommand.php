<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Recommand_EweiShopV2Page extends WebPage 
{
	public function main() 
	{
		global $_W;
		global $_GPC;
		if ($_W['ispost']) 
		{
			$shop = $_W['shopset']['shop'];
			$shop['indexrecommands'] = $_GPC['goodsid'];
			m('common')->updateSysset(array('shop' => $shop));
			plog('shop.recommand', '修改首页推荐商品设置');
			show_json(1);
		}
		$goodsids = $storeid = $gsdata = [];
		if(isset($_W['shopset']['shop']['indexrecommands']) && !empty(isset($_W['shopset']['shop']['indexrecommands'])))
        {
            foreach($_W['shopset']['shop']['indexrecommands'] as $key => $val)
            {
                $goodsids = array_merge($goodsids,$val);
                $storeid[] = $key;
                foreach($val as $v)
                {
                    $gsdata[$v][] = $key;
                }
            }
        }

		$goods = $store = false;
		if (!(empty($goodsids))) 
		{
			$goods_list = pdo_fetchall('select id,title,thumb from ' . tablename('ewei_business_goods') . ' where id in (' . implode(',',$goodsids) . ') and status=1 and deleted=0 and uniacid=' . $_W['uniacid'] . ' order by instr(\'' . $goodsids . '\',id)');
			if(!empty($goods_list))
            {
                foreach($goods_list as $value)
                {
                    $goods[$value['id']] = $value;
                }
            }
		}
		if(!empty($storeid))
        {
            $store_list = pdo_fetchall('select id,storename from ' . tablename('ewei_shop_store') . ' where id in (' . implode(',',$storeid) . ') and uniacid=' . $_W['uniacid'] . ' order by instr(\'' . $storeid . '\',id)');

            foreach($store_list as $value)
            {
                $store[$value['id']] = $value;
            }
        }

		$goodsstyle = $_W['shopset']['shop']['goodsstyle'];

		include $this->template();
	}
	public function setstyle() 
	{
		global $_W;
		global $_GPC;
		$shop = $_W['shopset']['shop'];
		$shop['goodsstyle'] = intval($_GPC['goodsstyle']);
		m('common')->updateSysset(array('shop' => $shop));
		plog('shop.recommand', '修改手机端商品组样式');
		show_json(1);
	}

	public function toAdd()
    {
        global $_W;
        global $_GPC;

        include $this->template();
    }

    public function doAdd()
    {
        global $_W;
        global $_GPC;

        if(isset($_GPC['storeid']) && !empty($_GPC['storeid']) && isset($_GPC['goodsid']) && !empty($_GPC['goodsid']))
        {
            $data = [];
            foreach($_GPC['storeid'] as $val)
            {
                $data[$val] = $_GPC['goodsid'];
            }
//            $uniacid = $_W['uniacid'];
//            $setdata = pdo_fetch('select id, sets from ' . tablename('ewei_shop_sysset') . ' where uniacid=:uniacid limit 1', array(':uniacid' => $uniacid));
//            $sets = iunserializer($setdata['sets']);
//            var_dump($sets);exit;
            $shop = $_W['shopset']['shop'];
            $shop['indexrecommands'] = $data;
            m('common')->updateSysset(array('shop' => $shop));
            plog('shop.recommand', '修改首页推荐商品设置');
            show_json(1);
//        var_dump($sets);
//        var_dump($_GPC['storeid']);
//        var_dump($_GPC['goodsid']);exit;
        }
        show_json(0,'操作失败');
    }

    public function toDel()
    {
        global $_W;
        global $_GPC;

        if(isset($_GPC['id']) && !empty($_GPC['id']))
        {
            $indexrecommands = [];
            if(isset($_W['shopset']['shop']['indexrecommands']) && !empty(isset($_W['shopset']['shop']['indexrecommands'])))
            {
                foreach($_W['shopset']['shop']['indexrecommands'] as $key => $val)
                {
                    foreach ($val as $v)
                    {
                        if($v != $_GPC['id'])
                        {
                            $indexrecommands[$key][] = $v;
                        }
                    }
                }
            }

            $shop = $_W['shopset']['shop'];
            $shop['indexrecommands'] = $indexrecommands;
            m('common')->updateSysset(array('shop' => $shop));
            plog('shop.recommand', '修改首页推荐商品设置');
            show_json(1);
        }
        show_json(0,'操作失败');
    }
}
?>