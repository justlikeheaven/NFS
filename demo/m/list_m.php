<?php
class list_m extends model{
	protected $table='pepsi_code';
	
	public function getCode(){
		$cache = cache::init('memcache')->get('code');
		if($cache) return $cache;
		
		$res = $this->getColumn(array('id'=>4), 'code');
		cache::init('memcache')->set('code', $res);
		return $res;
	}
	
	
	
}