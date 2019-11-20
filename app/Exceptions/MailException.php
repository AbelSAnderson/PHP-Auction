<?php


namespace App\Exceptions;

use Exception;
use Throwable;

/**
 * Class MailException
 * @package App\Exceptions
 */
class MailException extends Exception {
    /**
     * MailException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}