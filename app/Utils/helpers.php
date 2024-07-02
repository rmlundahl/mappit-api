<?php

/*
  |--------------------------------------------------------------------------
  | Helper functions
  |--------------------------------------------------------------------------
  |
 */

if (!function_exists('p')) {

	function p(mixed $_var):void {

		if ( !App::environment('testing')) {
			if (empty($_SERVER['REMOTE_ADDR'])) return;
			if ($_SERVER['REMOTE_ADDR'] !== $_ENV['IP_ADDRESS_DEV'] && !in_array($_SERVER['SERVER_NAME'], ['sharemystory.local','sharemystory-v2.local','hvaindestad-v2.local','lerenmetdestadleiden.local']) ) return;
		}

		echo "<pre>";
		print_r($_var);
		echo "</pre>";
		die();
	}

}

if (!function_exists('s')) {

	function s(mixed $_var): void {

		echo "<pre>";
		print_r($_var);
		echo "</pre>";
	}

}

if (!function_exists('q')) {

	function q(string $connection = ''): void {
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
