<?php
if (!defined('IN_IA')) {
	exit('Access Denied');
}

define('IA_ROOT', str_replace('\\', '/', dirname(dirname(__FILE__))));
require_once IA_ROOT . '/framework/library/phpexcel/PHPExcel/Reader/CSV.php';
class Taobaocsv_EweiShopV2Page extends PluginWebPage
{
	public function main()
	{
		global $_W;
		global $_GPC;
		$uploadStart = '0';
		$uploadnum = '0';
		$excelurl = $_W['siteroot'] . 'addons/ewei_shopv2/plugin/taobao/data/test.xlsx';
		$zipurl = $_W['siteroot'] . 'addons/ewei_shopv2/plugin/taobao/data/test.zip';
		$sign = '';
		if ($_W['ispost']) {
			$rows = m('excel')->import('excelfile');
            $num = count($rows);
			$i = 0;
			$colsIndex = array();
			foreach ($rows[0] as $cols => $col) {
				if ($col == 'title') {
					$colsIndex['title'] = $i;
				}

				if ($col == 'price') {
					$colsIndex['price'] = $i;
				}

				if ($col == 'num') {
					$colsIndex['num'] = $i;
				}

				if ($col == 'description') {
					$colsIndex['description'] = $i;
				}

				if ($col == 'skuProps') {
					$colsIndex['skuProps'] = $i;
				}

				if ($col == 'picture') {
					$colsIndex['picture'] = $i;
				}

				if ($col == 'propAlias') {
					$colsIndex['propAlias'] = $i;
				}

				++$i;
			}

			$filename = $_FILES['excelfile']['name'];
			$filename = substr($filename, 0, strpos($filename, '.'));
			$rows = array_slice($rows, 1, count($rows) - 1);
			$items = array();
			//$this->get_zip_originalsize($_FILES['zipfile']['tmp_name'], '../attachment/images/' . $_W['uniacid'] . '/' . date('Y') . '/' . date('m') . '/');

			foreach ($rows as $rownu => $col) {
				$item = [];
				$item['goodsname']	= $col[0];
				$item['goodscode']		= $col[1];
                $item['goodsretailprice']	= $col[3];
                $item['barcode']	= $col[2];
				$item['goodsstock']	= $col[4];

                $items[$rownu%10][] = $item;
			}

			foreach ($items as $vvvv){
                //组合数据 发送写入商家商品库
                $post = [
                    'uniacid' => $_W['uniacid'],
                    'items' => $vvvv
                ];

                $cfg['post'] = $post;
                $cfg['ssl'] = true;

                $res = $this->curlOpen('http://api.ymkchen.com/goods',$cfg);
			}

            $sign = '导入成功';

        }

		include $this->template();
	}

    public function curlOpen ($url, $cfg)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        if ($cfg['ssl']) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($cfg['post']));
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        //print_r($info);exit;
        curl_close($ch);

        return $result;
    }

	public function fetch()
	{
		global $_GPC;
		set_time_limit(0);
		$num = intval($_GPC['num']);
		$totalnum = intval($_GPC['totalnum']);
		session_start();
		$items = $_SESSION['taobaoCSV'];
		$ret = $this->model->save_taobaocsv_goods($items[$num]);
		plog('taobaoCSV.main', '淘宝CSV宝贝批量导入' . $ret[goodsid]);

		if ($totalnum <= $num + 1) {
			unset($_SESSION['taobaoCSV']);
		}

		exit(json_encode($ret));
	}

	public function get_zip_originalsize($filename, $path)
	{
		if (!file_exists($filename)) {
			exit('文件 ' . $filename . ' 不存在！');
		}

		$filename = iconv('utf-8', 'gb2312', $filename);
		$path = iconv('utf-8', 'gb2312', $path);
		$resource = zip_open($filename);
		$i = 1;

		while ($dir_resource = zip_read($resource)) {
			if (zip_entry_open($resource, $dir_resource)) {
				$file_name = $path . zip_entry_name($dir_resource);
				$file_path = substr($file_name, 0, strrpos($file_name, '/'));

				if (!is_dir($file_path)) {
					mkdir($file_path, 511, true);
				}

				if (!is_dir($file_name)) {
					$file_size = zip_entry_filesize($dir_resource);

					if ($file_size < (1024 * 1024 * 10)) {
						$file_content = zip_entry_read($dir_resource, $file_size);
						$ext = strrchr($file_name, '.');

						if ($ext == '.png') {
							file_put_contents($file_name, $file_content);
						}
						else {
							if ($ext == '.tbi') {
								$file_name = substr($file_name, 0, strlen($file_name) - 4);
								file_put_contents($file_name . '.png', $file_content);
							}
						}
					}
				}

				zip_entry_close($dir_resource);
			}
		}

		zip_close($resource);
	}
}

?>
