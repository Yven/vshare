<?php

namespace Src;

class Config
{
    private static $config = [];
    private static $path = __DIR__ . '/setting.php';

    /**
     * load setting file
     * @param string $path
     */
    public static function load($path = null){
        // load custom setting path
        if (!is_null($path)) {
            self::$path = file_exists($path) ? $path : self::$path;
        }
        self::$config = require self::$path;
    }

    /**
     * get the spc-field in the setting file
     * @param string $field
     * @return array|null
     */
    public static function get($field = null){
        // the config has not be init
        if (empty(self::$config)) {
            self::load();
        }
        // the field has be specified
        if (!is_null($field)) {
            // traverse the first layer
            foreach (self::$config as $fkey => $first) {
                if ($fkey == $field) {
                    return $first;
                }
                // traverse the second layer
                foreach ($first as $skey => $second) {
                    if ($skey == $field) {
                        return $second;
                    }
                }
            }
            // has no be matched
            return null;
        }
        // have no spc-field
        return self::$config;
    }

}
