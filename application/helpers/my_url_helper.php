<?php 
if( !function_exists('module') ) {
	function module($module = '') {
		$url                    =  base_url() . $module . '/' ;
		if($module == '' ) $url =  base_url() . $this->uri->segment(1) .  '/' ;
		return $url ;
	}
}

//echo module() ;