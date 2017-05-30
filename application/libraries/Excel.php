<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  
require_once APPPATH."/third_party/PHPExcel.php"; 

class Excel extends PHPExcel { 

    public function __construct() { 

        parent::__construct(); 

        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;

        $cacheSettings = array( 'memoryCacheSize' => '128MB');

        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        
        ini_set('max_execution_time', 123456);
    }
}