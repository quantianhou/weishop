<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='fui-page  fui-page-current'>
    <div class="fui-header">
	<div class="fui-header-left">
	    &nbsp;
	</div>
	<div class="title">核销台</div> 
	<div class="fui-header-right">&nbsp;</div>
    </div>
    <div class='fui-content navbar'>
	
	  <?php  if(!empty($store)) { ?>

	<div class='fui-list-group' >
  
	  
	    <div class='fui-list'>
		<div class='fui-list-media'><i class='icon icon-shop'></i></div>
		<div class='fui-list-inner'>
		    <div class='title'><?php  echo $store['storename'];?></div>
		</div>
	    </div>
	  
	</div>
	<?php  } ?>
<div class='fui-list-group' >
  
	  
	    <div class='fui-list'>
		<div class='fui-list-media'><img src='<?php  echo $member['avatar'];?>' /></div>
		<div class='fui-list-inner'>
		    <div class='title'><?php  echo $saler['salername'];?></div>
		    <div class='text'><?php  echo $member['nickname'];?></div>
		</div>
	    </div>
	  
	</div>
	
	
	<div class='fui-cell-group'>
	    <div class='fui-cell-title'>请输入消费码或自提码</div>
	    <div class='fui-cell'>
		
		<div class='fui-cell-info'>
		       <input type='text' class='fui-input' id='verifycode' placeholder='消费码或自提码'/>
		</div>
		<div class='fui-cell-remark noremark'>
		       <div class='btn btn-danger btn-sm btn-search'>查询订单</div>
			<a class='btn btn-danger btn-sm btn-search' href=" <?php  echo mobileUrl('verify.verifyorder.log')?>">核销记录</a>
		</div>
	    </div>
	</div>

	<div id='container'></div>
	
  
    </div>
    <div class='fui-footer' style='display:none'>
	<div class="btn btn-danger order-verify block" data-orderid="<?php  echo $order['id'];?>" data-verifytype="<?php  echo $order['verifytype'];?>">
	    <i class="icon icon-check"></i> 
	    <span>确定使用</span>
	</div>

    </div>
	
    <script id='tpl_container' type='text/html'>
	
	<div class='fui-list-group'>
	    <div class='fui-list order-status'>
		<div class='fui-list-inner'>
		    <div class='title'><%order.ordersn%></div>
		    <div class='text'>订单金额: ￥<%order.price%><span></div>
		</div>
	    </div>
	</div>
    
	<div class='fui-list-group' >
	    <div class='fui-list'>
		<div class='fui-list-media'><i class='icon icon-person2'></i></div>
		<div class='fui-list-inner'>
		    <div class='title'><%carrier.carrier_realname%> <%carrier.carrier_mobile%></div>
		</div>
	    </div>
	</div>
    
    
	<div class="fui-list-group goods-list-group">  
	    <div class="fui-list-group-title"><i class="icon icon-shop"></i><%shop.name%></div>
 
	   <%each allgoods as g%>
	    <a href="<?php  echo mobileUrl('goods/detail')?>&id=<%g.goodsid%>">

		<div class="fui-list goods-list">
		    <div class="fui-list-media">
			<img src="<%g.thumb%>" class="round">
		    </div>
		    <div class="fui-list-inner">
			<div class="text goodstitle"><%g.title%></div>
		    <%if g.optionid!='0'%><div class='subtitle'><%g.optiontitle%></div><%/if%>

		    </div>
		    <div class='fui-list-angle'>
			￥<span class='marketprice'><%g.price%><br/>   x<%g.total%>
		    </div>
		</div>
	    </a>
	    <%/each%>
	</div>



    </script>
    
    <script language='javascript'>require(['biz/verify/page'], function (modal) {
                modal.init();
            });</script>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
