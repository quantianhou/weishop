<?php
defined('IN_IA') or exit('Access Denied');
if ($do == 'online') {
	header(''.$_W['setting']['site']['key']);
	exit;
} elseif ($do == 'offline') {
	header('');
	exit;
} else {
}
template('cloud/device');
