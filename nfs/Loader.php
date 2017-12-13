<?php
namespace nfs;

class Loader {
    public static function autoload($class) {
        print '<p>[autoload] '. $class .'</p>';
        $file = ROOT_PATH.strtr($class, '\\', DS).PHP_EXT;

        // Win环境严格区分大小写
        if (IS_WIN && pathinfo($file, PATHINFO_FILENAME) != pathinfo(realpath($file), PATHINFO_FILENAME)) {
            return false;
        }

        
        include $file;
    }
}

