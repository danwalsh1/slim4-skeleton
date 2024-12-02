<?php

namespace App\Application\Exceptions;

use Exception;

class ActionNotFoundException extends Exception
{
    /**
     * @var string
     */
    public $message = 'The requested action does not exist.';
}
