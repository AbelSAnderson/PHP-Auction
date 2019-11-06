<?php


namespace App\Lib;

use App\Exceptions\ClassException;
use PDO;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

/**
 * Class Model
 * @package App\Lib
 */
abstract class Model {
    use Helper;

    /**
     * @param null        $cond
     * @param string|null $groupBy
     * @param string|null $orderBy
     * @param null        $limit
     *
     * @return array
     */
    public static function find($cond = null, string $groupBy = null, string $orderBy = null, $limit = null) {
        $sql = "SELECT * FROM " . static::$table_name;
        $db = Database::getConnection();
        if (is_array($cond)) {
            $sql .= " WHERE ";
            $bindings = [];
            foreach ($cond as $key => $value) {
                $bindings[] = "`key` = :$key";
            }
            $sql .= implode(" AND ", $bindings);
        } elseif ($cond != null) {
            $sql .= " WHERE $cond";
        }

        if (isset($groupBy)) {
            $sql .= " GROUP BY $groupBy";
        }

        if (isset($orderBy)) {
            $sql .= " ORDER BY $orderBy";
        }

        if (isset($limit)) {
            $sql .= " LIMIT $limit";
        }

        $results = $db->fetch($sql, get_called_class(), $cond);
        return $results;
    }

    /**
     * @param null        $cond
     * @param string|null $groupBy
     *
     * @return mixed
     */
    public static function findFirst($cond = null, string $groupBy = null) {
        $objs = self::find($cond, $groupBy, null, 1);
        if (empty($objs)) throw new ClassException("Model not found");
        return array_shift($objs);
    }

    /**
     * @param string|null $groupBy
     * @param string|null $orderBy
     *
     * @return array
     */
    public static function all(string $groupBy = null, string $orderBy = null): array {
        $objs = self::find(null, $groupBy, $orderBy, null);
        if (empty($objs)) throw new ClassException("Model not found");
        return $objs;
    }
}