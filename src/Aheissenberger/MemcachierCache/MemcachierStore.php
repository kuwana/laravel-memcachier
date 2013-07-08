<?php namespace Aheissenberger\MemcachierCache;

use Illuminate\Cache\StoreInterface;
//use Illuminate\Encryption\Encrypter;
use Memcached;

class MemcachierStore implements StoreInterface {

		/**
	 * The Memcachier instance.
	 *
	 * @var Memcached
	 */
	protected $memcachier;

	/**
	 * A string that should be prepended to keys.
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * Create a new Memcached store.
	 *
	 * @param  Memcached  $memcached
	 * @param  string     $prefix
	 * @return void
	 */
	public function __construct(Memcached $memcachier, $prefix = '')
	{
		$this->prefix = $prefix.':';
		$this->memcachier = $memcachier;
	}

	/**
	 * Retrieve an item from the cache by key.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function get($key)
	{
		$value = $this->memcachier->get($this->prefix.$key);

		if ($this->memcachier->getResultCode() == 0)
		{
			return $value;
		}
	}

	/**
	 * Store an item in the cache for a given number of minutes.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @param  int     $minutes
	 * @return void
	 */
	public function put($key, $value, $minutes)
	{
		$this->memcachier->set($this->prefix.$key, $value, $minutes * 60);
	}

	/**
	 * Increment the value of an item in the cache.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function increment($key, $value = 1)
	{
		return $this->memcachier->increment($this->prefix.$key, $value);
	}

	/**
	 * Increment the value of an item in the cache.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function decrement($key, $value = 1)
	{
		return $this->memcachier->decrement($this->prefix.$key, $value);
	}

	/**
	 * Store an item in the cache indefinitely.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function forever($key, $value)
	{
		return $this->put($key, $value, 0);
	}

	/**
	 * Remove an item from the cache.
	 *
	 * @param  string  $key
	 * @return void
	 */
	public function forget($key)
	{
		$this->memcachier->delete($this->prefix.$key);
	}

	/**
	 * Remove all items from the cache.
	 *
	 * @return void
	 */
	public function flush()
	{
		$this->memcachier->flush();
	}

	/**
	 * Begin executing a new section operation.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Cache\Section
	 */
	public function section($name)
	{
		return new Section($this, $name);
	}

	/**
	 * Get the underlying Memcached connection.
	 *
	 * @return \Memcached
	 */
	public function getMemcached()
	{
		return $this->memcachier;
	}

	/**
	 * Get the cache key prefix.
	 *
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

}