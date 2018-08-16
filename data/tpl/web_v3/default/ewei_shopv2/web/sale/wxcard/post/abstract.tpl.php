<?php defined('IN_IA') or exit('Access Denied');?><div class="cover">


    <div class="control control-cover">
        <div class='form-group-title'>添加卡券元素</div>
        <div class="control-group">
            <a class="btn btn-default" href="javascript:void(0);"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } else { ?>id="addabstract" <?php  } ?><?php  } else { ?>disabled<?php  } ?> >添加封面内容</a>
            <a class="btn btn-default" href="javascript:void(0);" id="addcustom">添加自定义入口1</a>
            <a class="btn btn-default" href="javascript:void(0);" id="addpromotion">添加自定义入口2</a>

        </div>

        <div class='form-group-title'>卡券顶部按钮</div>
        <!--入口名称-->
        <div class="form-group user-defined">
            <label  class="col-lg control-label">按钮文字</label>
            <div class="col-sm-9 col-xs-12">
                <input id="center_title" name="center_title" type="text" maxlength="5" class="input form-control" value="<?php  echo $item['center_title'];?>" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?> />
                <p class='help-block'>(不能超过5个字符)</p>
                <span style="display: none" class='error'>卡券顶部按钮文字不能为空</span>
            </div>

        </div>
        <!--引导语-->
        <div class="form-group">
            <label  class="col-lg control-label">引导语</label>
            <div class="col-sm-9 col-xs-12">
                <input id="center_sub_title" name="center_sub_title" type="text" maxlength="6" class="input form-control"  value="<?php  echo $item['center_sub_title'];?>"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?> />
                <p class='help-block'>(不能超过6个字符)</p>
            </div>
        </div>
        <!--网页链接-->
        <div class="form-group">
            <label  class="col-lg control-label">网页链接</label>
            <div class="col-sm-9 col-xs-12">
                <div class="input-group">
                    <input type="text" id="center_url" name="center_url" class='input form-control' value="<?php  echo $item['center_url'];?>"/>
                    <div class="input-group-btn">
                        <span data-input="#center_url" data-toggle="selectUrl" class="btn btn-default " data-full="true">选择链接</span>
                    </div>
                </div>
                <p class='help-block'>仅卡券被用户领取且处于有效状态时显示（未到有效期、转赠中、核销后不显示）。</p>
            </div>

        </div>

        <div id="showsetcustom" <?php  if(empty($isedit)||$item['setcustom']==0) { ?>style="display: none"<?php  } ?>>
            <div class='form-group-title'>自定义入口1<?php if( ce('sale.wxcard' ,$item) ) { ?><a id="delcustom" class="pull-right">删除</a><?php  } ?></div>
            <!--入口名称-->
            <div class="form-group user-defined">
                <input type="hidden" id="setcustom" name="setcustom" value=""/>
                <label  class="col-lg control-label">入口名称</label>
                <div class="col-sm-9 col-xs-12">
                    <input id="custom_url_name" name="custom_url_name" type="text" maxlength="5" class="input form-control"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?>
                    value="<?php  echo $item['custom_url_name'];?>"/>
                    <p class='help-block'>(不能超过5个字符)</p>
                    <span style="display: none" class='error'>自定义入口名称不能为空</span>
                </div>
            </div>
            <!--引导语-->
            <div class="form-group">
                <label  class="col-lg control-label">引导语</label>
                <div class="col-sm-9 col-xs-12">
                    <input id="custom_url_sub_title" name="custom_url_sub_title" type="text" maxlength="6" class="input form-control"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?>
                    value="<?php  echo $item['custom_url_sub_title'];?>"/>
                    <p class='help-block'>(不能超过6个字符)</p>
                </div>
            </div>
            <!--网页链接-->
            <div class="form-group">
                <label  class="col-lg control-label">网页链接</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="input-group">
                        <input class="form-control" type="text" id="custom_url" name="custom_url"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?> value="<?php  echo $item['custom_url'];?>"  class='input'/>
                        <div class="input-group-btn">
                            <span data-input="#custom_url" data-toggle="selectUrl" class="btn btn-default" data-full="true">选择链接</span>
                        </div>
                    </div>
                    <p class='help-block'>仅卡券被用户领取且处于有效状态时显示（转赠中、核销后不显示）。</p>
                </div>
            </div>

        </div>


        <div id="showsetpromotion"   <?php  if(empty($isedit)||$item['setpromotion']==0) { ?>style="display: none"<?php  } ?>>
            <div class='form-group-title'>自定义入口2<?php if( ce('sale.wxcard' ,$item) ) { ?><a id="delpromotion" class="pull-right">删除</a><?php  } ?></div>
            <!--入口名称-->
            <div class="form-group user-defined">
                <input type="hidden" id="setpromotion" name="setpromotion" value=""/>
                <label  class="col-lg control-label">入口名称</label>
                <div class="col-sm-9 col-xs-12">
                    <input id="promotion_url_name" name="promotion_url_name" type="text" maxlength="5" class="input form-control"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?>
                    value="<?php  echo $item['promotion_url_name'];?>"/>
                    <p class='help-block'>(不能超过5个字符)</p>
                    <span style="display: none" class='error'>自定义入口名称不能为空</span>
                </div>

            </div>
            <!--引导语-->
            <div class="form-group">
                <label  class="col-lg control-label">引导语</label>
                <div class="col-sm-9 col-xs-12">
                    <input id="promotion_url_sub_title" name="promotion_url_sub_title" type="text" maxlength="6"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?>
                    class="input form-control"   value="<?php  echo $item['promotion_url_sub_title'];?>"/>
                    <p class='help-block'>(不能超过6个字符)</p>
                </div>
            </div>
            <!--网页链接-->
            <div class="form-group">
                <label  class="col-lg control-label">网页链接</label>
                <div class="col-sm-9 col-xs-12">
                    <div class="input-group">
                        <input type="text" id="promotion_url" name="promotion_url"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?> value="<?php  echo $item['promotion_url'];?>"   class='input form-control'/>
                        <div class="input-group-btn">
                            <span data-input="#promotion_url" data-toggle="selectUrl" class="btn btn-default" data-full="true">选择链接</span>
                        </div>
                    </div>
                    <p class='help-block'>卡券处于正常状态、转赠中、核销后等异常状态均显示该入口。</p>
                </div>
            </div>

        </div>

        <div id="showsetabstract"   <?php  if(empty($isedit)||$item['setabstract']==0) { ?>style="display: none"<?php  } ?>>
            <div class='form-group-title'>封面 <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(empty($isedit)) { ?><a id="delabstract" class="pull-right">删除</a><?php  } ?><?php  } ?> </div>
            <!--封面图片-->
            <div class="form-group">
                <label  class="col-lg control-label">封面图片</label>
                <div class="col-sm-9 col-xs-12">
                    <input type="hidden" name="setabstract" id="setabstract" value="<?php  echo $item['setabstract'];?>"/>
                    <input type="hidden" name="abstractimgurl" id="abstractimgurl" value="<?php  echo $item['abstractimg'];?>"/>
                    <input type="hidden" name="abstractimglocalpath" id="abstractimglocalpath" value=""/>
                    <!--微信图片上传路径-->
                    <a class="btn btn-default" href="javascript:void(0);"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } else { ?>id="upabstractimg"<?php  } ?><?php  } else { ?>disabled<?php  } ?>>上传图片</a>
                    <p class='help-block'>图片建议尺寸：850像素*350像素，大小不超过2M。</p>
                </div>


            </div>

            <!--封面简介-->
            <div class="form-group cover" style="margin-bottom: 60px">
                <label  class="col-lg control-label">封面简介</label>
                <div class="col-sm-9 col-xs-12">
                    <input id="abstract" name="abstract" type="text" maxlength="12" class="input form-control" value="<?php  echo $item['abstract'];?>"   <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?>/>
                    <p style="display: none" class='error'>简介不能为空且长度不超过12个汉字</p>
                    <p class='help-block'>封面一旦创建完成则不允许修改,请注意!</p>
                </div>
            </div>

        </div>
    </div>
</div>
