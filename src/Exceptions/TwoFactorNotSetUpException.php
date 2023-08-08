<?php

namespace MityDigital\StatamicTwoFactor\Exceptions;

use Exception;

class TwoFactorNotSetUpException extends Exception
{
    protected $message = 'Two Factor is not set up for this user. They are missing a two factor secret.';
}
