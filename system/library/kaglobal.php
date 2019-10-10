<?php
/*
	Project: Ka Extensions
	Author : karapuz <support@ka-station.com>

	Version: 3 ($Revision: 54 $)
*/

abstract class KaGlobal {

	protected static $registry;
	
	public static function init($registry) {
		self::$registry = $registry;
	}
	
	public static function t($text) {
		return self::$registry->get('language')->get($text);
	}

	public static function getRegistry() {		
		return self::$registry;
	}
	
	public static function getLanguageImage($language) {
		$var = '';	
		if (!self::isAdminArea()) {
			$var = "catalog/";
		}
		$var .= "language/" . $language['code'] . "/" . $language['code'] . ".png";
		
		return $var;
	}
	
	public static function isAdminArea() {
		return defined('DIR_CATALOG');
	}
	
	public static function iterator($iter) {
		$class = preg_replace('/[^a-zA-Z0-9]/', '', $iter) . 'Iterator';

		if (!class_exists($class)) {
			$file = DIR_SYSTEM . 'kaiterators/' . $iter . '.php';
			if (file_exists($file)) {
				include_once($file);
			} else {
				trigger_error('Error: Could not load file ' . $file . '!');
				exit();
			}
			
			if (!class_exists($class)) {
				trigger_error('Error: Could not load class ' . $class . '!');
				exit();
			}
		}
		
		$obj = new $class(self::$registry);
		return $obj;
	}
	
	
	public static function getTemplateDir() {
		
		$dir = '';
		if (self::isAdminArea()) {
			return $dir;
		} else {
			if (self::$registry->get('config')->get('config_theme') == 'theme_default') {
				$dir = self::$registry->get('config')->get('theme_default_directory');
			} else {
				$dir = self::$registry->get('config')->get('config_theme');
			}
			$dir = $dir . '/' . 'template/';
		}
		
		return $dir;
	}
	
	
}