<?php
namespace nfs;

use nfs\Db;

class Model {
	protected static $db;
	protected $auto;
	protected static $_table;


	public $columns = null;
    public $prefix;
    public $debug = 0;
	protected static $_sql = [];
	protected static $_bind = [];
	public static $last_sql;
	

	
	/**
	 * 自动填充
	 * @param array $query
	 */
	protected function auto(&$query, $func='find'){
		is_array($this->auto[$func]) && is_array($query) && $query = array_merge($this->auto[$func], $query);
	}
	
	public static function execute($sql){
		return $this->db->execute($sql);
	}
	
	public static function fields($fields){
		is_array($fields) && $fields = implode(', ', $fields);
		self::$_sql['fields'] = $fields;
		return new self;
	}
	
	public static function table($table){
		self::$_sql['table'] = $table;
		return new self;
	}
	
	public static function where($sql, $bind=[]){
		self::$_sql['where'] = $sql;
		self::bind($bind);
		return new self;
	}
	
	public static function bind($bind){
	    self::$_bind = array_merge(self::$_bind, $bind);
	}
	
	public static function orderby($orderby){
		self::$_sql['orderby'] = $orderby;
		return new self;
	}
	
	public function limit($limit){
		self::$_sql['limit'] = $limit;
		return new self;
	}

	public static function select(){
	    list($sql, $bind) = self::_createSql(__FUNCTION__);
		return Db::query($sql, $bind);
	}
	
	
	public static function getSql(){
	    return Db::getSql();
	}
	
	public function insert($data){
	    list($sql, $bind) = self::_createSql(__FUNCTION__, $data);
		return db::execute($sql, $bind);
	}
	
	public function update($data){
		return db::execute(self::$_sql(__FUNCTION__, $data));
	}
	
	public function delete(){
		return db::execute(self::$_sql(__FUNCTION__));
	}

	public function afterFetch(&$res){
	    
	}
	
	protected static function _createSql($method='get', $data=null){
		$_sql = self::$_sql;
		self::$_sql = [];
		
		$table = !empty($_sql['table']) ? $_sql['table'] : ltrim(strrchr(get_called_class(), '\\'), '\\');
        $bind=[];
        $sql='';
        
		if($method=='select'){
		    $fields = empty($_sql['fields']) ? '*' : $_sql['fields'];
			$sql = "SELECT {$fields} FROM `{$table}`";

		}else if($method=='update'){
			foreach ($data as $k=>$v){
				is_string($v) && $v="'{$v}'";
				$set.="`{$k}`={$v}";
			}
			$sql = "UPDATE `{$table}` SET {$set}";
		}else if($method=='delete'){
			$sql = "DELETE FROM `{$table}`";
		}else if($method=='insert'){
			foreach ($data as $k=>$v){				
				$key[]="`{$k}`";
			}
			self::bind(array_values($data));
			$keystr = implode(', ', $key);
			$valuestr = implode(', ', array_fill(0, count($data), '?'));
			$sql = "INSERT INTO `{$table}` ({$keystr}) VALUES ({$valuestr})";
			
		}
		if(!empty($_sql['where'])){
		    $sql.=" WHERE ".$_sql['where'];
		}

		if(!empty($_sql['orderby']))	$sql.=" ORDER BY ".$_sql['orderby'];
		if(!empty($_sql['groupby']))	$sql.=" GROUP BY ".$_sql['groupby'];
		if(!empty($_sql['limit']))	$sql.=" LIMIT ".$_sql['limit'];

		return $sql;
	}
	
}