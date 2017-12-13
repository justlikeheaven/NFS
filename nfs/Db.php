<?php
namespace nfs;
use PDO, PDOException;

class Db {

    private static $_conn = []; //连接数组
    private static $_conn_cur = ''; //当前连接
	
	public static function conn($name=null){
	    is_null($name) && $name = 'default';
	    self::$_conn_cur = $name;
	    if(!isset(self::$_conn[$name])){
	        $config = Config::get("common.db.{$name}");
	        self::$_conn[$name] = DbFactory::create($config);
	    }
	    return self::$_conn[$name];
	}
	
	public static function query($sql, $data){
	    return self::conn()->query($sql, $data);
	}
	
	public static function execute($sql, $data){
	    return self::conn()->execute($sql, $data);
	}
	
	public static function getSql(){
	    return self::$_conn[self::$_conn_cur]->getSql();
	}
	

}

/**
 * 
 * @desc sql构造器
 *
 */
class SqlBuilder{
    protected $sql = [];
    protected $bind = [];
    
    public function bind($bind=null){
    	
        if(is_null($bind))
            return $this->bind;
        else
            $this->_bind = array_merge($bind, $this->bind);
    }
    
    public function table($table){
         $this->sql['table'] = $table;
    }
    
    public function where($where, $bind){
        $this->sql['where'] = $where;
        $this->bind($bind);
    }
    
    public function getSql(){
        return $this->sql;
    }
    
}

class DbFactory{
	

	public static function create($config){
        
        
		$driver = __NAMESPACE__."\PdoDriver";
		if(class_exists($driver)){
			return new $driver($config);
		}else{
			die("{$driver} not found");
		}
	}
}

/**
 * driver的模板
 * 所有的drive必须定义这里包含的方法
 */
interface DbDriverTemplate{
	public function query($sql, $bind=null, $fetchStyle);

	public function execute($sql, $bind=null);

}

/**
 * driver的公共方法
 * 可以共用的方法写在这里
 */
class DbDriver{
	public function debug($msg){
		echo $msg.PHP_EOL;
	}

	
}

/**
 * pdo驱动
 *
 */
class PdoDriver extends DbDriver implements DbDriverTemplate{
	protected $db;
	protected $sql;
	protected $bind;
	
	public function __construct($config){
		try{
		    $this->db = new PDO($config['dsn'], $config['username'], $config['password'],
		    array(
			    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
			    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,//ERRMODE_WARNING, PDO::ERRMODE_EXCEPTION, PDO::ERRMODE_SILENT
			    PDO::ATTR_TIMEOUT => 10,
		    ));
		    
		    //关闭本地模拟prepare
		    $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		}catch (PDOException $e){
		    die('Connection failed: ' . $e->getMessage());
		}
	}

	public function table($table){
		$this->table = $table;
	}
	
	protected function prepare($sql){
	    
	    try{
	        $stmt = $this->db->prepare($sql);
	    }catch (PDOException $e){
	        die('prepare failed: ' . $e->getMessage());
	    }
	
	    if(false===$stmt)	{
	        echo "\nPDO::errorInfo():\n";
	        var_dump($this->db->errorInfo());
	    }
	    return $stmt;
	}
	
	public function getSql(){
	    $sql = $this->sql;
	    if(is_array($this->bind) && !empty($this->bind)){
    	    foreach ($this->bind as $v){
    	        is_string($v) && $v = $this->db->quote($v);
    	        $sql = substr_replace($sql, $v, strpos($sql, '?'), 1);
    	    }
	    }
	    return $sql;
	}
	
	protected function statement($sql, $bind=null){
	    $this->sql = $sql;
	    if(!is_null($bind) && !is_array($bind)){
	        $bind = array($bind);
	    }
	    $this->bind = $bind;
        $stmt = $this->prepare($sql);
		if(!is_null($bind)){
			$i=1;
			
			foreach ($bind as $v){
				$stmt->bindValue($i++, $v);
			}
			
		}
		return $stmt;	
	}
	
	/**
	 * 
	 * @param string $sql
	 * @param mix $bind
	 * @return boolean
	 * @usage db::execute("insert into tbl_user values (null, 'kkoo')"); 
	 *
	 */
	public function execute($sql, $bind=null){
		$stmt = $this->statement($sql, $bind);
		return $stmt->execute();
	}
	
	/**
	 * @usage DB::getAll("select * from user where status=?", 1);
	 * @see dbdriver_template::getAll()
	 */
	public function query($sql, $bind=null, $fetch_style=PDO::FETCH_ASSOC){
		$stmt = $this->statement($sql, $bind);
		$stmt->execute();
		
		return $stmt->fetchAll($fetch_style);
	}
	
	
	
    

}