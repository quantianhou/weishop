<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<style>
	.label{line-height: 1.8}
</style>
<?php  if($do != 'credit_record' && $do != 'credit_stat') { ?>
<div class="we7-page-title">会员管理</div>
<ul class="we7-page-tab">
	<li <?php  if($do == 'display' || $do == 'post') { ?> class="active"<?php  } ?>>
	<a href="<?php  echo url('mc/member')?>">会员管理</a>
	</li>
	<li <?php  if($_GPC['a'] == 'group') { ?> class="active"<?php  } ?>>
	<a href="<?php  echo url('mc/group')?>">会员组</a>
	</li>
	<li<?php  if($do == 'uc') { ?> class="active"<?php  } ?>>
	<a href="<?php  echo url('site/editor/uc')?>">会员中心</a>
	</li>
	<li <?php  if($do == 'quickmenu') { ?> class="active"<?php  } ?>>
	<a href="<?php  echo url('site/editor/quickmenu', array('type' => 4))?>">快捷菜单</a>
	</li>
	<li <?php  if($do == 'credit_setting') { ?> class="active"<?php  } ?>>
	<a href="<?php  echo url('mc/member/credit_setting')?>">积分设置</a>
	</li>
	<li <?php  if($do == 'register_setting') { ?> class="active"<?php  } ?>>
	<a href="<?php  echo url('mc/member/register_setting')?>">注册设置</a>
	</li>
</ul>
<?php  } else { ?>
<ul class="nav nav-tabs">
	<li <?php  if($do  == 'credit_stat') { ?> class="active"<?php  } ?>><a href="<?php  echo url('mc/member/credit_stat', array('type' => 1, 'uid' => $uid));?>">数据概况</a></li>
	<li <?php  if($do  == 'credit_record' &&  $type == 'credit1') { ?> class="active"<?php  } ?>><a href="<?php  echo url('mc/member/credit_record', array('type' => 'credit1', 'uid' => $uid));?>">积分日志</a></li>
	<li <?php  if($do  == 'credit_record' &&  $type == 'credit2') { ?> class="active"<?php  } ?>><a href="<?php  echo url('mc/member/credit_record', array('type' => 'credit2', 'uid' => $uid));?>">余额日志</a></li>
</ul>
<?php  } ?>
<?php  if($do=='display') { ?>
<div class="alert we7-page-alert">
	<p><i class="wi wi-info-sign"></i> 当前会员总数:<strong class="text-danger"><?php  echo $stat['total'];?></strong>。今日新增会员:<strong class="text-danger"><?php  echo $stat['today'];?></strong>。昨日新增会员:<strong class="text-danger"><?php  echo $stat['yesterday'];?></strong>。<br></p>
	<p><strong class="text-danger">
		<i class="wi wi-info-sign"></i> 会员的总积分 = 账户积分 + 账户贡献. 会员所在的会员组是根据 "总积分的多少" 和 "会员组的变更规则" (<根据总积分多少自动升价> 或 <根据总积分多少只升不降>) 自动匹配.<br>
	</strong></p>
</div>
<div class="panel we7-panel" ng-app="member" ng-controller="display" ng-cloak>
	<div class="panel-heading">筛选</div>
	<div class="panel-body we7-padding">
		<form action="./index.php" method="get" class="we7-form" role="form">
			<input type="hidden" name="c" value="mc">
			<input type="hidden" name="a" value="member">
			<div class="form-group">
				<label class="col-sm-2 control-label">注册时间</label>
				<div class="col-sm-5">
					<?php  echo tpl_form_field_daterange('createtime', array('starttime' => date('Y-m-d', $starttime), 'endtime' => date('Y-m-d', $endtime),));?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">昵称/姓名/手机号码/openid</label>
				<div class="col-sm-5">
					<div class="input-group">
						<div class="input-group-btn">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
								{{ searchMod == 2 ? '模糊' : '精准' }}
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu" style="width: 10px;">
							<li><a href="javascript:;" ng-click="setSearchMod('2')">模糊</a></li>
							<li><a href="javascript:;" ng-click="setSearchMod('1')">精准</a></li>
						</ul>
							</div>
						<input type="text" class="form-control" name="username" value="<?php  echo $_GPC['username'];?>" />
						<input type="text" name="search_mod" ng-model="searchMod" style="display:none;"/>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">会员编号</label>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="uid" value="<?php  echo $_GPC['uid'];?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">所属用户组</label>
				<div class="col-sm-5">
					<select name="groupid" class="form-control">
						<option value="" selected="selected">不限</option>
						<?php  if(is_array($groups)) { foreach($groups as $group) { ?>
						<option value="<?php  echo $group['groupid'];?>"<?php  if($group['groupid'] == $_GPC['groupid']) { ?> selected="selected"<?php  } ?>><?php  echo $group['title'];?></option>
						<?php  } } ?>
					</select>
				</div>
				<div class="pull-right col-sm-5">
					<button class="btn btn-default"><i class="fa fa-search"></i> 搜索</button>
					<input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
					<input class="btn btn-primary" type="submit" name="export_submit" id="export_submit" value="导出">
					<a href="<?php  echo url('mc/member/add');?>" class="btn btn-primary">添加会员</a>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
	angular.module('member', []).controller('display', function($scope) {
		$scope.searchMod = "<?php  echo $search_mod;?>";

		$scope.setSearchMod = function(type) {
			$scope.searchMod = type;
		};
	});
</script>
<form method="post" class="we7-form" id="form1">
			<table class="table we7-table table-hover vertical-middle">
				<input type="hidden" name="do" value="del" />
				<col width="40px"/>
				<col width="90px"/>
				<col width="150px"/>
				<col />
				<col />
				<col />
				<col width="90px"/>
				<col width="210px"/>
				<tr>
					<th class="text-left">删除</th>
					<th >会员编号</th>
					<th>昵称/真实姓名</th>
					<th>手机</th>
					<th >邮箱</th>
					<th class="text-left">余额/积分</th>
					<th >注册时间</th>
					<th class="text-right">操作</th>
				</tr>
				<?php  if(is_array($list)) { foreach($list as $li) { ?>
				<tr>
					<td style="overflow: hidden;">
						<input type="checkbox" we7-check-all="1" name="uid[]" id="uid-<?php  echo $li['uid'];?>" value="<?php  echo $li['uid'];?>" class="">
						<label for="uid-<?php  echo $li['uid'];?>">&nbsp;</label>
					</td>
					<td><?php  echo $li['uid'];?></td>
					<td>
						<?php  if($li['nickname']) { ?><?php  echo $li['nickname'];?><?php  } else { ?>未完善<?php  } ?>
						<br>
						<?php  if($li['realname']) { ?><?php  echo $li['realname'];?><?php  } else { ?>未完善<?php  } ?>
					</td>
					<td><?php  if($li['mobile']) { ?><?php  echo $li['mobile'];?><?php  } else { ?>未完善<?php  } ?></td>
					<td><?php  if($li['email_effective'] == 1) { ?><?php  echo $li['email'];?><?php  } else { ?>未完善<?php  } ?></td>
					<td class="text-left">
						<span class="label label-primary">余额：<?php  echo $li['credit2'];?></span>
						<br>
						<span class="label label-warning">积分：<?php  echo $li['credit1'];?></span>
					</td>
					<td ><?php  echo date('Y-m-d H:i',$li['createtime'])?></td>
					<td style="overflow:visible;">
						<div class="link-group">
							<a href="javascript:;" title="积分" class="modal-trade-credit1" data-type="credit1" data-uid="<?php  echo $li['uid'];?>">积分</a>
							<a href="javascript:;" title="余额" class="modal-trade-credit2" data-type="credit2" data-uid="<?php  echo $li['uid'];?>">余额</a>
							<a href="javascript:;" title="消费" class="modal-trade-consume" data-type="consume" data-uid="<?php  echo $li['uid'];?>">消费</a>
							<div class="btn-group">
								<a class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<?php  if(count($_W['account']['groups']) > 0) { ?>
										<?php  echo $_W['account']['groups'][$li['groupid']]['title'];?>
										<span class="caret"></span>
									<?php  } else { ?>
										暂无分组
									<?php  } ?>
								</a>
								<ul class="dropdown-menu more-group">
									<?php  if(is_array($_W['account']['groups'])) { foreach($_W['account']['groups'] as $group) { ?>
									<?php  if($group ['groupid'] != $li['groupid']) { ?>
									<li class="user-group" data-id="<?php  echo $group['groupid'];?>" data-uid="<?php  echo $li['uid'];?>"><a href="javascript:;"><?php  echo $group['title'];?></a></li>
									<?php  } ?>
									<?php  } } ?>
								</ul>
							</div>
							<a href="<?php  echo url('mc/member/post',array('uid' => $li['uid']))?>" title="编辑" class="">编辑</a>
							<a href="<?php  echo url('mc/member/credit_stat',array('uid' => $li['uid'], 'type' => 1))?>" title="日志" class="">日志</a>
							<!--<a href="">管理设置</a>-->
							<a href="<?php  echo url('mc/member/del',array('uid' => $li['uid']))?>" onclick="return confirm('确定删除会员吗?')" title="删除" class="">删除</a>
						</div>
					</td>
				</tr>
				<?php  } } ?>
				<!--<tr>
					<td>
						
					</td>
					<input name="token" type="hidden" value="<?php  echo $_W['token'];?>" />
					<td colspan="7" class="text-left"></td>
				</tr>-->
			</table>
			<div class="we7-margin-left">
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
				<input type="checkbox" we7-check-all="1" id="check-all" name="uid[]" onclick="var ck = this.checked;$(':checkbox').each(function(){this.checked = ck});">
				<label for="check-all"> &nbsp;</label>
				<input type="submit" name="submit" class="btn btn-danger btn-submit" value="删除">
			</div>

	<div class="pull-right">
		<?php  echo $pager;?>
	</div>
</form>
<script>
	require(['trade'], function(trade){
		trade.init();

		$('#form1').submit(function(){
			if($(":checkbox[name='uid[]']:checked").size() > 0){
				return confirm('删除后不可恢复，您确定删除吗？');
			}
			util.message('没有选择会员', '', 'error');
			return false;
		});

		$('.user-group').click(function(){
			if(!confirm('确定更改会员组吗')) return false;
			var id = $(this).data('id');
			var uid = $(this).data('uid');
			$.post("<?php  echo url('mc/member/group');?>", {uid: uid, id: id}, function(data){
				if(data != 'success') {
					util.message(data, '', 'error');
				} else {
					location.reload();
				}
			});
		});
	});
	$(".more-group").each(function(){
		if($(this).children('li').length == 0){
			$(this).append("<li><a href=\"javascript:;\">没有更多分组了</a></li>");
		}
	});
</script>
<?php  } ?>

