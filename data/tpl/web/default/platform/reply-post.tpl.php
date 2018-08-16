<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/header', TEMPLATE_INCLUDEPATH)) : (include template('common/header', TEMPLATE_INCLUDEPATH));?>
<div class="new-keyword">
	<ol class="breadcrumb we7-breadcrumb">
		<a href="<?php  echo url('platform/reply', array('m' => $m));?>"><i class="wi wi-back-circle"></i> </a>
		<li>
			<a href="<?php  echo url('platform/reply', array('m' => $m));?>">自动回复管理</a>
		</li>
		<li>
			<?php  if(empty($rid)) { ?><a href="<?php  echo url('platform/reply/post', array('m' => $m));?>">新建<?php  echo $module['title'];?></a></li><?php  } ?>
			<?php  if(!empty($rid)) { ?><a href="<?php  echo url('platform/reply/post', array('m' => $m, 'rid' => $rid))?>">编辑<?php  echo $module['title'];?></a></li><?php  } ?>
		</li>
	</ol>
	<?php  if($m == 'keyword' || $m == 'userapi' || !in_array($m, $sysmods)) { ?>
	<form id="reply-form" action="<?php  echo url('platform/reply/post', array('m' => $m, 'rid' => $rid))?>" method="post" enctype="multipart/form-data" ng-controller="KeywordReply" ng-cloak>
		<div class="we7-form">
		<input type="hidden" name="rid" value="<?php  echo $rid;?>" />
		<div class="form-group">
			<label for="" class="control-label col-sm-2">规则名称</label>
			<div class="form-controls col-sm-8">
				<input type="text" class="form-control" placeholder="请输入回复规则的名称" name="rulename" value="<?php  if(empty($reply['name']) && !empty($_GPC['name'])) { ?><?php  echo $_GPC['name'];?><?php  } else { ?><?php  echo $reply['name'];?><?php  } ?>">
				<span class="help-block">您可以给这组触发关键字规则起一个名字, 方便下次修改和查看. </span>
			</div>
		</div>
		<div class="form-group" ng-show="reply.showAdvance">
			<label for="" class="control-label col-sm-2">全局置顶</label>
			<div class="form-controls col-sm-8">
				<input id="istop-1" type="radio" name="istop" ng-model="reply.entry.istop" ng-value="1" value="1" <?php  if(intval($reply['displayorder'] >= 255)) { ?> checked="checked"<?php  } ?>/>
				<label for="istop-1" >是</label>
				<input id="istop-2" type="radio" name="istop" ng-model="reply.entry.istop" ng-value="0" value="0" <?php  if(intval($reply['displayorder'] < 255)) { ?> checked="checked"<?php  } ?>/>
				<label for="istop-2">否</label>
			</div>
			
		</div>
		<div class="form-group" ng-show="reply.entry.istop == 0 && reply.showAdvance">
			<label for="" class="control-label col-sm-2">回复优先级</label>
			<div class="form-controls col-sm-4">
			<input type="number" min="0" max="254" name="displayorder_rule" value="<?php  if(intval($reply['displayorder']) < 255) { ?><?php  echo $reply['displayorder'];?><?php  } ?>" placeholder="输入这条回复规则的优先级" class="form-control">
			<span class="help-block">
				规则优先级，越大则越靠前，最大不得超过254
			</span>
			</div>
		</div>
		<div class="form-group">
			<label for="" class="control-label col-sm-2">&nbsp;</label>
			<div class="form-controls color-default col-sm-8">
				<a href="javascript:void(0);" ng-click="changeShowAdvance()"><span ng-show="!reply.showAdvance">展开</span><span ng-show="reply.showAdvance">收起</span>高级设置<i class="fa fa-chevron-down" ng-class="{'fa-chevron-down': !reply.showAdvance, 'fa-chevron-up': reply.showAdvance}"></i></a>
			</div>
		</div>
		<div class="form-group">
			<input type="hidden" name="keywords">
			<input type="hidden" name="reply_type">
			<label class="control-label col-sm-2">触发关键字</label>
			<div class="form-controls col-sm-8 we7-padding-bottom">
				<input id="reply_type-1" type="radio" name="reply_type" ng-model="reply.entry.reply_type" ng-value="2" value="2" <?php  if(intval($reply['reply_type'] == 2)) { ?> checked="checked"<?php  } ?>/>
				<label for="reply_type-1" >单一</label>
				<input id="reply_type-2" type="radio" name="reply_type" ng-model="reply.entry.reply_type" ng-value="1" value="1" <?php  if(intval($reply['reply_type'] == 1)) { ?> checked="checked"<?php  } ?>/>
				<label for="reply_type-2">混合</label>
			</div>
			
			<div class="form-controls col-sm-8 col-sm-offset-2" ng-if="reply.entry.reply_type == 2">
				<div class="input-group">
					<div class="input-group-btn">
						<div class="font-default">
							<select name="trigger_type" class="we7-select" ng-model="reply.advanceTrigger" ng-change="changeTriggerType()">
								<option value="exact" ng-selected="!reply.advanceTrigger">精准触发</option>
								<option value="indistinct" ng-selected="reply.advanceTrigger">模糊触发</option>
							</select>
						</div>
					</div>
					<input type="text" class="keyword-input form-control" max="100" id="keyword-input-exact" ng-model="reply.keyword.exact" ng-blur="checkKeyWord($event);" data-type="keyword" style="color: #555!important;">
					<span class="input-group-btn"><a href="javascript:;" class="btn btn-default" id="keyword-emoji" ng-init="initEmotion();"><i class="fa fa-github-alt"></i> 表情</a></span>
				</div>
				<span class="help-block">多个关键字请使用逗号隔开，如天气，今日天气</span>
				<span class="help-block"></span><!-- 提示语标签，勿删 -->
			</div>

			<div class="form-controls col-sm-8 col-sm-offset-2" ng-if="reply.entry.reply_type == 1">
				<div class="we7-form">
					<div class="form-group">
						<label for="" class="control-label col-sm-3">精准触发</label>
						<div class="form-controls input-group col-sm-6">
							<input type="text" class="keyword-input form-control" max="30" id="keyword-exact" ng-model="reply.keyword.exact" ng-blur="checkKeyWord($event);" data-type="keyword">
							<span class="input-group-btn"><a href="javascript:;" class="btn btn-default" id="emoji-exact" ng-init="initEmotion();"><i class="fa fa-github-alt"></i> 表情</a></span>
						</div>
						<span></span><span class="help-block"></span><!-- 提示语标签，勿删 -->
					</div>
					<div class="form-group">
						<label for="" class="control-label col-sm-3">包含关键字触发</label>
						<div class="form-controls input-group col-sm-6">
							<input type="text" class="keyword-input form-control" max="30" id="keyword-indistinct" ng-model="reply.keyword.contain" ng-blur="checkKeyWord($event);" data-type="keyword">
							<span class="input-group-btn"><a href="javascript:;" class="btn btn-default" id="emoji-indistinct"><i class="fa fa-github-alt"></i> 表情</a></span>
						</div>
						<span></span><span class="help-block"></span><!-- 提示语标签，勿删 -->
					</div>
					<div class="form-group">
						<label for="" class="control-label col-sm-3">正则匹配关键字触发</label>
						<div class="form-controls input-group col-sm-6">
							<input type="text" class="keyword-input form-control" max="30" id="keyword-regexp" ng-model="reply.keyword.regexp" ng-blur="checkKeyWord($event);" data-type="keyword">
							<span class="help-block">只支持一条正则</span>
						</div>
						<span></span><span class="help-block"></span><!-- 提示语标签，勿删 -->
					</div>
					<span class="help-block"></span>
				</div>
				<span class="help-block">多个关键字请使用逗号隔开，如天气，今日天气</span>
			</div>				

		</div>
		<div class="form-group">
			<label for="" class="control-label col-sm-2">是否开启</label>
			<div class="form-controls col-sm-8">
				<label>
					<input name="status" class="form-control" ng-model="reply.status" ng-hide="1">
					<div class="switch" ng-class="{'switchOn': reply.status}" ng-click="changeStatus()"></div>
				</label>
				<span class="help-block">您可以选择临时禁用这条回复.</span>
			</div>
		</div>
		</div>
		<div class="form-group">
			<nav class="navbar navbar-wxapp-bottom navbar-fixed-bottom" role="navigation">
				<div class="container">
					<div class="pager">
						<input type="submit" name="submit" value="发布" class="reply-form-submit hidden">
						<span value="发布" class="btn btn-primary" ng-click="submitForm()">发布</span>
					</div>
				</div>
			</nav>
		</div>
		<div class="form-group">
			<?php  if($m ==  'userapi') { ?>
				<?php  echo module_build_form('userapi', $rid)?>
			<?php  } else if(in_array($m, $sysmods)) { ?>
				<?php  echo module_build_form('core', $rid, array('basic'=> false,'news'=> false,'image'=> false,'music'=> false,'voice'=> false,'video'=> false))?>
			<?php  } else { ?>
				<?php  echo module_build_form($m, $rid)?>
			<?php  } ?>
		</div>
		<input type="hidden" name="token" value="<?php  echo $_W['token'];?>">
	</form>
	<?php  } ?>
	<?php  if($m == 'apply') { ?>
	<div id="js-apply" ng-controller="ApplyReply" ng-cloak>
		<div class="we7-step">
			<ul>
				<li class="active">
					1 选择应用 
				</li>
				<li>
					2 添加应用关键字
				</li>
			</ul>
		</div>
		<ul class="letters-list">
			<li ng-repeat="letter in alphabet" ng-style="{'background-color': letter == activeLetter ? '#ddd' : 'none'}" ng-class="{'active': letter == activeLetter}" ng-click="searchModule(letter)">
				<a href="javascript:;" ng-bind="letter"></a>
			</li>
		</ul>
		<?php  if($moudles) { ?>
		<div class="keyword-app">
			<ul class="we7-list-group">
			<?php  if($enable_modules) { ?>
				<?php  if(is_array($enable_modules)) { foreach($enable_modules as $key => $row) { ?>
				<?php  if(empty($current_user_permission_types) || in_array($key, $current_user_permission_types) || $row['issystem'] == 1) { ?>
				<li data-pinyin="<?php  echo $row['title_first_pinyin'];?>" ng-show="activeLetter ? activeLetter == '<?php  echo $row['title_first_pinyin'];?>' : '1'">
					<div class="we7-list-item">
						<!-- <span class="keyword-status" title="已启用"><i class="fa fa-2x fa-check-circle text-success"></i></span> -->
						<div class="keyword-app-info">
							<img src="<?php  echo $row['icon'];?>" alt="<?php  echo $row['title'];?>"/>
							<p><?php  echo $row['title'];?></p>
							<a href="<?php  if($row['type'] != 'system') { ?>./index.php?c=home&a=welcome&do=ext&m=<?php  echo $row['name'];?><?php  } else { ?>./index.php?c=platform&a=reply<?php  } ?>" class="cover-dark">
								<span class="cover-circle">进入</span>
							</a>
						</div>
					</div>
				</li>
				<?php  } ?>
				<?php  } } ?>
			<?php  } ?>
			<?php  if($unenable_modules) { ?>
				<?php  if(is_array($unenable_modules)) { foreach($unenable_modules as $key => $row) { ?>
				<li data-pinyin="<?php  echo $row['title_first_pinyin'];?>" ng-show="activeLetter == <?php  echo $row['title_first_pinyin'];?>">
					<div class="we7-list-item">
						<!-- <span class="keyword-status" title="未启用"><i class="fa fa-2x fa-times-circle text-warning"></i></span> -->
						<div class="keyword-app-info">
							<img src="<?php  echo $row['icon'];?>" alt="<?php  echo $row['title'];?>"/>
							<p><?php  echo $row['title'];?></p>
							<a href="<?php  if($row['type'] != 'system') { ?>./index.php?c=home&a=welcome&do=ext&m=<?php  echo $row['name'];?><?php  } else { ?>./index.php?c=platform&a=reply<?php  } ?>" class="cover-dark">
								<span class="cover-circle">进入</span>
							</a>
						</div>
					</div>
				</li>
				<?php  } } ?>
			<?php  } ?>
			</ul>
		</div>
		<?php  } ?>
	</div>
	<?php  } ?>
</div>
<script>
<?php  if($m == 'keyword' || $m == 'userapi' || !in_array($m, $sysmods)) { ?>
require(['underscore'], function() {
	angular.module('replyFormApp').value('config', {
		replydata: <?php  echo json_encode($reply)?>,
		uniacid: <?php  echo json_encode($_W['uniacid'])?>,
		links: {
			postUrl: "<?php  echo url('platform/reply/post', array('m' => $_GPC['m']));?>",
		},
	});
	angular.bootstrap($('#reply-form'), ['replyFormApp']);
});
<?php  } ?>
<?php  if($m == 'apply') { ?>
	angular.bootstrap($('#js-apply'), ['replyFormApp']);
<?php  } ?>
</script>
<?php (!empty($this) && $this instanceof WeModuleSite || 0) ? (include $this->template('common/footer', TEMPLATE_INCLUDEPATH)) : (include template('common/footer', TEMPLATE_INCLUDEPATH));?>