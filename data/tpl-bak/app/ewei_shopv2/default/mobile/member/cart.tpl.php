<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<script language='javascript' src='//api.map.baidu.com/getscript?v=2.0&ak=ZQiFErjQB7inrGpx27M1GR5w3TxZ64k7'></script>
<style>
	.store h2 {
		height: 39px;
		line-height: 39px;
		padding-right: 15px;
		font-size: 15px;
		color: #000;
		border-bottom: .5px solid #e4e4e4;
		font-weight: 400;
		list-style-type: none;
		display: flex;
		position: relative;
	}
	h2.branch input[type=checkbox]{
		left: 12px;
		position: absolute;
		top: 10px;
	}
	.store input[type=checkbox] {

		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
		position: relative;
		width: .95rem;
		height: .95rem;
		border: 1px solid #DFDFDF;
		outline: 0;
		border-radius: 16px;
		box-sizing: border-box;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		border: 1px solid #DFDFDF;
		background: #fff;
		vertical-align: middle;
		-moz-transition-duration: 300ms;
		-webkit-transition-duration: 300ms;
		transition-duration: 300ms;
	}
	.store input[type=checkbox]:checked{
		background-color: #ff5555;
		border: 1px solid #ff5555;
	}
	.store input[type=checkbox]:checked:before{
		content: " ";
		display: inline-block;
		-webkit-transform: rotate(135deg);
		-ms-transform: rotate(135deg);
		transform: rotate(135deg);
		height: 0.25rem;
		width: 0.45rem;
		border-width: 1px 1px 0 0;
		border-color: #d9d9d9;
		border-style: solid;
		position: relative;
		top: -.05rem;
		margin-left: .2rem;
	}
	.store label {
		width: 40px;
		line-height: normal;
		display: inline-block;
		float: left;
		vertical-align: middle;
		background: url(/attachment/images/unchecked.png) center no-repeat;
		background-size: 18px 18px;
	}
	.store .branchName {
		display: block;
		position: relative;
		margin: 0 10px 0 40px;
		padding-left: 28px;
		background: url(/attachment/images/store-icon.png) left center no-repeat;
		background-size: 18px 15px;
		width: 100%;
	}
	.store .branchName .branchName_area {
		display: block;
		text-overflow: ellipsis;
		margin-right: auto;
	}
	.store .branchName:after {
		display: inline-block;
		width: 7px;
		height: 11px;
		line-height: normal;
		position: absolute;
		top: 15px;
		right: -10px;
		z-index: 9;
		content: "";
		background: url(/attachment/images/arrow_r.png) center right no-repeat;
		background-size: 7px 11px;
	}
</style>
<div class='fui-page  fui-page-current member-cart-page'>
	<div class="fui-header">
		<div class="fui-header-left">
			<a class="back"></a>
		</div>
		<div class="title">我的购物车</div>

		<div class="fui-header-right">
			<a class="btn-edit" style="display:none">编辑</a>
		</div>

	</div>
	<div class='fui-content navbar cart-list' style="bottom: 4.9rem">
		<div id="cart_container" class="store"></div>
	</div>

	<div id="footer_container"></div>

	<?php  $this->footerMenus()?>
</div>


