<?php

/*
  |--------------------------------------------------------------------------
  | Helper functions
  |--------------------------------------------------------------------------
  |
 */

if (!function_exists('p')) {

	function p($_var) {
		if ($_SERVER['APP_ENV']!=='testing') {
			if (empty($_SERVER['REMOTE_ADDR'])) return;
			if ($_SERVER['REMOTE_ADDR'] !== $_ENV['IP_ADDRESS_DEV'] && !in_array($_SERVER['SERVER_NAME'], ['sharemystory.local','hvaindestad-v2.local','lerenmetdestadleiden.local']) ) return;
		}

		echo "<pre>";
		print_r($_var);
		echo "</pre>";
		die();
	}

}

if (!function_exists('s')) {

	function s($_var) {

		echo "<pre>";
		print_r($_var);
		echo "</pre>";
	}

}

if (!function_exists('q')) {

	function q($connection = '') {
		\DB::enableQueryLog();
		if (empty($connection)) {
			$querylog = \DB::getQueryLog();
		}else{
			$querylog = \DB::connection($connection)->getQueryLog();			
		}
		$counter = 0;
		foreach($querylog as $_v) {
			$tmp = explode('?',$_v['query']);
			$query = '';
			foreach($_v['bindings'] as $_b) {
				$query .= array_shift($tmp).'"'.$_b.'"';
			}
			$query .= array_shift($tmp);
			$_v = ['combined_query'=>$query]+$_v;
			s($_v);
			$counter++;
		}
		p($counter." queries.");
	}

}
