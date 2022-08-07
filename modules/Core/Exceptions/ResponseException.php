<?php

namespace Modules\Core\Exceptions;

class ResponseException extends \Exception
{
    public function __construct($message = '', $status = 400)
    {
        parent::__construct($message, $status);
    }
}
