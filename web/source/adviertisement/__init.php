<?php
defined('IN_IA') or exit('Access Denied');
define('FRAME', 'adviertisement');
if ($do == 'display') {
	define('ACTIVE_FRAME_URL', url('adviertisement/content-provider/account_list'));
}

