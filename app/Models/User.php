<?php


namespace App\Models;


use App\Lib\Model;
use App\Exceptions\ClassException;
use App\Exceptions\MailException;
use App\Lib\Logger;
use App\Lib\Mail;

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
        $this->verify = $this->randStr();
    }

    public function randStr(): string {
        return substr(md5(rand()), 0, 16);
    }

    public function mailUser(): bool {
        $verifystring = urlencode($this->verify);
        $email = urlencode($this->email);
        $url = CONFIG_URL;
        $mail_body = <<<_MAIL_
Hi $this->username,\n\n

Please click on the following link to verify your new account:
<a href="{$url}/verify.php?email=$email&verify=$verifystring">Click here</a>


_MAIL_;

        try {
            return Mail::sendMail($this->email, CONFIG_AUCTIONNAME . " user verification", $mail_body);
        } catch (MailException $e) {
            Logger::getLogger()->critical("could not send mail: ", ['exception' => $e]);
        }

    }
}