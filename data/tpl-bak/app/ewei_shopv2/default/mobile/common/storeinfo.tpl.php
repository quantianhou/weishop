<?php defined('IN_IA') or exit('Access Denied');?><style>
    .fui-content{
        top:0px !important;
    }
    .head1 {
        background-color: #fff;
        width: auto;
        line-height: normal;
        font-size: 14px;
        color: #fff;
        padding: 8px 13px;
    }
    .head1 .branchBtn {
        position: relative;
        width: 80%;
        height: 30px;
        line-height: 30px;
        border-radius: 25px;
        -webkit-border-radius: 25px;
        display: inline-block;
    }
    .head1 .branchBtn img {
        width: 28px;
        height: 28px;
        line-height: normal;
        border: .5px solid #e4e4e4;
        border-radius: 3px;
        -webkit-border-radius: 3px;
    }
    .fl {
        float: left;
    }
    .head1 .branchBtn .bName {
        max-width: 70%;
        color: #000;
        margin-left: 10px;
        margin-right: 8px;
        letter-spacing: 1px;
    }
    .fl {
        float: left;
    }
    .head1 .branchBtn .bArrow {

        background-size: 14px 8px;
        width: 14px;
        height: 14px;
        line-height: normal;
        display: inline-block;
        position: absolute;
        top: 8px;
    }
    .head1 .change {
        display: inline-block;
        float: right;
        margin: 5px 0;
        color: #353535;
        width: 36px;
        height: 20px;
        line-height: 20px;
        border: 1px solid #353535;
        border-radius: 10px;
        -webkit-border-radius: 10px;
        text-align: center;
    }

    /*遮罩层*/
    .mask {
        background-color: rgba(0,0,0,.6);
        position: fixed;
        width: 100%;
        height: 100%;
        line-height: normal;
        top: 0;
        left: 0;
        z-index: 99999;
    }
    #store_wrap {
        width: 100%;
        height: 400px;
        line-height: normal;
    }
    .slide_down {
        -webkit-animation: slideDown .3s ease;
        animation: slideDown .3s ease;
    }
    @media screen and (max-width: 320px)
        #store_wrap #storeMap, #store_wrap .bg_line_store, #store_wrap .evaluate_list {
            height: 290px!important;
        }

        #store_wrap .bg_line_store {
            background: #fff;
            background: -moz-linear-gradient(top,rgba(255,255,255,.9),#fff);
            background: -webkit-gradient(linear,0 0,0 bottom,from(rgba(255,255,255,.9)),to(#fff));
            background: -o-linear-gradient(top,rgba(255,255,255,.9),#fff);
            height: 400px;

        }
        .col-10 {
            width: 100%;
        }
        #store_wrap #store_tab {
            width: 100%;
            height: 45px;
            line-height: normal;
        }
        #store_wrap #store_detail_tab {
            height: 100%;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }
        @media screen and (max-width: 320px)
            #store_wrap #storeMap, #store_wrap .bg_line_store, #store_wrap .evaluate_list {
                height: 290px!important;
            }

            #store_wrap #storeMap {
                height: 400px!important;
                position: relative;
            }
            .dn {
                display: none;
            }
            #store_wrap .store_pay {
                color: #fff;
                font-size: 13px;
                padding: 5px 0;
                background-color: #2b2b2b;
            }
            #store_wrap .store {
                padding: 15px 10px 0;
            }
            #store_wrap .store_about {
                width: 100%;
                padding: 10px 0;
            }
            #store_wrap .store_about .item {
                width: 33%;
                float: left;
                font-size: 14px;
                color: #333;
            }
            #store_wrap .store_about .item.store_tel i {
                background-image: url(/attachment/images/icon_tel.png);
                width: 60px;
                height: 60px;
                line-height: normal;
            }
            #store_wrap .store_about .item.store_product i {
                background-image: url(/attachment/images/icon_product.png);
                width: 60px;
                height: 60px;
                line-height: normal;
            }
            #store_wrap .store_about .item.favorite i.collect {
                background-image: url(/attachment/images/collect_no.png);
            }

            #store_wrap .store_about .item.favorite i {
                width: 60px;
                height: 60px;
                line-height: normal;
            }
            #store_wrap .store_about .item i {
                display: inline-block;
                background-repeat: no-repeat;
                background-position: center center;
                background-size: 100%;
                position: relative;
            }
            .mt1 {
                margin-top: 5px;
            }
            .ellipsis, .oh {
                overflow: hidden;
            }
            .mk_geo, .tc {
                text-align: center;
            }
            #store_wrap .store_ps_types {
                border: 1px solid #b2b2b2;
                border-radius: 10px;
                -webkit-border-radius: 10px;
                margin-bottom: 10px;
                margin-left: 10px;
                margin-right: 10px;
            }

            .mt1 {
                margin-top: 5px;
            }
            #store_wrap .w50 {
                width: 50%;
            }
            #store_wrap .store .zizzm {
                position: absolute;
                top: 0;
                right: 0;
                z-index: 9999;
                background: url(/attachment/images/zzzm.png) no-repeat;
                background-size: 72px 20px;
                width: 72px;
                height: 20px;
                line-height: normal;
            }
            #store_wrap .w50, #store_wrap .w95 {
                margin-left: auto;
                margin-right: auto;
            }
            #store_wrap #pay p {
                background: url(/attachment/images/pay.png) center left no-repeat;
                background-size: 17px;
                height: 20px;
                line-height: 20px;
                margin-left: 8%;
                padding: 0 0 0 27px;
            }
            #store_wrap .store .store_pic {
                /*background-image: url(/QWWAP_NG/common/img/yaofang_logo.png);*/
                width: 70px;
                height: 70px;
                line-height: normal;
                background-size: cover;
                vertical-align: middle;
                background-color: #fff;
                border: .5px solid #e4e4e4;
            }
            #store_wrap .store .store_info_rt {
                margin: 0 0 0 80px;
                position: relative;
            }
            #store_wrap .store .store_name {
                font-size: 18px;
                color: #000;
                font-weight: 600;
                margin-bottom: 5px;
            }
            #store_wrap .store_evaluates {
                display: inline-block;
                width: 100%;
                margin-left: auto;
                margin-right: auto;
            }
            #store_wrap .store #store_star {
                float: left;
            }
            @media screen and (max-width: 320px)
                #store_wrap #evaluates {
                    padding-top: 2px;
                }
                @media screen and (max-width: 320px)
                    #store_wrap #evaluates span {
                        font-size: 11px;
                    }

                    #store_wrap #evaluates span {
                        color: #9e8052;
                        font-size: 12px;
                    }
                    #store_wrap #evaluates {
                        padding-top: 1px;
                    }
                    .pl1 {
                        padding-left: 5px;
                    }
                    @media screen and (max-width: 320px)
                        #store_wrap #evaluates span.bl {
                            margin-left: 0;
                        }
                        #store_wrap #evaluates span.bl {
                            margin-left: 5px;
                            border-left: 1px solid #9e8052;
                        }
                        #store_wrap .store .store_star img {
                            width: 14px;
                        }

                        @media screen and (max-width: 320px)
                            #store_wrap .store_star img {
                                margin-right: 0;
                                width: 15px;
                            }
                            img {
                                max-width: 100%;
                                height: auto;
                                border: 0;
                            }
                            #store_wrap .addr {
                                margin-top: 5px;
                            }
                            #store_wrap .store_address {
                                background: url(/attachment/images/poi.png) center left no-repeat;
                                background-size: 11px;
                                color: #666;
                                font-size: 12px;
                            }

                            #store_wrap .store_i {
                                padding: 0 5px 0 15px;
                                position: relative;
                            }
                            #store_wrap .store_i:after {
                                content: "";
                                width: 15px;
                                height: 10px;
                                line-height: normal;
                                position: absolute;
                                top: 50%;
                                right: 0;
                                z-index: 9;
                                margin-top: -5px;
                                background: url(/attachment/images/arrow_r.png) center right no-repeat;
                                background-size: 7px 12px;
                            }
                            :after, :before {
                                -webkit-box-sizing: border-box;
                                box-sizing: border-box;
                            }
                            @media screen and (max-width: 320px)
                                #store_wrap #evaluates font {
                                    font-size: 11px;
                                    margin-left: 0;
                                }
                                #store_wrap #evaluates font {
                                    color: #f60;
                                    font-size: 12px;
                                    margin-left: 5px;
                                }
                                #store_wrap .store_ps_types {
                                    border: 1px solid #b2b2b2;
                                    border-radius: 10px;
                                    -webkit-border-radius: 10px;
                                    margin-bottom: 10px;
                                    margin-left: 10px;
                                    margin-right: 10px;
                                }
                                #store_wrap .store_ps_types {
                                    border: 1px solid #b2b2b2;
                                    border-radius: 10px;
                                    -webkit-border-radius: 10px;
                                    margin-bottom: 10px;
                                    margin-left: 10px;
                                    margin-right: 10px;
                                }
                                #store_wrap #evaluate_list li, #store_wrap .store_info:not(:last-child) {
                                    border-bottom: .5px solid #e4e4e4;
                                }

                                #store_wrap .store_info {
                                    padding: 10px;
                                    min-height: 28px;
                                }
                                @media screen and (max-width: 320px)
                                    #store_wrap .ps_type, #store_wrap .ps_type_info {
                                        font-size: 14px;
                                    }

                                    #store_wrap .ps_type {
                                        width: 80px;
                                        padding-right: 10px;
                                        margin-right: 10px;
                                        color: #000;
                                        font-size: 17px;
                                    }
                                    @media screen and (max-width: 320px)
                                        #store_wrap .ps_type, #store_wrap .ps_type_info {
                                            font-size: 14px;
                                        }

                                        #store_wrap .ps_type_info {
                                            margin-left: 40%;
                                            color: #353535;
                                            font-size: 17px;
                                        }
                                        #store_wrap .ps_type p, #store_wrap .ps_type_info p {
                                            line-height: 20px;
                                            font-size: 14px;
                                        }
                                        #store_wrap .ps_type p:nth-child(2), #store_wrap .ps_type_info p:nth-child(2){
                                            font-size: 12px;
                                        }
                                        @media screen and (max-width: 320px)
                                            #store_wrap .ps_type p:nth-child(2), #store_wrap .ps_type_info p:nth-child(2) {
                                                font-size: 12px;
                                            }
                                            #store_wrap #store_tab {
                                                width: 100%;
                                                height: 45px;
                                                line-height: normal;
                                            }
                                            #store_wrap #store_tab ul {
                                                height: 45px;
                                                line-height: 45px;
                                                background-color: #748297;
                                                border-bottom-left-radius: 5px;
                                                -webkit-border-bottom-left-radius: 5px;
                                                border-bottom-right-radius: 5px;
                                                -webkit-border-bottom-right-radius: 5px;
                                            }
                                            #store_wrap #store_tab li.active_tab_left {
                                                color: #000;
                                                border-bottom-left-radius: 5px;
                                                border-bottom-right-radius: 5px;
                                                background: url(/attachment/images/active_tab_left.png) center left no-repeat;
                                                background-size: 100% 50px;
                                            }
                                            #store_wrap #store_tab li.active_tab_right {
                                                color: #000;
                                                border-bottom-left-radius: 5px;
                                                border-bottom-right-radius: 5px;
                                                background: url(/attachment/images/active_tab_right.png) center left no-repeat;
                                                background-size: 100% 50px;
                                            }
                                            li{
                                                list-style: none;
                                            }
                                            #store_wrap #store_tab li {
                                                width: 50%;
                                                color: #fff;
                                                font-size: 16px;
                                                float: left;
                                                text-align: center;
                                                padding: 4px 0 8px;
                                                margin: -4px 0 0;
                                                position: relative;
                                            }
                                            #store_wrap .store_close {
                                                width: 47px;
                                                height: 47px;
                                                line-height: normal;
                                                margin: 20px auto;
                                                background: url(/attachment/images/store_close_btn.png) center center no-repeat;
                                                background-size: 100%;
                                            }
                                            .change_store{
                                                width: 100%;
                                                height:100%;
                                                background: #ffffff;
                                                position: fixed;
                                                left:0;
                                                top:0;
                                                z-index: 999999;
                                                display: none;
                                            }
                                            .headFixed {
                                                position: fixed;
                                                width: 100%;
                                                z-index: 999;
                                            }
                                            #new_head {
                                                width: 100%;
                                                height: 39px;
                                                line-height: normal;
                                                background-color: #efeff4;
                                                padding: 6px 0 0;
                                            }
                                            #new_head .goSearch {
                                                width: 80%;
                                                margin-left: auto;
                                                margin-right: auto;
                                                background-color: #fff;
                                                color: #b2b2b2;
                                                display: block;
                                                border-radius: 5px;
                                                -webkit-border-radius: 5px;
                                                border: .5px solid #e4e4e4;
                                                font-size: 14px;
                                                padding: 5px 10px;
                                            }
                                            #new_head .goSearch img {
                                                width: 14px;
                                                height: 13.5px;
                                                line-height: normal;
                                                margin: -2px 5px 0 0;
                                            }
                                            #new_head .goSearch input{
                                                border: none;
                                                color: #b2b2b2;
                                            }
                                            .change_store .location {
                                                width: 100%;
                                                height: 40px;
                                                line-height: 40px;
                                                padding: 0 4%;
                                                border-bottom: .5px solid #e4e4e4;
                                            }
                                            .change_store .location p {
                                                width: 88%;
                                                float: left;
                                                font-size: 14px;
                                                color: #888;
                                            }
                                            .change_store .location img {
                                                height: 21px;
                                                margin-top:10px;
                                                float: right;
                                            }
                                            .col-10 {
                                                width: 100%;
                                            }
                                            .change_store .store_info {
                                                background-color: #fff;
                                            }
                                            .change_store .store_info .store_li {
                                                margin: auto;
                                                padding: 15px 0;
                                                border-bottom: .5px solid #e4e4e4;
                                                overflow: hidden;
                                            }
                                            .change_store .store_pic {
                                                width: 80px;
                                                height: 80px;
                                                line-height: normal;
                                                border-radius: 3px;
                                                -webkit-border-radius: 3px;
                                                margin-left: 10px;
                                            }
                                            .change_store .store_info_rt {
                                                margin-left: 100px;
                                            }
                                            .change_store .store_name {
                                                font-weight: 100;
                                                margin-bottom: 10px;
                                                line-height: 0;
                                            }
                                            .change_store .s_name {
                                                width: 70%;
                                            }
                                            .change_store .s_range {
                                                width: 50px;
                                                height: 20px;
                                                line-height: 20px;
                                                background-color: #efeff4;
                                                border-radius: 3px;
                                                -webkit-border-radius: 3px;
                                                color: #999;
                                                font-size: 12px;
                                                text-align: center;
                                            }
                                            .change_store .s_name_ {
                                                width: 100%;
                                                height: 16px;
                                                line-height: 16px;
                                                display: inline-block;
                                                font-size: 16px;
                                                color: #000;
                                            }
                                            .change_store .ps_types {
                                                font-size: 12px;
                                                color: #666;
                                            }
                                            .change_store .s_location {
                                                font-size: 12px;
                                                color: #999;
                                                margin-top: 18px;
                                                background: url(/attachment/images/s_loc.png) center left no-repeat;
                                                background-size: 11px 14px;
                                                padding-left: 20px;
                                            }
    .BMap_mask{
        height: 400px !important;
    }
    #js-map{
        height: 400px !important;
    }
