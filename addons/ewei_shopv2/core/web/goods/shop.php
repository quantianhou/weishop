<?php
if (!(defined('IN_IA')))
{
    exit('Access Denied');
}
class Shop_EweiShopV2Page extends WebPage
{

    public function categorytoshop(){

        global $_W;
        global $_GPC;

        $goodsids = $_GPC['goodsids'];
        $categoryids = $_GPC['cates'];

        if(empty($goodsids)){
            show_json(0, '请选择门店！');
        }

        if(empty($categoryids)){
            show_json(0, '请选择分类！');
        }

        $sql = 'UPDATE ' . tablename('ewei_business_goods') . ' SET pcate=:pcate WHERE id IN (' . implode(',', $goodsids) . ')';
        $sql2 = 'UPDATE ' . tablename('ewei_shop_goods') . ' SET pcate=:pcate WHERE business_goods_id IN (' . implode(',', $goodsids) . ')';
        $paras = array(':pcate' => current($categoryids));
        $goodslist = pdo_fetch($sql, $paras);
        $goodslist = pdo_fetch($sql2, $paras);

        show_json(1, '下发成功！');

    }

    public function pricetoshop(){
        global $_W;
        global $_GPC;

        $goodsids = $_GPC['goodsids'];
        $shopsids = $_GPC['cates'];
        $iscover = $_GPC['iscover'];    //0下发选中 1全部下发

        if(empty($shopsids)){
            show_json(0, '请选择门店！');
        }

        if($iscover == 1){
            //获取全部商品
            $condition = ' uniacid = :uniacid';
        }else{
            $condition = ' uniacid = :uniacid and id IN (' . implode(',', $goodsids) . ')';
        }
        $sql = 'SELECT * FROM ' . tablename('ewei_business_goods') . ' WHERE ' . $condition;
        $paras = array(':uniacid' => $_W['uniacid']);
        $goodslist = pdo_fetchall($sql, $paras);

        foreach ($shopsids as $val){
            foreach ($goodslist as $goods){

                //判断门店存在该商品
                $sql = 'SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE business_goods_id='.$goods['id'].' AND shop_id='.$val;
                $hasgood = pdo_fetchall($sql, $paras);

                if(empty($hasgood)){
                    continue;
                }
                //修改价格
                pdo_update('ewei_shop_goods', [ 'productprice' =>  $goods['productprice'], 'marketprice' =>  $goods['marketprice']], array('business_goods_id' => $goods['id'],'shop_id'=>$val));
            }
        }
        show_json(1, '下发成功！');

    }

    public function pushtoshop(){
        global $_W;
        global $_GPC;

        $goodsids = $_GPC['goodsids'];
        $shopsids = $_GPC['cates'];
        $iscover = $_GPC['iscover'];    //0下发选中 1全部下发

        if(empty($shopsids)){
            show_json(0, '请选择门店！');
        }

        if($iscover == 1){
            //获取全部商品
            $condition = ' uniacid = :uniacid';
        }else{
            $condition = ' uniacid = :uniacid and id IN (' . implode(',', $goodsids) . ')';
        }
        $sql = 'SELECT * FROM ' . tablename('ewei_business_goods') . ' WHERE ' . $condition;
        $paras = array(':uniacid' => $_W['uniacid']);
        $goodslist = pdo_fetchall($sql, $paras);

        foreach ($shopsids as $val){
            foreach ($goodslist as $goods){

                //判断门店存在该商品
                $sql = 'SELECT * FROM ' . tablename('ewei_shop_goods') . ' WHERE business_goods_id='.$goods['id'].' AND shop_id='.$val;
                $hasgood = pdo_fetchall($sql, $paras);

                if(!empty($hasgood)){
                    continue;
                }
                //不存在添加
                $goods['business_goods_id'] = $goods['id'];
                $goods['shop_id'] = $val;
                unset($goods['id']);
                pdo_insert('ewei_shop_goods', $goods);

            }
        }
        show_json(1, '下发成功！');

    }

