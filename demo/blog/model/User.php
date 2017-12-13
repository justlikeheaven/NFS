<?php
namespace demo\blog\model;

class User {
	protected static $_table='user';
	
	
	public static function get(){
	    //$res = DB::get("select username,nickname from user where id=?", 22);
	    return 1;
	}
	
	public static function checkLogin(){
	    
	}
	
	
}