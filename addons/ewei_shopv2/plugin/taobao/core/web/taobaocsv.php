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
			$rows = array_slice($rows, 2, count($rows) - 2);
			$items = array();
			//$this->get_zip_originalsize($_FILES['zipfile']['tmp_name'], '../attachment/images/' . $_W['uniacid'] . '/' . date('Y') . '/' . date('m') . '/');

			foreach ($rows as $rownu => $col) {
				$item = array();
				$item['name']	= $col[0];
				$item['sn']		= $col[1];
                $item['price']	= $col[3];
                $item['nation_sn']	= $col[2];
				$item['inventory']	= $col[4];

                $items[] = $item;
			}

			//组合数据 发送写入商家商品库
            $post = [
            	'uniacid' => $_W['uniacid'],
				'items' => $items
			];

			$res = $this->curl('http://api.test.ymkchen.com/goods',$post);
		}

		include $this->template();
	}

    public function curl($url, $postFields = NULL)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->readTimeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $this->readTimeout);
        }


        if ($this->connectTimeout) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }


        curl_setopt($ch, CURLOPT_USERAGENT, 'top-sdk-php');

        if ((5 < strlen($url)) && (strtolower(substr($url, 0, 5)) == 'https')) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }


        if (is_array($postFields) && (0 < count($postFields))) {
            $postBodyString = '';
            $postMultipart = false;

            foreach ($postFields as $k => $v ) {
                if (!is_string($v)) {
                    continue;
                }


                if ('@' != substr($v, 0, 1)) {
                    $postBodyString .= $k . '=' . urlencode($v) . '&';
                }
                else {
                    $postMultipart = true;

                    if (class_exists('\\CURLFile')) {
                        $postFields[$k] = new CURLFile(substr($v, 1));
                    }

                }
            }

            unset($k);
            unset($v);
            curl_setopt($ch, CURLOPT_POST, true);

            if ($postMultipart) {
                if (class_exists('\\CURLFile')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                }
                else if (defined('CURLOPT_SAFE_UPLOAD')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
                }


                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            }
            else {
                $header = array('content-type: application/x-www-form-urlencoded; charset=UTF-8');
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString, 0, -1));
            }
        }


        $reponse = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        }
        else {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (200 !== $httpStatusCode) {
                throw new Exception($reponse, $httpStatusCode);
            }

        }

        curl_close($ch);
        return $reponse;
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
