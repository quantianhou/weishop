<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<link href="/favicon.ico" rel="shortcut icon" />
<link rel="stylesheet" type="text/css" href="../addons/ewei_shopv2/template/web_v3/sale/wxcard/css/iconfont.css"/>
<link rel="stylesheet" type="text/css" href="../addons/ewei_shopv2/template/web_v3/sale/wxcard/css/index.css"/>

<script type="text/javascript" src="../addons/ewei_shopv2/static/js/app/biz/sale/wxcard/colorpalette.js" charset="utf-8"></script>

<div class="page-header">
    当前位置：<span class="text-primary">添加<?php  if($type==0) { ?>代金券<?php  } else if($type==1) { ?>折扣券<?php  } ?></span>
</div>

<div class="page-content" style="display: flex;display: -webkit-box;display: -webkit-flex;display: -ms-flexbox;display: flex;">
    <div id="bgcolor" class="preview Color010" style="height: 1%;">
        <div class="title">
            <i class="back iconfont">&#xe7e0;</i>
            <i class="more pull-right iconfont">&#xe7e3;</i>
        </div>
        <div class="panel">

            <!--商户信息-->
            <div class="logo-area">
                <div class="logo">
                    <img id="showlogo" src="<?php echo  empty($item)?'':$item['logo_url']?>"/>
                </div>
                <p class="name" id="showbrand_name"><?php  echo $item['brand_name'];?>
                </p>
            </div>
            <!--卡券标题-->
            <div class="card">
                <p id="showtitle" class="card-title"><?php  echo $item['title'];?></p>
                <div id="btnuse" class='btn Color010'>使用</div>
            </div>
            <!--使用条件-->
            <div class="card_usage">
                <ul>
                    <li id="showuselimit" style="display: none;"><em>使用条件：</em><span id="showlimittext"></span></li>
                    <li><em>可用时间：</em><span id="showbeginendtime"></span></li>
                </ul>
            </div>

            <!--卡券封面-->
            <div class="card-cover" id="showallabstract"   <?php  if(empty($item['setabstract'])) { ?>style="display: none;"<?php  } ?>>
            <img id="showabstractimg" src="<?php echo  empty($item)?'':$item['abstractimg']?>"/>
            <span class="card-cover-intr">
                <span id="showabstract" class="cover-title">
                    <?php  echo $item['abstract'];?>
                    <i class="go pull-right iconfont">&#xe6a7;</i>
                </span>
            </span>
        </div>

        <!--卡券其他-->
        <div class="card-other">
            <ul>
                <li>公众号<i class="go pull-right iconfont">&#xe6a7;</i></li>
                <li>详情<i class="go pull-right iconfont">&#xe6a7;</i></li>
            </ul>
        </div>

    </div>

    <!--自定义入口1-->
    <div class="custom-detail" id="showcustom" <?php  if(empty($item['setcustom'])) { ?>style="display: none;"<?php  } ?>>
    <ul>
        <li><span id="show_custom_url_name"><?php  if(empty($item['custom_url_name'])) { ?>自定义入口1(选填)<?php  } else { ?><?php  echo $item['custom_url_name'];?><?php  } ?></span><i class="go pull-right iconfont">&#xe6a7;</i><span  class="pull-right gray"  id="show_custom_url_sub_title"><?php  echo $item['custom_url_sub_title'];?></span></li>
    </ul>
</div>
<!--自定义入口2-->
<div class="custom-detail" id="showpromotion" <?php  if(empty($item['setpromotion'])) { ?> style="display: none;"<?php  } ?>>
<ul>
    <li><span id="show_promotion_url_name"><?php  if(empty($item['promotion_url_name'])) { ?>自定义入口2(选填)<?php  } else { ?><?php  echo $item['promotion_url_name'];?><?php  } ?></span><i class="go pull-right iconfont">&#xe6a7;</i><span  class="pull-right gray"  id="show_promotion_url_sub_title"><?php  echo $item['promotion_url_sub_title'];?></span></li>
</ul>
</div>
</div>

<form style="-webkit-box-flex: 1;-webkit-flex: 1;-ms-flex: 1;flex: 1;" <?php if( ce('sale.wxcard' ,$item) ) { ?>action="" method='post'<?php  } ?> class='form-horizontal form-validate'>
<input type="hidden" name="id" value="<?php  echo $item['id'];?>">
<input type="hidden" name="tab" id='tab' value="<?php  echo $_GPC['tab'];?>" />

