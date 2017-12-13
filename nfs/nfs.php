<?php
/**
 * NFS框架初始化文件
 *
 * @version        2013 Fri Dec 27 09:50:23 GMT 2013
 * @author         justlikeheaven <328877098@qq.com>
 * @link           https://github.com/justlikeheaven/NFS.git
 */

namespace nfs;

define('NFS_VERSION', '2.0');

define('FRAMEWORK', 'NFS');

//开始时间
define('TIME', time());

//文件分隔符
define('DS', DIRECTORY_SEPARATOR);

//PHP文件后缀
define('PHP_EXT', '.php');

//默认控制器
define('DEFAULT_CONTROLLER', 'index');

//默认方法
define('DEFAULT_ACTION', 'index');

define('BEFORE', 'before');

define('AFTER', 'after');

define('SEPARATOR', '_');

define('COMMON_FLAG', '~~');

define('TOKEN', base64_encode("I'm NFS"));

//NFS框架根目录
define('NFS_PATH', __DIR__.DS);

defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
defined('ROOT_PATH') or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);

//项目控制器文件夹名称
define('CONTROLLER_FOLDER_NAME', 'controller');

//项目模型文件夹名称
define('MODEL_FOLDER_NAME', 'model');

//项目模板文件夹名称
define('VIEW_FOLDER_NAME', 'view');

//项目配置文件夹名称
define('CONFIG_FOLDER_NAME', 'cfg');

//项目扩展类库文件夹名称
define('EXTEND_FOLDER_NAME', 'extend');
define('APP_NAMESPACE', 'demo');

define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);

/*
define('CONTROLLER_ROOT', APP_ROOT.DS.CONTROLLER_FOLDER_NAME.DS);
define('MODEL_ROOT', APP_ROOT.DS.MODEL_FOLDER_NAME.DS);
define('CONFIG_ROOT', APP_ROOT.DS.CONFIG_FOLDER_NAME.DS);
define('VIEW_ROOT', APP_ROOT.DS.VIEW_FOLDER_NAME.DS);
//项目扩展类库目录
define('NFS_EXTEND_ROOT', APP_ROOT.DS.EXTEND_FOLDER_NAME.DS);
*/

if (IS_CLI) {
    // CLI模式下 index.php module/controller/action...
    $_SERVER['PATH_INFO'] = isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : '';
}

//注册自动加载
require NFS_PATH.'Loader.php';
spl_autoload_register(__NAMESPACE__ .'\Loader::autoload');

//加载通用方法
include NFS_PATH.'func.php';

App::run();