<?php  if($do=='post') { ?>
<ol class="breadcrumb we7-breadcrumb">
	<a href="<?php  echo url('mc/member/display')?>"><i class="wi wi-back-circle"></i> </a>
	<li>
		<a href="<?php  echo url('mc/member/display')?>">会员管理</a>
	</li>
	<li>
		编辑会员
	</li>
</ol>
<div class="main">
	<form action="" class="form-horizontal form" method="post" enctype="multipart/form-data">
		<?php  if(!empty($profile)) { ?>
		<div class="panel panel-default">
			<div class="panel-heading">修改密码</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">新密码</label>
					<div class="col-sm-9 col-xs-12">
						<input type="password" class="form-control" name="newpwd" value="" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">重复新密码</label>
					<div class="col-sm-9 col-xs-12">
						<input type="password" class="form-control" name="rnewpwd" value="" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"></label>
					<div class="col-sm-9 col-xs-12">
						<a href="javascript:;" id="updatepwd" class="btn btn-primary">修改密码</a>
						<span class="label"></span>
					</div>
				</div>
			</div>
		</div>
		<?php  } ?>
		<div class="panel panel-default">
			<input type="hidden" name="uid" value="<?php  echo $uid;?>" />
			<input type="hidden" name="fanid" value="<?php  echo $_GPC['fanid'];?>" />
			<input type="hidden" name="email_effective" value="<?php  echo $profile['email_effective'];?>" />
			<div class="panel-heading">
				基本资料
			</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">头像</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('avatar', $profile['avatar']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">用户UID</label>
					<div class="col-sm-9 col-xs-12">
						<input type="text" class="form-control" name="" value="<?php  echo $uid;?>" readonly="readonly">
					</div>
				</div>
				<div class="form-group hide">
					<label class="col-sm-2 control-label">所在会员组</label>
					<div class="col-sm-9 col-xs-12">
						<select name="groupid" class="form-control" disabled>
							<?php  if(is_array($groups)) { foreach($groups as $group) { ?>
							<option value="<?php  echo $group['groupid'];?>" <?php  if($profile['groupid'] == $group['groupid']) { ?>selected<?php  } ?>><?php  echo $group['title'];?></option>
							<?php  } } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">昵称</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('nickname',$profile['nickname']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">真实姓名</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('realname',$profile['realname']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">性别</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('gender',$profile['gender']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">生日</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('birth',array('year' => $profile['birthyear'],'month' => $profile['birthmonth'],'day' => $profile['birthday']));?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">户籍</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('reside',array('province' => $profile['resideprovince'],'city' => $profile['residecity'],'district' => $profile['residedist']));?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">详细地址</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('address',$profile['address']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">手机</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('mobile',$profile['mobile']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">QQ</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('qq',$profile['qq']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Email</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('email',$profile['email']);?>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">联系方式</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">固定电话</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('telephone',$profile['telephone']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">MSN</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('msn',$profile['msn']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">阿里旺旺</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('taobao',$profile['taobao']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">支付宝帐号</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('alipay',$profile['alipay']);?>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default" id="tip">
			<div class="panel-heading">收货地址</div>
			<div class="panel-body">
				<div id="address">
					<?php  if(is_array($addresss)) { foreach($addresss as $address) { ?>
					<div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-9 col-xs-12">
							<label  data-id="<?php  echo $address['id'];?>"><?php  echo $address['province'];?>-<?php  echo $address['city'];?>-<?php  echo $address['district'];?>-<?php  echo $address['address'];?><?php  if(!empty($address['isdefault'])) { ?><span id="default">【默认收货地址】</span><?php  } ?></label>
				<span class="help-block">
					<a href="javascript:" id="" class="isdefault" data-id="<?php  echo $address['id'];?>"><?php  if(!$address['isdefault']) { ?>设为默认<?php  } ?></a>
					<a href="javascript:" data-toggle="modal" data-target="#myModal" class="editaddr" data-id="<?php  echo $address['id'];?>" data-username="<?php  echo $address['username'];?>" data-mobile="<?php  echo $address['mobile'];?>" data-zipcode="<?php  echo $address['zipcode'];?>" data-province="<?php  echo $address['province'];?>" data-city="<?php  echo $address['city'];?>" data-district="<?php  echo $address['district'];?>" data-address="<?php  echo $address['address'];?>">编辑</a>
					<a href="javascript:" class="deleteaddr" data-id="<?php  echo $address['id'];?>">删除</a>
				</span>
						</div>
					</div>
					<?php  } } ?>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label"></label>
					<div class="col-sm-9 col-xs-12">
						<button type="button" id="add" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
							添加收货地址
						</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">添加收货地址</h4>
					</div>
					<div class="modal-body">
						<div class="addaddress">
							<div class="form-group">
								<label class="col-sm-2 control-label">姓名</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" name="name" value="<?php  echo $_GPC['record']['username'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">手机号</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" name="phone" value="<?php  echo $_GPC['record']['mobile'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">邮编</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" name="code" value="<?php  echo $_GPC['record']['zipcode'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">收货地址</label>
								<div class="col-sm-9 col-xs-12">
									<?php  echo tpl_form_field_district("addaddress", array('province' => $_GPC['record']['province'], 'city' => $_GPC['record']['city'], 'district' => $_GPC['record']['district']));?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label">详细地址</label>
								<div class="col-sm-9 col-xs-12">
									<input class="form-control" name="detail" value="<?php  echo $_GPC['record']['address'];?>">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"></label>
								<div class="col-sm-9 col-xs-12">
									<a href="javascript:"  id="submitaddr" data-id="" data-op="" class="btn btn-default btn-primary">提交</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script>
			$(function() {
				$('#add').click(function () {
					$('#myModalLabel').html('添加收货地址');
					$('[name="name"]').val('');
					$('[name="addaddress[province]"]').val('');
					$('[name="addaddress[city]"]').val('');
					$('[name="addaddress[district]"]').val('');
					$('[name="detail"]').val('');
					$('[name="code"]').val('');
					$('[name="phone"]').val('');
					$('#submitaddr').data('op', 'addaddress');
				});
				$('#address').on('click', ".editaddr", function() {
					$('#myModalLabel').html('修改收货地址');
					$('#submitaddr').data('op', 'editaddress');
					$('#edit').prop('id', '');
					$(this).prop('id', 'edit');
					$('[name="name"]').val($(this).data('username'));
					$('[name="addaddress[province]"]').val($(this).data('province'));
					$('[name="addaddress[province]"]').trigger('change');
					$('[name="addaddress[city]"]').val($(this).data('city'));
					$('[name="addaddress[city]"]').trigger('change');
					$('[name="addaddress[district]"]').val($(this).data('district'));

					$('[name="detail"]').val($(this).data('address'));
					$('[name="code"]').val($(this).data('zipcode'));
					$('[name="phone"]').val($(this).data('mobile'));
					$('#submitaddr').data('id', $(this).data('id'));
				});
				$('#address').on('click', ".isdefault",function () {
					var uid = <?php  echo $_GPC['uid'];?>;
					var id = $(this).data('id');
					var obj = $(this);
					$.post("<?php  echo url('mc/member/post' ,array('op' => 'isdefault'))?>", {uid : uid, id: id}, function(data) {
						if (data.message.errno) {
							$('#default').parent().next().children(':first').html('设为默认');
							$('#default').remove();
							obj.parent().prev().html(obj.parent().prev().html()+'<span id="default">【默认收货地址】</span>');
							obj.html('');
						}
					},'json');
				});
				$('#address').on('click', '.deleteaddr', function () {
					if (!confirm('确认删除收货地址')) {
						return false;
					}
					var id = $(this).data('id');
					var obj = $(this);
					$.post("<?php  echo url('mc/member/post', array('op' => 'del'));?>", {id : id}, function(data) {
						if (data.message.errno) {
							obj.parent().parent().remove();
						}
					}, 'json');
				});
				$('#submitaddr').click(function () {
					var name = $('[name="name"]').val();
					if (name == '') {
						util.message('请填写收货人姓名');
						return false;
					}
					var province = $('[name="addaddress[province]"]').val();
					var city = $('[name="addaddress[city]"]').val();
					var district = $('[name="addaddress[district]"]').val();
					var detail = $('[name="detail"]').val();
					var uid = <?php  echo $_GPC['uid'];?>;
					var code = $('[name="code"]').val();
					var phone = $('[name="phone"]').val();
					if (phone == '') {
						util.message('请填写收货人手机号');
						return false;
					}
					var reg = /^1\d{10}$/;
					if (!reg.test(phone)) {
						util.message('请填写正确的手机号码');
						return false;
					}
					if (city == '') {
						util.message('请填写收货地址');
						return false;
					}
					if (detail == '') {
						util.message('请填写详细收货地址');
						return false;
					}
					$('#myModal').modal('hide');
					var id = $(this).data('id');
					var op = $(this).data('op');
					$.post("./index.php?c=mc&a=member&do=post&op="+op, {province : province, city : city, district : district, detail : detail, uid : uid, name : name, code : code, phone : phone, id : id}, function(data) {
						var post = data.message.message;
						if (data.message.errno) {
							if (op == 'editaddress') {
								$('#edit').data({'username' : post.username, 'id' : post.id, 'mobile' : post.mobile, 'zipcode' : post.zipcode, 'province' : post.province, 'city' : post.city, 'district' : post.district, 'address' : post.address});
								var html = $('#edit').parent().prev().html();
								var edithtml = post.province + ' - ' + post.city + ' - ' + post.district + ' - '+ post.address;
								if (html.indexOf('默认收货地址') > -1) {
									edithtml += '<span id="default">【默认收货地址】</span>';
								}
								$('#edit').parent().prev().html(edithtml);
								util.message('修改成功');
							} else{
								var isdefault = '';
								if (post.isdefault == 1) {
									isdefault += '<span id="default">【默认收货地址】</span>';
								}
								$('#address').append(
										'<div class="form-group">' +
										'   <label class="col-sm-2 control-label"></label>' +
										'   <div class="col-sm-9 col-xs-12">'+
										'<label  data-id='+post.id+'>'+post.province+'-'+post.city+'-'+post.district+'-'+post.address+isdefault+'</label>'+
										'       <span class="help-block">' +
										'           <a href="javascript:" class="isdefault" data-id="'+post.id+'">设为默认</a>'+
										'           <a href="javascript:" class="editaddr" data-toggle="modal" data-target="#myModal" data-id="'+post.id+'" data-username="'+post.username+'" data-mobile="'+post.mobile+'" data-zipcode="'+post.zipcode+'" data-province="'+post.province+'" data-city="'+post.city+'" data-district="'+post.district+'" data-address="'+post.address+'">编辑</a>'+
										'           <a href="javascript:" class="deleteaddr" data-id="'+post.id+'">删除</a>'+
										'       </span>'+
										'   </div>'+
										'</div>'
								);
								util.message('添加成功');
							}
						}
					}, 'json');
				});
			});
		</script>

		<div class="panel panel-default">
			<div class="panel-heading">教育情况</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">学号</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('studentid',$profile['studentid']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">班级</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('grade',$profile['grade']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">毕业学校</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('graduateschool',$profile['graduateschool']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">学历</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('education',$profile['education']);?>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">工作情况</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">公司</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('company',$profile['company']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">职业</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('occupation',$profile['occupation']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">职位</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('position',$profile['position']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">年收入</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('revenue',$profile['revenue']);?>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-default">
			<div class="panel-heading">个人情况</div>
			<div class="panel-body">
				<div class="form-group">
					<label class="col-sm-2 control-label">星座</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('constellation',$profile['constellation']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">生肖</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('zodiac',$profile['zodiac']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">国籍</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('nationality',$profile['nationality']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">身高</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('height',$profile['height']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">体重</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('weight',$profile['weight']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">血型</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('bloodtype',$profile['bloodtype']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">身份证号</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('idcard',$profile['idcard']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">邮编</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('zipcode',$profile['zipcode']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">个人主页</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('site',$profile['site']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">情感状态</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('affectivestatus',$profile['affectivestatus']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">交友目的</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('lookingfor',$profile['lookingfor']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">自我介绍</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('bio',$profile['bio']);?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">兴趣爱好</label>
					<div class="col-sm-9 col-xs-12">
						<?php  echo tpl_fans_form('interest',$profile['interest']);?>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">其他信息</div>
			<div class="panel-body">
				<?php  if(is_array($custom_fields)) { foreach($custom_fields as $field) { ?>
				<div class="form-group">
					<label class="col-sm-2 control-label"><?php  echo $all_fields[$field];?></label>
					<div class="col-sm-9 col-xs-12">
						<input name="<?php  echo $field;?>" class="form-control" value="<?php  echo $profile[$field];?>">
					</div>
				</div>
				<?php  } } ?>
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-12">
				<button type="submit" class="btn btn-primary col-lg-1" name="submit" value="提交">提交</button>
				<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
			</div>
		</div>
	</form>
</div>
<script type="text/javascript">
	$('#updatepwd').click(function(){
		var newpwd = $("input[name='newpwd']").val();
		var rnewpwd = $("input[name='rnewpwd']").val();
		if (newpwd.length < 6) {
			util.message('密码不得少于6个字符');
			return false;
		}
		if (newpwd != rnewpwd) {
			util.message('两次输入的密码不一致');
			return false;
		}
		var uid = window.location.href.substr(window.location.href.lastIndexOf('=') + 1);
		$.post(location.href, {uid:uid, password:newpwd}, function(resp){
			if(resp == 'success') {
				$('#updatepwd').next().removeClass().addClass('label label-success').text('密码修改成功');
				$("input[name='newpwd']").val('');
				$("input[name='rnewpwd']").val('');
				setTimeout(function() {$('#updatepwd').next().empty();}, 2000);
			} else if (resp == 'error') {
				$('#updatepwd').next().removeClass().addClass('label label-danger').text('会员不存在或已删除');
			} else {
				$('#updatepwd').next().removeClass().addClass('label label-danger').text('网络错误，请稍后重试');
			}
		});

	});
</script>
<?php  } ?>

<?php  if($do == 'add') { ?>
<form action="./index.php?c=mc&a=member&do=add" method="post" class="form-horizontal" role="form" id="form1">
	<div class="panel panel-info">
		<div class="panel-heading">添加会员</div>
		<div class="panel-body we7-padding">
			<div class="form-group">
				<label class="col-sm-2 control-label">会员姓名</label>
				<div class="col-sm-9 col-xs-12">
					<input type="text" name="realname" value="" class="form-control"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">手机号</label>
				<div class="col-sm-9 col-xs-12">
					<input type="text" name="mobile" value="" class="form-control"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">登陆密码</label>
				<div class="col-sm-9 col-xs-12">
					<input type="password" name="password" value="" class="form-control"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">邮箱</label>
				<div class="col-sm-9 col-xs-12">
					<input type="text" name="email" value="" class="form-control"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">积分</label>
				<div class="col-sm-9 col-xs-12">
					<input type="text" name="credit1" value="0" class="form-control"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">余额</label>
				<div class="col-sm-9 col-xs-12">
					<input type="text" name="credit2" value="0" class="form-control"/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">会员组</label>
				<div class="col-sm-9 col-xs-12">
					<select name="groupid" class="form-control">
						<?php  if(is_array($_W['account']['groups'])) { foreach($_W['account']['groups'] as $group) { ?>
						<option value="<?php  echo $group['groupid'];?>"><?php  echo $group['title'];?></option>
						<?php  } } ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-9 col-xs-12">
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>"/>
			<input type="hidden" name="form" value="<?php  echo $_W['token'];?>"/>
			<input type="submit" value="提交" class="btn btn-primary"/>
		</div>
	</div>
</form>
<script>
	require(['validator'], function(){
		$(function(){
			$('#form1').bootstrapValidator({
				fields: {
					realname: {
						validators: {
							notEmpty: {
								message: '姓名不能为空'
							}
						}
					},
					mobile: {
						validators: {
							notEmpty: {
								message: '手机不能为空'
							},
							regexp: {
								regexp: /1\d{10}/,
								message: '手机号格式不正确'
							},
							remote: {
								url: "<?php  echo url('mc/member/add');?>",
								data: function(validator) {
									return {
										type: 'mobile',
										data: validator.getFieldElements('mobile').val()
									};
								},
								message: '手机号已经被占用'
							}
						}
					},
					email: {
						validators: {
							regexp: {
								regexp: /\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/i,
								message: '邮箱格式不正确'
							},
							remote: {
								url: "<?php  echo url('mc/member/add');?>",
								data: function(validator) {
									return {
										type: 'email',
										data: validator.getFieldElements('email').val()
									};
								},
								message: '邮箱已经被占用'
							}
						}
					},
					password: {
						validators: {
							notEmpty: {
								message: '密码不能为空'
							},
							stringLength: {
								min: 8,
								max: 15,
								message: '密码最少为8位'
							}
						}
					},
					credit1: {
						validators: {
							regexp: {
								regexp: /^[0-9]\d*$/i,
								message: '积分格式不正确'
							}
						}
					},
					credit2: {
						validators: {
							regexp: {
								regexp: /^[0-9]\d*$/i,
								message: '余额格式不正确'
							}
						}
					}
				}
			});
		});
	});
</script>
<?php  } ?>

<?php  if($do == 'credit_record') { ?>
<div class="panel panel-default">
	<div class=" panel-body table-responsive">
		<table class="table table-hover vertical-middle">
			<thead>
			<tr>
				<th style="width:100px">账户类型</th>
				<th style="width:100px">操作员</th>
				<?php  if($type == 'credit2') { ?>
				<th style="width:100px">消费金额</th>
				<th style="width:100px">实收金额</th>
				<?php  } ?>
				<th style="width:100px">数量</th>
				<th style="width:100px">模块</th>
				<th style="width:200px">操作时间</th>
				<th>备注</th>
			</tr>
			</thead>
			<tbody>
			<?php  if(is_array($data)) { foreach($data as $da) { ?>
			<tr>
				<td><?php  echo $credits[$type];?></td>
				<td><?php  if($da['username']) { ?><?php  echo $da['username'];?><?php  } else { ?>系统操作<?php  } ?></td>
				<?php  if($type == 'credit2') { ?>
				<td><?php  echo abs($da['num'])?>元</td>
				<td><?php  echo abs($da['final_num'])?>元</td>
				<?php  } ?>
				<td><?php  echo $da['num'];?></td>
				<td>
					<?php  if(!empty($da['module']) && !empty($modules[$da['module']]['title'])) { ?>
					<?php  echo $modules[$da['module']]['title'];?>
					<?php  } else { ?>
					未知
					<?php  } ?>
				</td>
				<td><?php  echo date('Y-m-d H:i:s', $da['createtime'])?></td>
				<td style="white-space:normal"><?php  echo $da['remark'];?></td>
			</tr>
			<?php  } } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="pull-right">
	<?php  echo $pager;?>
</div>
<?php  } ?>

<?php  if($do == 'credit_stat') { ?>
<style>
	.account-stat{overflow:hidden; color:#666;}
	.account-stat .account-stat-btn{width:100%; overflow:hidden;}
	.account-stat .account-stat-btn > div{text-align:center; margin-bottom:5px;margin-right:2%; float:left;width:48%; padding-top:10px;font-size:16px; border-left:1px #DDD solid;}
	.account-stat .account-stat-btn > div:first-child{border-left:0;}
	.account-stat .account-stat-btn .stat{width:80%;margin:10px auto;font-size:15px}
</style>
<div class="panel panel-default" style="padding:1em">
	<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin: -1em -1em 1em -1em;">
		<div class="navbar-header">
			<a class="navbar-brand" href="javascript:;">数据统计</a>
			<ul class="nav navbar-nav navbar-right">
				<li <?php  if($_GPC['type'] == 1) { ?>class="active"<?php  } ?>><a href="<?php  echo url('mc/member/credit_stat', array('uid' => $uid, type => 1));?>">今日</a></li>
				<li <?php  if($_GPC['type'] == -1) { ?>class="active"<?php  } ?>><a href="<?php  echo url('mc/member/credit_stat', array('uid' => $uid, type => -1));?>">昨日</a></li>
				<form class="navbar-form navbar-left" role="search" id="form1">
					<input name="c" value="mc" type="hidden" />
					<input name="a" value="member" type="hidden" />
					<input name="do" value="credit_stat" type="hidden" />
					<input name="uid" value="<?php  echo $uid;?>" type="hidden" />
					<?php  echo tpl_form_field_daterange('datelimit', array('start' => date('Y-m-d', $starttime),'end' => date('Y-m-d', $endtime)), '')?>
				</form>
			</ul>
		</div>
	</nav>
	<div class="account-stat">
		<div class="account-stat-btn">
			<?php  if(is_array($credits)) { foreach($credits as $key => $li) { ?>
			<div>
				<strong><?php  echo $li;?></strong>
				<div id="rule" class="stat">
					<div>增加 <strong><span class="text-success"><?php  echo $data[$key]['add'];?></span></strong></div>
					<div>减少 <strong><span class="text-danger"><?php  echo $data[$key]['del'];?></span></strong></div>
				</div>
			</div>
			<?php  } } ?>
		</div>
	</div>
</div>
<script>
	require(['chart', 'daterangepicker'], function(c) {
		$('.daterange').on('apply.daterangepicker', function(ev, picker) {
			$('#form1')[0].submit();
		});
	});
</script>
<?php  } else if($do == 'credit_setting') { ?>

<div id="js-profile-credit" ng-controller="creditCtrl" ng-cloak>
	<table class="table we7-table table-hover vertical-middle">
		<col width="140px " />
		<col />
		<col width="140px" />
		<tr>
			<th class="text-left" colspan="4">支付参数</th>
		</tr>
		<tr ng-repeat="(key, credit) in creditSetting  track by key">
			<td >{{ key }}</td>
			<td>{{ credit.title }}</td>
			<td>
				<label>
					<input name="" class="form-control" type="checkbox"  style="display: none;">
					<div ng-class="credit.enabled == 1 ? 'switch switchOn' : 'switch'" ng-if="key != 'credit1' && key != 'credit2'"  ng-click="changeEnabled(key)"></div>
					<div ng-class="credit.enabled == 1 ? 'switch switchOn' : 'switch'" ng-if="key == 'credit1' || key == 'credit2'" style="opacity : 0.3"></div>
				</label>
			</td>
			<td class="pull-right"><div class="link-group"><a href="javascript:;" ng-click="editCreditName(key)">设置名称</a></div></td>
		</tr>
	</table>

	<table class="table we7-table table-hover table-form">
		<col width="140px " />
		<col />
		<col width="140px" />
		<tr>
			<th class="text-left" colspan="3">积分策略</th>
		</tr>
		<tr>
			<td >基本和营销</td>
			<td>
				{{ creditSetting[tactics.activity].title }}
			</td>
			<td class="text-center">
				<div class="link-group"><a href="javascript:;" ng-click="editCreditTactics('activity')">更改</a></div>
			</td>
		</tr>
		<tr>
			<td >交易和支付(余额)</td>
			<td>
				{{ creditSetting[tactics.currency].title }}
			</td>
			<td class="text-center">
				<div class="link-group"><a href="javascript:;" ng-click="editCreditTactics('currency')">更改</a></div>
			</td>
		</tr>
	</table>

	<div class="modal fade" id="credit-name" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="we7-modal-dialog modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="modal-title">设置积分名称</div>
				</div>
				<div class="modal-body">
					<div class="we7-form">
						<div class="form-group">
							<div class="form-controls">
								<input type="text" name="" class="form-control" ng-model="creditTitle" placeholder="输入积分名称" ng-model="paysetting.alipay.partner">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="setCreditName(key)" data-dismiss="modal">确定</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="tactics" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="we7-modal-dialog modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<div class="modal-title">设置积分策略</div>
				</div>
				<div class="modal-body">
					<div class="we7-form">
						<div class="form-group">
							<div class="form-controls">
								<select ng-model="tactics.activity" ng-if="activeTacticsType == 'activity'" class="form-control">
									<option ng-repeat="credit in enabledCredit" value="{{ credit }}" ng-selected="credit == tactics.activity">{{ creditSetting[credit].title }}</option>
								</select>

								<select ng-model="tactics.currency" ng-if="activeTacticsType == 'currency'" class="form-control">
									<option ng-repeat="credit in enabledCredit" value="{{ credit }}" ng-selected="credit == tactics.currency">{{ creditSetting[credit].title }}</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary" ng-click="setCreditTactics()" data-dismiss="modal">确定</button>
				</div>
			</div>
		</div>
	</div>

</div>

<script>
	angular.module('profileApp').value('config', {
		'creditSetting' : <?php  echo json_encode($credit_setting)?>,
		'enabledCredit' : <?php  echo json_encode($enable_credit)?>,
		'activity' : "<?php  echo $credit_tactics['activity'];?>",
		'currency' : "<?php  echo $credit_tactics['currency'];?>",
		'saveCreditSetting' : "<?php  echo url('mc/member/save_credit_setting')?>",
		'saveTacticsSetting' : "<?php  echo url('mc/member/save_tactics_setting')?>"
	});
	angular.bootstrap($('#js-profile-credit'), ['profileApp']);
</script>

<?php  } else if($do == 'register_setting') { ?>
	<form id="payform" action="<?php  echo url('mc/member/register_setting')?>" method="post" class="we7-form form">
		<div class="panel-body">
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">会员注册设置</label>
					<div class="col-sm-9 col-xs-12">
							<input id="radio-1" type="radio" name="passport[focusreg]" value="1" <?php  if(!empty($register_setting['focusreg'])) { ?> checked="checked"<?php  } ?>/>
						<label for="radio-1">
						会员手动注册
						</label>
							<input id="radio-2" type="radio" name="passport[focusreg]" value="0" <?php  if(empty($register_setting['focusreg'])) { ?> checked="checked"<?php  } ?>/>
						<label for="radio-2">

						系统自动注册
						</label>
						<span class="help-block">当设置为"系统自动注册"，用户从微信进入系统时，当模块使用"checkauth"验证用户身份时，可以在非登录状态下直接使用模块功能。</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">身份验证项</label>
					<div class="col-sm-9 col-xs-12">
						<input id="radio-3"  type="radio" name="passport[item]" value="mobile" <?php  if($register_setting['item'] == 'mobile') { ?>
						checked="checked"<?php  } ?>/>
						<label for="radio-3">
						手机注册
						</label>
							<input id="radio-4" type="radio" name="passport[item]" value="email" <?php  if($register_setting['item'] == 'email') { ?> checked="checked"<?php  } ?>/>
						<label for="radio-4">
						邮箱注册
						</label>
							<input id="radio-5" type="radio" name="passport[item]" value="random" <?php  if($register_setting['item'] == 'random' || empty($register_setting['item'])) { ?> checked="checked"<?php  } ?>/>
						<label for="radio-5">

						二者都行
						</label>
						<span class="help-block">该项设置用户注册时用户名的格式,如果设置为:"邮箱注册",系统会判断用户的注册名是否是邮箱格式</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">强制验证手机号/邮箱</label>
					<div class="col-sm-9 col-xs-12">
							<input id="radio-6" type="radio" name="passport[audit]" value="1" <?php  if($register_setting['audit'] == 1) { ?> checked="checked"<?php  } ?>/>
						<label for="radio-6">是</label>
						<input id="radio-7" type="radio" name="passport[audit]" value="0" <?php  if(empty($register_setting['audit'])) { ?> checked="checked"<?php  } ?>/>
						<label for="radio-7">否</label>
						<span class="help-block">设置强制验证手机号/邮箱，用户在注册的时候，系统会给用户的手机或邮箱发送验证码，以验证手机或邮箱的有效性</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-12 col-sm-3 col-md-2 control-label">身份验证方式</label>
					<div class="col-sm-9 col-xs-12">
						<input id="radio-8" type="radio" name="passport[type]" value="code" <?php  if($register_setting['type'] == 'code') { ?> checked="checked"<?php  } ?>/>
						<label for="radio-8">随机密码</label>
						<input id="radio-9" type="radio" name="passport[type]" value="password" <?php  if($register_setting['type'] == 'password' || empty($passport['type'])) { ?> checked="checked"<?php  } ?>/>
						<label for="radio-9">固定密码</label>
						<input id="radio-10" type="radio" name="passport[type]" value="hybird" <?php  if($register_setting['type'] == 'hybird') { ?> checked="checked"<?php  } ?>/>
						<label for="radio-10">混合密码</label>
						<span class="help-block">使用邮箱或者手机号+密码来登录系统</span>
						<span class="help-block">随机密码方式: 采用发送验证码的方式, 用户不需要记录密码. 在微信以外的渠道登录系统时, 需要输入手机或邮箱+验证码来进入系统</span>
						<span class="help-block">固定密码方式: 采用设置密码的方式, 用户在首次使用时设置固定的访问密码. 在微信以外的渠道登录系统时, 需要输入手机或邮箱+密码来进入系统</span>
						<span class="help-block">混合密码方式: 混合使用两种验证方式, 用户可以自己选择是否设置访问密码. 如果设置了访问密码, 那么登录是可以使用手机或邮箱+随机密码或固定密码来进入系统</span>
						<span class="help-block"><strong>注意: 使用随机密码或者混合密码时, 必须先 <a href="<?php  echo url('profile/notify');?>" target="_blank">设置邮件</a> 或<a href="<?php  echo url('cloud/sms')?>">短信</a></strong> 选项</strong></span>
					</div>
				</div>
			</div>
		<div class="form-group col-sm-12">
			<input type="submit" name="submit" value="提交" class="btn btn-primary col-lg-1" />
			<input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
		</div>
	</form>
<?php  } ?>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>
