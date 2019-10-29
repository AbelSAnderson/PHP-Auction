<?php

namespace App\Lib;

use PDO;
use PDOException;
use ReflectionClass;
use ReflectionException;

/**
 * Class Database
 * @package App\Lib
 */
class Database {

    /**
     * @var
     */
    private static $databaseObj;
    /**
     * @var PDO
     */
    private $connection;

    /**
     * @return Database
     */
    public static function getConnection() {
        if (!self::$databaseObj) self::$databaseObj = new self();
        return self::$databaseObj;
    }

    /**
     * Database constructor.
     */
    private function __construct() {
        try {
            $this->connection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            Logger::getLogger()->critical("could not create DB connection: ", ['exception' => $e]);
            die();
        }
    }

    /**
     *
     */
    public function __destruct() {
        $this->connection = null;
    }

    /**
     * @param      $sql
     * @param null $bindVal
     * @param bool $retStmt
     *
     * @return bool|\PDOStatement
     */
    public function sqlQuery($sql, $bindVal = null, $retStmt = false) {
        try {
            $statement = $this->connection->prepare($sql);
            if (is_array($bindVal))
                $result = $statement->execute($bindVal);
            else
                $result = $statement->execute();

            if ($retStmt)
                return $statement;
            else
                return $result;
        } catch (PDOException $e) {
            Logger::getLogger()->critical("could not execute query: ", ['exception' => $e]);
            die();
        }
    }

    /**
     * @param      $sql
     * @param      $class
     * @param null $bindVal
     *
     * @return array
     */
    public function fetch($sql, $class, $bindVal = null) {
        $statement = $this->sqlQuery($sql, $bindVal, true);
        if ($statement->rowCount() == 0) {
            return [];
        }

        try {
            $reflect = new ReflectionClass($class);

            if ($reflect->getConstructor() == null) {
                $ctor_args = [];
            } else {
                $num = count($reflect->getConstructor()->getParameters());
                $ctor_args = array_fill(0, $num, null);
            }

            return $statement->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class, $ctor_args);

        } catch (ReflectionException $e) {
            Logger::getLogger()->critical("Reflection error: ", ['exception' => $e]);
            die();
        }
    }

    /**
     * @return string
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    /**
     * @param      $sql
     * @param null $bindVal
     *
     * @return int
     */
    public function rowCount($sql, $bindVal = null) {
        $statement = $this->sqlQuery($sql, $bindVal, true);
        return $statement->rowCount();
    }
}