<?php

namespace App\Lib;

use App\Models\User;
use PDO;
use PDOException;
use SessionHandlerInterface;

/**
 * Class Session
 * @package App\Lib
 */
class Session implements SessionHandlerInterface {

    /**
     * @var PDO|mixed
     */
    public static $dbConnection;

    /**
     * @var bool|mixed
     */
    private $user = false;

    /**
     * Session constructor.
     */
    public function __construct() {
        session_set_save_handler($this, true);
        session_start();
        if (isset($_SESSION['user'])) {
            $this->user = $_SESSION['user'];
        }
    }

    public function __destruct() {
        session_write_close();
    }

    /**
     * @return bool|mixed
     */
    public function isLoggedIn() {
        return $this->user;
    }

    /**
     * @return bool|mixed
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param User $userObj
     *
     * @return bool
     */
    public function login(User $userObj) {
        $this->user = $userObj;
        $_SESSION['user'] = $userObj;
        return true;
    }

    /**
     * @return bool
     */
    public function logout() {
        $this->user = false;
        $_SESSION = [];
        session_destroy();
        return true;
    }

    /**
     * Close the session
     * @link  https://php.net/manual/en/sessionhandlerinterface.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close() {
        self::$dbConnection = null;
        return true;
    }

    /**
     * Destroy a session
     * @link  https://php.net/manual/en/sessionhandlerinterface.destroy.php
     *
     * @param string $id The session ID being destroyed.
     *
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function destroy($id) {
        try {
            $sql = "DELETE FROM sessions WHERE id = :id";
            $statement = self::$dbConnection->prepare($sql);
            $result = $statement->execute(compact("id"));
            return $result ? true : false;
        } catch (PDOException $e) {
            Logger::getLogger()->critical("Could not execute query: ", ['exception' => $e]);
            die();
        }
    }

    /**
     * Cleanup old sessions
     * @link  https://php.net/manual/en/sessionhandlerinterface.gc.php
     *
     * @param int $expire <p>
     *                         Sessions that have not updated for
     *                         the last maxlifetime seconds will be removed.
     *                         </p>
     *
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function gc($expire) {
        try {
            $sql = "DELETE FROM sessions WHERE DATE_ADD(last_accessed, INTERVAL $expire SECOND) < NOW()";
            $statement = self::$dbConnection->prepare($sql);
            $result = $statement->execute();
            return $result ? true : false;
        } catch (PDOException $e) {
            Logger::getLogger()->error("Could not execute query: ", ['exception' => $e]);
            return false;
        }
    }

    /**
     * Initialize session
     * @link  https://php.net/manual/en/sessionhandlerinterface.open.php
     *
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $name      The session name.
     *
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function open($save_path, $name) {
        try {
            self::$dbConnection = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
            self::$dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            Logger::getLogger()->critical("Could not create DB connection: ", ['exception' => $e]);
            die();
        }

        if (isset(self::$dbConnection)) {
            return true;
        }

        return false;
    }

    /**
     * Read session data
     * @link  https://php.net/manual/en/sessionhandlerinterface.read.php
     *
     * @param string $id The session id to read data for.
     *
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function read($id) {
        try {
            $sql = "SELECT * FROM sessions WHERE id = :id";
            $statement = self::$dbConnection->prepare($sql);
            $statement->execute(compact("id"));
            if ($statement->rowCount() == 1) {
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result['data'];
            } else {
                return "";
            }
        } catch (PDOException $e) {
            Logger::getLogger()->critical("Could no execute query: ", ['exception' => $e]);
            die();
        }
    }

    /**
     * Write session data
     * @link  https://php.net/manual/en/sessionhandlerinterface.write.php
     *
     * @param string $id   The session id.
     * @param string $data <p>
     *                             The encoded session data. This data is the
     *                             result of the PHP internally encoding
     *                             the $_SESSION superglobal to a serialized
     *                             string and passing it as this parameter.
     *                             Please note sessions use an alternative serialization method.
     *                             </p>
     *
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function write($id, $data) {
        try {
            $sql = "REPLACE INTO sessions (id, data) values (:id, :data) ";
            $statement = self::$dbConnection->prepare($sql);
            $result = $statement->execute(compact("id", "data"));
            return $result ? true : false;
        } catch (PDOException $e) {
            Logger::getLogger()->critical("Could not execute query: ", ['exception' => $e]);
            die();
        }
    }
}