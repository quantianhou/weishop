<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<style type="text/css">
    .table_kf {display: none;}
    .table_kf.active {display: table-row-group;}
</style>
<div class="page-header">当前位置：<span class="text-primary">赠品管理</span></div>

<div class="page-content">
    <form action="./index.php" method="get" class="form-horizontal form-search" role="form" >
        <input type='hidden' id='tab' name='type' value="<?php  echo $_GPC['type'];?>"/>
        <input type="hidden" name="c" value="site" />
        <input type="hidden" name="a" value="entry" />
        <input type="hidden" name="m" value="ewei_shopv2" />
        <input type="hidden" name="do" value="web" />
        <input type="hidden" name="r"  value="sale.gift" />
        <input type="hidden" name="goodsfrom" value="<?php  echo $goodsfrom;?>" />

        <div class="page-toolbar" style="margin-bottom: 0;">
            <span class='col-sm-3'>
                <?php if(cv('sale.gift.add')) { ?>
                <a class='btn btn-primary btn-sm' href="<?php  echo webUrl('sale/gift/add',array('type'=>$type))?>"><i class='fa fa-plus'></i> 添加赠品</a>
                <?php  } ?>
            </span>
            <div class="col-sm-6 pull-right">
                <div class="input-group">
                    <input type="text" class="input-sm form-control" name='keyword' value="<?php  echo $_GPC['keyword'];?>" placeholder="赠品名称"> <span class="input-group-btn">
                <button class="btn btn-primary" type="submit"> 搜索</button> </span>
                </div>
            </div>
        </div>

        <ul class="nav nav-arrow-next nav-tabs" id="myTab">
            <li class="<?php  if(empty($_GPC['type'])) { ?>active<?php  } ?>" >
                <a href="<?php  echo webUrl('sale/gift')?>">所有赠品</a>
            </li>
            <li class="<?php  if($_GPC['type']=='ing') { ?>active<?php  } ?>" >
                <a href="<?php  echo webUrl('sale/gift',array('type'=>'ing'))?>">进行中</a>
            </li>
            <li class="<?php  if($_GPC['type']=='none') { ?>active<?php  } ?>" >
                <a href="<?php  echo webUrl('sale/gift',array('type'=>'none'))?>">未开始</a>
            </li>
            <li class="<?php  if($_GPC['type']=='end') { ?>active<?php  } ?>">
                <a href="<?php  echo webUrl('sale/gift',array('type'=>'end'))?>">已结束</a>
            </li>
        </ul>
    </form>
    <?php  if(count($gifts)>0) { ?>
    <form action="" method="post">
        <div class="page-table-header" style="border: none;">
            <input type='checkbox' />
            <div class="btn-group">
                <?php if(cv('sale.gift.edit')) { ?>
                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch'  data-href="<?php  echo webUrl('sale/gift/status',array('status'=>0))?>">
                    <i class="icow icow-xiajia3"></i> 下架
                </button>
                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('sale/gift/status',array('status'=>1))?>">
                    <i class="icow icow-shangjia2"></i> 上架
                </button>
                <?php  } ?>
                <?php if(cv('sale.gift.delete1')) { ?>
                <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="如果商品存在购买记录，会无法关联到商品, 确认要彻底删除吗?" data-href="<?php  echo webUrl('sale/gift/delete1')?>">
                    <i class='icow icow-shanchu1'></i> 删除
                </button>
                <?php  } ?>
            </div>
        </div>
        <table class="table table-hover table-responsive">
            <thead class="navbar-inner">
            <tr>
                <th style="width:25px;"></th>
                <th style="width:60px;text-align:center;">排序</th>
                <th style="">赠品名称</th>
                <th  style="">&nbsp;</th>
                <th  style="width:60px;" >状态</th>
                <th style="width:65px;text-align: center;">操作</th>
            </tr>
            </thead>
            <tbody class=" table_kf <?php  if($_GPC['type']=='all' || empty($_GPC['type'])) { ?>active<?php  } ?>" id="tab_all"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/gift/list', TEMPLATE_INCLUDEPATH)) : (include template('sale/gift/list', TEMPLATE_INCLUDEPATH));?></tbody>
            <tbody class=" table_kf <?php  if($_GPC['type']=='ing') { ?>active<?php  } ?>" id="tab_ing"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/gift/list', TEMPLATE_INCLUDEPATH)) : (include template('sale/gift/list', TEMPLATE_INCLUDEPATH));?></tbody>
            <tbody class=" table_kf <?php  if($_GPC['type']=='none') { ?>active<?php  } ?>" id="tab_none"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/gift/list', TEMPLATE_INCLUDEPATH)) : (include template('sale/gift/list', TEMPLATE_INCLUDEPATH));?></tbody>
            <tbody class=" table_kf <?php  if($_GPC['type']=='end') { ?>active<?php  } ?>" id="tab_end"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/gift/list', TEMPLATE_INCLUDEPATH)) : (include template('sale/gift/list', TEMPLATE_INCLUDEPATH));?></tbody>
            <tfoot>
                <tr>
                    <td><input type="checkbox"></td>
                    <td colspan="2">
                        <div class="btn-group">
                            <?php if(cv('sale.gift.edit')) { ?>
                            <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch'  data-href="<?php  echo webUrl('sale/gift/status',array('status'=>0))?>">
                                <i class="icow icow-xiajia3"></i> 下架
                            </button>
                            <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch' data-href="<?php  echo webUrl('salse/gift/status',array('status'=>1))?>">
                                <i class="icow icow-shangjia2"></i> 上架
                            </button>
                            <?php  } ?>
                            <?php if(cv('sale.gift.delete1')) { ?>
                            <button class="btn btn-default btn-sm btn-operation" type="button" data-toggle='batch-remove' data-confirm="如果商品存在购买记录，会无法关联到商品, 确认要彻底删除吗?" data-href="<?php  echo webUrl('sale/gift/delete1')?>">
                                <i class='icow icow-shanchu1'></i> 删除
                            </button>
                            <?php  } ?>
                        </div>
                    </td>
                    <td style="text-align: right" colspan="3">
                        <?php  echo $pager;?>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
    <?php  } else { ?>
    <div class="panel panel-default">
        <div class="panel-body empty-data">暂时没有任何赠品</div>
    </div>
    <?php  } ?>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>

