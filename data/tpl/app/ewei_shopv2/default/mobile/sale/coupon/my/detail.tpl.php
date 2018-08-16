<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>
<link rel="stylesheet" type="text/css" href="../addons/ewei_shopv2/template/mobile/default/static/css/coupon-new.css?v=20170303112">

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
							<div class="subtitle"><span class="bold"><?php  echo $coupon['backmoney'];?> </span><span class="threetitle">元余额</span></div>
							<?php  } ?>
							<?php  if(!empty($coupon['backcredit']) && $coupon['backcredit'] > 0) { ?>
							<div class="subtitle"><span class="bold"><?php  echo $coupon['backcredit'];?> </span><span class="threetitle">积分</span></div>
							<?php  } ?>
							<?php  if(!empty($coupon['backredpack']) && $coupon['backredpack'] > 0) { ?>
							<div class="subtitle"><span class="bold"><?php  echo $coupon['backredpack'];?> </span><span class="threetitle">元红包</span></div>
							<?php  } ?>
						</div>
					</div>

					<?php  } else { ?>
					<div class="bigtitle"> <?php  echo $coupon['title3'];?></div>
					<div class="subtitle"> <?php  echo $coupon['title2'];?></div>
					<?php  } ?>

					<div class="usetime">
						<div class="text">
							<?php  if($coupon['timestr']=='0') { ?>
								永久有效
							<?php  } else { ?>
								<?php  if($coupon['timestr']=='1') { ?>
									即<?php  echo $coupon['gettypestr'];?>日内 <?php  echo $coupon['timedays'];?> 天有效
								<?php  } else { ?>
									<?php  echo $coupon['timestr'];?>
								<?php  } ?>
							<?php  } ?></div>
					</div>

				</div>
			</div>

			<div class="detail-body">

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
					<div class="title">有效期限</div>
					<div class="text dot"><?php  if($coupon['timestr']=='0') { ?>
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
					<div class="text "><?php  echo $g['title'];?></div>
					<?php  } } ?>
					<?php  } ?>
					<?php  if($coupon['limitgoodcatetype']=='1') { ?>
					<div class="text dot">允许以下商品分类使用:</div>
					<div class="text ">
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


		<?php  if(!empty($coupon['used'])) { ?>
		<div class="nav-item btn btn-gray" >已使用</div>
		<?php  } else if($coupon['past']) { ?>
		<div class="nav-item btn btn-gray" >已过期</div>
		<?php  } else { ?>
		<div class="nav-item btn btn-<?php  echo $coupon['color'];?>" >
		<a  href="<?php  echo $useurl;?>"  style="color:#ffffff;">
				<?php  if(empty($coupon['coupontype'])) { ?>
				立即去选商品使用
				<?php  } else if($coupon['coupontype']=='1') { ?>
				立即去充值
				<?php  } else { ?>
				返回我的优惠卷
				<?php  } ?>
		</a>
		</div>
		<?php  } ?>

	</div>
</div>

<?php (!empty($this) && $this instanceof WeModuleSite) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
