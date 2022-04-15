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