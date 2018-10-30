<?php defined('IN_IA') or exit('Access Denied');?><div id="batchcates" style="z-index: 999;display: none;position: fixed;top: 0;left: 0;right: 0;bottom: 0;background: rgba(0,0,0,0.5)" class="form-horizontal form-validate batchcates"  enctype="multipart/form-data">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button data-dismiss="modal" class="close" type="button">×</button>
                <h4 class="modal-title">选取分类</h4>
            </div>
            <div class="modal-body" style="height:270px">
                <div class="form-group">
                    <label class="col-sm-2 control-label"></label>
                    <div class="col-sm-8 col-xs-12">
                        <!--<label class="radio-inline"><input type="radio"  name="iscover" value="0" <?php  if($iscover ==0) { ?> checked="checked"<?php  } ?> /> 保留原有分类</label>-->
                        <label class="radio-inline">覆盖原有分类</label>

                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 control-label">商品分类</label>
                    <div class="col-sm-8 col-xs-12">
                        <?php if( ce('goods' ,$item) ) { ?>
                            <select id="cates2"  name='cates[]' class="form-control select2" style='width:550px;'>
                                <?php  if(is_array($category)) { foreach($category as $c) { ?>
                                <option value="<?php  echo $c['id'];?>" <?php  if(is_array($cates) &&  in_array($c['id'],$cates)) { ?>selected<?php  } ?> ><?php  echo $c['name'];?></option>
                                <?php  } } ?>
                            </select>
                        <?php  } ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" onclick="shopcategorylist()">确认</button>
                <button class="btn btn-default" >取消</button>
            </div>
        </div>
    </div>
</div>

<script>

    //确认
    //$('.modal-footer .btn.btn-primary').click(pushtoshop);
    function shopcategorylist() {
        var selected_checkboxs = $('.table-responsive tbody tr td:first-child [type="checkbox"]:checked');
        var goodsids = selected_checkboxs.map(function () {
            return $(this).val()
        }).get();

        var cates=$('#cates2').val();

        $.post(biz.url('goods/shop/categorytoshop'),{'goodsids':goodsids,'cates': cates}, function (ret) {
            if (ret.status == 1) {
                $('#batchcates').hide();
                tip.msgbox.suc('修改成功');
                window.location.reload();
                return
            } else {
                tip.msgbox.err('修改失败');
            }
        }, 'json');
    }
</script>



