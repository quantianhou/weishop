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
        $a_merchant_id =  (!empty($_W['user']['a_merchant_id'])) ? intval($_W['user']['a_merchant_id']) : 0;

        $uniacid = (!empty($_W['uniacid'])) ? intval($_W['uniacid']) : 0 ;
        if(!empty($name))
        {
            $store = pdo_fetchall('select * from '.tablename('ewei_shop_store').' where uniacid = :id and a_merchant_id = :a_merchant_id and store_status = 1 and storename like "%'.$name.'%"' , [':a_merchant_id'=>  $a_merchant_id , ':id' => $uniacid]);
        }else{
            $store = pdo_fetchall('select * from '.tablename('ewei_shop_store').' where uniacid = :id and a_merchant_id = :a_merchant_id and store_status = 1' , [':a_merchant_id'=>  $a_merchant_id , ':id' => $uniacid]);
        }
        if(empty($store))
        {
            show_json(2, array('childBranchList' => false , 'parentBranchList' =>false));
            return ;
        }

        foreach($store as $k => $v){
            if( !(time() >= strtotime($v['contract_start_time']) && time() <= strtotime($v['contract_end_time'])))
            {
                unset($store[$k]);
            }
        }
        $store = array_values($store);
        show_json(1, array('childBranchList' => $store , 'parentBranchList' =>[['name' => '全部店铺']]));
	}
}
?>