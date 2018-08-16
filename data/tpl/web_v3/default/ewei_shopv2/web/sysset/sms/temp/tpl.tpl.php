<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group sms-template-1 data-item">
    <label class="col-lg control-label <?php if(cv('sysset.sms.temp.edit')) { ?>must<?php  } ?>">数据值</label>
    <div class="col-sm-9 col-xs-12">
        <?php if( ce('sysset.sms.temp' ,$item) ) { ?>
        <div class="input-group form-group" style="margin: 0;">
            <span class="input-group-addon">短信模版变量</span>
            <input type="text" name="data_temp[]" class="form-control valid" value="<?php  echo $data['data_temp'];?>">
            <span class="input-group-addon" style="border-left: 0; border-right: 0;">商城变量</span>
            <input type="text" name="data_shop[]" class="form-control valid" value="<?php  echo $data['data_shop'];?>">
            <span class="input-group-btn data-item-delete">
                <span class="btn btn-default"><i class="fa fa-remove"></i> 删除</span>
            </span>

            <!--<span class="input-group-addon btn btn-default data-item-delete"><i class="fa fa-remove"></i> 删除</span>-->
        </div>
        <span class="help-block">商城变量替换模板变量，例如：模版变量=ordensn 商城变量=[订单编号]，<span class="text-danger">仅填英文变量即可</span></span>
        <?php  } else { ?>
        <div class="input-group form-group" style="margin: 0;">
            <span class="input-group-addon">模版变量</span>
            <span class="form-control"><?php  echo $data['data_temp'];?></span>
            <span class="input-group-addon" style="border-left: 0; border-right: 0;">商城变量</span>
            <span class="form-control"><?php  echo $data['data_shop'];?></span>
        </div>
        <?php  } ?>
    </div>
</div>