    public function main(){
        global $_W;
        global $_GPC;

        if (empty($_W['shopversion'])) {
            $goodsfrom = strtolower(trim($_GPC['goodsfrom']));

            if (empty($goodsfrom)) {
                header('location: ' . webUrl('goods', array('goodsfrom' => 'sale')));
            }
        }
        else {
            if (!empty($_GPC['goodsfrom'])) {
                header('location: ' . webUrl('goods/' . $_GPC['goodsfrom']));
            }
        }

        $merch_plugin = p('merch');
        $merch_data = m('common')->getPluginset('merch');
        if ($merch_plugin && $merch_data['is_openmerch']) {
            $is_openmerch = 1;
        }
        else {
            $is_openmerch = 0;
        }

        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $sqlcondition = $groupcondition = '';
        $condition = ' WHERE g.`uniacid` = :uniacid and deleted != 1';
        $params = array(':uniacid' => $_W['uniacid']);

        if (!empty($_GPC['keyword'])) {
            $_GPC['keyword'] = trim($_GPC['keyword']);
            $sqlcondition = ' left join ' . tablename('ewei_shop_goods_option') . ' op on g.id = op.goodsid';

            if ($merch_plugin) {
                $sqlcondition .= ' left join ' . tablename('ewei_shop_merch_user') . ' merch on merch.id = g.merchid and merch.uniacid=g.uniacid';
            }

            $groupcondition = ' group by g.`id`';
            $condition .= ' AND (g.`id` = :id or g.`title` LIKE :keyword or g.`keywords` LIKE :keyword or g.`goodssn` LIKE :keyword or g.`productsn` LIKE :keyword or op.`title` LIKE :keyword or op.`goodssn` LIKE :keyword or op.`productsn` LIKE :keyword';

            if ($merch_plugin) {
                $condition .= ' or merch.`merchname` LIKE :keyword';
            }

            $condition .= ' )';
            $params[':keyword'] = '%' . $_GPC['keyword'] . '%';
            $params[':id'] = $_GPC['keyword'];
        }

        if (!empty($_GPC['cate'])) {
            $_GPC['cate'] = intval($_GPC['cate']);
            $condition .= ' AND FIND_IN_SET(' . $_GPC['cate'] . ',cates)<>0 ';
        }

        empty($goodsfrom) && $_GPC['goodsfrom'] = $goodsfrom = 'sale';
        $_GPC['goodsfrom'] = $goodsfrom;

        $sql = 'SELECT g.id FROM ' . tablename('ewei_business_goods') . 'g' . $sqlcondition . $condition . $groupcondition;
        $total_all = pdo_fetchall($sql, $params);
        $total = count($total_all);
        unset($total_all);

        if (!empty($total)) {
            $sql = 'SELECT g.* FROM ' . tablename('ewei_business_goods') . 'g' . $sqlcondition . $condition . $groupcondition . " ORDER BY g.`status` ASC, g.`displayorder` ASC,\r\n                g.`id` DESC LIMIT " . (($pindex - 1) * $psize) . ',' . $psize;
            $list = pdo_fetchall($sql, $params);

            foreach ($list as $key => &$value) {
                $url = mobileUrl('goods/detail', array('id' => $value['id']), true);
                $value['qrcode'] = m('qrcode')->createQrcode($url);
                
                //查询下发门店数
                $sql = 'SELECT s.* FROM ' . tablename('ewei_shop_goods') . 'g LEFT JOIN ' .tablename('ewei_shop_store') . ' s ON g.shop_id=s.id WHERE business_goods_id='.$value['id'];
                $needs = pdo_fetchall($sql, []);
                $value['has_shop_name'] = '';
                foreach ($needs as $vvvv){
                    $value['has_shop_name'] .= '<li>'.$vvvv['storename'].'</li>';
                }
                $value['has_shop'] = count($needs);
            }

            $pager = pagination2($total, $pindex, $psize);

            if ($merch_plugin) {
                $merch_user = $merch_plugin->getListUser($list, 'merch_user');
                if (!empty($list) && !empty($merch_user)) {
                    foreach ($list as &$row) {
                        $row['merchname'] = $merch_user[$row['merchid']]['merchname'] ? $merch_user[$row['merchid']]['merchname'] : $_W['shopset']['shop']['name'];
                    }
                }
            }
        }

        $categorys = m('shop')->getFullCategory(true);
        $category = array();

        foreach ($categorys as $cate) {
            $category[$cate['id']] = $cate;
        }

        $goodstotal = intval($_W['shopset']['shop']['goodstotal']);

        //获取全部门店
        $condition = ' uniacid = :uniacid';
        $sql = 'SELECT * FROM ' . tablename('ewei_shop_store') . ' WHERE ' . $condition . ' ORDER BY displayorder desc,id desc';
        $paras = array(':uniacid' => $_W['uniacid']);
        $shop = pdo_fetchall($sql, $paras);
        include $this->template('goods/shop');
    }

