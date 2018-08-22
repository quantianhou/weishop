<?php
defined('IN_IA') or exit('Access Denied');


if($_W['config']['setting']['cache'] == 'memcache') {
	if (extension_loaded('memcache')) {
		$config = $_W['config']['setting']['memcache'];
		if (!empty($config['server']) && !empty($config['port'])) {
			$memcacheobj = new Memcache();
			$connect = @$memcacheobj->connect($config['server'], $config['port'], 3);
			if (empty($memcacheobj) || empty($connect)) {
				$_W['config']['setting']['cache'] = 'mysql';
			}
		} else {
			$_W['config']['setting']['cache'] = 'mysql';
		}
	} else {
		$_W['config']['setting']['cache'] = 'mysql';
	}
}
load()->func('cache.' . $_W['config']['setting']['cache']);

function cache_load($key, $unserialize = false) {
	global $_W;
	if (!empty($_W['cache'][$key])) {
		return $_W['cache'][$key];
	}
	$data = $_W['cache'][$key] = cache_read($key);
	if ($key == 'setting') {
		$_W['setting'] = $data;
		return $_W['setting'];
	} elseif ($key == 'modules') {
		$_W['modules'] = $data;
		return $_W['modules'];
	} elseif ($key == 'module_receive_enable' && empty($data)) {
				cache_build_module_subscribe_type();
		return cache_read($key);
	} else {
		return $unserialize ? iunserializer($data) : $data;
	}
}


function cache_system_key($cache_key) {
	$args = func_get_args();
	switch (func_num_args()) {
		case 1:
			break;
		case 2:
			$cache_key = sprintf($cache_key, $args[1]);
			break;
		case 3:
			$cache_key = sprintf($cache_key, $args[1], $args[2]);
			break;
		case 4:
			$cache_key = sprintf($cache_key, $args[1], $args[2], $args[3]);
			break;
		case 5:
			$cache_key = sprintf($cache_key, $args[1], $args[2], $args[3], $args[4]);
			break;
		case 6:
			$cache_key = sprintf($cache_key, $args[1], $args[2], $args[3], $args[4], $args[5]);
			break;
	}
	$cache_key = 'we7:' . $cache_key;
	if (strlen($cache_key) > CACHE_KEY_LENGTH) {
		trigger_error('Cache name is over the maximum length');
	}
	return $cache_key;
}

function &cache_global($key) {
	
}
