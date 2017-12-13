<?php
function dump($vars){
	$vars = func_get_args();
	
	echo "<pre><code>";
	foreach ($vars as $v){
	   var_dump($v);
	}
	echo "</code></pre>";
}

if(!function_exists('model')){
    function model($name){
        $class = '\\'.APP_NAMESPACE.'\\model\\'.ucfirst($name);
        return new $class();
    }
}

/**
 * 加载类
 * @param string 需要加载的类名
 * @param string 类所在目录
 * @param mix   实例化类需要的参数
 * @return object
 */
function &load_class($class, $directory = 'base', $param = NULL){
    static $_classes = array();

    // 已经实例化过了，直接返回
    if (isset($_classes[$class]))
    {
        return $_classes[$class];
    }

    $name = FALSE;

    // 优先加载网站下面的类库，没有找到才加载框架的类库
    foreach (array(APPPATH, BASEPATH) as $path){
        if (file_exists($path.$directory.'/'.$class.'.php')){
            $name = 'CI_'.$class;

            if (class_exists($name, FALSE) === FALSE){
                require_once($path.$directory.'/'.$class.'.php');
            }

            break;
        }
    }

    // 找不到，抛出异常
    if ($name === FALSE){
        echo 'Unable to locate the specified class: '.$class.'.php';
        exit(5); // EXIT_UNK_CLASS
    }

    $_classes[$class] = isset($param) ? new $name($param) : new $name();
    return $_classes[$class];
}