    public function create()
    {
        global $_W;
        global $_GPC;
        $merchid = intval($_W['merchid']);
        $com_virtual = com('virtual');

        if ($_W['ispost']) {
            $data = array('uniacid' => intval($_W['uniacid']), 'title' => trim($_GPC['goodsname']), 'unit' => trim($_GPC['unit']), 'keywords' => trim($_GPC['keywords']), 'type' => intval($_GPC['type']), 'thumb_first' => intval($_GPC['thumb_first']), 'isrecommand' => intval($_GPC['isrecommand']), 'isnew' => intval($_GPC['isnew']), 'ishot' => intval($_GPC['ishot']), 'issendfree' => intval($_GPC['issendfree']), 'isnodiscount' => intval($_GPC['isnodiscount']), 'marketprice' => floatval($_GPC['marketprice']), 'minprice' => floatval($_GPC['marketprice']), 'maxprice' => floatval($_GPC['marketprice']), 'productprice' => trim($_GPC['productprice']), 'costprice' => $_GPC['costprice'], 'virtualsend' => intval($_GPC['virtualsend']), 'virtualsendcontent' => trim($_GPC['virtualsendcontent']), 'virtual' => intval($_GPC['type']) == 3 ? intval($_GPC['virtual']) : 0, 'cash' => intval($_GPC['cash']), 'cashier' => intval($_GPC['cashier']), 'invoice' => intval($_GPC['invoice']), 'dispatchtype' => intval($_GPC['dispatchtype']), 'dispatchprice' => trim($_GPC['dispatchprice']), 'dispatchid' => intval($_GPC['dispatchid']), 'status' => intval($_GPC['status']), 'goodssn' => trim($_GPC['goodssn']), 'productsn' => trim($_GPC['productsn']), 'weight' => $_GPC['weight'], 'total' => intval($_GPC['total']), 'showtotal' => intval($_GPC['showtotal']), 'totalcnf' => intval($_GPC['totalcnf']), 'hasoption' => intval($_GPC['hasoption']), 'subtitle' => trim($_GPC['subtitle']), 'shorttitle' => trim($_GPC['shorttitle']), 'content' => m('common')->html_images($_GPC['content']), 'createtime' => TIMESTAMP);
            $cateset = m('common')->getSysset('shop');
            $pcates = array();
            $ccates = array();
            $tcates = array();
            $fcates = array();
            $cates = array();
            $pcateid = 0;
            $ccateid = 0;
            $tcateid = 0;

            if (is_array($_GPC['cates'])) {
                $cates = $_GPC['cates'];

                foreach ($cates as $key => $cid) {
                    $c = pdo_fetch('select level from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));

                    if ($c['level'] == 1) {
                        $pcates[] = $cid;
                    }
                    else if ($c['level'] == 2) {
                        $ccates[] = $cid;
                    }
                    else {
                        if ($c['level'] == 3) {
                            $tcates[] = $cid;
                        }
                    }

                    if ($key == 0) {
                        if ($c['level'] == 1) {
                            $pcateid = $cid;
                        }
                        else if ($c['level'] == 2) {
                            $crow = pdo_fetch('select parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));
                            $pcateid = $crow['parentid'];
                            $ccateid = $cid;
                        }
                        else {
                            if ($c['level'] == 3) {
                                $tcateid = $cid;
                                $tcate = pdo_fetch('select id,parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $cid, ':uniacid' => $_W['uniacid']));
                                $ccateid = $tcate['parentid'];
                                $ccate = pdo_fetch('select id,parentid from ' . tablename('ewei_shop_category') . ' where id=:id and uniacid=:uniacid limit 1', array(':id' => $ccateid, ':uniacid' => $_W['uniacid']));
                                $pcateid = $ccate['parentid'];
                            }
                        }
                    }
                }
            }

            $data['pcate'] = $pcateid;
            $data['ccate'] = $ccateid;
            $data['tcate'] = $tcateid;
            $data['cates'] = implode(',', $cates);
            $data['pcates'] = implode(',', $pcates);
            $data['ccates'] = implode(',', $ccates);
            $data['tcates'] = implode(',', $tcates);

            if (is_array($_GPC['thumbs'])) {
                $thumbs = $_GPC['thumbs'];
                $thumb_url = array();

                foreach ($thumbs as $th) {
                    $thumb_url[] = trim($th);
                }

                $data['thumb'] = save_media($thumb_url[0]);
                unset($thumb_url[0]);
                $data['thumb_url'] = serialize(m('common')->array_images($thumb_url));
            }

            if ($data['type'] == 4) {
                $intervalfloor = intval($_GPC['intervalfloor']);
                if ((3 < $intervalfloor) || ($intervalfloor < 1)) {
                    show_json(0, '请至少添加一个区间价格！');
                }

                $intervalprices = array();

                if (0 < $intervalfloor) {
                    if (intval($_GPC['intervalnum1']) <= 0) {
                        show_json(0, '请设置起批发量！');
                    }

                    if (floatval($_GPC['intervalprice1']) <= 0) {
                        show_json(0, '批发价必须大于0！');
                    }

                    $intervalprices[] = array('intervalnum' => intval($_GPC['intervalnum1']), 'intervalprice' => floatval($_GPC['intervalprice1']));
                }

                if (1 < $intervalfloor) {
                    if (intval($_GPC['intervalnum2']) <= 0) {
                        show_json(0, '请设置起批发量！');
                    }

                    if (intval($_GPC['intervalnum2']) <= intval($_GPC['intervalnum1'])) {
                        show_json(0, '批发量需大于上级批发量！');
                    }

                    if (floatval($_GPC['intervalprice1']) <= floatval($_GPC['intervalprice2'])) {
                        show_json(0, '批发价需小于上级批发价！');
                    }

                    $intervalprices[] = array('intervalnum' => intval($_GPC['intervalnum2']), 'intervalprice' => floatval($_GPC['intervalprice2']));
                }

                if (2 < $intervalfloor) {
                    if (intval($_GPC['intervalnum3']) <= 0) {
                        show_json(0, '请设置起批发量！');
                    }

                    if (intval($_GPC['intervalnum3']) <= intval($_GPC['intervalnum2'])) {
                        show_json(0, '批发量需大于上级批发量！');
                    }

                    if (floatval($_GPC['intervalprice2']) <= floatval($_GPC['intervalprice3'])) {
                        show_json(0, '批发价需小于上级批发价！');
                    }

                    $intervalprices[] = array('intervalnum' => intval($_GPC['intervalnum3']), 'intervalprice' => floatval($_GPC['intervalprice3']));
                }

                $intervalprice = iserializer($intervalprices);
                $data['intervalfloor'] = $intervalfloor;
                $data['intervalprice'] = $intervalprice;
                $data['minbuy'] = $_GPC['intervalnum1'];
                $data['marketprice'] = $_GPC['intervalprice1'];
                $data['productprice'] = 0;
                $data['costprice'] = 0;
            }

            $data['isstatustime'] = intval($_GPC['isstatustime']);
            $data['statustimestart'] = strtotime($_GPC['statustime']['start']);
            $data['statustimeend'] = strtotime($_GPC['statustime']['end']);
            if (($data['status'] == 1) && (0 < $data['isstatustime'])) {
                if (!(($data['statustimestart'] < time()) && (time() < $data['statustimeend']))) {
                    show_json(0, '上架时间不符合要求！');
                }
            }

            pdo_insert('ewei_business_goods', $data);
            $id = pdo_insertid();
            plog('goods.add', '添加商品 ID: ' . $id . '<br>' . (!empty($data['nocommission']) ? '是否参与分销 -- 否' : '是否参与分销 -- 是'));
            $files = $_FILES;
            $spec_ids = $_POST['spec_id'];
            $spec_titles = $_POST['spec_title'];
            $specids = array();
            $len = count($spec_ids);
            $specids = array();
            $spec_items = array();
            $k = 0;

            while ($k < $len) {
                $spec_id = '';
                $get_spec_id = $spec_ids[$k];
                $a = array('uniacid' => $_W['uniacid'], 'goodsid' => $id, 'displayorder' => $k, 'title' => $spec_titles[$get_spec_id]);

                if (is_numeric($get_spec_id)) {
                    pdo_update('ewei_shop_goods_spec', $a, array('id' => $get_spec_id));
                    $spec_id = $get_spec_id;
                }
                else {
                    pdo_insert('ewei_shop_goods_spec', $a);
                    $spec_id = pdo_insertid();
                }

                $spec_item_ids = $_POST['spec_item_id_' . $get_spec_id];
                $spec_item_titles = $_POST['spec_item_title_' . $get_spec_id];
                $spec_item_shows = $_POST['spec_item_show_' . $get_spec_id];
                $spec_item_thumbs = $_POST['spec_item_thumb_' . $get_spec_id];
                $spec_item_oldthumbs = $_POST['spec_item_oldthumb_' . $get_spec_id];
                $spec_item_virtuals = $_POST['spec_item_virtual_' . $get_spec_id];
                $itemlen = count($spec_item_ids);
                $itemids = array();
                $n = 0;

                while ($n < $itemlen) {
                    $item_id = '';
                    $get_item_id = $spec_item_ids[$n];
                    $d = array('uniacid' => $_W['uniacid'], 'specid' => $spec_id, 'displayorder' => $n, 'title' => $spec_item_titles[$n], 'show' => $spec_item_shows[$n], 'thumb' => save_media($spec_item_thumbs[$n]), 'virtual' => $data['type'] == 3 ? $spec_item_virtuals[$n] : 0);
                    $f = 'spec_item_thumb_' . $get_item_id;

                    if (is_numeric($get_item_id)) {
                        pdo_update('ewei_shop_goods_spec_item', $d, array('id' => $get_item_id));
                        $item_id = $get_item_id;
                    }
                    else {
                        pdo_insert('ewei_shop_goods_spec_item', $d);
                        $item_id = pdo_insertid();
                    }

                    $itemids[] = $item_id;
                    $d['get_id'] = $get_item_id;
                    $d['id'] = $item_id;
                    $spec_items[] = $d;
                    ++$n;
                }

                if (0 < count($itemids)) {
                    pdo_query('delete from ' . tablename('ewei_shop_goods_spec_item') . ' where uniacid=' . $_W['uniacid'] . ' and specid=' . $spec_id . ' and id not in (' . implode(',', $itemids) . ')');
                }
                else {
                    pdo_query('delete from ' . tablename('ewei_shop_goods_spec_item') . ' where uniacid=' . $_W['uniacid'] . ' and specid=' . $spec_id);
                }

                pdo_update('ewei_shop_goods_spec', array('content' => serialize($itemids)), array('id' => $spec_id));
                $specids[] = $spec_id;
                ++$k;
            }

            if (0 < count($specids)) {
                pdo_query('delete from ' . tablename('ewei_shop_goods_spec') . ' where uniacid=' . $_W['uniacid'] . ' and goodsid=' . $id . ' and id not in (' . implode(',', $specids) . ')');
            }
            else {
                pdo_query('delete from ' . tablename('ewei_shop_goods_spec') . ' where uniacid=' . $_W['uniacid'] . ' and goodsid=' . $id);
            }

            $totalstocks = 0;
            $optionArray = json_decode($_POST['optionArray'], true);
            $option_idss = $optionArray['option_ids'];
            $len = count($option_idss);
            $optionids = array();
            $k = 0;

            while ($k < $len) {
                $option_id = '';
                $ids = $option_idss[$k];
                $get_option_id = $optionArray['option_id'][$k];
                $idsarr = explode('_', $ids);
                $newids = array();

                foreach ($idsarr as $key => $ida) {
                    foreach ($spec_items as $it) {
                        if ($it['get_id'] == $ida) {
                            $newids[] = $it['id'];
                            break;
                        }
                    }
                }

                $newids = implode('_', $newids);
                $a = array('uniacid' => $_W['uniacid'], 'title' => $optionArray['option_title'][$k], 'productprice' => $optionArray['option_productprice'][$k], 'costprice' => $optionArray['option_costprice'][$k], 'marketprice' => $optionArray['option_marketprice'][$k], 'stock' => $optionArray['option_stock'][$k], 'weight' => $optionArray['option_weight'][$k], 'goodssn' => $optionArray['option_goodssn'][$k], 'productsn' => $optionArray['option_productsn'][$k], 'goodsid' => $id, 'specs' => $newids, 'virtual' => $data['type'] == 3 ? $optionArray['option_virtual'][$k] : 0);

                if ($data['type'] == 4) {
                    $a['presellprice'] = 0;
                    $a['productprice'] = 0;
                    $a['costprice'] = 0;
                    $a['marketprice'] = intval($_GPC['intervalprice1']);
                }

                $totalstocks += $a['stock'];
                pdo_insert('ewei_shop_goods_option', $a);
                $option_id = pdo_insertid();
                $optionids[] = $option_id;
                if ((0 < count($optionids)) && ($data['hasoption'] !== 0)) {
                    pdo_query('delete from ' . tablename('ewei_shop_goods_option') . ' where goodsid=' . $id . ' and id not in ( ' . implode(',', $optionids) . ')');
                    $sql = 'update ' . tablename('ewei_business_goods') . " g set\r\n                    g.minprice = (select min(marketprice) from " . tablename('ewei_shop_goods_option') . ' where goodsid = ' . $id . "),\r\n                    g.maxprice = (select max(marketprice) from " . tablename('ewei_shop_goods_option') . ' where goodsid = ' . $id . ")\r\n                    where g.id = " . $id . ' and g.hasoption=1';
                    pdo_query($sql);
                }
                else {
                    pdo_query('delete from ' . tablename('ewei_shop_goods_option') . ' where goodsid=' . $id);
                    $sql = 'update ' . tablename('ewei_business_goods') . ' set minprice = marketprice,maxprice = marketprice where id = ' . $id . ' and hasoption=0;';
                    pdo_query($sql);
                }

                ++$k;
            }

            $sqlgoods = 'SELECT id,title,thumb,marketprice,productprice,minprice,maxprice,isdiscount,isdiscount_time,isdiscount_discounts,sales,total,description,merchsale FROM ' . tablename('ewei_business_goods') . ' where id=:id and uniacid=:uniacid limit 1';
            $goodsinfo = pdo_fetch($sqlgoods, array(':id' => $id, ':uniacid' => $_W['uniacid']));
            $goodsinfo = m('goods')->getOneMinPrice($goodsinfo);
            pdo_update('ewei_business_goods', array('minprice' => $goodsinfo['minprice'], 'maxprice' => $goodsinfo['maxprice']), array('id' => $id, 'uniacid' => $_W['uniacid']));
            if (($data['type'] == 3) && $com_virtual) {
                $com_virtual->updateGoodsStock($id);
            }
            else {
                if (($data['hasoption'] !== 0) && ($data['totalcnf'] != 2) && empty($data['unite_total'])) {
                    pdo_update('ewei_business_goods', array('total' => $totalstocks), array('id' => $id));
                }
            }

            show_json(1, array('url' => webUrl('goods/edit', array('id' => $id))));
        }

        $statustimestart = time();
        $statustimeend = strtotime('+1 month');
        $category = m('shop')->getFullCategory(true, true);
        $com_virtual = com('virtual');
        $levels = m('member')->getLevels();

        foreach ($levels as &$l) {
            $l['key'] = 'level' . $l['id'];
        }

        unset($l);
        $dispatch_data = pdo_fetchall('select * from ' . tablename('ewei_shop_dispatch') . ' where uniacid=:uniacid and merchid=:merchid and enabled=1 order by displayorder desc', array(':uniacid' => $_W['uniacid'], ':merchid' => $merchid));
        $levels = array_merge(array(
            array('id' => 0, 'key' => 'default', 'levelname' => empty($_W['shopset']['shop']['levelname']) ? '默认会员' : $_W['shopset']['shop']['levelname'])
        ), $levels);
        include $this->template('goods/create');
    }

    /**
     * 新增
     */
    public function add()
    {
        $this->post();
    }

    /**
     * 修改
     */
    public function edit()
    {
        $this->post();
    }

    protected function post()
    {
        require dirname(__FILE__) . '/post.php';
    }

    /**
     * 删除商品
     */
    public function delete()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);

        if (empty($id)) {
            $id = (is_array($_GPC['ids']) ? implode(',', $_GPC['ids']) : 0);
        }

        $items = pdo_fetchall('SELECT id,title FROM ' . tablename('ewei_business_goods') . ' WHERE id in( ' . $id . ' )');

        foreach ($items as $item) {
            pdo_update('ewei_business_goods', array('deleted' => 1), array('id' => $item['id']));
            plog('goods.delete', '删除商品 ID: ' . $item['id'] . ' 商品名称: ' . $item['title'] . ' ');
        }

        show_json(1, array('url' => referer()));
    }


    /**
     * 修改标签
     */
    public function property()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);
        $type = $_GPC['type'];
        $data = intval($_GPC['data']);

