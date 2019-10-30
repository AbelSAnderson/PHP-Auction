<?php


namespace App\Models;


/**
 * Class User
 * @package App\Models
 */
class User {
    /**
     * @var string
     */
    protected static $table_name = "users";
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
     * User constructor.
     *
     * @param $username
     * @param $password
     * @param $email
     */
    public function __construct($username, $password, $email) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }
}