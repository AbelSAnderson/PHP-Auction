<?php


namespace App\Lib;
use App\Exceptions\ClassException;

/**
 * Trait Helper
 * @package App\Lib
 */
trait Helper {
    /**
     * @param string $var
     *
     * @return bool
     */
    function get(string $var) {
        if (property_exists(get_called_class(), $var)) {
            return $this->$var;
        }

        return false;
    }

    /**
     * @param string $var
     * @param        $value
     *
     * @return bool
     */
    function set(string $var, $value) {
        if (property_exists(get_called_class(), $var)) {
            $this->$var = $value;
            return true;
        }

        return false;
    }

    public static function displayError($errorCode): string {
        if(!property_exists(get_called_class(), "errorArray")) {
            throw new ClassException("Property doesn't exist");
        }
        if(array_key_exists($errorCode,static::$errorArray)) {
            return static::$errorArray[$errorCode];
        } else {
            throw new ClassException("Key doesn't exist");
        }
    }
}