<?php
namespace demo\model;
use nfs\Db;
use nfs\Model;

class User extends Model {

	public static function gets($id){
	    
	    //Db::conn('vm')->query("select * from test.product where name like ?", '%小狗%');
	    $res = Db::query("select username,nickname from user where id=?", $id);
	    echo Db::getSql();
	    
	    return $res;
	}
	
}