</style>
<!--这里是遮罩层-->
<div id="mmmmask" class="mask ng-scope">
    <div id="store_wrap" class="slide_down">
        <div class="proBox col-10 bg_line_store oh">
            <!--药房详情模块-->
            <!-- ngIf: branchDetail && storeTabFlg == 1 -->
            <div id="store_detail_tab"  class="ng-scope">
            <!--支持在线支付-->
            <!-- ngIf: branchDetail.supportOnlineTrading -->
                <div class="store_pay ng-scope" id="pay" >
                    <div class="w50">
                        <p>该药房支持在线支付</p>
                    </div>
                </div><!-- end ngIf: branchDetail.supportOnlineTrading -->
                <!--药房头部-->
                <div class="store">
                    <div class="oh">
                        <img class="fl store_pic lazy ng-isolate-scope" width="100%" src="/attachment/<?php  echo $thishop['logo'];?>">
                        <div class="store_info_rt">
                            <h2 class="store_name ellipsis ng-binding"><?php  echo $thishop['storename'];?></h2>
                            <div class="store_evaluates">
                                <div id="store_star" class="ng-isolate-scope">
                                    <p class="store_star ng-scope">
                                        <img  src="/attachment/images/star.png">
                                        <img  src="/attachment/images/star.png">
                                        <img  src="/attachment/images/star.png">
                                        <img  src="/attachment/images/star.png">
                                        <img  src="/attachment/images/star.png">
                                    </p>
                                </div>
                                <div id="evaluates">
                                    <span class="pl1">服务态度</span><font color="#ccc" class="ng-binding">5.0</font>
                                    <span class="pl1 bl">送货速度</span><font color="#ccc" class="ng-binding">5.0</font>
                                </div>
                            </div>
                            <div class="addr">
                                <p class="store_address store_i ellipsis ng-binding"><?php  echo $thishop['address'];?></p>
                            </div>
                            <div class="zizzm" onclick="window.location.href='/app/index.php?i=17&c=entry&m=ewei_shopv2&do=mobile&r=index.zzzm&store_id=<?php  echo $thishop['id'];?>'"></div>
                        </div>
                    </div>
                </div>
                <!--药房公告-->
                <!-- ngIf: branchNotice --> <!-- <p class="fl pl2 ellipsis" ng-cloak>{{branchNotice}}</p> -->
                <!--药房快捷按钮-->
                <!-- ngIf: !showBranchTel -->
                <div class="store_about oh tc mt1 ng-scope">
                    <a class="item store_tel" href="tel:<?php  echo $thishop['tel'];?>"><i></i><p>拨打电话</p></a>
                    <a class="item store_product" onclick="$('#mmmmask').remove()" href="/app/index.php?i=<?php  echo $uniacid;?>&c=entry&m=ewei_shopv2&do=mobile&r=shop.category&storeid=<?php  echo $thishop['id'];?>"><i></i><p>本店商品</p></a>
                </div><!-- end ngIf: !showBranchTel -->
                <!--药房电话-->
                <!-- ngIf: showBranchTel -->
                <!--配送方式-->
                <!-- ngIf: !showBranchTel -->
                <div class="store_ps_types mt1 ng-scope" >
                <!-- ngRepeat: post in branchDetail.postTips track by $index -->
                    <div class="store_info ng-scope">
                        <div class="oh">
                            <div class="ps_type fl"><p class="ng-binding">物流快递</p><p class="ng-binding"></p></div>
                            <div class="ps_type_info"><p class="ng-binding">首件￥<?php  echo $dispath['firstprice'];?>元，续件￥<?php  echo $dispath['secondprice'];?>元</p></div>
                        </div>
                    </div><!-- end ngRepeat: post in branchDetail.postTips track by $index --><div class="store_info ng-scope">
                    <div class="oh">
                        <div class="ps_type fl"><p class="ng-binding">送货上门</p></div>
                        <div class="ps_type_info"><p class="ng-binding">营业时间：<?php  echo $yysj['time1'];?> - <?php  echo $yysj['time2'];?></p></div>
                    </div>
                </div><!-- end ngRepeat: post in branchDetail.postTips track by $index -->
                <div class="store_info ng-scope">
                    <div class="oh">
                        <div class="ps_type fl"><p class="ng-binding">到店取货</p><p class="ng-binding"></p></div>
                        <div class="ps_type_info"><p class="ng-binding">营业时间：<?php  echo $yysj['time1'];?> - <?php  echo $yysj['time2'];?></p><p class="ng-binding"></p></div>
                    </div>
                </div>
                    <!-- end ngRepeat: post in branchDetail.postTips track by $index -->
        </div><!-- end ngIf: !showBranchTel -->
        </div><!-- end ngIf: branchDetail && storeTabFlg == 1 -->
            <!--药房评价模块-->
            <!-- ngIf: storeTabFlg == 2 -->
            <!--地图容器-->
            <div id="storeMap" class="dn" style="overflow:hidden;">

                <div class='fui-page  fui-page-current store-map-page' >
                    <div class='fui-content' >
                        <div id='js-map' class='map-container'></div>
                    </div>
                    <script language='javascript'>
                        $(function () {
                            require(['biz/store/map'], function (modal) {
                                modal.init({store: <?php  echo json_encode($thishop)?>, isios: "<?php echo is_h5app()&&is_ios()?1:0?>"});
                            });
                        })

                    </script>

                </div>

            </div>
        </div>

        <!--notice content-->
        <!-- ngIf: showNotice -->

        <!--药房TAB-->
        <div id="store_tab">
            <ul id="tabs_store">
                <li class="active_tab_left">详情</li>
                <li>路线</li>
            </ul>
            <!--关闭按钮-->
            <div class="store_close" onclick="$('#mmmmask').remove()"></div>
        </div>
    </div>
</div>
<!--这里是遮罩层 end-->
<script>
    $('#tabs_store li').click(function(){
        //store_detail_tab storeMap
        if($(this).index() == 0){
            //第一个
            $('#store_detail_tab').css('display','block');
            $('#storeMap').css('display','none');

            $('#tabs_store li').removeClass('active_tab_right').removeClass('active_tab_left');
            $(this).addClass('active_tab_left');
        }else{
            $('#store_detail_tab').css('display','none');
            $('#storeMap').css('display','block');
            $('#tabs_store li').removeClass('active_tab_right').removeClass('active_tab_left');
            $(this).addClass('active_tab_right');
        }
    });
</script>