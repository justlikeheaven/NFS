<?php
namespace nfs;

class App {
    public static $controller;
    public static $action;
    
    public static function run(){
        $path_arr = explode('/', ltrim($_SERVER['PATH_INFO'], '/'));
        $action = array_pop($path_arr);
        $controller = ucfirst(array_pop($path_arr));
        
        
        //控制器/操作
        $class = '\\'.APP_NAMESPACE."\\controller\\".$controller;
        
        //模块/控制器/操作
        if(!empty($path_arr)){
            $module = '\\'.APP_NAMESPACE.'\\'.implode('\\', $path_arr)."\\controller\\".$controller;
            file_exists(APP_PATH.DS.implode(DS, $path_arr).DS.'controller'.DS.$controller.PHP_EXT) && method_exists($module, $action) && $class = $module;
        }

        //dump($class, $action);
        $ctl = new $class;
        $ctl->$action();
    }
}

class nfs{
    public static $controller;
    public static $action;
    public static $cfg;



    public static function run(){
         
         
        self::$controller = $controller = !empty($_REQUEST['c']) ? strtolower($_REQUEST['c']) : DEFAULT_CONTROLLER;
        $controller_name = $controller.CONTROLLER_EXT;
        $ctl = new $controller_name();
        //var_dump($ctl);exit;
        $resful = '_'.strtolower($_SERVER['REQUEST_METHOD']);

        if( !empty($_REQUEST['a']) && ($a=strtolower($_REQUEST['a'])) && method_exists($ctl, $a) )	$act = $a;
        elseif(method_exists($ctl, $resful))	$act = $resful;
        elseif(method_exists($ctl, DEFAULT_ACTION))	$act = DEFAULT_ACTION;
        else die('error action');
        self::$action = $act;

        $ctl->$act();

        /**
         * 通用方法调度
         * 应付普通的增删改查功能
         * 表名和字段经过加密之后放到表单，这边会解析出来，加密的token在配置文件中设置
         */
        /*
         if(substr($act, 0, 2) == str_repeat(SEPARATOR, 2)){
         //调度前执行before方法
         $act_before = $act.SEPARATOR.BEFORE;
         method_exists($ctl, $act_before) && $ctl->$act_before();
         	
         list($func, $table) = explode(SEPARATOR, substr($act, 2));
         if(in_array($func, array('insert', 'update', 'delete', 'select'))){
         //根据表字段过滤请求参数
         $m = Model::load($table);
         //var_dump($m->columns);exit;
         if(is_array($m->columns) && !empty($m->columns)){
         foreach ($m->columns as $v){
         if(isset($_REQUEST[$v['COLUMN_NAME']])){
         $data[$v['COLUMN_NAME']] = $_REQUEST[$v['COLUMN_NAME']];
         }
         }
         }
         var_dump($data);
         !empty($data) && $m->$func($data);
         }
         	
         //调度后执行after方法
         $act_after = $act.SEPARATOR.AFTER;
         method_exists($controller, $act_after) && $controller->$act_after($res);
         }
         */
    }


     




}