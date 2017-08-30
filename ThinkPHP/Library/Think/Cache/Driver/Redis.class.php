<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think\Cache\Driver;
use Think\Cache;
defined('THINK_PATH') or exit();

/**
 * Redis缓存驱动
 * 要求安装phpredis扩展：https://github.com/nicolasff/phpredis
 */
class Redis extends Cache {
	/**
	 * 架构函数
	 * @param array $options 缓存参数
	 * @access public
	 */
	public function __construct($options=array()) {
		if ( !extension_loaded('redis') ) {
			E(L('_NOT_SUPPORT_').':redis');
		}
		$options = array_merge(array (
			'host'          => C('REDIS_HOST') ? : '127.0.0.1',
			'port'          => C('REDIS_PORT') ? : 6379,
			'timeout'       => C('DATA_CACHE_TIMEOUT') ? : false,
			'persistent'    => false,
		),$options);

		$this->options =  $options;
		$this->options['expire'] =  isset($options['expire'])?  $options['expire']  :   C('DATA_CACHE_TIME');
		$this->options['prefix'] =  isset($options['prefix'])?  $options['prefix']  :   C('DATA_CACHE_PREFIX');
		$this->options['length'] =  isset($options['length'])?  $options['length']  :   0;
		$func = $options['persistent'] ? 'pconnect' : 'connect';
		$this->handler  = new \Redis;
		$options['timeout'] === false ?
			$this->handler->$func($options['host'], $options['port']) :
			$this->handler->$func($options['host'], $options['port'], $options['timeout']);
	}

	/**
	 * 读取缓存
	 * @access public
	 * @param string $key 缓存变量名
	 * @return mixed
	 */
	public function get($key) {
		N('cache_read',1);
		$value = $this->handler->get($this->options['prefix'].$key);
		$jsonData  = json_decode( $value, true );
		return ($jsonData === NULL) ? $value : $jsonData;	//检测是否为JSON数据 true 返回JSON解析数组, false返回源数据
	}


	/**
	 * 写入缓存
	 * @access public
	 * @param string $key 缓存变量名
	 * @param mixed $value  存储数据
	 * @param integer $expire  有效时间（秒）
	 * @return boolean
	 */
	public function set($key, $value, $expire = null) {
		N('cache_write',1);
		if(is_null($expire)) {
			$expire  =  $this->options['expire'];
		}
		$key   =   $this->options['prefix'].$key;
		//对数组/对象数据进行缓存处理，保证数据完整性
		$value  =  (is_object($value) || is_array($value)) ? json_encode($value) : $value;
		if(is_int($expire) && $expire) {
			$result = $this->handler->setex($key, $expire, $value);
		}else{
			$result = $this->handler->set($key, $value);
		}
		if($result && $this->options['length']>0) {
			// 记录缓存队列
			$this->queue($key);
		}
		return $result;
	}


	public function hget($key, $hash_field)
	{
		N('cache_read',1);
		return $this->handler->hGet($this->options['prefix'].$key, $hash_field);
	}



	/**
	 * @description 设置hash值 hmset('user:1', 'name', 'Jpe')
	 * @param $key
	 * @param $hash_field
	 * @param $hash_value
	 * @param null $expire
	 * @return int
	 */
	public function hset($key, $hash_field, $hash_value, $expire = null)
	{
		if (is_array($hash_field) || is_array($hash_value)) {
			return false;
		}

		N('cache_write',1);
		if(is_null($expire)) {
			$expire  =  $this->options['expire'];
		}
		$key   =   $this->options['prefix'].$key;
		$result = $this->handler->hset($key, $hash_field, $hash_value);
		if($result && $this->options['length']>0) {
			// 记录缓存队列
			$this->queue($key);
		}
		return $result;
	}


	/**
	 * @description 根据key得到该键的hash值信息
	 * @param $key
	 * @return array
	 */
	public function hget_all($key)
	{
		N('cache_read',1);
		return $this->handler->hGetAll($this->options['prefix'].$key);
	}


	/**
	 * @description 设置hash值 hmset('user:1', array('name' => 'Joe', 'salary' => 2000))
	 * @param string $key
	 * @param array $value 一维数组，且里面可以有多个键值对 array('field1'=>$value1, 'field2'=>$value2, ...)
	 * @param null $expire
	 * @return bool
	 */
	public function hmset($key, $value, $expire = null)
	{
		N('cache_write',1);
		if(is_null($expire)) {
			$expire  =  $this->options['expire'];
		}
		$key   =   $this->options['prefix'].$key;
		//对数组/对象数据进行缓存处理，保证数据完整性
		$result = $this->handler->hmset($key, $value);
		if($result && $this->options['length']>0) {
			//记录缓存队列
			$this->queue($key);
		}
		return $result;
	}


	/**
	 * 删除缓存
	 * @access public
	 * @param string $key 缓存变量名
	 * @return boolean
	 */
	public function rm($key) {
		return $this->handler->delete($this->options['prefix'].$key);
	}


	/**
	 * 删除key中的某个field
	 * @access public
	 * @param string $key
	 * @param $field
	 * @return boolean
	 */
	public function hdel($key, $field) {
		return $this->handler->hdel($this->options['prefix'].$key, $field);
	}


	/**
	 * @description 获取当前key的所有fields
	 * @param $key
	 * @return array
	 */
	public function hkeys($key)
	{
		N('cache_read',1);
		return $this->handler->HKEYS($this->options['prefix'].$key);
	}


	/**
	 * 清除缓存
	 * @access public
	 * @return boolean
	 */
	public function clear() {
		return $this->handler->flushDB();
	}
}
