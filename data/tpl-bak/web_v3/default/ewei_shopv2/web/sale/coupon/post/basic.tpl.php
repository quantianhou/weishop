<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
	<label class="col-lg control-label">排序</label>
	<div class="col-sm-9 col-xs-12">
		<?php if( ce('sale.coupon' ,$item) ) { ?>
		<input type="text" name="displayorder" class="form-control" value="<?php  echo $item['displayorder'];?>"  />
		<span class='help-block'>数字越大越靠前</span>
		<?php  } else { ?>
		<div class='form-control-static'><?php  echo $item['displayorder'];?></div>
		<?php  } ?>
	</div>
</div>

<div class="form-group">
	<label class="col-lg control-label must"> 优惠券名称</label>
	<div class="col-sm-9 col-xs-12">
		<?php if( ce('sale.coupon' ,$item) ) { ?>
		<input type="text" name="couponname" class="form-control" value="<?php  echo $item['couponname'];?>" data-rule-required="true"  />
		<?php  } else { ?>
		<div class='form-control-static'><?php  echo $item['couponname'];?></div>
		<?php  } ?>
	</div>
</div>

<div class="form-group">
	<label class="col-lg control-label"> 标签名称</label>
	<div class="col-sm-9 col-xs-12">
		<?php  if($type == 2) { ?>
			<?php if( ce('sale.coupon' ,$item) ) { ?>
			<input type="text" name="tagname" class="form-control" value="<?php  echo $item['tagname'];?>" data-rule-required="true"  />
			<?php  } else { ?>
			<div class='form-control-static'><?php  echo $item['tagname'];?></div>
			<?php  } ?>
		<?php  } else { ?>
			<?php if( ce('sale.coupon' ,$item) ) { ?>
			<input type="text" name="tagname" class="form-control" value="<?php  echo $item['tagname'];?>" data-rule-required="true"  />
			<?php  } else { ?>
			<div class='form-control-static'><?php  echo $item['tagname'];?></div>
			<?php  } ?>
		<?php  } ?>

	</div>
</div>


<div class="form-group">
	<label class="col-lg control-label">分类</label>
	<div class="col-sm-9 col-xs-12">
		<?php if( ce('sale.coupon' ,$item) ) { ?>
		<select name='catid' class='form-control select2'>
			<option value=''></option>
			<?php  if(is_array($category)) { foreach($category as $k => $c) { ?>
			<option value='<?php  echo $k;?>' <?php  if($item['catid']==$k) { ?>selected<?php  } ?>><?php  echo $c['name'];?></option>
			<?php  } } ?>
		</select>
		<?php  } else { ?>
		<div class='form-control-static'><?php  if(empty($item['catid'])) { ?>暂时无分类<?php  } else { ?> <?php  echo $category[$item['catid']]['name'];?><?php  } ?></div>
		<?php  } ?>
	</div>
</div>
<div class="form-group">
	<label class="col-lg control-label">缩略图</label>
	<div class="col-sm-9 col-xs-12">
		<?php if( ce('sale.coupon' ,$item) ) { ?>
		<?php  echo tpl_form_field_image2('thumb', $item['thumb'])?>
		<?php  } else { ?>
		<input type="hidden" name="thumb" value="<?php  echo $item['thumb'];?>"/>
		<?php  if(!empty($item['thumb'])) { ?>
		<a href='<?php  echo tomedia($item['thumb'])?>' target='_blank'>
		   <img src="<?php  echo tomedia($item['thumb'])?>" style='width:100px;border:1px solid #ccc;padding:1px' />
		</a>
		<?php  } ?>
		<?php  } ?>
	</div>
</div>
<div class="form-group">
	<label class="col-lg control-label">使用条件</label>
	<div class="col-sm-9 col-xs-12">
		<?php  if($type == 2) { ?>
			<?php if( ce('sale.coupon' ,$item) ) { ?>
			<input type="text" name="enough" class="form-control" value="到店使用"  readonly/>
			<?php  } else { ?>
			<div class='form-control-static'>到店使用</div>
			<?php  } ?>
		<?php  } else { ?>
			<?php if( ce('sale.coupon' ,$item) ) { ?>
			<input type="text" name="enough" class="form-control" value="<?php  echo $item['enough'];?>"  />
			<span class='help-block' ><?php  if(empty($type)) { ?>消费<?php  } else { ?>充值<?php  } ?>满多少可用, 空或0 不限制</span>
			<?php  } else { ?>
			<div class='form-control-static'><?php  if($item['enough']>0) { ?>满 <?php  echo $item['enough'];?> 可用 <?php  } else { ?>不限制<?php  } ?></div>
			<?php  } ?>
		<?php  } ?>

	</div>
</div>