<script type="text/html" id="tpl_member_cart">
	<div class='content-empty' <%if list.length>0%>style="display:none"<%/if%>>
	<img src="<?php echo EWEI_SHOPV2_STATIC;?>images/nogoods.png" style="width: 6rem;margin-bottom: .5rem;"><br/><p style="color: #999;font-size: .75rem">您的购物车中没有商品哦！</p><br/><a href="<?php  echo mobileUrl()?>" class='btn btn-sm btn-danger-o external'style="border-radius: 100px;height: 1.9rem;line-height:1.9rem;width:  7rem;font-size: .75rem">去首页逛逛吧</a>
	</div>

		<%if list.length>0%>

		<div class="fui-list-group" id="container" style="margin-top: 0">
			<!--fanhailong添加：这里是新增加的门店html -->
			<%each newlist as gg%>
			<div class="itemshop">
			<h2 class="branch">
				<input class="shopallinput cartmode" type="checkbox"/>
				<span class="branchName" store-id="<%gg.store.id%>">
						<span class="branchName_area ng-binding"><%gg.store.storename%></span>
					</span>
				<em class="ng-binding"></em>
			</h2>
			<!--fanhailong添加：这里是新增加的门店html 结尾-->
			<%each gg.goods as g%>
			<div class="fui-list goods-item align-start"
				 data-cartid="<%g.id%>"
				 data-goodsid="<%g.goodsid%>"
				 data-optionid="<%g.optionid%>"
				 data-seckill-maxbuy = "<%g.seckillmaxbuy%>"
				 data-seckill-selfcount = "<%g.seckillselfcount%>"
				 data-seckill-price = "<%g.seckillprice%>"
				 data-type = "<%g.type%>"
			>
				<div class="fui-list-media ">
					<input type="checkbox" name="checkbox" class="fui-radio fui-radio-danger cartmode check-item "<%if g.selected==1%>checked<%/if%>/>
					<input type="checkbox" name="checkbox" class="fui-radio fui-radio-danger editmode edit-item"/>
				</div>

				<div class="fui-list-media image-media">
					<a href="<?php  echo mobileUrl('goods/detail')?>&id=<%g.goodsid%>">
						<img id="gimg_<?php  echo $g['id'];?>" data-lazy="<%g.thumb%>" class="">
					</a>
				</div>
				<div class="fui-list-inner">
					<a href="<?php  echo mobileUrl('goods/detail')?>&id=<%g.goodsid%>">
						<div class="subtitle">
							<%if  g.type==4%>
							<span class='fui-label fui-label-danger'>批发</span>
							<%/if%>
							<%if  g.discounttype>0&& g.isnodiscount ==0%>
							<span class='fui-label fui-label-danger'>折扣</span>
							<%/if%>
							<%if g.seckillprice>0%>
							<div class="fui fui-label fui-label-danger"><%g.seckilltag%></div>
							<%/if%>
							<%g.title%>
						</div>
						<%if g.optionid>0%>
						<div class="text cart-option cartmode">
							<div class="choose-option"><%g.optiontitle%></div>
						</div>
						<%/if%>
					</a>
					<%if g.optionid>0%>
						<div class="text  cart-option  editmode">
							<div class="choose-option" data-optionid="<%g.optionid%>"><%g.optiontitle%></div>
						</div>
					<%/if%>
					<div class='price'>
						<span class="bigprice text-danger">￥<span class='marketprice'><%g.marketprice%></span></span>
						<%if g.type==4%>
						<div class="fui-number small "
							 data-value="<%g.total%>"
							 data-max="<%g.totalmaxbuy%>"
							 data-min="<%g.minbuy%>"
							 data-mintoast="<%g.minbuy%><%g.unit%>起批"
						>

							<%else%>
							<div class="fui-number small "
								 data-value="<%g.total%>"
								 data-max="<%g.totalmaxbuy%>"
								 data-min="<%g.minbuy%>"
								 data-maxtoast="最多购买{max}<%g.unit%>"
								 data-mintoast="{min}<%g.unit%>起售"
							>
								<%/if%>
								<div class="minus">-</div>
								<input class="num shownum" type="tel" name="" value="<%g.total%>"/>
								<div class="plus ">+</div>
							</div>

						</div>
					</div>
			</div>
			<%/each%>
			</div>
			<%/each%>
			
		</div>
	<%/if %>
</script>

<script type="text/html" id="tpl_member_cart_footer">
	<%if list.length>0%>
	<div class="fui-footer cartmode" style="bottom: 2.45rem">
		<div class="fui-list noclick">
			<div class="fui-list-media editmode">
				<label class="checkbox-inline editcheckall"><input type="checkbox" name="checkbox" class="fui-radio fui-radio-danger " />&nbsp;全选</label>
			</div>
			<div class="fui-list-media">
				<label class="checkbox-inline checkall" style="display: none;"><input type="checkbox" name="checkbox"
															   class="fui-radio fui-radio-danger " <%if ischeckall%>checked<%/if%>/>&nbsp;全选</label>
			</div>
			<div class="fui-list-inner">
				<div class='subtitle'>合计:<span class="text-danger bigprice"> ￥</span><span class='text-danger totalprice  bigprice'><%totalprice%></span></div>
				<div class='text'>不含运费</div>
			</div>
			<div class='fui-list-angle'>
				<div style="	width: 5rem;" class="btn  btn-submit <%if total<=0%>}btn-default disabled<%else%>btn-danger<%/if%>" <%if total<=0%>stop="1"<%/if%>>结算(<span class='total'><%total%></span>)</div>
		</div>
	</div>
	</div>
	<div class="fui-footer editmode" style="bottom: 2.45rem">
		<div class="fui-list noclick">
			<div class="fui-list-media">
				<label class="checkbox-inline editcheckall"><input type="checkbox" name="checkbox" class="fui-radio fui-radio-danger " />&nbsp;全选</label>
			</div>
			<div class='fui-list-angle'>
				<div class="btn  btn-default btn-favorite disabled attention">移到关注</div>
				<div class="btn  btn-danger btn-delete  disabled">删除</div>
			</div>
		</div>
	</div>
	<%/if %>
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('goods/picker', TEMPLATE_INCLUDEPATH)) : (include template('goods/picker', TEMPLATE_INCLUDEPATH));?>
<script language='javascript'>require(['biz/member/cart'], function (modal) {
	modal.init();
});</script>
<script>
    $(document).on('click','.branchName',function(){
        var store_id = $(this).attr('store-id');
        $.post(
            "/app/index.php?i=<?php  echo $_W['uniacid'];?>&c=entry&m=ewei_shopv2&do=mobile&r=getstoreinfo&stroeid="+store_id,
            {},
            function(res){
                $('body').append(res)
            }
        );
    });

    // $(document).on('click','.shopallinput',function(){
    //     $(this).parent().parent().find('.check-item').each(function () {
	// 		$(this).click();
    //     });
	// });
</script>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
