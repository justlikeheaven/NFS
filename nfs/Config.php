<?php
namespace nfs;

class Config{
    private static $config = [];
    
    public static function get($name){
        $arr = explode('.', $name);
        $file = array_shift($arr);
        //$key = "['".implode("']['", $arr)."']";
        if(!isset(self::$config[$file])){
            self::$config[$file] = include APP_PATH.DS."cfg".DS.$file.".php";
        }
        
        $config = self::$config[$file];
        for($i=0; $i<count($arr); $i++){
            $config = $config[$arr[$i]];
            
        }
        return $config;
        
        
    }
}