<?php  if($type == 2) { ?>
<div class="form-group">
	<label class="col-lg control-label"> 兑换礼品名称</label>
	<div class="col-sm-9 col-xs-12">
		<?php if( ce('sale.coupon' ,$item) ) { ?>
		<input type="text" name="giftname" class="form-control" value="<?php  echo $item['giftname'];?>" data-rule-required="true"  />
		<?php  } else { ?>
		<div class='form-control-static'><?php  echo $item['giftname'];?></div>
		<?php  } ?>
	</div>
</div>
<?php  } ?>
<?php if( ce('sale.coupon' ,$item) ) { ?>

<div class="form-group">
	<label class="col-lg control-label">使用时间限制</label>

	
	<div class="col-sm-7">
		<div class='input-group'>
			<span class='input-group-addon'>
				<label class="radio-inline" style='margin-top:-5px;' ><input type="radio" name="timelimit" value="0" <?php  if($item['timelimit']==0) { ?>checked<?php  } ?>>获得后</label>
			</span>

			<input type='text' class='form-control' name='timedays' value="<?php  echo $item['timedays'];?>" />
			<span class='input-group-addon'>天内有效(空为不限时间使用)</span>
		</div>
	</div>
 
</div>

<div class="form-group">
	<label class="col-lg control-label"></label>
	<div class="col-sm-3">
		<div class='input-group'>
			<span class='input-group-addon'>
				<label class="radio-inline" style='margin-top:-5px;' ><input type="radio" name="timelimit" value="1" <?php  if($item['timelimit']==1) { ?>checked<?php  } ?>>在日期</label>
			</span>
			<?php  echo tpl_form_field_daterange('time', array('starttime'=>date('Y-m-d', $starttime),'endtime'=>date('Y-m-d', $endtime)));?>
			<span class='input-group-addon'>内有效</span>
		</div>
	</div>
	 

</div>
<?php  } else { ?>
<div class="form-group">
	<label class="col-lg control-label">使用时间限制</label>
 
	<div class="col-sm-9 col-xs-12">
		<div class='form-control-static'>
			<?php  if($item['timelimit']==0) { ?>
			<?php  if(!empty($item['timedays'])) { ?>获得后 <?php  echo $item['timedays'];?> 天内有效<?php  } else { ?>不限时间<?php  } ?>
			<?php  } else { ?>
			<?php  echo date('Y-m-d',$starttime)?> - <?php  echo date('Y-m-d',$endtime)?>  范围内有效
			<?php  } ?></div>
	</div>
</div>
<?php  } ?>

<?php  if(empty($type)) { ?>
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/coupon/post/consume', TEMPLATE_INCLUDEPATH)) : (include template('sale/coupon/post/consume', TEMPLATE_INCLUDEPATH));?>
<?php  } else if($type==1) { ?>
	<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('sale/coupon/post/recharge', TEMPLATE_INCLUDEPATH)) : (include template('sale/coupon/post/recharge', TEMPLATE_INCLUDEPATH));?>
<?php  } else if($type==2) { ?>
<!--cashier-->
<?php  } ?>


  


         <div class="form-group">
                <label class="col-lg control-label">发放总数</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('sale.coupon' ,$item) ) { ?>
                    <input type="text" name="total" class="form-control" value="<?php  echo $item['total'];?>"  />
                    <span class='help-block' >优惠券总数量，没有不能领取或发放,-1 为不限制张数</span>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  if($item['total']==-1) { ?>无限数量<?php  } else { ?>剩余 <?php  echo $item['total'];?> 张<?php  } ?></div>
                    <?php  } ?>
                </div>
   </div>
<!--efwww_com-->
<!--店铺信息-->
<div class="form-group">
	<label class="col-lg control-label">店铺</label>
	<!--<div class="col-sm-9 col-xs-12">-->
	<!--<select class="form-control tpl-category-parent select2" id="storeid" name="storeid">-->
	<!--<option value="0">全部店铺</option>-->
	<?php  if(is_array($store)) { foreach($store as $store_item) { ?>
	<!--<option value="<?php  echo $store_item['id'];?>" <?php  if($item['storeid'] == $store_item['id']) { ?>selected="true"<?php  } ?>><?php  echo $store_item['storename'];?></option>-->
	<?php  } } ?>
	<!--</select>-->
	<!--</div>-->
	<div class="col-sm-9 col-xs-12">
		<table>
			<tbody><tr>
				<td>
					<div id="allBranchDiv" class="box" style="width: 350px; height: 450px; border: 1px solid #F8F8F8;overflow:auto;background:#F8F8F8">
						<div class="box-header well" style="background:#F8F8F8;border: 0px;">
							<input type="hidden" id="allBranchUrl" name="allBranchUrl" value="<?php  echo webUrl('shop/store/me')?>">
							<input type="hidden" id="selectedBranchUrl" name="selectedBranchUrl" value="<?php  echo webUrl('sale/coupon/me')?>">
							<input id="branchName" name="branchName" size="16" type="text" value="" style="width:180px;border: 1px solid #cccccc;background-color: #fff;border-radius:3px; height: 30px; line-height: 30px;">
							<button type="button" onclick="searchBranch()" class="btn btn-primary btn-sm" style="margin-top: 5px;">查 询</button>
						</div>
						<div class="box-content">
							<div class="row-fluid">
								<div style="overflow:auto;height: 400px;background:#F8F8F8; ">
									<ul id="ulId" class="allBranch">

									</ul>
								</div>
							</div>
						</div>
					</div>
				</td>
				<td>
					<img src="<?php  echo EWEI_SHOPV2_LOCAL?>static/images/move.png">
				</td>
				<td>
					<div id="selectedBranchDiv" class="box" style="width: 350px; height: 450px; border: 1px solid #F8F8F8;background:#F8F8F8">
						<div class="box-header well" style="background:#F8F8F8;border: 0px">
							<span style="color: blue;margin-left: 85%;cursor:pointer" onclick="cleanSelected()">清 空</span>
						</div>
						<div class="box-content">
							<div class="row-fluid">
								<div style="overflow:auto;height: 400px;background:#F8F8F8 ">
									<ul id="selectUlId" class="selectBranch">
									</ul>
								</div>
							</div>
						</div>
					</div>
				</td>
			</tr>
			</tbody></table>
	</div>
</div>
<!--23333-->