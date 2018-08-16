<?php
defined('IN_IA') or exit('Access Denied');

if ($controller == 'activity') {
	header('Location: ' . murl('entry', array('m' => 'we7_coupon', 'do' => 'activity')));
	exit;
}
