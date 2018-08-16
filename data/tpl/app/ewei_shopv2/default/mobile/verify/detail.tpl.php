<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<div class='fui-page  fui-page-current'>
    <div class="fui-header">
	<div class="fui-header-left">
	    <a class="back"></a>
	</div>
	<div class="title">订单核销</div> 
	<div class="fui-header-right">&nbsp;</div>
    </div>
    <div class='fui-content navbar'>

	<div class='fui-list-group'>
	    <div class='fui-list order-status'>

		<div class='fui-list-inner'>
		    <div class='title'><?php  echo $order['ordersn'];?></div>
		    <div class='text'>订单金额: ￥<?php  echo $order['price'];?></div>
		</div>
	    </div>
	</div>

	<?php  if(!empty($carrier) ||!empty($store)) { ?>

	<div class='fui-list-group' style='margin-top:5px;'>

	    <div class='fui-list'>
		<div class='fui-list-media'><i class='icon icon-person2'></i></div>
		<div class='fui-list-inner'>
		    <div class='title'><?php  echo $carrier['carrier_realname'];?> <?php  echo $carrier['carrier_mobile'];?></div>
		</div>
	    </div>
	    <?php  if(!empty($store)) { ?>
	    <div class='fui-list'>
		<div class='fui-list-media'><i class='icon icon-shop'></i></div>
		<div class='fui-list-inner'>
		    <div class='title'><?php  echo $store['storename'];?></div>
		</div>
	    </div>
	    <?php  } ?>
	</div>
	<?php  } ?>

	<div class="fui-list-group goods-list-group">  

	    <div class="fui-list-group-title"><i class="icon icon-shop"></i> <?php  echo $_W['shopset']['shop']['name'];?></div>

	    <?php  if(is_array($allgoods)) { foreach($allgoods as $g) { ?>
	    <a href="<?php  echo mobileUrl('goods/detail',array('id'=>$g['goodsid']))?>">

		<div class="fui-list goods-list">
		    <div class="fui-list-media">
			<img src="<?php  echo tomedia($g['thumb'])?>" class="round">
		    </div>
		    <div class="fui-list-inner">
			<div class="text goodstitle"><?php  echo $g['title'];?></div> 
			<?php  if(!empty($g['optionid'])) { ?><div class='subtitle'><?php  echo $g['optiontitle'];?></div><?php  } ?>

		    </div>
		    <div class='fui-list-angle'>
			￥<span class='marketprice'><?php  echo $g['price'];?><br/>   x<?php  echo $g['total'];?>
		    </div>

		</div>
	    </a>
	    <?php  } } ?>
	</div>
<?php  if($order['dispatchtype']) { ?>
<div class='fui-cell-group'>
	    <div class='fui-cell'>
		<div class='fui-cell-label'>提货码</div>
		<div class='fui-cell-info'></div>
		<div class='fui-cell-remark noremark'><?php  echo $order['verifycode'];?></div>
	    </div>
	</div>
<?php  } else { ?>
	<?php  if($order['verifytype']==0 || $order['verifytype']==3) { ?>
	<div class='fui-cell-group'>
	    <div class='fui-cell'>
		<div class='fui-cell-label'>消费码</div>
		<div class='fui-cell-info'></div>
		<div class='fui-cell-remark noremark'><?php  echo $order['verifycode'];?></div>
	    </div>
	</div>
	<?php  } else if($order['verifytype']==1) { ?>

	<div class='fui-cell-group'>
	    <div class='fui-cell'>
		<div class='fui-cell-label' style='width:auto;'>使用次数 (剩余 <span class='text-danger'><?php  echo $lastverifys;?></span> 次)</div>
		<div class='fui-cell-info'></div>
		<div class='fui-cell-remark noremark'>
		    <div class="fui-number" 
			 data-value='1'
			 data-max="<?php  echo $lastverifys;?>" 
			 data-min="1">
			<div class="minus">-</div>
			<input class="num shownum" type="tel" name="" value="1"/>
			<div class="plus ">+</div>
		    </div>
		</div>
	    </div>
	</div>
	<?php  } else { ?>

	<div class='fui-according-group <?php  if(count($verifyinfo)<=3) { ?>expanded<?php  } ?> verify-container' data-orderid="<?php  echo $order['id'];?>" data-verifytype="<?php  echo $order['verifytype'];?>">
	    <div class='fui-according expanded'>
		<div class='fui-according-header'>

		    <i class='icon icon-list'></i>	     
		    <span class="text">消费码</span>
		    <span class="remark"><div class="badge"><?php  if(!empty($_GPC['verifycode'])) { ?>1<?php  } else { ?><?php  echo $lastverifys;?><?php  } ?></div></span>
		</div>
		<div class="fui-according-content verifycode-container">

		    <div class='fui-cell-group'>
			<?php  if(is_array($verifyinfo)) { foreach($verifyinfo as $v) { ?>
			<?php  if(empty($_GPC['single']) || ( !empty($_GPC['single']) && $v['select'] ) ) { ?>
			    <div class='fui-cell verify-cell' data-verifycode="<?php  echo $v['verifycode'];?>">
				<div class='fui-cell-label' style='width:auto'>
				    <?php  if(!$v['verified']) { ?>
				    <input type='checkbox' class='fui fui-checkbox fui-checkbox-danger verify-checkbox' <?php  if(!empty($v['select'])) { ?>checked<?php  } ?> 
					   <?php  if(!empty($_GPC['single']) && $v['select']) { ?>style='display:none'<?php  } ?>
					   />
					   <?php  } ?>
					   <?php  echo $v['verifycode'];?>
				</div>
				<div class='fui-cell-info'></div>
				<div class='fui-cell-remark noremark'>
				    <?php  if($v['verified']) { ?>
				    <div class='fui-label fui-label-danger' >已使用</div>
				    <?php  } else { ?>
				    <div class='fui-label fui-label-default' >未使用</div>
				    <?php  } ?>

				</div>
			    </div>
			 
			   <?php  } ?>
		 
			<?php  } } ?>
		    </div>


		</div>
	    </div>
	</div>
	<?php  } ?>
<?php  } ?>



    </div>
    <div class='fui-footer'>
	<div class="btn btn-danger order-verify block" data-orderid="<?php  echo $order['id'];?>" data-verifytype="<?php  echo $order['verifytype'];?>">
	    <i class="icon icon-check"></i> 
	    <span><?php  if($order['dispatchtype']) { ?>确认取货<?php  } else { ?>确定使用<?php  } ?></span>
	</div>

    </div>
    <script language='javascript'>require(['biz/verify/detail'], function (modal) {
                modal.init();
            });</script>
</div>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
