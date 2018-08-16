<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<link rel="stylesheet" type="text/css" href="../addons/ewei_shopv2/template/mobile/default/static/css/coupon-new.css?v=2017030310125">
<div class="fui-page">

	<div class="fui-header">
		<div class="fui-header-left">
			<a class="back" href="<?php  echo mobileUrl('sale/coupon')?>"></a>
		</div>
		<div class="title">详情</div>
		<div class="fui-header-left"></div>
	</div>

	<div class="fui-content navbar coupon-page">

		<div class="coupon-detail <?php  echo $coupon['color'];?>">
			<div class="detail-dots"></div>
			<div class="detail-head">
				<div class="inner">
					<div class="title">
						<div class="text"><?php  echo $coupon['couponname'];?></div>
					</div>


					<?php  if($coupon['backtype']==2) { ?>
					<div class="childs">
						<div class="child">
							<div class="subtitle"> <?php  echo $coupon['title2'];?></div>
							<div class="bigtitle">立返</div>
						</div>
						<div class="child">
							<?php  if(!empty($coupon['backmoney']) && $coupon['backmoney'] > 0) { ?>
								<div class="subtitle"><span class="bold"><?php  echo $coupon['backmoney'];?></span> <span class="threetitle">元余额</span></div>
							<?php  } ?>
							<?php  if(!empty($coupon['backcredit']) && $coupon['backcredit'] > 0) { ?>
								<div class="subtitle"><span class="bold"><?php  echo $coupon['backcredit'];?></span> <span class="threetitle">积分</span></div>
							<?php  } ?>
							<?php  if(!empty($coupon['backredpack']) && $coupon['backredpack'] > 0) { ?>
								<div class="subtitle"><span class="bold"><?php  echo $coupon['backredpack'];?></span> <span class="threetitle">元红包</span></div>
							<?php  } ?>
						</div>
					</div>

					<?php  } else { ?>
						<div class="bigtitle"> <?php  echo $coupon['title3'];?></div>
						<div class="subtitle"> <?php  echo $coupon['title2'];?></div>
					<?php  } ?>

					<div class="usetime">
						<div class="text">有效期:<?php  if($coupon['timestr']=='0') { ?>
							永久有效
							<?php  } else { ?>
							<?php  if($coupon['timestr']=='1') { ?>
							即<?php  echo $coupon['gettypestr'];?>日内 <?php  echo $coupon['timedays'];?> 天有效
							<?php  } else { ?>
							有效期 <?php  echo $coupon['timestr'];?>
							<?php  } ?>
							<?php  } ?></div>
					</div>

				</div>
			</div>

			<div class="detail-body">
				<?php  if($coupon['money'] > 0||$coupon['credit']>0) { ?>
				<div class="block">
					<div class="title">所需金额与积分</div>
					<?php  if($coupon['money'] > 0) { ?>
					<div class="text dot">所需金额:<?php  echo $coupon['money'];?></div>
					<?php  } ?>
					<?php  if($coupon['credit'] > 0) { ?>
					<div class="text dot">所需积分:<?php  echo $coupon['credit'];?></div>
					<?php  } ?>
				</div>
				<?php  } ?>

				<div class="block">

					<?php  if(!empty($coupon['merchname'])) { ?>
					<div class="title">使用商铺</div>
					<div class="text dot">限购[<?php  echo $coupon['merchname'];?>]店铺商品</div>
					<?php  } ?>
					<div class="title">使用说明</div>
					<div class="text">
						<?php  if(empty($coupon['descnoset'])) { ?>
							<?php  if(empty($coupon['coupontype'])) { ?>
								<?php  echo htmlspecialchars_decode($set['consumedesc'])?>
							<?php  } else { ?>
								<?php  echo htmlspecialchars_decode($set['rechargedesc'])?>
							<?php  } ?>
						<?php  } else { ?>
							<?php  echo $coupon['desc'];?>
						<?php  } ?>
					</div>
				</div>

				<div class="block">
					<div class="title">领取限制</div>
					<?php  if($coupon['islimitlevel']=='1') { ?>
						<div class="text dot">用户必须达到以下条件之一:</div>
						<?php  if(!empty($coupon['limitmemberlevels'])|| $coupon['limitmemberlevels']==='0') { ?>
						<div class="text dot">	会员:  <?php  echo $meblvname;?>
							<?php  if(is_array($level1)) { foreach($level1 as $l1) { ?>
								<?php  echo $l1['levelname'];?>
							<?php  } } ?>
						</div>
						<?php  } ?>
						<?php  if((!empty($coupon['limitagentlevels']) || $coupon['limitagentlevels']==='0')&&$hascommission) { ?>
							<div class="text dot">		<?php  echo $leveltitle2;?>:
								<?php  if(in_array("0",$limitagentlevels)) { ?>
								<?php  echo $commissionname;?>
								<?php  } ?>
								<?php  if(is_array($level2)) { foreach($level2 as $l2) { ?>
								<?php  echo $l2['levelname'];?>
								<?php  } } ?>
							</div>
						<?php  } ?>
						<?php  if((!empty($coupon['limitpartnerlevels']) || $coupon['limitpartnerlevels']==='0')&&$hasglobonus) { ?>
							<div class="text dot">	<?php  echo $leveltitle3;?>:  <?php  echo $globonuname;?>
								<?php  if(is_array($level3)) { foreach($level3 as $l3) { ?>
								<?php  echo $l3['levelname'];?>
								<?php  } } ?>
							</div>
						<?php  } ?>
						<?php  if((!empty($coupon['limitaagentlevels']) || $coupon['limitaagentlevels']==='0')&&$hasabonus) { ?>
							<div class="text dot">	<?php  echo $leveltitle4;?>:  <?php  echo $abonuname;?>
								<?php  if(is_array($level4)) { foreach($level4 as $l4) { ?>
								<?php  echo $l4['levelname'];?>
								<?php  } } ?>
							</div>
						<?php  } ?>
					<?php  } ?>
					<?php  if($coupon['islimitlevel']=='0') { ?>
						<div class="text dot">无</div>
					<?php  } ?>
				</div>
				<div class="block">
					<div class="title">有效期限</div>
					<div class="text dot">


						<?php  if($coupon['timestr']=='0') { ?>
						永久有效
						<?php  } else { ?>
						<?php  if($coupon['timestr']=='1') { ?>
						即<?php  echo $coupon['gettypestr'];?>日内 <?php  echo $coupon['timedays'];?> 天有效
						<?php  } else { ?>
						有效期 <?php  echo $coupon['timestr'];?>
						<?php  } ?>
						<?php  } ?>
						<?php  if(!empty($coupon['merchname'])) { ?>
						限购[<?php  echo $coupon['merchname'];?>]店铺商品
						<?php  } ?></div>
				</div>
				<div class="block">
					<div class="title">使用限制</div>
					<?php  if($coupon['coupontype']=='2') { ?>
						<div class="text dot">本优惠卷只能在收银台中使用</div>
					<?php  } ?>

					<?php  if($coupon['limitdiscounttype']=='1') { ?>
						<div class="text dot">不允许与促销优惠同时使用</div>
					<?php  } else if($coupon['limitdiscounttype']=='2') { ?>
						<div class="text dot">不允许与会员折扣同时使用</div>
					<?php  } else if($coupon['limitdiscounttype']=='3') { ?>
						<div class="text dot">不允许与促销优惠和会员折扣同时使用</div>
					<?php  } ?>
					<?php  if($coupon['limitgoodtype']=='1') { ?>
					<div class="text dot">允许以下商品使用:</div>
						<?php  if(is_array($goods)) { foreach($goods as $g) { ?>
							<div class="text"><?php  echo $g['title'];?></div>
						<?php  } } ?>
					<?php  } ?>
					<?php  if($coupon['limitgoodcatetype']=='1') { ?>
						<div class="text dot">允许以下商品分类使用:</div>
						<div class="text">
							<?php  if(is_array($category)) { foreach($category as $c) { ?>
							<?php  echo $c['name'];?>&nbsp;
							<?php  } } ?>
						</div>
					<?php  } ?>
					<?php  if($coupon['limitgoodtype']=='0'&& $coupon['limitgoodcatetype']=='0'&&$coupon['limitdiscounttype']=='0'&&$coupon['coupontype']!='2') { ?>
						<div class="text dot">无</div>
					<?php  } ?>
				</div>
			</div>
		</div>
	</div>

	<div class="fui-navbar">
		<?php  if($coupon['canget']===false) { ?>
		<div class="nav-item btn btn-gray" >已达到<?php  echo $coupon['gettypestr'];?>上限</div>
		<?php  } else if($pass) { ?>
		<div class="nav-item btn btn-<?php  echo $coupon['color'];?>"  id="btncoupon">立即<?php  echo $coupon['gettypestr'];?></div>
		<?php  } else { ?>
		<div class="nav-item btn btn-gray">未达到<?php  echo $coupon['gettypestr'];?>权限</div>
		<?php  } ?>
	</div>
</div>



<script language='javascript'>
	require(['biz/sale/coupon/detail'], function (modal) {
		modal.init({coupon: <?php  echo json_encode($coupon)?>});
	});
</script>
<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
