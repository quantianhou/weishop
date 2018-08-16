<?php defined('IN_IA') or exit('Access Denied');?><input type="hidden" name="coupontype" value="1"/>
<input type="hidden" name="backtype" value="2"/>
<div class="form-group">
                <label class="col-lg control-label">优惠</label>
                
                    <?php if( ce('sale.coupon' ,$item) ) { ?>
                 
                     <div class="col-sm-9 col-xs-12 backtype backtype2"  <?php  if($item['backtype']!=2 && $item['backtype']!=0) { ?>style='display:none'<?php  } ?>>
                    <div class='input-group'>
                        <span class='input-group-addon'>返</span>
                        <input type='text' class='form-control' name='backmoney' value="<?php  echo $item['backmoney'];?>"/>
                        <span class='input-group-addon'>余额　返</span>
                        <input type='text' class='form-control' name='backcredit' value="<?php  echo $item['backcredit'];?>"/>
                        <span class='input-group-addon'>积分　返</span>
                        <input type='text' class='form-control'  name='backredpack'  value="<?php  echo $item['backredpack'];?>"/>
                        <span class='input-group-addon'>现金</span>
                        
                     </div>   
                    　<span class='help-block'>带%为返充值金额的百分比: 如10% ，消费200元，返20元，反现金，需要商户平台有钱，并需要上传微信证书</span>
             
                  
                       <?php  } else { ?>
					   <div class='form-control-static'>
						  <?php  if(!empty($item['backmoney'])) { ?>返 <?php  echo $item['backmoney'];?> 余额;<?php  } ?>
						  <?php  if(!empty($item['backcredit'])) { ?>返 <?php  echo $item['backcredit'];?> 积分;<?php  } ?>
						  <?php  if(!empty($item['backredpack'])) { ?>返 <?php  echo $item['backredpack'];?> 红包;<?php  } ?>
					   </div>
                    <?php  } ?>
                </div>
</div>
            
<!--efwww_com-->