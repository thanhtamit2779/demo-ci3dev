<?php
/*
// Uncached model call
$this->blog_m->getPosts($category_id, 'live');

// cached model call
$this->scache->model('blog_m', 'getPosts', array($category_id, 'live'), 120); // keep for 2 minutes

// cached library call
$this->scache->library('some_library', 'calcualte_something', array($foo, $bar, $bla)); // keep for default time (0 = unlimited)

// cached array or object
$this->scache->write($data, 'cached-name');
$data = $this->scache->get('cached-name');

// Delete cache
$this->scache->delete('cached-name');

// Delete all cache
$this->scache->delete_all();

// Delete cache group
$this->scache->write($data, 'nav_header');
$this->scache->write($data, 'nav_footer');
$this->scache->delete_group('nav_');

// Delete cache item
// Call like a normal library or model but give a negative $expire
$this->scache->model('blog_m', 'getPosts', array($category_id, 'live'), -1); // delete this specific cache file

*/
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Scache {
	private $_ci;
	function __construct() {
		$this->_ci =& get_instance();
		$this->_ci->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
	   // $this->_ci->load->model('simage');
		$path = $this->_ci->config->item('cache_path');
		$this->_cache_path = ($path == '') ? APPPATH.'cache/' : $path;
	}
	
	function html(){
		if($this->_ci->config->item('CACHE_ON') == 1){
			$controler = $this->_ci->router->fetch_class();
			$class = $this->_ci->router->fetch_method();
			$cache = $this->_ci->config->item('cachePosition');
			$position = strtoupper($controler."_".$class);
			$cacheTime = $this->_ci->config->item('CACHE_'.$position);
			if(isset($cacheTime) && $cacheTime && $cacheTime > 0){
				$this->_ci->output->cache($cacheTime);
			}
			/*
			if(array_key_exists($position,$cache)){
				$n = $cache[$position];
				$this->output->cache($n);
			}
			*/
		}
	}
	
	function script($cache_name, $PHP_RunScript, $time_cache = 0){
		if($time_cache ==0)
			$time_cache = $this->config->item('CACHE_FUNCTION');//Seconds
		if($time_cache <=0){
			return eval("return ".$PHP_RunScript);
		}
		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		$cache_name = $cache_name.".cache";
		if ( !$Data = $this->cache->get($cache_name))
		{
			$time_cache = $time_cache*60;
			$Data= eval("return ".$PHP_RunScript);
			$this->cache->save($cache_name, $Data, $time_cache);
		}
		return $Data;
	}
	/**
	 * Call a library's cached result or create new cache
	 *
	 * @access	public
	 * @param	string
	 * @return	array
	 */
	public function library($library, $method, $arguments = array(), $expires = NULL)
	{
		if ( ! class_exists(ucfirst($library)))
		{
			$this->_ci->load->library($library);
		}

		return $this->_call($library, $method, $arguments, $expires);
	}

	/**
	 * Call a model's cached result or create new cache
	 *
	 * @access	public
	 * @return	array
	 */
	public function model($model, $method, $arguments = array(), $expires = NULL)
	{
		if ( ! class_exists(ucfirst($model)))
		{
			$this->_ci->load->model($model);
		}

		return $this->_call($model, $method, $arguments, $expires);
	}
	// Depreciated, use model() or library()
	private function _call($property, $method, $arguments = array(), $expires = NULL)
	{
		$this->_ci->load->helper('security');

		if ( !  is_array($arguments))
		{
			$arguments = (array) $arguments;
		}

		// Clean given arguments to a 0-index array
		$arguments = array_values($arguments);

		$cache_file = 'call.'.$property.'.'.$method.'.'.do_hash($method.serialize($arguments), 'sha1');

		// See if we have this cached or delete if $expires is negative
		if($expires >= 0)
		{
			$cached_response = $this->get($cache_file);
		}
		else
		{
			$this->delete($cache_file);
			return;
		}

		// Not FALSE? Return it
		if($cached_response !== FALSE && $cached_response !== NULL)
		{
			return $cached_response;
		}

		else
		{
			// Call the model or library with the method provided and the same arguments
			$new_response = call_user_func_array(array($this->_ci->$property, $method), $arguments);
			$this->write($new_response, $cache_file, $expires);

			return $new_response;
		}
	}
	/**
	 * Retrieve Cache File
	 *
	 * @access	public
	 * @param	string
	 * @param	boolean
	 * @return	mixed
	 */
	function get($filename = NULL, $use_expires = true)
	{
		$filename = $this->set_cache_name($filename).'.cache';
		return $this->_ci->cache->get($filename);
	}
	/**
	 * Write Cache File
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function write($contents = NULL, $filename = NULL, $expires = 0)
	{
		$filename = $this->set_cache_name($filename).'.cache';
		$this->create_dirs($this->_cache_path.$filename);
		if($expires ==0)
		{
			$expires = 31536000; //1 year
		}
		$this->_ci->cache->save($filename, $contents, $expires);
	}
	
	public function delete($cache_name = "")
	{	
		$cache_name = $cache_name.'.cache';
		
		$this->_ci->cache->delete($cache_name);
	}
	
	function delete_all()
	{
		$this->_ci->cache->clean();
	}
	
	function set_cache_name($cache_name = NULL)
	{
		if($cache_name ===NULL || $cache_name == '')
		{
			$trace = debug_backtrace();
			array_shift($trace);

			//if call get cache in view
			if(empty($trace[1]['object']))
			{
				$class = 'line_'.$trace[1]['line'];
			}
			else
			{
				$class = get_class($trace[1]['object']);
				$class = strtolower($class);
			}
			
			$arguments = @$trace[1]['args'];
			$function = strtolower($trace[1]['function']);

			if(!is_array($arguments))
			{
				$arguments = (array)$arguments;
			}
			
			return $class.'/'.$function.'.'
			.do_hash($function.serialize($arguments), 'sha1');
		}
		return $cache_name;
	}
	
	
	public function delete_group($group = null)
	{
		if ($group === null)
		{
			return FALSE;
		}
		$patch = $this->_ci->config->item('cache_path');
		$patch = (($patch=='')?'application/cache/':$patch);
		$file_delete = $group;
		if(strpos($group, '/') !== FALSE)
		{
			$group = explode('/', $group);
			$file_delete = array_pop($group);
			$group = implode('/', $group);
			$patch = $patch.$group.'/';
		}

		$this->_ci->load->helper('directory');
		$map = directory_map($patch, TRUE);
		if($map)
			foreach ($map AS $file)
			{
				if (strpos($file, $file_delete)  !== FALSE)
				{
					@unlink($patch.$file);
				}
			}
		}

		function create_dirs($file =''){
			$dir_paths = explode("/",$file);
			$dir = "";
			for($i=0; $i<count($dir_paths) -1; $i++){
				$dir_path = $dir_paths[$i];
				$dir.= $dir_path."/";
				if (!is_dir($dir)) {
					mkdir($dir);
					chmod($dir, 0777);
				}
			}
			return $dir;
		}
	}

	?>