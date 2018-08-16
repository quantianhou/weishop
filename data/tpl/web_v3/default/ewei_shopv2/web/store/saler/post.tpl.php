<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>


<div class="page-header">
	当前位置：<span class="text-primary"><?php  if(!empty($item['id'])) { ?>编辑<?php  } else { ?>添加<?php  } ?>店员 <small><?php  if(!empty($item['id'])) { ?>修改【<?php  echo $item['salername'];?>】<?php  } ?></small></span>
</div>

<div class="page-content">
    <?php if(cv('store.saler.add')) { ?>
    <div class="page-sub-toolbar">
        <a class="btn btn-primary btn-sm" href="<?php  echo webUrl('store/saler/add')?>">添加新店员</a>
    </div>
    <?php  } ?>
    <form <?php if( ce('store.saler' ,$item) ) { ?>action="" method="post"<?php  } ?> class="form-horizontal form-validate" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php  echo $item['id'];?>" />
 

            <div class="form-group">
                <label class="col-lg control-label must">选择会员</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('store.saler' ,$item) ) { ?>
                       <?php  echo tpl_selector('openid',array('key'=>'openid', 'required'=>true, 'text'=>'nickname', 'thumb'=>'avatar','placeholder'=>'昵称/姓名/手机号','buttontext'=>'选择会员 ', 'items'=>$saler,'url'=>webUrl('member/query') ))?>
                    <?php  } else { ?>
                         <?php  if(!empty($saler)) { ?>
                         <span class='help-block'><img  style="width:100px;height:100px;border:1px solid #ccc;padding:1px" src="<?php  echo tomedia($saler['avatar'])?>"/><br/>
                             <?php  if(!empty($saler)) { ?><?php  echo $saler['nickname'];?>/<?php  echo $saler['realname'];?>/<?php  echo $saler['mobile'];?><?php  } ?></span>
                        <?php  } ?>
                    <?php  } ?>
                </div>
            </div>


            <div class="form-group">
                <label class="col-lg control-label must">店员姓名</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('store.saler' ,$item) ) { ?>
                    <input type="text" name="salername" class="form-control" value="<?php  echo $item['salername'];?>" data-rule-required='true'/>
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['salername'];?></div>
                    <?php  } ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg control-label must">手机号</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('store.saler' ,$item) ) { ?>
                    <input type="text" name="mobile" class="form-control" value="<?php  echo $item['mobile'];?>" />
                    <?php  } else { ?>
                    <div class='form-control-static'><?php  echo $item['mobile'];?></div>
                    <?php  } ?>
                </div>
            </div>

             <div class="form-group">
                <label class="col-lg control-label">所属门店</label>
                <div class="col-sm-9 col-xs-12">
                    <?php if( ce('store.saler' ,$item) ) { ?>
                      <?php  echo tpl_selector('storeid',array('text'=>'storename','preview'=>true,'type'=>'text',  'thumb'=>'avatar','placeholder'=>'门店名称','buttontext'=>'选择门店 ', 'items'=>$store,'url'=>webUrl('store/query')))?>
                      <span class='help-block'>如果不选择门店，则可以进行全门店的核销</span>
                    <?php  } else { ?>
                       <div class='form-control-static'><?php  if(empty($store['storename'])) { ?>全店核销<?php  } else { ?><?php  echo $store['storename'];?><?php  } ?></div>
                    <?php  } ?>
                </div>
            </div>

            <?php  if(p('newstore')) { ?>
                <div class="form-group">
                    <label class="col-lg control-label must">用户名</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('store.saler' ,$item) ) { ?>
                            <?php  if(empty($item['username'])) { ?>
                                <input type="text" name="username" class="form-control" value="<?php  echo $item['username'];?>" data-rule-required='true'/>
                            <?php  } else { ?>
                                <div class='form-control-static'><?php  echo $item['username'];?></div>
                            <?php  } ?>
                        <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $item['username'];?></div>
                        <?php  } ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg control-label <?php  if(empty($item)) { ?> must<?php  } ?>">登陆密码</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('store.saler' ,$item) ) { ?>
                        <input type="text" name="pwd" class="form-control" value="" />
                        <?php  } else { ?>
                        <div class='form-control-static'></div>
                        <?php  } ?>
                    </div>
                </div>


                <div class="form-group">
                    <label class="col-lg control-label">所属角色</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('store.saler' ,$item) ) { ?>
                        <input type='hidden' id='userid' name='roleid' value="<?php  echo $role['id'];?>" />
                        <div class='input-group'>
                            <input type="text" name="user" maxlength="30" value="<?php  echo $role['rolename'];?>" id="user" class="form-control" readonly required/>
                            <div class='input-group-btn'>
                                <button class="btn btn-default" type="button" onclick="popwin = $('#modal-module-menus1').modal();">选择角色</button>
                                <button class="btn btn-danger" type="button" onclick="$('#userid').val('');$('#user').val('');">清除选择</button>
                            </div>
                        </div>
                        <span class='help-block'>如果您选择了角色，则此用户本身就继承了此角色的所有权限</span>
                        <div id="modal-module-menus1"  class="modal fade" tabindex="-1">
                            <div class="modal-dialog" style='width: 920px;'>
                                <div class="modal-content">
                                    <div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="close2" type="button">×</button><h3>选择角色</h3></div>
                                    <div class="modal-body" >
                                        <div class="row">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="keyword" value="" id="search-kwd1" placeholder="请输入角色名称" />
                                                <span class='input-group-btn'><button type="button" class="btn btn-default" onclick="search_users();">搜索</button></span>
                                            </div>
                                        </div>
                                        <div id="module-menus1" style="padding-top:5px;"></div>
                                    </div>
                                    <div class="modal-footer"><a href="#" class="btn btn-default" data-dismiss="modal" aria-hidden="true">关闭</a></div>
                                </div>

                            </div>
                        </div>
                        <?php  } else { ?>
                        <div class='form-control-static'><?php  echo $user['username'];?></div>
                        <?php  } ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg control-label">门店推送消息和短信通知</label>
                    <div class="col-sm-9 col-xs-12">
                        <?php if( ce('store.saler' ,$item) ) { ?>
                        <label class='radio-inline'>
                            <input type='radio' name='getnotice' value=1' <?php  if($item['getnotice']==1) { ?>checked<?php  } ?> /> 启用
                        </label>
                        <label class='radio-inline'>
                            <input type='radio' name='getnotice' value=0' <?php  if($item['getnotice']==0) { ?>checked<?php  } ?> /> 禁用
                        </label>
                        <?php  } else { ?>
                        <div class='form-control-static'><?php  if($item['getnotice']==1) { ?>启用<?php  } else { ?>禁用<?php  } ?></div>
                        <?php  } ?>
                    </div>
                </div>
            <?php  } ?>

            <div class="form-group">
                <label class="col-lg control-label">状态</label>
                <div class="col-sm-9 col-xs-12">
                         <?php if( ce('store.saler' ,$item) ) { ?>
                    <label class='radio-inline'>
                        <input type='radio' name='status' value=1' <?php  if($item['status']==1) { ?>checked<?php  } ?> /> 启用
                    </label>
                    <label class='radio-inline'>
                        <input type='radio' name='status' value=0' <?php  if($item['status']==0) { ?>checked<?php  } ?> /> 禁用
                    </label>
                         <?php  } else { ?>
                          <div class='form-control-static'><?php  if($item['status']==1) { ?>启用<?php  } else { ?>禁用<?php  } ?></div>
                         <?php  } ?>
                </div>
            </div>
                
           <div class="form-group"></div>
            <div class="form-group">
                    <label class="col-lg control-label"></label>
                    <div class="col-sm-9 col-xs-12">
                           <?php if( ce('store.saler' ,$item) ) { ?>
                            <input type="submit" value="提交" class="btn btn-primary"  />
                            
                        <?php  } ?>
                       <input type="button" name="back" onclick='history.back()' value="返回列表" class="btn btn-default" />
                    </div>
            </div>
    </form>
</div>

<script language='javascript'>

    function search_users() {
        $("#module-menus1").html("正在搜索....")
        $.get('<?php  echo webUrl("store/perm/role/query")?>', {
            keyword: $.trim($('#search-kwd1').val())
        }, function(dat){
            $('#module-menus1').html(dat);
        });
    }

    function select_role(o) {
        $("#userid").val(o.id);
        $("#user").val( o.rolename );
        $(".close2").click();
    }
</script>
 
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
 
