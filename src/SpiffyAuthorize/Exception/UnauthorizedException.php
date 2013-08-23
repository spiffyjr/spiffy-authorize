<?php

namespace SpiffyAuthorize\Exception;

class UnauthorizedException extends \Exception implements ExceptionInterface
{
    public function __construct($message = 'Unauthorized', $code = 403, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