<ul class="nav nav-arrow-next nav-tabs" id="myTab">
    <li <?php  if($_GPC['tab']=='basic' || empty($_GPC['tab'])) { ?>class="active"<?php  } ?> ><a href="#tab_basic">基本</a></li>
    <li <?php  if($_GPC['tab']=='abstract') { ?>class="active"<?php  } ?> ><a href="#tab_abstract">自定义元素</a></li>
    <li <?php  if($_GPC['tab']=='limit') { ?>class="active"<?php  } ?> ><a href="#tab_limit">使用限制</a></li>
    <li <?php  if($_GPC['tab']=='center') { ?>class="active"<?php  } ?> ><a href="#tab_center">领取设置</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane  <?php  if($_GPC['tab']=='basic' || empty($_GPC['tab'])) { ?>active<?php  } ?>" id="tab_basic"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/wxcard/post/basic', TEMPLATE_INCLUDEPATH)) : (include template('sale/wxcard/post/basic', TEMPLATE_INCLUDEPATH));?></div>
    <div class="tab-pane  <?php  if($_GPC['tab']=='abstract') { ?>active<?php  } ?>" id="tab_abstract"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/wxcard/post/abstract', TEMPLATE_INCLUDEPATH)) : (include template('sale/wxcard/post/abstract', TEMPLATE_INCLUDEPATH));?></div>
    <div class="tab-pane  <?php  if($_GPC['tab']=='limit') { ?>active<?php  } ?>" id="tab_limit"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/wxcard/post/limit', TEMPLATE_INCLUDEPATH)) : (include template('sale/wxcard/post/limit', TEMPLATE_INCLUDEPATH));?></div>
    <div class="tab-pane  <?php  if($_GPC['tab']=='center') { ?>active<?php  } ?>" id="tab_center"><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/wxcard/post/center', TEMPLATE_INCLUDEPATH)) : (include template('sale/wxcard/post/center', TEMPLATE_INCLUDEPATH));?></div>
</div>

<div class="form-group"></div>
<div class="form-group">
    <label class="col-lg control-label"f></label>
    <div class="col-sm-9 col-xs-12">
        <?php if( ce('sale.wxcard' ,$item) ) { ?>
        <input type="submit" name="submit" value="提交" class="btn btn-primary"  />
        <?php  } ?>
        <input type="button" name="back" onclick='history.back()' <?php if( ce('sale.wxcard' ,$item) ) { ?>style='margin-left:10px;'<?php  } ?> value="返回列表" class="btn btn-default" />
    </div>
</div>





