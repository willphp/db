<?php
/*--------------------------------------------------------------------------
 | Software: [WillPHP framework]
 | Site: www.113344.com
 |--------------------------------------------------------------------------
 | Author: 无念 <24203741@qq.com>
 | WeChat: www113344
 | Copyright (c) 2020-2022, www.113344.com. All Rights Reserved.
 |-------------------------------------------------------------------------*/
namespace willphp\db;
use willphp\config\Config;
use willphp\db\build\Query;
class Db {
	protected $link = null;
	/**
	 * 连接数据库配置，设置默认表
	 * @param string|array $config 数据库配置
	 * @param string $table 默认表
	 * @return $this
	 */
	protected function connect($config = [], $table = '') {
		static $conn = [];
		if (is_array($config) && !empty($config)) {
			ksort($config);
			$signid = md5(serialize($config));
		} else {
			$signid = empty($config)? 'default' : $config;
		}
		$signid .= '_'.$table;
		if (!isset($conn[$signid])) {
			$default = Config::get('database.default', []); //默认配置
			if (!is_array($config) && $config != 'default') {
				$config = Config::get('database.'.$config, []);
			}
			$config = ($config == 'default')? $default : array_merge($default, $config);
			$conn[$signid] = new Query($config, $table);
		}
		$this->link = $conn[$signid];
		return $this;
	}
	public function __call($method, $params) {
		if (is_null($this->link)) {
			$this->connect();
		}
		return call_user_func_array([$this->link, $method], $params);
	}
	public static function __callStatic($name, $arguments) {
		return call_user_func_array([new static(), $name], $arguments);
	}
}