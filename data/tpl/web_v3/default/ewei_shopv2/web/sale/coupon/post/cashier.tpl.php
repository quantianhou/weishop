<?php defined('IN_IA') or exit('Access Denied');?><div class="form-group">
    <label class="col-lg control-label">优惠方式</label>
    <div class="col-sm-9 col-xs-12">
        <input type="hidden" name="coupontype" value="2"/>
        <?php if( ce('sale.coupon' ,$item) ) { ?>
         <label class="radio-inline " ><input type="radio" name="backtype" onclick='showbacktype(0)' value="0" <?php  if($item['backtype']==0) { ?>checked<?php  } ?>>立减</label>
         <label class="radio-inline"><input type="radio" name="backtype" onclick='showbacktype(1)' value="1" <?php  if($item['backtype']==1) { ?>checked<?php  } ?>>打折</label>
           <?php  } else { ?>
          <div class='form-control-static'>
              <?php  if($item['backtype']==0) { ?>
              立减
              <?php  } else if($item['backtype']==1) { ?>
              打折
              <?php  } ?>
          </div>
        <?php  } ?>
    </div>
 </div>
<div class="form-group">
    <label class="col-lg control-label"></label>
    <?php if( ce('sale.coupon' ,$item) ) { ?>
        <div class="col-sm-9 col-xs-12 backtype backtype0" <?php  if($item['backtype']!=0) { ?>style='display:none'<?php  } ?>>
            <div class='input-group fixsingle-input-group'>
                <span class='input-group-addon'>立减</span>
                <input type='text' class='form-control' name='deduct' value="<?php  echo $item['deduct'];?>"/>
                <span class='input-group-addon'>元</span>
             </div>
        </div>

        <div class="col-sm-9 col-xs-12 backtype backtype1"  <?php  if($item['backtype']!=1) { ?>style='display:none'<?php  } ?>>
            <div class='input-group fixsingle-input-group'>
                <span class='input-group-addon'>打</span>
                <input type='text' class='form-control' name='discount'  placeholder='0.1-10' value="<?php  echo $item['discount'];?>"/>
                <span class='input-group-addon'>折</span>
             </div>
        </div>
    <?php  } else { ?>
        <div class='form-control-static'>
            <?php  if($item['backtype']==0) { ?>
                立减 <?php  echo $item['deduct'];?> 元
            <?php  } else if($item['backtype']==1) { ?>
                打 <?php  echo $item['discount'];?>折
            <?php  } ?>
        </div>
    <?php  } ?>
</div>
<!--efwww_com-->