</form>
<div style="clear: both"></div>
</div>
<script language='javascript'>
      require(['bootstrap'],function(){
             $('#myTab a').click(function (e) {
                 e.preventDefault();
                $('#tab').val( $(this).attr('href'));
                 $(this).tab('show');
             })
     });

    function showbacktype(type){

        $('.backtype').hide();
        $('.backtype' + type).show();
    }

    function setshowuselimit()
    {
        var shiyongtext = $("#accept_category").val();

        var text="";
        if(shiyongtext!='')
        {
            text="适用于"+shiyongtext;
        }
        var bushiyongtext = $("#reject_category").val();

        if(bushiyongtext!='')
        {
            if(shiyongtext!='')
            {
              text=text+",";
            }

          text=text+"不适用于"+bushiyongtext;
        }

        $("#showlimittext").html(text);
    }



	$(function(){

        $('form').submit(function(){

            <?php  if(empty($id)) { ?>
                if($('#title').val()==''||$('#title').val().length>9)
                {
                    $('#myTab a[href=#tab_basic]').tab('show');
                    tip.msgbox.err('卡券标题不能为空且不能超过9个字符!');
                    $('form').attr('stop',1);


                    return false;
                }

                if($('#logourl').val()==''||$('#logolocalpath').val()=='')
                {
                    $('#myTab a[href=#tab_basic]').tab('show');
                    tip.msgbox.err('logo图片未上传!');
                    $('form').attr('stop',1);


                    return false;
                }




                if($('#quantity').val()=='')
                {
                    $('#myTab a[href=#tab_basic]').tab('show');
                    tip.msgbox.err('卡券库存数量不能为空且必须大于1!');
                    $('form').attr('stop',1);


                    return false;
                }

                if($('#brand_name').val()==''||$('#brand_name').val().length>12)
                {
                    $('#myTab a[href=#tab_basic]').tab('show');
                    tip.msgbox.err('商铺名称不能为空且长度超过12个字符!');
                    $('form').attr('stop',1);

                    return false;
                }


                <?php  if(empty($type)) { ?>
                    if($('#reduce_cost').val()==''||$('#reduce_cost').val()<=0)
                    {
                        $('#myTab a[href=#tab_basic]').tab('show');
                        tip.msgbox.err('减免金额不能为空,且必须大于1,支持2位小数点!');
                        $('form').attr('stop',1);


                        return false;
                    }

                <?php  } else { ?>
                    if($('#discount').val()==''||$('#discount').val()>9.9||$('#discount').val()<1)
                    {
                        $('#myTab a[href=#tab_basic]').tab('show');
                        tip.msgbox.err('不能为空且折扣额度需填写范围在1-9.9的数字!');
                        $('form').attr('stop',1);

                        return false;
                    }
                <?php  } ?>

            <?php  } ?>




            $('form').removeAttr('stop');
            return true;
        });


        require(['jquery', 'util'], function($, util){
            //示例图logo控制
            $('#uploadlogo').click(function(){
                util.image('',function(data){
                    $("#showlogo").attr('src',data.url);
                    $("#logourl").val(data.url);
                    $("#logolocalpath").val(data.attachment);
                });
            });

            //示例图封面图片控制
            $('#upabstractimg').click(function(){
                util.image('',function(data){
                    $("#showabstractimg").attr('src',data.url);
                    $("#abstractimgurl").val(data.url);
                    $("#abstractimglocalpath").val(data.attachment);
                });
            });
        });

        //示例图l品牌名称文本控制
        $('#brand_name').change(function(){
            $('#showbrand_name').html($(this).val());
        });

        //示例图有效期控制
        window.timer_id = setInterval(function(){
            if($("input[name='datetype']:checked").val()=="DATE_TYPE_FIX_TIME_RANGE")
            {
                $("#showbeginendtime").html( $("input[name='beginendtime[start]']").val().replace("-",".")+"  "+$("input[name='beginendtime[end]").val().replace("-","."));
            }
            else if($("input[name='datetype']:checked").val()=="DATE_TYPE_FIX_TERM")
            {
                var fixed_begin_term = $("#fixed_begin_term").val();
                var fixed_term = $("#fixed_term").val();
                var nowdate = new Date();
                nowdate=new Date(nowdate.getTime()+fixed_begin_term*86400*1000);
                var showdate =nowdate.getFullYear() + "." + (nowdate.getMonth()+1) + "." + nowdate.getDate();

                nowdate=new Date(nowdate.getTime()+(fixed_term-1)*86400*1000);
                var showdate =showdate+"-"+nowdate.getFullYear() + "." + (nowdate.getMonth()+1) + "." + nowdate.getDate();
                $("#showbeginendtime").html(showdate);
            }

        },1000);
        //clearInterval(window.time_id);

        //示例图标题控制
        $('#title').change(function(){
            $('#showtitle').html($(this).val());
        });
        //示例图适用商品控制
        $('#accept_category').change(function(){
            setshowuselimit();
        });
        $('#reject_category').change(function(){
            setshowuselimit();
        });

        //示例图封面文本控制
        $('#abstract').change(function(){
            $('#showabstract').html($(this).val());
        });

        //示例图卡券顶部按钮文本控制
        $('#center_title').change(function(){

            var str = $(this).val();

            if(str=="")
            {
                str="使用";
            }

            $('#btnuse').html(str);
        });


        //添加自定义入口1
        $("#addcustom") .on('click', function(){
            $("#showsetcustom").show();
            $("#showcustom").show();
            $("#setcustom").val(1);
        });
        //删除自定义入口1
        $("#delcustom") .on('click', function(){
            $("#showsetcustom").hide();
            $("#showcustom").hide();
            $("#setcustom").val(0);
        });


        //添加自定义入口2
        $("#addpromotion") .on('click', function(){
            $("#showsetpromotion").show();
            $("#showpromotion").show();
            $("#setpromotion").val(1);

        });
        //删除自定义入口2
        $("#delpromotion") .on('click', function(){
            $("#showsetpromotion").hide();
            $("#showpromotion").hide();
            $("#setpromotion").val(0);
        });


        //添加封面
        $("#addabstract") .on('click', function(){
            $("#showsetabstract").show();
            $("#showallabstract").show();
            $("#setabstract").val(1);
        });
        //删除封面
        $("#delabstract") .on('click', function(){
            $("#showsetabstract").hide();
            $("#showallabstract").hide();
            $("#setabstract").val(0);
        });


        //示例图自定义入口1文本控制
        $('#custom_url_name').change(function(){

            var str = $(this).val();

            if(str=="")
            {
                str="自定义入口1(选填)";
            }

            $('#show_custom_url_name').html(str);
        });
        //示例图自定义入口1引导文本控制
        $('#custom_url_sub_title').change(function(){
            $('#show_custom_url_sub_title').html($(this).val());
        });



        //示例图自定义入口2文本控制
        $('#promotion_url_name').change(function(){

            var str = $(this).val();

            if(str=="")
            {
                str="自定义入口2(选填)";
            }

            $('#show_promotion_url_name').html(str);
        });
        //示例图自定义入口2引导文本控制
        $('#promotion_url_sub_title').change(function(){
            $('#show_promotion_url_sub_title').html($(this).val());
        });



        //示例图使用条件控制
        $("#use_condition") .on('click', function(){

            if($(this).prop("checked"))
            {
                $("#showuse_condition").show();
                $("#showuselimit").show();

            }else
            {
                $("#showuse_condition").hide();
                $("#showuselimit").hide();
            }
        });




	})
</script>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
