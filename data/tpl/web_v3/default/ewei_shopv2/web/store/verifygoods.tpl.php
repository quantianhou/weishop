<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class="page-header">当前位置： <span class="text-primary">商品核销管理</span> </div>
<div class="page-content">
    <form action="./index.php" method="get" class="form-horizontal" role="form" id="form1">
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="m" value="ewei_shopv2" />
        <input type="hidden" name="do" value="web" />
        <input type="hidden" name="r" value="store.verifygoods" />
        <div class="page-toolbar">
            <div class="col-sm-6" >
                <div class="input-group " >
                    <?php  echo tpl_daterange('buydate', array('sm'=>true,'placeholder'=>'购买时间'),true);?>
                </div>
            </div>
            <div class="input-group " >
                <div class="input-group-select">
                    <select name='searchfield'  class='form-control  input-sm select-md'    >
                        <option value='goodtitle' <?php  if($_GPC['searchfield']=='goodtitle') { ?>selected<?php  } ?>>商品名称</option>
                        <option value='ordersn' <?php  if($_GPC['searchfield']=='ordersn') { ?>selected<?php  } ?>>订单号</option>
                        <option value='verifyid' <?php  if($_GPC['searchfield']=='verifyid') { ?>selected<?php  } ?>>核销ID</option>
                        <option value='store' <?php  if($_GPC['searchfield']=='store') { ?>selected<?php  } ?>>所属门店</option>
                    </select>
                </div>
                <input type="text" class="form-control input-sm"  name="keyword" value="<?php  echo $_GPC['keyword'];?>" placeholder="请输入关键词" />
                    <span class="input-group-btn">
                    <button class="btn btn-primary" type="submit"> 搜索</button>
                </span>
            </div>
        </div>

    </form>
    <?php  if(count($list)>0) { ?>
    <table class="table table-responsive">
        <thead class="navbar-inner">
        <tr>
            <th style='width:70px;'>核销ID</th>
            <th style=';'>记次时商品信息</th>
            <th style='width:190px;'>订单单号</th>
            <th style='width:70px;'>总次数</th>
            <th style='width:120px;'>购买日期</th>
            <th style='width:65px;'>剩余次数</th>
            <th style='width:80px;'>可用门店</th>
            <th style='width:70px;'>是否有效</th>
            <th style='width:115px;'>操作</th>
        </tr>
        </thead>
        <tbody>
        <?php  if(is_array($list)) { foreach($list as $item) { ?>
        <tr>
            <td><?php  echo $item['id'];?></td>
            <td>
                <img src="<?php  echo tomedia($item['thumb'])?>" style="width:30px;height:30px;padding1px;border:1px solid #ccc" onerror="this.src='../addons/ewei_shopv2/static/images/nopic.png'"
                />
                <?php  echo $item['title'];?>
            </td>
            <td><?php  echo $item['ordersn'];?></td>
            <td>
                <?php  if(empty($item['limitnum'])) { ?>不限 <?php  } else { ?><?php  echo intval($item['limitnum'])?><?php  } ?>

            </td>
            <td><?php  echo date('Y-m-d',$item['paytime'])?></td>
            <td>

                <?php  if(empty($item['limitnum'])) { ?>不限 <?php  } else { ?><?php  echo intval($item['limitnum']) - intval($item['verifynum'])?><?php  } ?>

            </td>
            <td>
                <?php  if(empty($item['storename'])) { ?>全部门店 <?php  } else { ?><?php  echo $item['storename'];?><?php  } ?>
            </td>
            <td>
                <?php  if(empty($item['invalid'])) { ?>可用 <?php  } else { ?>失效<?php  } ?>
            </td>
            <td>

                <?php if(cv('store.verifygoods.view|store.verifygoods.edit')) { ?>
                <a  class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('store/verifygoods/detail', array('id' => $item['id']))?>">
                  <span data-toggle="tooltip" data-placement="top" title="" data-original-title="编辑">
                         <i class='icow icow-bianji2'></i>
                    </span>
                </a>
                <?php  } ?>

                <?php if(cv('store.verifygoods.view|store.verifygoods.edit')) { ?>
                <a  class='btn btn-default btn-sm btn-op btn-operation' href="<?php  echo webUrl('store/verifygoods/verifygoodslog', array('id' => $item['id']))?>" >
                     <span data-toggle="tooltip" data-placement="top" title="" data-original-title="核销记录管理">
                         <i class='icow icow-zhongdiangongchengzongliangluru'></i>
                    </span>
                </a>
                <?php  } ?>

                <?php if(cv('store.verifygoods.edit')) { ?>
                    <?php  if(empty($item['invalid'])) { ?>
                    <a  class='btn btn-default btn-sm btn-op btn-operation' data-toggle='ajaxRemove' href="<?php  echo webUrl('store/verifygoods/invalid', array('id' => $item['id'],'type'=>1))?>" data-confirm='将此商品状态设置为失效？ 失效后用户将无法核销此商品'>
                        <span data-toggle="tooltip" data-placement="top" title="" data-original-title="关闭">
                             <i class='icow icow-jinyong'></i>
                        </span>
                    </a>
                    <?php  } else { ?>
                    <a  class='btn btn-default btn-sm btn-op btn-operation' data-toggle='ajaxRemove' href="<?php  echo webUrl('store/verifygoods/invalid', array('id' => $item['id'],'type'=>0))?>" data-confirm='将此商品状态设置为有效？ 用户可用继续进行该商品的核销'>
                        <span data-toggle="tooltip" data-placement="top" title="" data-original-title="启用">
                             <i class='icow icow-qiyong'></i>
                        </span>
                    </a>
                    <?php  } ?>
                <?php  } ?>

            </td>
        </tr>
        <?php  } } ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="text-right"><?php  echo $pager;?></td>
            </tr>
        </tfoot>
    </table>
<?php  } else { ?>
    <div class='panel panel-default'>
        <div class='panel-body' style='text-align: center;padding:30px;'>
            暂时没有任何商品
        </div>
    </div>
<?php  } ?>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
