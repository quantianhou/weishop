<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Store_EweiShopV2Page extends WebPage
{
	public function me()
	{
        global $_W;
        global $_GPC;
        $name = trim($_GPC['branchName'],' ');
        $a_merchant_id = (!empty($_W['user']['a_merchant_id'])) ? intval($_W['user']['a_merchant_id']) : 0 ;

        if(!empty($name))
        {
            $store = pdo_fetchall('select * from '.tablename('ewei_shop_store').' where a_merchant_id = :id and storename like "%'.$name.'%"' , [':id' => $a_merchant_id]);
        }else{
            $store = pdo_fetchall('select * from '.tablename('ewei_shop_store').' where a_merchant_id = :id' , [':id' => $a_merchant_id]);
        }
        if(empty($store))
        {
            show_json(2, array('childBranchList' => false , 'parentBranchList' =>false));
            return ;
        }

        show_json(1, array('childBranchList' => $store , 'parentBranchList' =>[['name' => '全部店铺']]));
	}
}
?>