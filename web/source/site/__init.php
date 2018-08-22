<?php
defined('IN_IA') or exit('Access Denied');

if ($action != 'entry') {
	checkaccount();
}

if (!($action == 'multi' && $do == 'post')) {
	define('FRAME', 'account');
}