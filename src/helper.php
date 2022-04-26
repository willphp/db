<?php
if (!function_exists('db')) {
	/**
	 * 获取数据库操作类
	 * @param string $table 要操作的表名
	 * @param string|array $config 数据库连接配置
	 * @return object 返回数据库操作对象
	 */
	function db($table = '', $config = []) {
		return \willphp\db\Db::connect($config, $table);
	}
}
if (!function_exists('get_sign_id')) {
	/**
	 * 获取名称标识
	 * @param string|array $data 数据
	 * @param number $len 长度
	 * @return string
	 */
	function get_sign_id($data, $len = 6) {
		if (is_array($data)) {
			ksort($data);
			$data = http_build_query($data);
		}
		return substr(md5($data), 0, $len);
	}
}
