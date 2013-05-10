<?php

namespace Recite\MainBundle\Exception;

/**
 *
 */
class ReciteException extends \Exception
{

    public function __construct($code, $message = null, $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    const BAD_CREDENTIAL = 10;

    const METHOD_NOT_ALLOW = 11;

    const USER_NO_PERMISSION = 12;

    const USER_NOT_FOUND = 13;

    const BAD_REQUEST = 14;
}
