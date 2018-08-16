<?php defined('IN_IA') or exit('Access Denied');?><div class="wrapper">
	<!--控制台-->
	<div class="control">
		<div class="editor_section">
			<div class='form-group-title'>基本信息</div>
			<!--卡券商户资料-->

			<!--卡券排序-->
			<div class="form-group">
				<label for="" class="col-lg control-label">排序</label>
				<div class="col-sm-8 col-xs-12">
					<input id="displayorder" name="displayorder"  type="text" class="input form-control" value="<?php  echo $item['displayorder'];?>"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?> />
					<p class='help-block'>数字越大越靠前</p>
				</div>

			</div>

			<!--卡券类型-->
			<div class="form-group">
				<label for="" class="col-lg control-label">分类</label>
				<div class="input-group-select col-sm-8 col-xs-12">
					<?php if( ce('sale.wxcard' ,$item) ) { ?>
					<select name='catid' class='form-control select2' style="width: 180px;">
						<option value=''></option>
						<?php  if(is_array($category)) { foreach($category as $k => $c) { ?>
						<option value='<?php  echo $k;?>' <?php  if($item['catid']==$k) { ?>selected<?php  } ?>><?php  echo $c['name'];?></option>
						<?php  } } ?>
					</select>
					<?php  } else { ?>
					<div class='form-control-static'><?php  if(empty($item['catid'])) { ?>暂时无分类<?php  } else { ?> <?php  echo $category[$item['catid']]['name'];?><?php  } ?></div>
					<?php  } ?>
					<p class='help-block'></p>
				</div>

			</div>

			<!--卡券标题-->
			<div class="form-group">
				<label for="" class="col-lg control-label must">卡券标题</label>
				<div class="col-sm-8 col-xs-12">
					<input id="title" name="title"  type="text" maxlength="9" class="input must form-control" value="<?php  echo $item['title'];?>"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?> />
					<p class='help-block'>卡券标题不能超过9个字符,此参数一旦创建则无法修改</p>
				</div>

			</div>

			<!--卡券库存-->
			<div class="form-group">
				<label for="" class="col-lg control-label must">库存总量</label>
				<div class="col-sm-8 col-xs-12">
					<input id="quantity" name="quantity"  type="text" class="input must form-control" value="<?php  echo $item['total_quantity'];?>"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?>  />
					<p style="display: none" class='error'>卡券库存总量必须大于1</p>
					<p class='help-block'>总共发布的库存总量，必须大于1</p>
				</div>
				<span class="pul-left" style="line-height: 32px;">份</span>
			</div>


			<div class="form-group user">
				<label for="" class="col-lg control-label must">logo图片</label>
				<div class="col-sm-8 col-xs-12">
					<input type="hidden" name="logourl" id="logourl" value="<?php  echo $item['logo_url'];?>"/>
					<input type="hidden" name="logolocalpath" id="logolocalpath" value=""/>
					<a class="btn btn-default upload" href="javascript:void(0);"   <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } else { ?> id="uploadlogo"<?php  } ?><?php  } else { ?>disabled<?php  } ?> >上传图片</a>
					<p class='help-block'>卡券的商户logo，建议像素为300*300。图片大小不能超过1mb,仅支持jpg,png格式</p>
				</div>
			</div>

			<!--商铺名称-->
			<div class="form-group limit">
				<label for="" class="col-lg control-label must">商铺名称</label>
				<div class="col-sm-8 col-xs-12">
					<input type="text" id="brand_name" name="brand_name"  maxlength="12" class='input must form-control' value="<?php  echo $item['brand_name'];?>"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?> >
					<p class='help-block'>商铺名称限制在12个字符之内,此参数一旦创建则无法修改</p>
				</div>
			</div>

			<!--卡券颜色-->
			<div class="form-group control-group color">
				<label for="" class="col-lg control-label">卡券背景色</label>
				<div class="col-sm-8 col-xs-12">
					<div class="btn-groups btn-group ">
						<input type="hidden" name="color" id="color" value="<?php  if(empty($id)) { ?>Color010<?php  } else { ?><?php  echo $item['color'];?><?php  } ?>"/>
						<?php if( ce('sale.wxcard' ,$item) ) { ?>
						<a id="selected-color2" class="dropdown-toggle" data-toggle="dropdown"><i class="<?php  if(empty($id)) { ?>Color010<?php  } else { ?><?php  echo $item['color'];?><?php  } ?>"></i><span class="caret pull-right"></span></a>
						<?php  } else { ?>
						<a class="dropdown-toggle" ><i class="<?php  if(empty($id)) { ?>Color010<?php  } else { ?><?php  echo $item['color'];?><?php  } ?>"></i><span class="caret pull-right"></span></a>
						<?php  } ?>
						<ul class="dropdown-menu" style="width:180px;">
							<li style="display:inline-block;">
								<div id="colorpalette3"></div>
							</li>
						</ul>
					</div>
				</div>

			</div>

			<?php  if(empty($type)) { ?>
			<!--起用金额-->
			<div class="form-group limit">
				<label for="" class="col-lg control-label">起用金额</label>
				<div class="col-sm-8 col-xs-12">
					<input type="text" id="least_cost" name="least_cost" class='input form-control' value="<?php  echo floatval($item['least_cost'])/100?>" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?> >
					<p class='help-block'>消费满多少可用, 空或0 不限制,此参数一旦创建则无法修改</p>
				</div>
				<span class="pull-left" style="line-height: 32px">元</span>
			</div>

			<!--减免金额-->
			<div class="form-group limit">
				<label for="" class="col-lg control-label must">减免金额</label>
				<div class="col-sm-8 col-xs-12">
					<input type="text" id="reduce_cost" name="reduce_cost" class='input must form-control' value="<?php  echo floatval($item['reduce_cost'])/100?>" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?> >
					<p class='help-block'>可抵扣的金额,此参数一旦创建则无法修改</p>
				</div>
				<span class="pull-left" style="line-height: 32px">元</span>
			</div>

			<?php  } else { ?>
				<!--折扣额度-->
				<div class="form-group limit">
					<label for="" class="col-lg control-label must">折扣额度</label>
					<div class="col-sm-8 col-xs-12">
						<input type="text" id="discount" name="discount" class='input must form-control' value="<?php  echo $discount;?>" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?> >
						<p class='help-block'>请填写1-9.9之间的数字，精确到小数点后1位,此参数一旦创建则无法修改</p>
					</div>
					<span class="pull-left" style="line-height: 32px">折</span>
				</div>
			<?php  } ?>

			<!--是否分享-->
			<div class="form-group caption">
				<label for="" class="col-lg control-label">卡券分享</label>
				<div class="col-sm-8 col-xs-12">
					<label class="checkbox-inline">
						<input type="checkbox"  name="can_share" id="can_share"  <?php  if(!empty($item['can_share'])) { ?>checked<?php  } ?>  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?>   / >用户可以分享领券链接
					</label>
					<label class="checkbox-inline">
						<input type="checkbox"  name="can_give_friend" id="can_give_friend"  <?php  if(!empty($item['can_give_friend'])) { ?>checked<?php  } ?>  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?>  />用户领券后可转赠其他好友
					</label>
				</div>
			</div>

			<!--有效期-->
			<div class="form-group date">
				<label for="" class="col-lg control-label must">有效期</label>
				<div class="col-sm-8 col-xs-12">
					<label class="radio-inline">
					<input type="hidden" id="datetypevalue" name="datetypevalue" value="<?php  echo $item['datetype'];?>">
					<input type="radio" name="datetype" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled <?php  } ?><?php  } else { ?>disabled<?php  } ?>  <?php  if($item['datetype']=='DATE_TYPE_FIX_TIME_RANGE') { ?>checked<?php  } ?>  value="DATE_TYPE_FIX_TIME_RANGE" />
					固定日期
					</label>
					<?php  echo tpl_form_field_daterange('beginendtime', array('start'=>date('Y-m-d H:i', $starttime),'end'=>date('Y-m-d H:i', $endtime)),false);?>
				</div>
			</div>
			<div class="form-group" style="margin-top: -15px">
				<label for="" class="col-lg control-label "></label>
				<div class="col-sm-8 col-xs-12">
					<label for="" class="radio-inline" style="padding-top: 0">
						<input style="margin-top: -8px" type="radio" name="datetype" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled <?php  } ?><?php  } else { ?>disabled<?php  } ?>    <?php  if($item['datetype']=='DATE_TYPE_FIX_TERM') { ?>checked<?php  } ?>  value="DATE_TYPE_FIX_TERM" />
						领取后
					</label>
						<span style="display: inline-block;padding: 10px 0;" >
							<select style="height: 30px;" class='input'  id="fixed_begin_term" name="fixed_begin_term" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?> >
						<option value="0">当天</option>
							<?php  for($i=1;$i<=90;$i++)
							{
								if($item['fixed_begin_term']==$i)
								{
									echo '<option value="'.$i.'" selected>'.$i.'</option>';
								}else
								{
									echo '<option value="'.$i.'">'.$i.'</option>';
								}
						}
						?>
							</select>
							<span>生效，有效天数</span>
					<select style="height: 30px;" class='input'  id="fixed_term" name="fixed_term" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?> >
							<?php  for($i=1;$i<=90;$i++)
							{
								if($item['fixed_term']==$i)
								{
									echo '<option value="'.$i.'" selected>'.$i.'</option>';
								}else
								{
									echo '<option value="'.$i.'">'.$i.'</option>';
								}
						}
						?>

							</select>
						</span>
				</div>
			</div>
		</div>

		<!--优惠详情-->
		<div class="editor_section">
			<div class='form-group-title'><b>卡券详情</b></div>
			<!--领券限制-->
			<div class="form-group receive">
				<label  class="col-lg control-label">领券限制</label>
				<div class="col-sm-8 col-xs-12">
					<input type="text" id="get_limit" name="get_limit" class='input form-control' value="<?php  echo $item['get_limit'];?>" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?> />
					<p class='help-block'>每个用户领券上限，必须大于1如不填，则默认为1</p>
				</div>
				<span class="pull-left" style="line-height: 32px">张</span>
			</div>

			<!--领券限制-->
			<div class="form-group receive">
				<label  class="col-lg control-label must">使用限制</label>
				<div class="col-sm-8 col-xs-12">
					<input type="text" id="use_limit" name="use_limit" class='input form-control' value="<?php  echo $item['use_limit'];?>" <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?> />
					<p class='help-block'>每个用户可以使用此卡券的次数,必须大于1如不填，则默认为1</p>
				</div>
				<span class="pull-left" style="line-height: 32px">张</span>
			</div>


			<?php  if(empty($type)) { ?>

			<!--使用条件-->
			<div class="form-group ">
				<label  class="col-lg control-label must">使用条件</label>
				<div class="col-sm-8 col-xs-12">
					<label class="checkbox-inline">
						<input type="checkbox"  name="use_condition" id="use_condition"   <?php  if(!empty($item['use_condition'])) { ?>checked<?php  } ?>   <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?>  />适用范围（至少填写一项）
					</label>
				</div>
			</div>
			<div id="showuse_condition"   <?php  if(empty($item['use_condition'])) { ?>style="display: none"<?php  } ?>>
			<div class="form-group">
				<label  class="col-lg control-label">适用商品</label>
				<div class="col-sm-8 col-xs-12">
					<input type="text" id="accept_category" name="accept_category" value="<?php  echo $item['accept_category'];?>" maxlength="15" class="input form-control"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?>   />
					<p class='error' style="display: none">适用商品不能为空且长度不能超过15个汉字或英文字母</p>
					<p class='help-block'>填写本券适用的商品、类目或服务,不能超过15个汉字或英文字母(仅卡券显示用)</p>
				</div>
			</div>
			<div class="form-group">
				<label  class="col-lg control-label ">不适用商品</label>
				<div class="col-sm-8 col-xs-12">
					<input type="text" id="reject_category" name="reject_category" value="<?php  echo $item['reject_category'];?>"  maxlength="15" class="input form-control"  <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  } else { ?>disabled<?php  } ?>   />
					<p class='error' style="display: none">适用商品不能为空且长度不能超过15个汉字或英文字母</p>
					<p class='help-block'>填写本券适用的商品、类目或服务,不能超过15个汉字或英文字母(仅卡券显示用)</p>
				</div>
			</div>
		</div>
			<?php  } ?>

			<!--优惠共享-->
			<div  class="form-group">
				<label  class="col-lg control-label">优惠共享</label>
				<div class="col-sm-8 col-xs-12">
							<select style="height: 30px;" id="can_use_with_other_discount" name="can_use_with_other_discount" class="input " <?php if( ce('sale.wxcard' ,$item) ) { ?><?php  if(!empty($id)) { ?>disabled<?php  } ?><?php  } else { ?>disabled<?php  } ?> >
							<option value="1" <?php  if($item['can_use_with_other_discount']=='1') { ?>selected<?php  } ?>>可与其他优惠共享</option>
							<option value="0" <?php  if($item['can_use_with_other_discount']=='0') { ?>selected<?php  } ?>>不与其他优惠共享</option>
							</select>
					<p class='help-block'>使用条件的设置会在券上展示，请务必仔细确认。(仅卡券显示用)</p>
				</div>

			</div>

			<!--使用须知-->
			<div class="form-group">
				<label  class="col-lg control-label">使用须知</label>
				<div class="col-sm-8 col-xs-12">
					<textarea  name="description" rows="5" class="form-control"><?php  echo $item['description'];?></textarea>
					<p class='help-block'>文本长度不能超过300字</p>
				</div>
			</div>

			<!--图文介绍-->
			<!--<div class="control-group">
                <div class="frm_label pull-left">
                    图文介绍
                </div>
                <button></button>
            </div>-->



		</div>
	</div>
</div>
<script>
	$('#colorpalette3').colorPalette()
			.on('selectColor', function(e) {
				$('#selected-color2 i').css('background-color', e.color);
			});

</script>
