<?php defined('IN_IA') or exit('Access Denied');?><?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_header', TEMPLATE_INCLUDEPATH)) : (include template('_header', TEMPLATE_INCLUDEPATH));?>

<link href="../addons/ewei_shopv2/plugin/app/static/css/page.css?v=20170922" rel="stylesheet" type="text/css"/>

<div class="page-header">
    当前位置：
    <span class="text-primary"><?php  echo m('plugin')->getName('app')?></span>
</div>

<div class="page-content">

    <div class="alert alert-primary">
        <p><b>小程序说明</b></p>
        <p>小程序是微信小程序的管理后台，可在此设置个性化首页排版、基本设置、设置微信支付、审核发布。</p>
    </div>

</div>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('_footer', TEMPLATE_INCLUDEPATH)) : (include template('_footer', TEMPLATE_INCLUDEPATH));?>
