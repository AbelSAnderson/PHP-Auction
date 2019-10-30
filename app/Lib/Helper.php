<?php


namespace App\Lib;


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
}