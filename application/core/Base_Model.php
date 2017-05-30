<?php

class Base_Model extends MY_Model
{
	protected $timestamps = FALSE;
	private $cache_name = NULL;
	private $cache_ttl = FALSE;
	private $_ci = NULL;
	private $cache_path = NULL;
	
	public function cache($cache_name = '', $cache_ttl = 0)
	{
		if($cache_name !== NULL)
		{
			if($this->_ci === NULL)
			{
				$this->_ci = & get_instance();
				$this->_ci->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
				$path = $this->_ci->config->item('cache_path');
				$path = ($path == '') ? APPPATH.'cache/' : $path;
				$this->cache_path = $path;
			}
			$this->cache_name = $cache_name.'.cache';
			$this->cache_ttl = ($cache_ttl == 0) ? 31536000 : $cache_ttl;
			$this->create_dirs($this->cache_path.$cache_name);
		}
		return $this;
	}

	public function get_all()
	{
		$where = func_get_args();
		if($this->cache_name)
		{
			return $this->_create_cache(__FUNCTION__, $where);
		}
		return call_user_func_array(array('parent', __FUNCTION__), $where);
	}

	public function get_by()
	{
		$where = func_get_args();
		if($this->cache_name)
		{
			return $this->_create_cache(__FUNCTION__, $where);
		}
		return call_user_func_array(array('parent', __FUNCTION__), $where);
	}

	public function reset_cache()
	{
		$this->cache_name = NULL;
		$this->cache_ttl = FALSE;
		return $this;
	}

	private function _create_cache($method_name = '', $args = array())
	{
		$contents = $this->_ci->cache->get($this->cache_name);
		if(!$contents)
		{
			$contents = call_user_func_array(array('parent', $method_name), $args);
			$this->_ci->cache->save($this->cache_name, $contents, $this->cache_ttl);
		}
		$this->reset_cache();
		return $contents;
	}

	public function create_dirs($file =''){
		if(is_dir($file))
			return $file;

		$dir_paths = explode("/",$file);
		$dir = "";
		for($i=0; $i<count($dir_paths) -1; $i++)
		{
			$dir_path = $dir_paths[$i];
			$dir.= $dir_path."/";
			if (!is_dir($dir)) 
			{
				mkdir($dir);
				chmod($dir, 0777);
			}
		}
		return $dir;
	}
}