<?php


namespace App\Models;


use App\Lib\Model;
use App\Exceptions\ClassException;

/**
 * Class User
 * @package App\Models
 */
class User extends Model {
    /**
     * @var string
     */
    protected static $table_name = "users";
    /**
     * @var array
     */
    public static $errorArray = array(
        'pass' => "Passwords do not match!",
        'taken' => "Username taken, please use another.",
        'no' => "Incorrect login details!",
        'failedlogin' => "Incorrect login, please try again!"
    );
    /**
     * @var int
     */
    protected $id = 0;
    /**
     * @var
     */
    protected $username;
    /**
     * @var
     */
    protected $password;
    /**
     * @var
     */
    protected $email;
    /**
     * @var string
     */
    protected $verify = "";
    /**
     * @var int
     */
    protected $active = 0;

    /**
     * @param $email
     * @param $password
     *
     * @return bool|mixed
     */
    public static function auth($email, $password) {
        try {
            $user = self::findFirst(['email' => $email]);

            if (password_verify($password, $user->get('password'))) {
                return $user;
            }
        } catch (ClassException $e) {
        }
        return false;
    }

    /**
     * User constructor.
     *
     * @param $username
     * @param $password
     * @param $email
     */
    public function __construct($username, $password, $email) {
        $this->username = $username;
        $this->password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
        $this->email = $email;
    }
}