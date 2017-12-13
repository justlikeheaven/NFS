<?php
class View {
    protected static $_vars;
    
    public static function setVar($key, $value){
        self::$_vars[$key] = $value;
    }
    
    public static function assign($key, $value){
        self::$_vars[$key] = $value;
    }
    
	public static function display($file='', $ext='.html'){
        
	    empty($file) && $file = NFS::$action;

	    if(!empty(self::$_vars)){
    		foreach (self::$_vars as $key=>$value){
    		    $$key = $value;
    		}
	    }
		include VIEW_ROOT.NFS::$controller.DS.$file.$ext;
		self::$_vars = array();
	}
	
}