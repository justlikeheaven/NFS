<?php
class User extends Model{
	protected static $_table='user';
	
	
	public static function getList(){
	    $res = DB::get("select username,nickname from user where id=?", 22);
	    return $res;
	}
	
	public static function checkLogin(){
	    
	}
	
	
}