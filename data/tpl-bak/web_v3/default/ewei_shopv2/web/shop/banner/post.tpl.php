<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<div class="page-header">
    当前位置：<span class="text-primary"><?php  if(!empty($item['id'])) { ?>编辑<?php  } else { ?>添加<?php  } ?>广告<?php  if(!empty($item['id'])) { ?>(<?php  echo $item['bannername'];?>)<?php  } ?></span>
</div>

<div class="page-content">
    <div class="page-sub-toolbar">
        <span class=''>
            <?php if(cv('shop.banner.add')) { ?>
                <a class="btn btn-primary btn-sm" href="<?php  echo webUrl('shop/banner/add')?>">添加新广告</a>
            <?php  } ?>
        </span>
    </div>
    <form <?php if( ce('shop.banner' ,$item) ) { ?>action="" method="post"<?php  } ?> class="form-horizontal form-validate" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>" id="item_id"/>
        <div class="form-group">
            <label class="col-lg control-label">排序</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.banner' ,$item) ) { ?>
                    <input type="text" name="displayorder" class="form-control" value="<?php  echo $item['displayorder'];?>" />
                    <span class='help-block'>数字越大，排名越靠前</span>
                <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['displayorder'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label must">广告标题</label>
            <div class="col-sm-9 col-xs-12 ">
                <?php if( ce('shop.banner' ,$item) ) { ?>
                    <input type="text" id='bannername' name="bannername" class="form-control" value="<?php  echo $item['bannername'];?>" data-rule-required="true" />
                <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['bannername'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">广告图片</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.banner' ,$item) ) { ?>
                    <?php  echo tpl_form_field_image2('thumb', $item['thumb'])?>
                    <span class='help-block'>建议尺寸:640 * 350 , 请将所有广告图片尺寸保持一致</span>
                <?php  } else { ?>
                    <?php  if(!empty($item['thumb'])) { ?>
                        <a href='<?php  echo tomedia($item['thumb'])?>' target='_blank'>
                            <img src="<?php  echo tomedia($item['thumb'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
                        </a>
                    <?php  } ?>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">广告链接</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.banner' ,$item) ) { ?>
                    <div class="input-group form-group" style="margin: 0;">
                        <input type="text" value="<?php  echo $item['link'];?>" class="form-control valid" name="link" placeholder="" id="bannerlink">
                        <span class="input-group-btn">
                            <span data-input="#bannerlink" data-toggle="selectUrl" class="btn btn-default">选择链接</span>
                        </span>
                    </div>
                <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['link'];?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">状态</label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.banner' ,$item) ) { ?>
                    <label class='radio-inline'><input type='radio' name='enabled' value=1' <?php  if($item['enabled']==1) { ?>checked<?php  } ?> /> 显示</label>
                    <label class='radio-inline'><input type='radio' name='enabled' value=0' <?php  if($item['enabled']==0) { ?>checked<?php  } ?> /> 隐藏</label>
                <?php  } else { ?>
                    <div class='form-control-static'><?php  if(empty($item['enabled'])) { ?>隐藏<?php  } else { ?>显示<?php  } ?></div>
                <?php  } ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label">店铺</label>
            <!--<div class="col-sm-9 col-xs-12">-->d
            <!--<select class="form-control tpl-category-parent select2" id="storeid" name="storeid">-->
            <!--<option value="0">全部店铺</option>-->
            <?php  if(is_array($store)) { foreach($store as $store_item) { ?>
            <!--<option value="<?php  echo $store_item['id'];?>" <?php  if($item['storeid'] == $store_item['id']) { ?>selected="true"<?php  } ?>><?php  echo $store_item['storename'];?></option>-->
            <?php  } } ?>
            <!--</select>-->
            <!--</div>-->
            <div class="col-sm-9 col-xs-12">
                <table>
                    <tbody><tr>
                        <td>
                            <div id="allBranchDiv" class="box" style="width: 350px; height: 450px; border: 1px solid #F8F8F8;overflow:auto;background:#F8F8F8">
                                <div class="box-header well" style="background:#F8F8F8;border: 0px;">
                                    <input type="hidden" id="allBranchUrl" name="allBranchUrl" value="<?php  echo webUrl('shop/store/me')?>">
                                    <input type="hidden" id="selectedBranchUrl" name="selectedBranchUrl" value="<?php  echo webUrl('shop/banner/me')?>">
                                    <input id="branchName" name="branchName" size="16" type="text" value="" style="width:180px;border: 1px solid #cccccc;background-color: #fff;border-radius:3px; height: 30px; line-height: 30px;">
                                    <button type="button" onclick="searchBranch()" class="btn btn-primary btn-sm" style="margin-top: 5px;">查 询</button>
                                </div>
                                <div class="box-content">
                                    <div class="row-fluid">
                                        <div style="overflow:auto;height: 400px;background:#F8F8F8; ">
                                            <ul id="ulId" class="allBranch">

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <img src="<?php  echo EWEI_SHOPV2_LOCAL?>static/images/move.png">
                        </td>
                        <td>
                            <div id="selectedBranchDiv" class="box" style="width: 350px; height: 450px; border: 1px solid #F8F8F8;background:#F8F8F8">
                                <div class="box-header well" style="background:#F8F8F8;border: 0px">
                                    <span style="color: blue;margin-left: 85%;cursor:pointer" onclick="cleanSelected()">清 空</span>
                                </div>
                                <div class="box-content">
                                    <div class="row-fluid">
                                        <div style="overflow:auto;height: 400px;background:#F8F8F8 ">
                                            <ul id="selectUlId" class="selectBranch">
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody></table>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg control-label"></label>
            <div class="col-sm-9 col-xs-12">
                <?php if( ce('shop.banner' ,$item) ) { ?>
                    <input type="submit" value="提交" class="btn btn-primary"/>
                <?php  } ?>
                <input type="button" name="back" onclick='history.back()' <?php if(cv('shop.banner.add|shop.banner.edit')) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default"/>
            </div>
        </div>
    </form>

</div>
<script src="<?php  echo EWEI_SHOPV2_LOCAL?>static/js/store.js?_V=3"></script>
<script>
    $(document).ready(function () {
        var name = $("#branchName").val();
        getBranch(true,name);
    })
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
<!--efwww_com54mI5p2D5omA5pyJ-->