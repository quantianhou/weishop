<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">
    当前位置：<span class="text-primary"><?php  if(!empty($item['id'])) { ?>编辑<?php  } else { ?>添加<?php  } ?>首页导航<?php  if(!empty($item['id'])) { ?>(<?php  echo $item['navname'];?>)<?php  } ?></span>
</div>

<div class="page-content">
    <div class="page-sub-toolbar">
        <span class=''>
            <?php if(cv('shop.nav.add')) { ?>
                <a class="btn btn-primary btn-sm" href="<?php  echo webUrl('shop/nav/add')?>">添加新首页导航</a>
            <?php  } ?>
        </span>
    </div>
    <form <?php if( ce('shop.nav' ,$item) ) { ?>action="" method="post"<?php  } ?> class="form-horizontal form-validate" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
            <div class="form-group">
                <label class="col-lg control-label">排序</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('shop.nav' ,$item) ) { ?>
                        <input type="text" name="displayorder" class="form-control" value="<?php  echo $item['displayorder'];?>" />
                        <span class='help-block'>数字越大，排名越靠前</span>
                    <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $item['displayorder'];?></div>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label must">首页导航标题</label>
                <div class="col-sm-9 col-xs-12 ">
                    <?php if( ce('shop.nav' ,$item) ) { ?>
                        <input type="text" id='navname' name="navname" class="form-control" value="<?php  echo $item['navname'];?>" data-rule-required="true"/>
                    <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $item['navname'];?></div>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">首页导航图片</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('shop.nav' ,$item) ) { ?>
                        <?php  echo tpl_form_field_image2('icon', $item['icon'])?>
                        <span class='help-block'>建议尺寸:100 * 100 , 请将所有首页导航图片尺寸保持一致</span>
                    <?php  } else { ?>
                        <?php  if(!empty($item['icon'])) { ?>
                            <a href='<?php  echo tomedia($item[' icon'])?>' target='_blank'>
                                <img src="<?php  echo tomedia($item['icon'])?>" style='width:100px;border:1px solid #ccc;padding:1px'/>
                            </a>
                        <?php  } ?>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">首页导航链接</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('shop.nav' ,$item) ) { ?>
                        <div class="input-group form-group" style="margin: 0;">
                            <input type="text" value="<?php  echo $item['url'];?>" class="form-control valid" name="url" placeholder="" id="navlink">
                            <span class="input-group-btn">
                                <span data-input="#navlink" data-toggle="selectUrl" class="btn btn-default">选择链接</span>
                            </span>
                        </div>
                    <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $item['url'];?></div>
                    <?php  } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg control-label">状态</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('shop.nav' ,$item) ) { ?>
                        <label class='radio-inline'><input type='radio' name='status' value=1' <?php  if($item['status']==1) { ?>checked<?php  } ?> /> 显示</label>
                        <label class='radio-inline'><input type='radio' name='status' value=0' <?php  if($item['status']==0) { ?>checked<?php  } ?> /> 隐藏</label>
                    <?php  } else { ?>
                        <div class='form-control-static'><?php  if(empty($item['status'])) { ?>隐藏<?php  } else { ?>显示<?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg control-label"></label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('shop.nav' ,$item) ) { ?>
                        <input type="submit" value="提交" class="btn btn-primary"/>
                    <?php  } ?>
                    <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.nav.add|shop.nav.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
                </div>
            </div>
    </form>
</div>

<script language='javascript'>
    function formcheck() {
        if ($("#navname").isEmpty()) {
            Tip.focus("navname", "请填写首页导航名称!");
            return false;
        }
        return true;
    }
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--efwww_com-->