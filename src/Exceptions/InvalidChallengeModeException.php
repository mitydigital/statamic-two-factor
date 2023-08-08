<?php

namespace MityDigital\StatamicTwoFactor\Exceptions;

use Exception;

class InvalidChallengeModeException extends Exception
{
    protected $message = 'The challenge mode can only be "code" or "recovery_code".';
}
