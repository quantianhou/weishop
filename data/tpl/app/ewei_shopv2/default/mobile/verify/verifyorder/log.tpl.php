<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<?php  if(is_h5app()) { ?>
<style>
    .fui-page-group.statusbar .fui-statusbar {background: #1ab394;}
    .fui-header a, .fui-header i, .fui-header .title {color: #fff;}
    .head-menu-mask, .fui-tab.fixed {top: 3.2rem !important;}
    .head-menu {top: 3.65rem !important;}
    .fui-tab.fixed ~ .fui-content {top: 5.2rem !important;}
</style>
<?php  } ?>

<link href="../addons/ewei_shopv2/template/mobile/default/static/css/verifyorder.css?v=2.0.3"rel="stylesheet"type="text/css"/>
<?php  $hideLayer=true?>

<div class='fui-page fui-page-current'>
    <div class="fui-header fui-header-success">
        <div class="fui-header-left">
            <a class="back"></a>
        </div>
        <div class="title">订单管理</div>
    </div>

    <!-- 顶部tab -->
    <div id="tab" class="fui-tab fui-tab-success fixed">

        <!--<a <?php  if($type==0) { ?>class="active"<?php  } ?> data-type="0">全部订单</a>-->
        <!--<a <?php  if($type==1) { ?>class="active"<?php  } ?> data-type="1">核销统计</a>-->
        <a data-tab="all" class="active">全部订单</a>
        <a data-tab="count">核销统计</a>
        <!--<a <?php  if($type==7) { ?>class="active"<?php  } ?> data-type="7">七日</a>-->
        <!--<a <?php  if($type==30) { ?>class="active"<?php  } ?> data-type="30">本月</a>-->
    </div>

    <div class='fui-content'>
        <div class="tab-content " id="tab_all" style="display: block">
                <!-- 搜索框 -->
                <div class="fui-search">
                    <div class="inner">
                        <i class="icon icon-search"></i>
                        <div class="select">
                            <select id="searchfieid">
                                <option value='ordersn' <?php  if($_GPC['searchfield']=='ordersn') { ?>selected<?php  } ?>>订单号</option>
                                <option value='verifycode' <?php  if($_GPC['searchfield']=='verifycode') { ?>selected<?php  } ?>>核销码</option>
                                <option value='member' <?php  if($_GPC['searchfield']=='member') { ?>selected<?php  } ?>>会员姓名</option>
                                <option value='address' <?php  if($_GPC['searchfield']=='address') { ?>selected<?php  } ?>>收件人姓名</option>
                                <option value='expresssn' <?php  if($_GPC['searchfield']=='expresssn') { ?>selected<?php  } ?>>快递单号</option>
                                <option value='goodstitle' <?php  if($_GPC['searchfield']=='goodstitle') { ?>selected<?php  } ?>>商品名称</option>
                                <option value='goodssn' <?php  if($_GPC['searchfield']=='goodssn') { ?>selected<?php  } ?>>商品编码</option>
                            </select>
                        </div>
                        <input value="<?php  echo $_GPC['keyword'];?>" placeholder="输入关键字..." id="keywords" />
                    </div>
                    <div class="fui-search-btn">搜索</div>
                </div>
                <!-- 订单列表 -->
                <div class="fui-list-outer container"></div>

                <div class="fui-title center" id="content-more">加载更多</div>
                <div class="fui-title center hide" id="content-empty">没有数据</div>
                <div class="fui-title center hide" id="content-nomore">没有更多了</div>
        </div>
        <div class="tab-content " id="tab_count">
            <div class="verification">
                <div class="verification-title">
                    今日核销
                </div>
                <div class="verification-middle">
                    <div class="verification-middle-div">
                        <div class="verification-middle-top">
                            <span class="verification-orange"></span>
                            <p>核销量</p>
                        </div>
                        <div class="verification-num"><?php  echo $order0['count'];?></div>
                    </div>
                    <div class="verification-middle-div">
                        <div class="verification-middle-top">
                            <span class="verification-green"></span>
                            <p>核销额</p>
                        </div>
                        <div class="verification-num">¥<?php  echo $order0['price'];?></div>
                    </div>
                </div>
                <div class="verification-bottom">
                    <div class="verification-bottom-left">
                        <p>核销员</p>
                        <p>核销数</p>
                        <p>核销额</p>
                    </div>
                    <div class="verification-bottom-right">
                        <ul <?php  if(count($order0['saler'])>2) { ?>style="width:<?php  echo count($order0['saler'])*5?>rem"<?php  } ?>>
                        <?php  if(is_array($order0['saler'])) { foreach($order0['saler'] as $key => $item) { ?>
                        <?php  if($all_verifyorder) { ?>
                        <li>
                            <p><?php  echo $item['name'];?></p>
                            <p><?php  echo $item['count'];?></p>
                            <p><?php  echo $item['price'];?></p>
                        </li>
                        <?php  } else { ?>
                        <?php  if($key==$openid) { ?>
                        <li>
                            <p><?php  echo $item['name'];?></p>
                            <p><?php  echo $item['count'];?></p>
                            <p><?php  echo $item['price'];?></p>
                        </li>
                        <?php  } ?>
                        <?php  } ?>
                        <?php  } } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="verification">
                <div class="verification-title">
                   七日核销
                </div>
                <div class="verification-middle">
                    <div class="verification-middle-div">
                        <div class="verification-middle-top">
                            <span class="verification-orange"></span>
                            <p>核销量</p>
                        </div>
                        <div class="verification-num"><?php  echo $order7['count'];?></div>
                    </div>
                    <div class="verification-middle-div">
                        <div class="verification-middle-top">
                            <span class="verification-green"></span>
                            <p>核销额</p>
                        </div>
                        <div class="verification-num">¥<?php  echo $order7['price'];?></div>
                    </div>
                </div>
                <div class="verification-bottom">
                    <div class="verification-bottom-left">
                        <p>核销员</p>
                        <p>核销数</p>
                        <p>核销额</p>
                    </div>
                    <div class="verification-bottom-right">
                        <ul <?php  if(count($order7['saler'])>2) { ?>style="width:<?php  echo count($order7['saler'])*5?>rem"<?php  } ?>>
                        <?php  if(is_array($order7['saler'])) { foreach($order7['saler'] as $key => $item) { ?>
                        <?php  if($all_verifyorder) { ?>
                        <li>
                            <p><?php  echo $item['name'];?></p>
                            <p><?php  echo $item['count'];?></p>
                            <p><?php  echo $item['price'];?></p>
                        </li>
                        <?php  } else { ?>
                        <?php  if($key==$openid) { ?>
                        <li>
                            <p><?php  echo $item['name'];?></p>
                            <p><?php  echo $item['count'];?></p>
                            <p><?php  echo $item['price'];?></p>
                        </li>
                        <?php  } ?>
                        <?php  } ?>
                        <?php  } } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="verification">
                <div class="verification-title">
                    本月核销
                </div>
                <div class="verification-middle">
                    <div class="verification-middle-div">
                        <div class="verification-middle-top">
                            <span class="verification-orange"></span>
                            <p>核销量</p>
                        </div>
                        <div class="verification-num"><?php  echo $order30['count'];?></div>
                    </div>
                    <div class="verification-middle-div">
                        <div class="verification-middle-top">
                            <span class="verification-green"></span>
                            <p>核销额</p>
                        </div>
                        <div class="verification-num">¥<?php  echo $order30['price'];?></div>
                    </div>
                </div>
                <div class="verification-bottom">
                    <div class="verification-bottom-left">
                        <p>核销员</p>
                        <p>核销数</p>
                        <p>核销额</p>
                    </div>
                    <div class="verification-bottom-right">
                        <ul <?php  if(count($order30['saler'])>2) { ?>style="width:<?php  echo count($order30['saler'])*5?>rem"<?php  } ?>>
                            <?php  if(is_array($order30['saler'])) { foreach($order30['saler'] as $key => $item) { ?>
                                <?php  if($all_verifyorder) { ?>
                                    <li>
                                        <p><?php  echo $item['name'];?></p>
                                        <p><?php  echo $item['count'];?></p>
                                        <p><?php  echo $item['price'];?></p>
                                    </li>
                                <?php  } else { ?>
                                    <?php  if($key==$openid) { ?>
                                        <li>
                                            <p><?php  echo $item['name'];?></p>
                                            <p><?php  echo $item['count'];?></p>
                                            <p><?php  echo $item['price'];?></p>
                                        </li>
                                    <?php  } ?>
                                <?php  } ?>
                            <?php  } } ?>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script type="text/html" id="tpl_order">
        <%each list as item%>
        <%if item.merchname && item.merchid>0%>
        <div class="fui-title small"><i class="icon icon-shop"></i> <%item.merchname%></div>
        <%/if%>
        <div class="fui-list-group <%if item.merchname && item.merchid>0%>nomargin<%/if%>" data-order="<%item.id%>">

            <a class="fui-list order-list title-b" href="<?php  echo mobileUrl('verify/verifyorder/detail')?>&id=<%item.id%>" data-nocache="true">
                <div class="fui-list-media">
                    <span class="fui-label fui-label-danger round"><%if item.statusvalue==0%><%if item.paytypevalue!=3%>未支付<%else%>货到付款<%/if%><%else%><%item.paytype%><%/if%></span>
                </div>
                <div class="fui-list-inner">
                    <div class="row">
                        <div class="row-text" style="font-size: 0.7rem; color: #666"><%item.ordersn%></div>
                    </div>
                    <div class="row gary">
                        <div class="row-text"><%item.createtime%></div>
                    </div>
                </div>
                <div class="angle"></div>
            </a>
            <%each item.goods as g%>
            <div class="fui-list goods-list">
                <div class="fui-list-media">
                    <img class="round" src="<%g.thumb%>" onerror="this.src='../addons/ewei_shopv2/static/images/nopic100.jpg';" />
                </div>
                <div class="fui-list-inner">
                    <div class="title"><%g.title%></div>
                    <div class="subtitle">
                        <div class="total">规格: <%g.optiontitle||"无"%> 编码: <%g.goodssn||"无"%></div>
                    </div>
                </div>
                <div class="fui-list-angle auto">￥<span class="marketprice"><%g.realprice/g.total%><br> x<%g.total%></span></div>
            </div>
            <%/each%>
            <div class="fui-list log-list noclick">
                <div class="fui-list-inner">
                    <div class="row log-row order">
                        <div class="row-text">订单状态</div>
                        <div class="row-remark"><span class="text-danger"><%item.status%></span></div>
                    </div>
                    <%if item.merchname && item.merchid%>
                    <div class="row log-row order">
                        <div class="row-text">店铺名称</div>
                        <div class="row-remark"><%item.merchname%></div>
                    </div>
                    <%/if%>
                    <div class="row log-row order">
                        <div class="row-text">买家昵称</div>
                        <div class="row-remark"><%item.nickname||'未更新'%></div>
                    </div>
                    <div class="row log-row order">
                        <div class="row-text">核销时间</div>
                        <div class="row-remark"><span class="text-success"><%item.verifytime%></span></div>
                    </div>

                </div>
            </div>

            <div class="fui-list-group-title text-left">
                <small class="status">共<span class="text-success"><%item.goodscount%></span>个商品 实付:<span class="text-success">￥<%item.price%></span> <%if item.dispatchprice!='0.00'%>(含运费: ￥<%item.dispatchprice%>元)<%/if%></small>
            </div>

        </div>
        <%/each%>
    </script>


    <script language="javascript">
        require(['../addons/ewei_shopv2/static/js/app/biz/verify/verifyorder.js'],function(modal){
            modal.initList({ keywords: "<?php  echo $_GPC['keyword'];?>"});
        });
    </script>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
