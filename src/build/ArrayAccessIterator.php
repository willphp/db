<?php
/*--------------------------------------------------------------------------
 | Software: [WillPHP framework]
 | Site: www.113344.com
 |--------------------------------------------------------------------------
 | Author: no-mind <24203741@qq.com>
 | WeChat: www113344
 | Copyright (c) 2020-2022, www.113344.com. All Rights Reserved.
 |-------------------------------------------------------------------------*/
namespace willphp\db\build;
trait ArrayAccessIterator {
	public function offsetSet($key, $value) {
		$this->objdata[$key] = $value;
	}	
	public function offsetGet($key)	{
		return isset($this->objdata[$key]) ? $this->objdata[$key] : null;
	}	
	public function offsetExists($key)	{
		return isset($this->objdata[$key]);
	}	
	public function offsetUnset($key)	{
		if (isset($this->objdata[$key])) {
			unset($this->objdata[$key]);
		}
	}	
	public function rewind() {
		reset($this->objdata);
	}	
	public function current() {
		return current($this->objdata);
	}	
	public function next() {
		return next($this->objdata);
	}	
	public function key() {
		return key($this->objdata);
	}	
	public function valid()	{
		return current($this->objdata);
	}
}