        if (in_array($type, array('new', 'hot', 'recommand', 'discount', 'time', 'sendfree', 'nodiscount'))) {
            pdo_update('ewei_business_goods', array('is' . $type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));

            if ($type == 'new') {
                $typestr = '新品';
            }
            else if ($type == 'hot') {
                $typestr = '热卖';
            }
            else if ($type == 'recommand') {
                $typestr = '推荐';
            }
            else if ($type == 'discount') {
                $typestr = '促销';
            }
            else if ($type == 'time') {
                $typestr = '限时卖';
            }
            else if ($type == 'sendfree') {
                $typestr = '包邮';
            }
            else {
                if ($type == 'nodiscount') {
                    $typestr = '不参与折扣状态';
                }
            }

            plog('goods.edit', '修改商品' . $typestr . '状态   ID: ' . $id);
        }

        if (in_array($type, array('status'))) {
            pdo_update('ewei_business_goods', array($type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));
            plog('goods.edit', '修改商品上下架状态   ID: ' . $id);
        }

        if (in_array($type, array('type'))) {
            pdo_update('ewei_business_goods', array($type => $data), array('id' => $id, 'uniacid' => $_W['uniacid']));
            plog('goods.edit', '修改商品类型   ID: ' . $id);
        }

        show_json(1);
    }

    /**
     * 更改库存
     */
    public function change()
    {
        global $_W;
        global $_GPC;
        $id = intval($_GPC['id']);

        if (empty($id)) {
            show_json(0, array('message' => '参数错误'));
        }
        else {
            pdo_update('ewei_business_goods', array('newgoods' => 0), array('id' => $id));
        }

        $type = trim($_GPC['type']);
        $value = trim($_GPC['value']);

        if (!in_array($type, array('title', 'marketprice','productprice', 'total', 'goodssn', 'productsn', 'displayorder', 'dowpayment'))) {
            show_json(0, array('message' => '参数错误'));
        }

        $goods = pdo_fetch('select id,hasoption,marketprice,dowpayment from ' . tablename('ewei_business_goods') . ' where id=:id and uniacid=:uniacid limit 1', array(':uniacid' => $_W['uniacid'], ':id' => $id));

        if (empty($goods)) {
            show_json(0, array('message' => '参数错误'));
        }

        if ($type == 'dowpayment') {
            if ($goods['marketprice'] < $value) {
                show_json(0, array('message' => '定金不能大于总价'));
            }
        }
        else {
            if ($type == 'marketprice') {
                if ($value < $goods['dowpayment']) {
                    show_json(0, array('message' => '总价不能小于定金'));
                }
            }
        }

        pdo_update('ewei_business_goods', array($type => $value), array('id' => $id));

        if ($goods['hasoption'] == 0) {
            $sql = 'update ' . tablename('ewei_business_goods') . ' set minprice = marketprice,maxprice = marketprice where id = ' . $goods['id'] . ' and hasoption=0;';
            pdo_query($sql);
        }

        show_json(1);
    }
}