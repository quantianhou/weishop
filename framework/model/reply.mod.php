<?php
defined('IN_IA') or exit('Access Denied');


function reply_search($condition = '', $params = array(), $pindex = 0, $psize = 10, &$total = 0) {
	if (!empty($condition)) {
		$where = "WHERE {$condition}";
	}
	$sql = 'SELECT * FROM ' . tablename('rule') . $where . " ORDER BY status DESC, displayorder DESC, id DESC";
	if ($pindex > 0) {
				$start = ($pindex - 1) * $psize;
		$sql .= " LIMIT {$start},{$psize}";
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('rule') . $where, $params);
	}
	return pdo_fetchall($sql, $params);
}


function reply_single($id) {
	$result = array();
	$id = intval($id);
	$result = pdo_fetch("SELECT * FROM " . tablename('rule') . " WHERE id = :id", array(':id' => $id));
	if (empty($result)) {
		return $result;
	}
	$result['keywords'] = pdo_fetchall("SELECT * FROM " . tablename('rule_keyword') . " WHERE rid = :rid", array(':rid' => $id));
	return $result;
}


function reply_keywords_search($condition = '', $params = array(), $pindex = 0, $psize = 10, &$total = 0) {
	if (!empty($condition)) {
		$where = " WHERE {$condition} ";
	}
	$sql = 'SELECT * FROM ' . tablename('rule_keyword') . $where . ' ORDER BY displayorder DESC, `type` ASC, id DESC';
	if ($pindex > 0) {
				$start = ($pindex - 1) * $psize;
		$sql .= " LIMIT {$start},{$psize}";
		$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('rule_keyword') . $where, $params);
	}
	$result = pdo_fetchall($sql, $params);
	if (!empty($result)) {
		foreach ($result as $key => $val) {
			$containtypes = pdo_get('rule', array('id' => $val['rid']), array('containtype'));
			if (!empty($containtypes)) {
				$containtype = explode(',', $containtypes['containtype']);
				$containtype = array_filter($containtype);
			} else {
				$containtype = array();
			}
			$result[$key]['reply_type'] = $containtype;
		}
	} else {
		$result = array();
	}
	return $result;
}


function reply_contnet_search($rid = 0) {
	$result = array();
	$result['sum'] = 0;
	$rid = intval($rid);
	if(empty($rid)){
		return $result;
	}
	$modules = array('basic', 'images', 'news', 'music', 'voice', 'video', 'wxcard');
	$params = array(':rid' => $rid);
	foreach($modules as $key => $module){
		$sql = 'SELECT COUNT(*) FROM ' . tablename($module.'_reply') . ' WHERE `rid` = :rid';
		$result[$module] = pdo_fetchcolumn($sql, $params);
		$result['sum'] += $result[$module];
	}
	return $result;
}