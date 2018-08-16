<?php defined('IN_IA') or exit('Access Denied');?><style type="text/css">
    .input-sm{padding:2px;}
</style>

<div class="cover">
    <div class="control control-cover">
        <div class='form-group-title'>加入领券中心</div>
        <div class="form-group user-defined">
            <label  class="col-lg control-label">是否加入</label>
            <div class="col-sm-8 col-xs-12">
                <?php if( ce('sale.wxcard' ,$item) ) { ?>
                <label class="radio-inline">
                    <input type="radio" name="gettype" value="1" <?php  if($item['gettype'] == 1) { ?>checked="true"<?php  } ?> onclick="$('.gettype').show()" /> 可以
                </label>
                <label class="radio-inline">
                    <input type="radio" name="gettype" value="0" <?php  if($item['gettype'] == 0) { ?>checked="true"<?php  } ?>  onclick="$('.gettype').hide()"/> 不可以
                </label>
                <?php  } else { ?>
                    <?php  if($item['gettype']==1) { ?>可以<?php  } else { ?>不可以<?php  } ?>
                <?php  } ?>
                <p class='help-block'>会员是否可以在领券中心直接领取</p>
            </div>
        </div>


        <div class="gettype" <?php  if($item['gettype']!=1) { ?>style="display:none"<?php  } ?>>
            <div class='form-group-title'>版式控制</div>
            <div class="form-group">
                <label  class="col-lg control-label">标签文字</label>
                <div class="col-sm-8 col-xs-12">
                    <?php if( ce('sale.wxcard' ,$item) ) { ?>
                    <input type='text' class='form-control' value="<?php  echo $item['tagtitle'];?>" name='tagtitle' maxlength="8"/>
                    <?php  } else { ?>
                        <?php  echo $item['tagtitle'];?>
                    <?php  } ?>
                    <p class='help-block'>不填写则使用默认文字</p>
                </div>

            </div>
            <div class="form-group">
                <label  class="col-lg control-label">设置标签颜色</label>
                <div class="col-sm-8 col-xs-12">
                    <?php if( ce('sale.coupon' ,$item) ) { ?>
                    <label class="radio-inline">
                        <input type="radio" name="settitlecolor" value="1" <?php  if($item['settitlecolor'] == 1) { ?>checked="true"<?php  } ?> onclick="$('.setcolor').show()" /> 是
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="settitlecolor" value="0" <?php  if($item['settitlecolor'] == 0) { ?>checked="true"<?php  } ?>  onclick="$('.setcolor').hide()"/> 否
                    </label>
                    <?php  } else { ?>
                        <?php  if($item['settitlecolor']==1) { ?>是<?php  } else { ?>否<?php  } ?>
                    <?php  } ?>
                    <span style="display: none" class='error'>会员是否自定义领券中心标签文字的底色,如果不定义则使用默认颜色风格</span>
                </div>
            </div>
            <div class="form-group setcolor"  <?php  if($item['settitlecolor']!=1) { ?>style="display:none"<?php  } ?>>
            <label  class="col-lg control-label">标签颜色</label>
                <div class="col-sm-8 col-xs-12">
                    <?php if( ce('sale.coupon' ,$item) ) { ?>
                    <input  class="color" name="titlecolor" value="<?php  echo $item['titlecolor'];?>" type="color" />
                    <span class="btn btn-default" onclick="$(this).prev().val('#000000').trigger('propertychange')">重置</span>
                    <?php  } else { ?>
                    <span style="width:15px; background: <?php  echo $item['titlecolor'];?>"> </span>
                    <?php  } ?>
                    <span style="display: none" class='error'>会员是否自定义领券中心标签文字的底色,如果不定义则使用默认颜色风格</span>
                </div>
            </div>

            <div class="form-group">
                <label  class="col-lg control-label">限制会员等级</label>
                <div class="col-sm-8 col-xs-12">
                    <?php if( ce('sale.coupon' ,$item) ) { ?>
                    <label class="radio-inline">
                        <input type="radio" name="islimitlevel" value="1" <?php  if($item['islimitlevel'] == 1) { ?>checked="true"<?php  } ?> onclick="$('.islimitlevel').show()" /> 是
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="islimitlevel" value="0" <?php  if($item['islimitlevel'] == 0) { ?>checked="true"<?php  } ?>  onclick="$('.islimitlevel').hide()"/> 否
                    </label>
                    <?php  } else { ?>
                        <?php  if($item['islimit']==1) { ?>是<?php  } else { ?>否<?php  } ?>
                    <?php  } ?>
                    <span style="display: none" class='error'>会员在领券中心直接领取或购买时是否需要达到指定的会员等级,如果不定义则使用默认颜色风格</span>
                </div>

            </div>
        </div>

        <div class="islimitlevel" <?php  if($item['islimitlevel']!=1) { ?>style="display:none"<?php  } ?>>
            <div class='form-group-title'>限制会员等级</div>
            <div class="form-group" >
                <label  class="col-lg control-label">是否全选</label>
                <div class="col-sm-8 col-xs-12">
                    <label class="a-inline" style="line-height: 30px">
                        <a href="javascript:void(0);"  id="btnCheckAll" />全选</a>
                    </label>
                    <label class="a-inline"  style="line-height: 30px">
                        <a  href="javascript:void(0);"  id="btnCheckNone" />反选</a>
                    </label>
                </div>
            </div>

            <!--显示隐藏会员等级限制-->
            <div class="form-group" >
                <label  class="col-lg control-label">会员等级</label>
                <div class="col-sm-8 col-xs-12">
                    <?php if( ce('sale.coupon' ,$item) ) { ?>
                        <label class="checkbox-inline"><input type="checkbox"  class="checkall" name="limitmemberlevels[]" value="0" <?php  if(!empty($limitmemberlevels)&&in_array("0",$limitmemberlevels)) { ?> checked="true"  <?php  } ?>  /> <?php echo empty($shop['levelname'])?'普通等级':$shop['levelname']?></label>
                        <?php  if(is_array($levels)) { foreach($levels as $level) { ?>
                            <label class="checkbox-inline"><input type="checkbox"  class="checkall" name="limitmemberlevels[]" value="<?php  echo $level['id'];?>"  <?php  if(!empty($limitmemberlevels)&&in_array( $level['id'] , $limitmemberlevels)) { ?> checked="true"  <?php  } ?>  /> <?php  echo $level['levelname'];?></label>
                        <?php  } } ?>
                    <?php  } else { ?>
                        <?php  if(!empty($limitmemberlevels)&&in_array("0",$limitmemberlevels)) { ?> <?php echo  empty($shop['levelname'])?'普通等级':$shop['levelname']?>  <?php  } ?>&nbsp;&nbsp;
                        <?php  if(is_array($levels)) { foreach($levels as $level) { ?>
                            <?php  if(!empty($limitmemberlevels)&&in_array($level['id'] , $limitmemberlevels)) { ?>
                                <?php  echo $level['levelname']?>&nbsp;&nbsp;
                            <?php  } ?>
                        <?php  } } ?>
                    <?php  } ?>
                </div>
            </div>

            <!--分销商等级限制-->
            <?php  if($hascommission) { ?>
                <div class="form-group" >
                    <label  class="col-lg control-label">分销商等级</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php if( ce('sale.coupon' ,$item) ) { ?>
                            <label class="checkbox-inline"><input type="checkbox"  class="checkall" name="limitagentlevels[]" value="0" <?php  if(!empty($limitagentlevels)&&in_array("0",$limitagentlevels)) { ?> checked="true"  <?php  } ?>  /> <?php echo empty($plugin_com_set['levelname'])?'普通等级':$plugin_com_set['levelname']?></label>
                            <?php  if(is_array($agentlevels)) { foreach($agentlevels as $level) { ?>
                                <label class="checkbox-inline"><input type="checkbox"  class="checkall" name="limitagentlevels[]" value="<?php  echo $level['id'];?>"  <?php  if(!empty($limitagentlevels)&&in_array( $level['id'] , $limitagentlevels)) { ?> checked="true"  <?php  } ?>  /> <?php  echo $level['levelname'];?></label>
                            <?php  } } ?>
                        <?php  } else { ?>
                            <?php  if(!empty($limitagentlevels)&&in_array("0",$limitagentlevels)) { ?> <?php echo empty($plugin_com_set['levelname'])?'普通等级':$plugin_com_set['levelname']?>  <?php  } ?>&nbsp;&nbsp;
                            <?php  if(is_array($agentlevels)) { foreach($agentlevels as $level) { ?>
                                <?php  if(!empty($limitagentlevels)&&in_array( $level['id'] , $limitagentlevels)) { ?>
                                    <?php  echo $level['levelname']?>&nbsp;&nbsp;
                                <?php  } ?>
                            <?php  } } ?>
                        <?php  } ?>
                    </div>
                </div>
            <?php  } ?>

            <!--是否开启人人股东-->
            <?php  if($hasglobonus) { ?>
                <div class="form-group" >
                    <label  class="col-lg control-label">股东等级</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php if( ce('sale.coupon' ,$item) ) { ?>
                            <label class="checkbox-inline"><input type="checkbox"  class="checkall" name="limitpartnerlevels[]" value="0" <?php  if(!empty($limitpartnerlevels)&&in_array("0",$limitpartnerlevels)) { ?> checked="true"  <?php  } ?>  /> <?php echo empty($plugin_globonus_set['levelname'])?'普通等级':$plugin_globonus_set['levelname']?></label>
                            <?php  if(is_array($partnerlevels)) { foreach($partnerlevels as $level) { ?>
                                <label class="checkbox-inline"><input type="checkbox"  class="checkall" name="limitpartnerlevels[]" value="<?php  echo $level['id'];?>"  <?php  if(!empty($limitpartnerlevels)&&in_array( $level['id'] , $limitpartnerlevels)) { ?> checked="true"  <?php  } ?>  /> <?php  echo $level['levelname'];?></label>
                            <?php  } } ?>
                        <?php  } else { ?>
                            <?php  if(!empty($limitpartnerlevels)&&in_array("0",$limitpartnerlevels)) { ?> <?php echo empty($plugin_globonus_set['levelname'])?'普通等级':$plugin_globonus_set['levelname']?>  <?php  } ?>&nbsp;&nbsp;
                            <?php  if(is_array($partnerlevels)) { foreach($partnerlevels as $level) { ?>
                                <?php  if(!empty($limitpartnerlevels)&&in_array( $level['id'] , $limitpartnerlevels)) { ?>
                                    <?php  echo $level['levelname']?>&nbsp;&nbsp;
                                <?php  } ?>
                            <?php  } } ?>
                        <?php  } ?>
                    </div>
                </div>
            <?php  } ?>

            <!--是否开启区域代理-->
            <?php  if($hasabonus) { ?>
                <div class="form-group" >
                    <label  class="col-lg control-label">区域代理等级</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php if( ce('sale.coupon' ,$item) ) { ?>
                            <label class="checkbox-inline"><input type="checkbox"  class="checkall"  name="limitaagentlevels[]" value="0" <?php  if(!empty($limitaagentlevels)&&in_array("0",$limitaagentlevels)) { ?> checked="true"  <?php  } ?>  /> <?php echo empty($plugin_abonus_set['levelname'])?'普通等级':$plugin_abonus_set['levelname']?></label>
                            <?php  if(is_array($aagentlevels)) { foreach($aagentlevels as $level) { ?>
                                <label class="checkbox-inline"><input type="checkbox"   class="checkall" name="limitaagentlevels[]" value="<?php  echo $level['id'];?>"  <?php  if(!empty($limitaagentlevels)&&in_array( $level['id'] , $limitaagentlevels)) { ?> checked="true"  <?php  } ?>  /> <?php  echo $level['levelname'];?></label>
                            <?php  } } ?>
                        <?php  } else { ?>
                        <?php  if(!empty($limitaagentlevels)&&in_array("0",$limitaagentlevels)) { ?> <?php echo empty($plugin_abonus_set['levelname'])?'普通等级':$plugin_abonus_set['levelname']?>  <?php  } ?>&nbsp;&nbsp;
                            <?php  if(is_array($aagentlevels)) { foreach($aagentlevels as $level) { ?>
                                <?php  if(!empty($limitaagentlevels)&&in_array( $level['id'] , $limitaagentlevels)) { ?>
                                    <?php  echo $level['levelname']?>&nbsp;&nbsp;
                                <?php  } ?>
                            <?php  } } ?>
                        <?php  } ?>
                    </div>
                </div>
            <?php  } ?>
        </div>
    </div>
</div>

<script language='javascript'>
    $("#btnCheckAll").bind("click", function () {
        $(".checkall").prop("checked",true);
    });

    $("#btnCheckNone").bind("click", function () {
        $(".checkall").prop("checked",false);
    });


</script>
