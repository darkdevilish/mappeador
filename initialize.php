<?php

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

require_once __DIR__.DS.'config.php';
require_once __DIR__.DS.'Database.php';

function autoload($className){
	/**
	 *With namespace autoload will take the whole namespace name including the
	 *classname, so you need to take out only the classname to autoload.
	 */
	$parts = explode('\\', $className);
	if(file_exists( end($parts) . '.php' ) ){
    	require_once end($parts) . '.php';
        return true;
    }
    return false; 
}

spl_autoload_register("autoload");